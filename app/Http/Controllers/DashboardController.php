<?php

namespace App\Http\Controllers;

use App\Enums\AILogStatusEnum;
use App\Enums\AIPromptTemplateEnum;
use App\Enums\JobStatus;
use App\Enums\RoleEnum;
use App\Enums\UserIntentEnum;
use App\Http\Resources\ExportJobResource;
use App\Jobs\ExportUserUtmChunkJob;
use App\Models\AI\AILogPrompt;
use App\Models\AIConversation\ChatMessage;
use App\Models\AIConversation\ChatSession;
use App\Models\ExportJob;
use App\Models\Major;
use App\Models\School;
use App\Models\Score;
use App\Models\UniversityEmbedding;
use App\Models\User;
use App\Services\QuestionClusteringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];

        // Người dùng
        $data['totalUsers'] = User::query()
            ->where('email', '!=', 'admin@gmail.com')
            ->count();

        // Phiên tư vấn
        $data['totalChatSessions'] = ChatSession::query()->count();

        // Tổng tin nhắn
        $data['totalMessages'] = ChatMessage::query()->count();

        // Tổng tài liệu RAG
        $data['totalDocuments'] = UniversityEmbedding::query()->count();

        // Tổng trường
        $data['totalSchools'] = School::query()->count();

        // Tổng ngành
        $data['totalMajors'] = Major::query()->count();

        // Tổng dữ liệu điểm chuẩn
        $data['totalScores'] = Score::query()->count();

        // Số câu hỏi hôm nay (chỉ tin nhắn của User)
        $data['todayQuestions'] = ChatMessage::query()
            ->where('role', RoleEnum::USER->value)
            ->whereDate('created_at', today())
            ->count();

        $data['avgExecutionTime'] = AILogPrompt::query()
            ->where('status', AILogStatusEnum::SUCCESS->value)
            ->avg('execution_time_ms');

        $data['avgTokens'] = AILogPrompt::query()
            ->where('status', AILogStatusEnum::SUCCESS->value)
            ->avg('tokens_total');

        $data['topModel'] = AILogPrompt::query()
            ->selectRaw('model, COUNT(*) total')
            ->groupBy('model')
            ->orderByDesc('total')
            ->first();

        // Tỷ lệ đúng / tỉ lệ lỗi
        $total = AILogPrompt::query()->count();

        $success = AILogPrompt::query()->where(
            'status',
            AILogStatusEnum::SUCCESS->value
        )->count();

        $error = AILogPrompt::query()->where(
            'status',
            AILogStatusEnum::ERROR->value
        )->count();

        $data['successRate'] = round($success / max($total,1) * 100,2);
        $data['errorRate'] = round($error / max($total,1) * 100,2);

        $data['questionByDays'] = ChatMessage::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('role', RoleEnum::USER->value)
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        $data['scoresByYear'] = Score::query()
            ->selectRaw('year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $schools = AILogPrompt::query()
            ->selectRaw("
                response::json->>'school' as school,
                COUNT(*) as total
            ")
            ->where('prompt_type', AIPromptTemplateEnum::EXTRACT_METADATA_QUERY->value)
            ->where('status', AILogStatusEnum::SUCCESS->value)
            ->whereRaw("response::json->>'school' IS NOT NULL")
            ->groupBy('school')
            ->get();

        $data['topSchools'] = $schools
            ->groupBy(function ($item) {

                $name = mb_strtolower($item->school);

                $name = str_replace([
                    'trường ',
                    'đại học ',
                    'đh ',
                ], '', $name);

                $name = str_replace([
                    ' tp.hcm',
                    ' tphcm',
                    ' tp hồ chí minh',
                    ' thành phố hồ chí minh',
                    ' hồ chí minh',
                    ' hcm',
                    ' hà nội',
                    ' hn',
                    ' đà nẵng',
                ], '', $name);

                $name = preg_replace('/\s+/', ' ', trim($name));

                return $name;
            })
            ->map(function ($items) {
                return (object)[
                    'school' => $items->sortByDesc(fn ($i) => strlen($i->school))->first()->school,
                    'total' => $items->sum('total'),
                ];
            })
            ->sortByDesc('total')
            ->take(10)
            ->values();

        $data['topMajors'] = AILogPrompt::query()
            ->selectRaw("
                response::json->>'major' as major,
                COUNT(*) as total
            ")
            ->where('prompt_type', AIPromptTemplateEnum::EXTRACT_METADATA_QUERY->value)
            ->where('status', AILogStatusEnum::SUCCESS->value)
            ->whereRaw("response::json->>'major' IS NOT NULL")
            ->groupBy('major')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $data['topYears'] = AILogPrompt::query()
            ->selectRaw("
                response::json->>'year' as year,
                COUNT(*) as total
            ")
            ->where('prompt_type', AIPromptTemplateEnum::EXTRACT_METADATA_QUERY->value)
            ->where('status', AILogStatusEnum::SUCCESS->value)
            ->whereRaw("response::json->>'year' IS NOT NULL")
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $data['promptTypes'] = AILogPrompt::query()
            ->selectRaw('prompt_type, COUNT(*) total')
            ->groupBy('prompt_type')
            ->orderByDesc('total')
            ->get();

        $questions = ChatMessage::query()
            ->where('role', RoleEnum::USER->value)
            ->whereIn(
                DB::raw("metadata->>'user_intent'"),
                [
                    UserIntentEnum::ADVISE->value,
                    UserIntentEnum::MBTI->value,
                ]
            )
            ->select('message')
            ->get();

        $data['topQuestions'] = app(QuestionClusteringService::class)
            ->cluster($questions)
            ->take(10);

        // Thời gian xử lý trung bình của từng loại Prompt
        $data['promptPerformance'] = AILogPrompt::query()
            ->selectRaw('
                prompt_type,
                AVG(execution_time_ms) as avg_time,
                MAX(execution_time_ms) as max_time,
                COUNT(*) as total
            ')
            ->groupBy('prompt_type')
            ->orderByDesc('avg_time')
            ->limit(10)
            ->get();

        $data['intentStats'] = ChatMessage::query()
            ->selectRaw("
                metadata->>'user_intent' as intent,
                COUNT(*) as total
            ")
            ->where('role', RoleEnum::USER->value)
            ->whereRaw("metadata->>'user_intent' IS NOT NULL")
            ->groupBy('intent')
            ->orderByDesc('total')
            ->get();

        return view('dashboard', $data);
    }

    public function status($jobId): JsonResponse
    {
        $status = Cache::get(config('cache_key.export_key') . ":{$jobId}");
        if (!$status) {
            $job = ExportJob::query()->where('job_id', $jobId)->first();

            if (!$job) {
                return response()->json(['error' => 'Job not found'], 404);
            }

            $job->file_url = url('/storage/' . $job->file_path);

            return response()->json(new ExportJobResource($job));
        }

        if ($status['status'] === 'completed') {
            $status['file_url'] = url('/storage/' . $status['file_path']);
        } else {
            $status['file_url'] = null;
        }

        return response()->json($status);
    }
}

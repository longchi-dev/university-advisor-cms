<?php

namespace App\Queries\AI;

use App\Contracts\Repositories\IUserRepository;
use App\Enums\AILogStatusEnum;
use App\Models\AI\AILogPrompt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LlmLogHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(LlmLogQuery $query): LengthAwarePaginator
    {
        $aiLogPromptQuery = AILogPrompt::query()
            ->whereBetween(DB::raw('DATE(created_at)'), [$query->fromDate, $query->toDate])
            ->orderByDesc('created_at');

        $paginator = $aiLogPromptQuery->paginate($query->perPage, ['*'], 'page', $query->page);

        $paginator->getCollection()->transform(function (AILogPrompt $aiLogPrompt) {
            $user = app(IUserRepository::class)->findById($aiLogPrompt->user_id);

            $responseRaw = $aiLogPrompt->response;
            $responseDecoded = json_decode($responseRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $responseDecoded = json_decode(stripslashes($responseRaw), true) ?? $responseRaw;
            }

            $promptRaw = $aiLogPrompt->prompt;
            $promptDecoded = json_decode($promptRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $promptDecoded = json_decode(stripslashes($promptRaw), true) ?? $promptRaw;
            }

            return [
                'name' => $user->name,
                'email' => $user->email,
                'model' => $aiLogPrompt->model,
                'prompt_type' => $aiLogPrompt->prompt_type,
                'prompt' => $promptDecoded,
                'response' => $responseDecoded,
                'metadata' => $aiLogPrompt->metadata,
                'tokens_input' => $aiLogPrompt->tokens_input,
                'tokens_output' => $aiLogPrompt->tokens_output,
                'tokens_total' => $aiLogPrompt->tokens_total,
                'execute_time_ms' => $aiLogPrompt->execution_time_ms,
                'status' => $aiLogPrompt->status instanceof AILogStatusEnum ? $aiLogPrompt->status->value : $aiLogPrompt->status,
                'error_message' => $aiLogPrompt->error_message,
                'logged_at' => $aiLogPrompt->logged_at?->format('d-m-Y H:i:s') ?? null,
                'created_at' => $aiLogPrompt->created_at?->format('d-m-Y H:i:s') ?? null,
            ];
        });

        return $paginator;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AI\AILogPrompt;
use App\Queries\AI\LlmLogHandler;
use App\Queries\AI\LlmLogQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class LlmLogController
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->get('perPage', 10);
        $page = (int) $request->get('page', 1);

        $data = [];
        $fromDate = $request->get('from_date', date('d-m-Y'));
        $toDate = $request->get('to_date', date('d-m-Y'));

        $fromDateCarbon = Carbon::parse($fromDate);
        $toDateCarbon = Carbon::parse($toDate);

        $data['models'] = AILogPrompt::query()
            ->select('model')
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

        $data['promptTypes'] = AILogPrompt::query()
            ->select('prompt_type')
            ->distinct()
            ->orderBy('prompt_type')
            ->pluck('prompt_type');

        $llmLogQuery = new LlmLogQuery(
            page: $page,
            perPage: $perPage,
            fromDate: $fromDateCarbon->toDateString(),
            toDate: $toDateCarbon->toDateString(),
            model: $request->get('model'),
            promptType: $request->get('prompt_type'),
        );

        $llmLogs = app(LlmLogHandler::class)->execute($llmLogQuery);

        $data['llmLogs'] = $llmLogs;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;

        return view('llm_log.index', $data);
    }
}

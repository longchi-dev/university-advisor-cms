<?php

namespace App\Http\Controllers;

use App\Models\LlmLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class LlmLogController
{
    public function index(Request $request): View
    {
        $data = [];
        $fromDate = $request->get('from_date', date('d-m-Y'));
        $toDate = $request->get('to_date', date('d-m-Y'));

        $fromDateCarbon = Carbon::parse($fromDate);
        $toDateCarbon = Carbon::parse($toDate);

        $llmLogs = LlmLog::query()
            ->whereBetween(DB::raw('DATE(created_at)'), [$fromDateCarbon->toDateString(), $toDateCarbon->toDateString()])
            ->orderByDesc('created_at')
            ->paginate(15);

        $llmLogs->appends($request->query());

        $data['llmLogs'] = $llmLogs;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;

        return view('llm_log.index', $data);
    }
}

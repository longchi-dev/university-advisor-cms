<?php

namespace App\Http\Controllers;

use App\Models\HubspotLog;
use App\Models\LlmLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HubspotLogController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];
        $fromDate = $request->get('from_date', date('d-m-Y'));
        $toDate = $request->get('to_date', date('d-m-Y'));

        $fromDateCarbon = Carbon::parse($fromDate);
        $toDateCarbon = Carbon::parse($toDate);

        $hubspotLogs = HubspotLog::query()
            ->whereIn('action', ['submit_form', 'send_otp'])
            ->whereBetween(DB::raw('DATE(requested_at)'), [$fromDateCarbon->toDateString(), $toDateCarbon->toDateString()])
            ->orderByDesc('requested_at')
            ->paginate(15);

        $hubspotLogs->appends($request->query());

        $data['hubspotLogs'] = $hubspotLogs;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;

        return view('hubspot_log.index', $data);
    }
}

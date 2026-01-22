<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Exports\UserUtmExport;
use App\Http\Resources\ExportJobResource;
use App\Jobs\ExportUserUtmChunkJob;
use App\Models\ExportJob;
use App\Models\GamingSession;
use App\Models\OutcomeImage;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];

        $data['totalPlayers'] = Player::query()->count();
        $data['totalGamingSessions'] = GamingSession::query()->whereNotNull('finished_at')->count();
        $data['totalGamingSessionsShareFacebook'] = OutcomeImage::query()->whereNotNull('share_facebook_at')->count();

        return view('dashboard', $data);
    }
}

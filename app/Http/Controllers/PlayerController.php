<?php

namespace App\Http\Controllers;

use App\Queries\Player\PlayerHandler;
use App\Queries\Player\PlayerQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlayerController extends Controller
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

        $playerUrl = $request->get('player_url');

        $playerQuery = new PlayerQuery(
            page: $page,
            perPage: $perPage,
            playerUrl: $playerUrl,
            fromDate: $fromDateCarbon->toDateString(),
            toDate: $toDateCarbon->toDateString(),
        );

        $players = app(PlayerHandler::class)->execute($playerQuery);

        $data['players'] = $players;
        $data['playerUrls'] = app(PlayerHandler::class)->getPlayerUrls($playerQuery);
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;

        return view('player.index', $data);
    }
}

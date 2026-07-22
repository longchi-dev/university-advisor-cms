<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Queries\DataAdvisor\DataAdvisorHandler;
use App\Queries\DataAdvisor\DataAdvisorQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataAdvisorController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('perPage', 10);

        $query = new DataAdvisorQuery(
            page: $page,
            perPage: $perPage,
            id: $request->get('id'),
            year: $request->get('year'),
            school: $request->get('school'),
            major: $request->get('major'),
        );

        $scores = app(DataAdvisorHandler::class)
            ->execute($query);

        $years = Score::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $data['years'] = $years;
        $data['scores'] = $scores;
        $data['school'] = $request->get('school');
        $data['major'] = $request->get('major');
        $data['year'] = $request->get('year');

        return view('data_advisor.index', $data);
    }
}

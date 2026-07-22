<?php

namespace App\Http\Controllers;

use App\Queries\DataAdvisor\DataAdvisorHandler;
use App\Queries\DataAdvisor\DataAdvisorQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataAdvisorController extends Controller
{
    public function index(Request $request): View
    {
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


        return view('data_advisor.index', [
            'scores' => $scores,
            'year' => $request->get('year'),
            'school' => $request->get('school'),
            'major' => $request->get('major'),
        ]);
    }
}

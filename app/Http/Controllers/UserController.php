<?php

namespace App\Http\Controllers;

use App\Queries\User\UserHandler;
use App\Queries\User\UserQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->get('perPage', 10);
        $page = (int) $request->get('page', 1);

        $data = [];

        $userType = $request->get('is_new_user');

        $emailInput = $request->input('email', '');
        $emails = array_filter(array_map('trim', explode(',', $emailInput)));

        $fromDate = $request->get('from_date', date('d-m-Y'));
        $toDate = $request->get('to_date', date('d-m-Y'));
        $fromDateCarbon = Carbon::parse($fromDate);
        $toDateCarbon = Carbon::parse($toDate);

        $userQuery = new UserQuery(
            page: $page,
            perPage: $perPage,
            userType: $userType,
            emails: $emails,
            fromDate: $fromDateCarbon->toDateString(),
            toDate: $toDateCarbon->toDateString(),
        );

        $users = app(UserHandler::class)->execute($userQuery);

        $data['users'] = $users;
        $data['emails'] = !empty($emails) ? implode(', ', $emails) : '';
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;

        return view('users.index', $data);
    }
}

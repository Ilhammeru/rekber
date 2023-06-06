<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Browser;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class UserController extends Controller
{
    public $service;

    public function __construct()
    {
        $this->service = new UserService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {
        $pageTitle = __('global.users');
        return view('users.index', compact('status', 'pageTitle'));
    }

    public function ajax($status)
    {
        return $this->service->datatable(['status' => $status]);
    }

    public function ajaxTransaction($id)
    {
        return $this->service->datatableTransaction($id);
    }

    public function ajaxLoginHistory($id)
    {
        return $this->service->datatableLoginHistory($id);
    }

    public function logins($id)
    {
        $pageTitle = __('global.user') . ' ' . __("global.logins");
        return view('users.login-history', compact('id', 'pageTitle'));
    }

    public function login($id)
    {
        Auth::loginUsingId(decrypt($id));
        return to_route('dashboard');
    }

    public function banUserForm($id)
    {
        $view = view('users.ban', compact('id'))->render();

        return $this->success('Succees', ['view' => $view]);
    }

    public function banUser(Request $request, $id)
    {
        $this->validate($request, [
            'reason' => 'required',
        ]);

        return $this->notify($this->service->ban($request, $id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->service->show($id);
        $pageTitle = __('global.detail_user') . ' - ' . $user->username;

        return view('users.show', compact('user', 'pageTitle', 'id'));
    }

    public function transactions($id)
    {
        $pageTitle = __('global.user') . ' ' . __('global.transactions');
        return view('users.transactions', compact('id', 'pageTitle'));
    }

    public function balanceForm($id, $type)
    {
        $view = view('users.balance-form', compact('id', 'type'))->render();

        return $this->success('Success', ['view' => $view]);
    }

    public function updateBalance($id)
    {
        return $this->notify($this->service->updateBalance($id));
    }

    public function addDeductBalance(Request $request, $id)
    {
        $this->validate($request, [
            'balance' => 'required',
        ]);

        return $this->service->submitBalance($request, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                Rule::unique('users', 'email')->ignore(decrypt($id)),
            ]
        ]);

        return $this->notify($this->service->update($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

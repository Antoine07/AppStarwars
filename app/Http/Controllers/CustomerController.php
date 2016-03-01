<?php

namespace App\Http\Controllers;

use Gate;
use App\User;
use App\History;
use App\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Menu\TraitMainMenu;


class CustomerController extends Controller
{

    use TraitMainMenu;

    protected $paginate = 5;

    public function __construct()
    {
        $this->getMenu();
    }

    public function show($id)
    {

        $user = User::findOrfail($id);
        $histories = $user->histories;
        $customer = $user->customer;

        if (Gate::denies('show', $customer))
            abort('403', 'Sorry, not sorry');

        return view('customer.show', compact('histories', 'customer'));
    }
}

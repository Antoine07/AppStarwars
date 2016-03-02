<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Menu\TraitMainMenu;

class InscriptionController extends Controller
{

    use TraitMainMenu;

    public function __construct()
    {
        $this->getMenu();
    }

    public function getStepOne()
    {

        $user = null;
        if (session()->has('user')) $user = session()->get('user');

        return view('inscription.step_one', compact('user'));

    }

    public function postStepOne(Request $request)
    {

        $id = '';
        $userExist = session()->has('user');

        if ($userExist) {
            $user = session()->get('user');
            $id = ",$user->id";
        }

        $this->validate($request, [
            'email' => 'required|email|unique:users,email' . $id,
            'name' => 'required|max:10'
        ]);

        if ($userExist) {
            $user = User::findOrFail($user->id);
            $user->update($request->all());
        } else
            $user = User::create($request->all());

        session()->put('user', $user);

        return redirect('inscription/step-two');

    }

    public function getStepTwo()
    {
        if (!session()->has('user')) redirect('inscription/step-one');

        $customer = null;
        if (session()->has('customer')) $customer = session()->get('customer');

        $user = session()->get('user');

        return view('inscription.step_two', compact('user', 'customer'));
    }

    public function postStepTwo(Request $request)
    {
        if (!session()->has('user')) redirect('inscription/step-one');

        $this->validate($request, [
            'address' => 'required|min:5|max:200',
        ]);

        $user = session()->get('user');

        $customer = Customer::create(['user_id'=>$user->id, 'address' => $request->input('address')]);

        session()->put('customer', $customer);

        return redirect('inscription/step-three');

    }

    public function getStepThree()
    {

       if (!session()->has('user') || !session()->has('customer')) redirect('inscription/step-one');

        $user = session()->get('user');
        $customer = session()->get('customer');

        return view('inscription.step_three', compact('user', 'customer'));

    }

    public function postStepEnd(Request $request)
    {
        if (!session()->has('user') || !session()->has('customer')) redirect('inscription/step-one');

        $user = session()->get('user');
        $customer = session()->get('customer');

        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required|max:10',
            'address' => 'required|min:5|max:200',
            'password' => 'min:5|max:7|required|confirmed',
            'password_confirmation' => 'min:5|max:7|required'
        ]);

        DB::transaction(function () use($user, $customer, $request) {
            $user->update($request->all());
            $customer->update($request->all());
        });

        session()->forget('user');
        session()->forget('customer');

        return redirect('inscription/confirmation')->with('message', 'success');


    }


    public function getConfirmation()
    {
        return view('inscription.step_confirmation');
    }

}

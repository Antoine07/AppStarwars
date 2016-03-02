<?php

namespace App\Http\Controllers;

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

        $user = session()->get('user');

        return view('inscription.step_two', compact('user'));
    }

    public function postStepTwo(Request $request)
    {
        if (!session()->has('user')) redirect('inscription/step-one');

        $user = session()->get('user');

        $this->validate($request, [
            'address' => 'required|min:5|max:200',
        ]);


    }

}

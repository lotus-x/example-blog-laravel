<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function loginView()
    {
        return view("login");
    }

    public function registerView()
    {
        return view("register");
    }

    public function login(UserLoginRequest $request)
    {
        $request->validated();

        $email=$request->string('email')->trim();
        $password=$request->string('password')->trim();

        if (Auth::attempt(['email'=>$email,'password'=>$password])) {
            $request->session()->regenerate();

            return redirect()->intended(route('articles.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(UserRegisterRequest $request)
    {
        $request->validated();

        $user=new User();

        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->password=$request->input('password');

        $user->save();

        return redirect()->route("login-view");
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login-view');
    }
}

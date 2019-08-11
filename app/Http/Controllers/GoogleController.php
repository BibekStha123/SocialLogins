<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $google = Socialite::driver('google')->user(); 
        // dd($google->id);
        $user = User::where('google_id', $google->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $google->name,
                'email'    => $google->email,
                'google_id' => $google->id
            ]);
        }
        Auth::login($user); 
        return redirect()->to('/home');
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{

    public function redirect()
    {
        return Socialite::driver('facebook')->scopes([
            'email', 'user_birthday','publish_pages', 'manage_pages'
        ])->redirect();
    }

    public function callback(Request $request)
    {
        if (!$request->has('code') || $request->has('denied')) {
            return redirect()->to('/login');
        }

        $facebook = Socialite::driver('facebook')->user();
        $user = User::whereFbProviderId($facebook->id)->first();
        if (!$user) {
            $user = User::create([
                'fb_provider_id' => $facebook->id,
                'name' => $facebook->name,
                'email' => $facebook->email,
                'fb_access_token' => $facebook->token,
            ]);
        }
        Auth::login($user);
        return redirect()->to('/home');
    }
}

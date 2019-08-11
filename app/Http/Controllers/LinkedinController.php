<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LinkedinController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }


    public function callback(Request $request)
    {
        if (!$request->has('code') || $request->has('denied')) {
            return redirect()->to('/login');
        }

            $linkdinUser = Socialite::driver('linkedin')->user();
        
            $user = User::where('linkedin_id',$linkdinUser->id)->first();
            if(!$user)
            {
                $user = User::create([
                    'email' => $linkdinUser->email,
                    'linkedin_id' => $linkdinUser->id,
                    'name' => $linkdinUser->name
                ]);
            }

            Auth::login($user);
            return redirect()->to('/home');
            
    }
}

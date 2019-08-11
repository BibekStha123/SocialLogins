<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class InstagramController extends Controller
{

    public function redirect()
    {
        return Socialite::with('instagram')->redirect();
    }

    public function callback()
    {
        $insta = Socialite::driver('instagram')->user();
        // dd($insta);
        $user = User::where('insta_id', $insta->id)->first();
        if(!$user){
            $user = User::create([
                'insta_id'=> $insta->id,
                'name' => $insta->name,
                'email' => $insta->email
            ]);
        }
        
        // \auth()->login($user);
        Auth::login($user);
        return redirect()->to('/home');
    }
}

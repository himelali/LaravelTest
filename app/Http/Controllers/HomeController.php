<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except("language");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function language(Request $request) {
        $request->validate([
            'language' => 'required|in:en,bg',
        ]);
        $language = $request->post("language") ?? 'en';
        $cookie = cookie(getLanguageKey(), $language, 43829); // 1 month cookie
        return redirect()->back()->withCookie($cookie);
    }
}

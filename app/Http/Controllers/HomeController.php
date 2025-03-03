<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //la page / vue home.blade.php
    public function home()
    {
        return view('home.home');
    }
     //la page / vue login.blade.php
    public function about()
    {
        return view('home.about');
    }
  //la page / vue dashboard.blade.php
  public function dashboard()
  {
      return view('home.dashboard');
  }

  public function builds()
  {
      $builds = Auth::user()->builds;
      return view('builds.index', compact('builds'));
  }
}

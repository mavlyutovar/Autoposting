<?php

namespace App\Http\Controllers;

use App\Api\PinterestApi;
use App\Group;
use App\Theme;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $themes = Theme::all();
        $themes = Theme::orderBy('name')->paginate(1);
        return view("start", compact("themes"));
//        $theme = Theme::find(1)->first();
//        $grp = Group::find(1)->first();
//        $theme->initGroup($grp);
//
//        dd($theme->sendPost());

    }
}

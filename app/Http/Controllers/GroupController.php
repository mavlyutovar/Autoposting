<?php

namespace App\Http\Controllers;

use App\Api\PinterestApi;
use App\Group;
use App\Theme;
use Illuminate\Http\Request;

class GroupController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    public function index()
    {
        $themes = Theme::all();
        $themes = Theme::orderBy('name')->paginate(1);
        return view("home", [ 'data' => Theme::all()]);
//        $theme = Theme::find(1)->first();example-component
//        $grp = Group::find(1)->first();
//        $theme->initGroup($grp);
//
//        dd($theme->sendPost());

    }

    public function store(Request $request)
    {
//        $this->validate($request, [
//            'name' => 'required',
//            'description' => 'required'
//        ]);
//        $task = Theme::create([
//            'name' => $request->name,
//            'description' => $request->description,
//            'user_id' => Auth::id()
//        ]);
//        return response()->json([
//            'task' => $task,
//            'message' => 'ok'
//        ], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param \App\Theme $task
     * @return \Illuminate\Http\Response
     */
    public function show(Theme $task)
    {
//
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Theme $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Theme $task)
    {

}
}

<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{

    public function index()
    {
        dd("ok");
        return Theme::all();
    }

    public function create()
    {
        dd("createok");
    }

    public function show($id)
    {
        return Theme::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $company = Theme::findOrFail($id);
        $company->update($request->all());

        return $company;
    }

    public function store(Request $request)
    {
        $company = Theme::create($request->all());
        return $company;
    }

    public function destroy($id)
    {
        $company = Theme::findOrFail($id);
        $company->delete();
        return '';
    }
}

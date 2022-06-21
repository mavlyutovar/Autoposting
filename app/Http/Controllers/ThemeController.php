<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{

    public function addText(Request $request)
    {
        $theme = Theme::where("ready", null)->first();
        $allText = json_decode($theme->text);

        if(isset($request->all()['text'])) {
            if(isset($theme->text)) {
                $allText->text[] = $request->all()['text'];
            }
        }
        $theme->text = json_encode($allText);
        $theme->update();
        return $theme->text;
    }

    public function showText(Request $request)
    {
        $theme = Theme::where("ready", null)->first();
        return $theme->text;
    }

    public function update(Request $request)
    {
        $theme = Theme::where("ready", null)->first();

        if(isset($theme->text)) {
            $allText = json_decode($theme->text);
            if(isset($request->all()['id'])) {
                $del_id = $request->all()['id'];

                unset($allText->text[$del_id]);
                array_splice($allText->text, $del_id, 0);

            }

            $theme->text = json_encode($allText);
            $theme->update();
        }
        else {
            $theme->text = json_encode(["text" => []]);
        }
        return $theme->text;
    }

    public function index()
    {
        $theme = Theme::where("ready", null)->first();
        return view("home", [ 'data' => $theme]);
    }

    public function create()
    {
        dd("createok");
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

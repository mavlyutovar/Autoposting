<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{

    public function addText(Request $request)//Добавить ограничение и уведомление, что больше пяти нельзя
    {
        $theme = Theme::where("ready", 0)->first();
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
        $theme = Theme::where("ready", 0)->first();
        return $theme->text;
    }

    public function updateText(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();

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

    public function addAudio(Request $request)//Добавить ограничение и уведомление, что больше пяти нельзя
    {
        $theme = Theme::where("ready", 0)->first();
        $allAudio = json_decode($theme->url_audio_board);

        if(isset($request->all()['url_audio_board'])) {
            if(isset($theme->url_audio_board)) {
                $allAudio->audioId[] = $request->all()['url_audio_board'];
            }
        }
        $theme->url_audio_board = json_encode($allAudio);
        $theme->update();
        return $theme->url_audio_board;
    }

    public function showAudio(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();
        return $theme->url_audio_board;
    }

    public function updateAudio(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();

        if(isset($theme->url_audio_board)) {
            $allAudio = json_decode($theme->url_audio_board);
            if(isset($request->all()['id'])) {
                $del_id = $request->all()['id'];

                unset($allAudio->audioId[$del_id]);
                array_splice($allAudio->audioId, $del_id, 0);

            }

            $theme->url_audio_board = json_encode($allAudio);
            $theme->update();
        }
        else {
            $theme->url_audio_board = json_encode(["audioId" => []]);
        }
        return $theme->url_audio_board;
    }

    public function addPicture(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();
        if(isset($request->all()['url_picture_board'])) {
            $theme->url_picture_board = $request->all()['url_picture_board'];
            $theme->update();
        }
        return $theme->url_picture_board;
    }

    public function showPicture(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();
        return $theme->url_picture_board;
    }

    public function updatePicture(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();

        $theme->url_picture_board = null;
        $theme->update();
        return $theme->url_picture_board;
    }

    public function setThemeName(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();
        if(isset($request->all()['name'])) {
            $theme->name = $request->all()['name'];
            $theme->update();
        }
        else {
            $theme->name = "Новый стиль";
            $theme->update();
        }
        return $theme;
    }

    public function getPercentReadyTheme(Request $request) {
        $theme      = Theme::where("ready", 0)->first();
        $percent    = 0;

        if(isset($theme->url_picture_board)){
            $percent += 50;
        }
        if(isset($theme->url_audio_board)) {
            $allAudio       = json_decode($theme->url_audio_board);
            $countAudio     = count($allAudio->audioId);
            $percentAudio   = 0;
            while($countAudio > 0 && $percentAudio < 20){
                $percentAudio    += 4;
                $percent    += 4;
                $countAudio--;
            }
        }
        if(isset($theme->text)) {
            $allText        = json_decode($theme->text);
            $countText      = count($allText->text);
            $percentText    = 0;
            while($countText > 0 && $percentText < 30){
                $percentText    += 6;
                $percent    += 6;
                $countText--;
            }
        }
        return ["data" => $percent];
    }

    public function getSettingTheme(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();
        return $theme->setting;
    }
    public function setSettingTheme(Request $request)
    {
        $theme = Theme::where("ready", 0)->first();

        if(isset($theme->setting)) {
            $settingTheme = json_decode($theme->setting);
            if(isset($request->all()['setting'])) {
                $setting = $request->all()['setting'];
                $settingTheme->textProbability = $setting['textProbability'];
                $settingTheme->audioProbability = $setting['audioProbability'];
                $settingTheme->pictureProbability = $setting['pictureProbability'];
            }

            $theme->setting = json_encode($settingTheme);
            $theme->ready   = 1;
            $theme->update();
        }
        else {
            $theme->setting = json_encode(["setting" => []]);
        }
        return $theme->setting;
    }

    public function getThemeModel(Request $request) {
        $theme = Theme::where("ready", 0)->first();
        return $theme;
    }

    public function getAllTheme(Request $request) {
        $userid = 1;
        $today  = getdate();

        $theme = Theme::where("userid", $userid)->get();
        if(count($theme) == 0){ //Заранее создаем пустое объявление
            $theme = new Theme();
            $theme->name    = "Новый стиль";
            $theme->userid  = $userid;
            $theme->text    = json_encode(["text" => []]);
            $theme->ready   = false;
            $newSetting     = [
                "textProbability"       => 50,
                "audioProbability"      => 50,
                "pictureProbability"    => 50,
            ];
            $theme->setting = json_encode($newSetting);
            $theme->url_audio_board = json_encode(["audioId" => []]);
            $theme->save();
        }
        $theme = Theme::where("ready", 1)->first();
        return $theme;
    }

    public function deleteTheme(Request $request) {
        if(isset($request->all()['id'])) {
            $del_id = $request->all()['id'];
            Theme::find($del_id)->delete();
        }
        $theme = Theme::where("ready", 1)->first();
        return $theme;
    }



    public function index()
    {
        $theme = Theme::where("ready", 1)->first();
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

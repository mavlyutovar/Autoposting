<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function index()
    {
        $theme = $this->getUseNowTheme();
        return view("layouts.theme_list", [ 'data' => $theme]);
    }
    public function addText(Request $request)//Добавить ограничение и уведомление, что больше пяти нельзя
    {
        $theme = $this->getUseNowTheme();
        if(isset($request->all()['text'])) {
            $allText = json_decode($theme->text);
            if(isset($theme->text)) {
                $allText->text[] = $request->all()['text'];
            }
            $theme->text = json_encode($allText);
            $theme->update();
        }
        return $theme->text;
    }

    public function showText(Request $request)
    {
        $theme = $this->getUseNowTheme();
        return $theme->text;
    }

    public function updateText(Request $request)
    {
        $theme = $this->getUseNowTheme();
        if(isset($request->all()['id'])) {
            $allText = json_decode($theme->text);
            $del_id = $request->all()['id'];

            unset($allText->text[$del_id]);
            array_splice($allText->text, $del_id, 0);

            $theme->text = json_encode($allText);
            $theme->update();
        }
        return $theme->text;
    }

    public function addAudio(Request $request)//Добавить ограничение и уведомление, что больше пяти нельзя
    {
        $theme = $this->getUseNowTheme();
        if(isset($request->all()['url_audio_board'])) {
            $allAudio = json_decode($theme->url_audio_board);
            if(isset($theme->url_audio_board)) {
                $allAudio->audioId[] = $request->all()['url_audio_board'];
            }
            $theme->url_audio_board = json_encode($allAudio);
            $theme->update();
        }
        return $theme->url_audio_board;
    }

    public function showAudio(Request $request)
    {
        $theme = $this->getUseNowTheme();
        return $theme->url_audio_board;
    }

    public function updateAudio(Request $request)
    {
        $theme = $this->getUseNowTheme();
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
        $theme = $this->getUseNowTheme();
        if(isset($request->all()['url_picture_board'])) {
            $theme->url_picture_board = $request->all()['url_picture_board'];
            $theme->update();
        }
        return $theme->url_picture_board;
    }

    public function showPicture(Request $request)
    {
        $theme = $this->getUseNowTheme();
        return $theme->url_picture_board;
    }

    public function updatePicture(Request $request)
    {
        $theme = $this->getUseNowTheme();
        $theme->url_picture_board = null;
        $theme->update();
        return $theme->url_picture_board;
    }

    public function setThemeName(Request $request)
    {
        $theme = $this->getUseNowTheme();
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
        $percent    = 0;

        $theme = $this->getUseNowTheme();
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
        $theme = $this->getUseNowTheme();
        return $theme->setting;
    }
    public function setSettingTheme(Request $request)
    {
        $theme = $this->getUseNowTheme();
        if(isset($request->all()['setting'])) {
            $settingTheme = json_decode($theme->setting);
            $setting = $request->all()['setting'];

            $settingTheme->textProbability = $setting['textProbability'];
            $settingTheme->audioProbability = $setting['audioProbability'];
            $settingTheme->pictureProbability = $setting['pictureProbability'];

            $theme->setting = json_encode($settingTheme);
            $theme->status   = "peace";
            $theme->update();
        }
        return $theme->setting;
    }

    public function showThemeModel(Request $request, $id = null)
    {
        if(isset($id)) {
            $theme =  Theme::find($id)->first();
            $settingTheme = json_decode($theme->setting);
            $theme->setting = $settingTheme;
            $allAudio       = json_decode($theme->url_audio_board);
            $theme->url_audio_board = $allAudio;
            $allText        = json_decode($theme->text);
            $theme->text    = $allText;
            return $theme;
        }
        return null;
    }

    public function getThemeModel(Request $request)
    {
        $theme = $this->getUseNowTheme();
        return $theme;
    }

    public function getAllTheme(Request $request)
    {
        $userid = Auth::id();
        $theme  = $this->getIsEditTheme();
        if(count($theme) > 0) { //Убираем статус редактирования.
            $buffer_theme               = $this->getEditBufferTheme();
            $theme->name                = $buffer_theme->name;
            $theme->description         = $buffer_theme->description;
            $theme->setting             = $buffer_theme->setting;
            $theme->url_picture_board   = $buffer_theme->url_picture_board;
            $theme->url_audio_board     = $buffer_theme->url_audio_board;
            $theme->text                = $buffer_theme->text;
            $theme->status              = "peace";
            $theme->update();
            $buffer_theme->delete();
        }
        $theme = Theme::where("userid",$userid)->where("status", "create")->first();
        if(count($theme) == 0){ //Заранее создаем пустое объявление
            $theme = new Theme();
            $theme->name    = "Новый стиль";
            $theme->userid  = $userid;
            $theme->text    = json_encode(["text" => []]);
            $theme->status  = "create";
            $newSetting     = [
                "textProbability"       => 50,
                "audioProbability"      => 50,
                "pictureProbability"    => 50,
            ];
            $theme->setting = json_encode($newSetting);
            $theme->url_audio_board = json_encode(["audioId" => []]);
            $theme->save();
        }
        return $this->getPeaceTheme();
    }

    public function deleteTheme(Request $request, $id)
    {
        if($id) {
            Theme::find($id)->delete();
        }
        return $this->getPeaceTheme();
    }

    public function duplicateThemeModel(Request $request, $id)
    {
        if(isset($id)) {
            $theme = Theme::find($id);
            $cloneTheme = $theme->replicate();
            $cloneTheme->name = "(Копия) ".$theme->name;
            $cloneTheme->save();
        }
        return $this->getPeaceTheme();
    }

    public function setEditTheme(Request $request, $id)
    {
        if(isset($id)) {
            $theme = Theme::find($id);
            $cloneTheme = $theme->replicate();
            $cloneTheme->status = "edit_buffer";
            $cloneTheme->save();

            $theme->status = "isEdit";
            $theme->update();
        }
        return $this->getPeaceTheme();
    }
    public function undoEditTheme(Request $request) {
        $theme = $this->getIsEditTheme();
        $theme->status = "peace";
        $theme->update();

        $theme = $this->getEditBufferTheme();
        $theme->delete();
        return $this->getPeaceTheme();
    }

    public function getUseNowTheme()
    {
        //Сначала работаем с редактируемой темой
        $theme = $this->getEditBufferTheme();
        if(count($theme) > 0){
            return $theme;
        }
        //Потом работаем с новой темой
        $theme = Theme::where('userid', Auth::id())->
        where("status", "create")->first();
        return $theme;
    }

    public function getEditBufferTheme() {
        $theme = Theme::where('userid', Auth::id())->
        where("status", "edit_buffer")->first();
        return $theme;
    }

    public function getIsEditTheme() {
        $theme = Theme::where('userid', Auth::id())->
        where("status", "isEdit")->first();
        return $theme;
    }

    public function getPeaceTheme() {
        $theme = Theme::where('userid', Auth::id())->
        where("status", "peace")->get();
        return $theme;
    }

}

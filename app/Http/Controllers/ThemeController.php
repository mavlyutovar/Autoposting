<?php

namespace App\Http\Controllers;

use App\StyleText;
use App\Text;
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

    public function listCases()
    {
        $cases = [];
        $userid = Auth::id();
        $styles = StyleText::where("userid", $userid)->get();
        foreach ($styles as $style) {
            $case['id']     = $style->id;
            $case['name']   = $style->name;
            $texts = Text::where("style_id", $style->id)->get();
            $orderId = 1;
            foreach ($texts as $item) {
                $case['items'][$orderId] = $item->string_value;
                $orderId++;
            }
            if($orderId == 1) {
                $style->delete();
                continue;
            }
            $cases[] = $case;
        }
        return $cases;
    }

    public function showText(Request $request)
    {
        return $this->listCases();
    }

    public function delText(Request $request, $id)
    {
        if($id) {
            $texts = Text::where('style_id', $id)->get();
            foreach ($texts as $text) {
                $text->delete();
            }
            StyleText::find($id)->delete();
            return $this->listCases();
        }
        return true;
    }


    public function saveText(Request $request)
    {
        $texts = (object)$request->all()['texts'] ?? null;
        $id = (int)$request->all()['id'] ?? null;
        $userid = Auth::id();
        if(isset($texts) && isset($texts->items)) {
            shuffle($texts->items);
            if($texts->is == "new") {
                $style = new StyleText();
                $style->name = $texts->name;
                $style->userid = $userid;
                $style->save();
                $idStyle = $style->id;
                $day = 31;
                foreach ($texts->items as $item) {
                    $newText                = new Text();
                    $newText->style_id      = $idStyle;
                    $newText->string_value  = $item;
                    $newText->case          = $day;
                    $newText->save();
                    $day--;
                    if($day < 1) {
                        $day = 31;
                    }
                }
            }
            else if($texts->is == "edit" && isset($id)) {
                $style = StyleText::find($id);
                $style->name = $texts->name;
                $style->userid = $userid;
                $style->save();
                $idStyle = $style->id;
                $day = 31;

                $oldTexts = Text::where('style_id', $idStyle)->get();
                foreach ($oldTexts as $text) {
                    $text->delete();
                }

                foreach ($texts->items as $item) {
                    $newText                = new Text();
                    $newText->style_id      = $idStyle;
                    $newText->string_value  = $item;
                    $newText->case          = $day;
                    $newText->save();
                    $day--;
                    if($day < 1) {
                        $day = 31;
                    }
                }
            }
        }
        return $this->listCases();
    }

//array:1 [
//"texts" => array:1 [
//0 => array:2 [
//"name" => "фыв"
//"items" => array:3 [
//0 => "ыфв"
//1 => "фыв"
//2 => "фыв"
//]
//]
//]
//]

    public function addAudio(Request $request)//Добавить ограничение и уведомление, что больше пяти нельзя
    {
        $theme = $this->getUseNowTheme();
        if(isset($request->all()['url_audio_board'])) {
            $allAudio = json_decode($theme->url_audio_board);
            if(isset($theme->url_audio_board)) {
                $allAudio->audioId[] = $request->all()['url_audio_board'];
            }
            if(isset($request->all()['rndAudio'])) {
                $allAudio->rndAudio = $request->all()['rndAudio'];
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
            $countArr       = (array) $allAudio->audioId;
            $countAudio     = sizeof($countArr);
            $percentAudio   = 0;
            while($countAudio > 0 && $percentAudio < 20){
                $percentAudio    += 4;
                $percent    += 4;
                $countAudio--;
            }
        }
        if(isset($theme->text)) {
            $allText        = json_decode($theme->text);
            $countArr       = (array) $allText->text;
            $countText      = sizeof($countArr);
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
            $theme =  Theme::find($id);
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
        if(isset($theme)) { //Убираем статус редактирования.
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
        if(!isset($theme)){ //Заранее создаем пустое объявление
            $theme = new Theme();
            $theme->name    = "Новый стиль";
            $theme->userid  = $userid;
            $theme->text    = json_encode([
                "text" => [],
                "rndText" => true,
            ]);
            $theme->status  = "create";
            $newSetting     = [
                "textProbability"       => 50,
                "audioProbability"      => 50,
                "pictureProbability"    => 100,
            ];
            $theme->setting = json_encode($newSetting);
            $theme->url_audio_board = json_encode([
                "audioId" => [],
                "rndAudio" => true,
            ]);
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
        if(isset($theme)){
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

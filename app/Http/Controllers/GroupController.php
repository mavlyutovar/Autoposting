<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = new Group;
        $groups->name = "Сядь за руль своего BMW";
        $groups->url = "https://vk.com/tvoi_bmw";
        $groups->group_vkid = "-112318746";
        $groups->info = "Паблик по интересам";
        $groups->save();
        dd($groups);
    }
}

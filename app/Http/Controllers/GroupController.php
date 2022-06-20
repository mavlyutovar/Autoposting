<?php

namespace App\Http\Controllers;

use App\Api\PinterestApi;
use App\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $linkPinBoard       = 'https://www.pinterest.se/kdnamehere/thismood-picture/';
        $pinterest = new PinterestApi($linkPinBoard);
        $pins = $pinterest->getImagesFromBoard();
        dd($pinterest->getUrlFromPinImage($pins[2]));
    }
}

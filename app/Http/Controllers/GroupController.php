<?php

namespace App\Http\Controllers;

use App\Api\PinterestApi;
use App\Group;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{

    public function index()
    {
        return view("layouts.group_list", [ 'data' => $this->getGroups()]);

    }

    public function addNewGroup(Request $request)
    {
        $group = new Group();
        $group->userid = Auth::id();

        if(isset($request->all()['groupId'])) {
            if(is_numeric($request->all()['groupId'])) {
                $group->group_vkid = $request->all()['groupId'];
            }
        }
        else {
            return ['data' => $this->getGroups()];
        }
        if(isset($request->all()['groupInfo'])) {
            $group->info = $request->all()['groupId'];
        }
        if(isset($request->all()['groupName'])) {
            $group->name = $request->all()['groupName'];
        }
        else {
            $group->name = "Новая группа";
        }
        $group->url = "https://thismood.info/";
        $group->save();
        return $this->getGroups();
    }

    public function showAllGroup()
    {
        return $this->getGroups();
    }

    public function deleteGroup(Request $request, $id)
    {
        if($id) {
            Group::find($id)->delete();
        }
        return $this->getGroups();
    }

    public function getGroups()
    {
        $group = Group::where('userid', Auth::id())->get();
        return $group;

    }
}

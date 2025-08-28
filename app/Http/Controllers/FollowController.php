<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $follow = Follow::where('follower_id', auth()->id())->where('followed_id', $user->id)->first();
        if ($follow) {
            $follow->delete();
        } else {
            Follow::create(['follower_id' => auth()->id(), 'followed_id' => $user->id]);
        }

        return back();
    }
}
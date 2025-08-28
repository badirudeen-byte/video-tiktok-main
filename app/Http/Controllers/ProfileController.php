<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $videos = $user->videos()->with('likes', 'comments')->latest()->paginate(10);
        return view('profile.show', compact('user', 'videos'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'bio' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($request->only('username', 'bio'));

        return redirect()->route('profile.show', auth()->user())->with('success', 'Profile updated!');
    }
}
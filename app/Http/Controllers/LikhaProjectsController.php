<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LikhaProjects;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;

class LikhaProjectsController extends Controller
{
    // show editor
    public function create()
    {
        $user = Auth::user();
        $currentUser = auth()->id();
        $data = \App\Models\User::find($currentUser);
        $likhaProjects = $data->LikhaProjects;
        $isPremium = $data->UserSubscription->isPremium ?? false;
        if (!$isPremium && count($likhaProjects) >= 5) {
            abort(403, 'Unauthorized Action');
        }

        return view('editor.editor');
    }

    // save fields to database
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'editor' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }

        $formFields['user_id'] = auth()->id();

        LikhaProjects::create($formFields);

        return redirect('/dashboard')->with('message', 'New Debt added!');
    }

    public function index()
    {
        return view('dashboard');
    }

    public function show(LikhaProjects $likha)
    {
        return view('likha.show', [
            'likha' => $likha
        ]);
    }

    public function edit(LikhaProjects $likha)
    {
        return view('likha.edit', [
            'likha' => $likha
        ]);
    }

    public function update(Request $request, LikhaProjects $likha)
    {

        if ($likha->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'editor' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }

        $formFields['user_id'] = auth()->id();

        $likha->update($formFields);

        return back()->with('message', 'Changes saved!');
    }

    public function destroy(LikhaProjects $likha)
    {
        if ($likha->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $likha->delete();
        return redirect('dashboard')->with('message', 'Likha successfully deleted');
    }
}
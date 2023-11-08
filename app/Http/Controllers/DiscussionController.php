<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Discussion;
use App\Models\DiscussionComment;

class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discussions = Discussion::where('user_id', auth()->user()->id)
        ->orderBy('updated_at', 'desc')
        ->with('comments')
        ->get();

        return Inertia::render('Discussions/Index', [
            'discussions' => $discussions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Discussions/Create');
    }

    /**@s
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:200'],
            'content' => ['required'],
        ]);

        $discussion = Discussion::create([
            'user_id' => auth()->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'votes' => 0
        ]);
  
        return back()->with([
            'data' => 'Discussion created',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $discussion = Discussion::where('id', $id)
        ->with('comments.user')
        ->with('user')
        ->first();

        return Inertia::render('Discussions/Show', [
            'discussion' => $discussion
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Discussion::where('id', $id)->delete();

        return back()->with([
            'data' => 'Discussion deleted',
        ]);
    }

    public function comment(Request $request, string $id)
    {
        $validated = $request->validate([
            'content' => ['required']
        ]);

        $discussion = DiscussionComment::create([
            'user_id' => auth()->user()->id,
            'discussion_id' => $id,
            'content' => $validated['content'],
        ]);
  
        return back()->with([
            'data' => 'Comment posted!',
        ]);
    }

    public function commentUpdate(Request $request, string $id, string $cid)
    {
        $validated = $request->validate([
            'content' => ['required']
        ]);

        DiscussionComment::where('id', $cid)->update([
            'content' => $validated['content']
        ]);
  
        return back()->with([
            'data' => 'Comment updated!',
        ]);
    }

    public function commentDelete(Request $request, string $id, string $cid)
    {
        DiscussionComment::where([
            'user_id' => auth()->user()->id,
            'discussion_id' => $id,
            'id' => $cid
        ])
        ->delete();
  
        return back()->with([
            'data' => 'Comment deleted!',
        ]);
    }
}

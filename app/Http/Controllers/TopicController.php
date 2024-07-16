<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        $parent_topics = Topic::whereNull('parent_id')->with('children')->get();
        //$topics = Topic::with('children')->get();
        return view('topics.index', compact('parent_topics'));
    }

    public function create($parent_id = null)
    {
        $topics = Topic::all();
        return view('topics.create', compact('topics', 'parent_id'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:topics,id',
        ]);

        Topic::create($request->all());

        return redirect()->route('topics.index')->with('success', 'Topic created successfully.');
    }

    public function show(Topic $topic)
    {
        $topic->load('children');
        return view('topics.show', compact('topic'));
    }


    public function edit(Topic $topic)
    {
        $topics = Topic::all();
        return view('topics.edit', compact('topic', 'topics'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:topics,id',
        ]);

        $topic->update($request->all());

        return redirect()->route('topics.index')->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('topics.index')->with('success', 'Topic deleted successfully.');
    }

    public function children(Topic $topic)
    {
        $children = $topic->children;
        return view('topics.children', compact('children', 'topic'));
    }
}

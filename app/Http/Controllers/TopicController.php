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
        $topic->load('test');
        if (isset($topic->test)) {
            // Obtener todas las preguntas
            $questions = $topic->test->questions;
    
            // Seleccionar aleatoriamente preguntas igual a amount_questions
            if ($topic->test->amount_questions > 0 && $topic->test->amount_questions <= $questions->count()) {
                $questions = $questions->random($topic->test->amount_questions);
            }
            $questions=$questions->shuffle();
    
            // Desordenar opciones y correct_answers consistentemente
            foreach ($questions as $q) {
                $cont = 0;
                foreach ($q->correct_answers as $ca) {
                    if ($ca > 0) $cont++;
                }
                $q->type = $cont > 1 ? "multiple" : 'single';
    
                // Combinar opciones y correct_answers para desordenarlas consistentemente
                $combined = array_map(null, $q->options, $q->correct_answers);
                shuffle($combined);
                $q->options = array_column($combined, 0);
                $q->correct_answers = array_column($combined, 1);
            }
    
            $topic->test->questions = $questions;
        }
        
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

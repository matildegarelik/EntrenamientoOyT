<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Topic;
use App\Models\Question;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with('topic')->get();
        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        $topics = Topic::doesntHave('children')->get();
        return view('tests.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.options' => 'required|array',
            'questions.*.correct_answers' => 'required|array',
        ]);

        $test = Test::create(['topic_id' => $request->topic_id]);

        foreach ($request->questions as $question) {
            $test->questions()->create([
                'question' => $question['question'],
                'options' => $question['options'],
                'correct_answers' => $question['correct_answers'],
            ]);
        }

        return redirect()->route('tests.index')->with('success', 'Test created successfully.');
    }

    public function show(Test $test)
    {
        $test->load('questions');
        return view('tests.show', compact('test'));
    }

    public function edit(Test $test)
    {
        $test->load('questions');
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.options' => 'required|array',
            'questions.*.correct_answers' => 'required|array',
        ]);

        $test->questions()->delete();

        foreach ($request->questions as $question) {
            $test->questions()->create([
                'question' => $question['question'],
                'options' => $question['options'],
                'correct_answers' => $question['correct_answers'],
            ]);
        }

        return redirect()->route('tests.index')->with('success', 'Test updated successfully.');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('tests.index')->with('success', 'Test deleted successfully.');
    }

    public function showUserTest(Topic $topic)
    {
        $topic->load('test.questions');
        return view('tests.user_tests', compact('topic'));
    }

    public function submitUserTest(Request $request, Topic $topic)
    {
        $user = Auth::user();
        $test = $topic->test;
        $correctAnswers = 0;
        $totalQuestions = count($test->questions);
        $userAnswers = [];

        foreach ($test->questions as $question) {
            $userAnswer = $request->input('question_' . $question->id);
            $userAnswers[$question->id] = $userAnswer;
            if (in_array($userAnswer, $question->correct_answers)) {
                $correctAnswers++;
            }
        }

        $score = ($correctAnswers / $totalQuestions) * 100;

        $userTest = new UserTest();
        $userTest->user_id = $user->id;
        $userTest->test_id = $test->id;
        $userTest->score = $score;
        $userTest->answers = $userAnswers;
        $userTest->save();

        return redirect()->route('user.test.results', ['userTest' => $userTest->id])->with('status', 'Test completed successfully!');
    }


    public function showUserTestResults(UserTest $userTest)
    {
        $userTest->load('test.topic', 'user');
        return view('tests.results', compact('userTest'));
    }



}

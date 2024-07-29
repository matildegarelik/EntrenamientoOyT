<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Question;
use App\Models\Topic;
use App\Models\UserTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with('topic')->get();
        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        $topics = Topic::whereDoesntHave('children')->get(); // Only topics that are not parents
        return view('tests.create', compact('topics'));
    }


    public function store(Request $request)
    {
        Log::info('Request data:', $request->all());

        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_answers' => 'required|array',
            'questions.*.correct_answers.*' => 'boolean'
        ]);

        $test = Test::create(['topic_id' => $request->topic_id]);

        foreach ($request->questions as $questionData) {
            $test->questions()->create([
                'question' => $questionData['question'],
                'options' => $questionData['options'],
                'correct_answers' => $questionData['correct_answers'],
            ]);
        }

        return redirect()->route('tests.index')->with('success', 'Test created successfully.');
    
    }
    public function show($id)
    {
        $test = Test::with('questions')->findOrFail($id);
        foreach($test->questions as $q){
            $cont=0;
            foreach($q->correct_answers as $ca){
                if($ca>0) $cont++;
            }
            if($cont>1){
                $q->type="multiple";
            }else{
                $q->type='single';
            }
        }
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

    /*public function showUserTest(Topic $topic)
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
    }*/

    public function submitTest(Request $request, $testId)
    {
        Log::info('Request data:', $request->all());

        $test = Test::findOrFail($testId);
        $answers = $request->input('answers');

        $score = 0;
        $userAnswers = [];

        foreach ($test->questions as $index => $question) {
            $cont=0;
            foreach($question->correct_answers as $ca){
                if($ca>0) $cont++;
            }
            if($cont>1){
                $question->type="multiple";
            }else{
                $question->type='single';
            }


            $correctAnswers = $question->correct_answers;
            $totalCorrectAnswers = array_sum($correctAnswers);

            if ($question->type == 'multiple') {
                $userAnswer = $answers[$index] ?? [];
                $correctCount = 0;
                $incorrectCount = 0;

                foreach ($userAnswer as $answer) {
                    if (isset($correctAnswers[$answer]) && $correctAnswers[$answer] == 1) {
                        $correctCount++;
                    } else {
                        $incorrectCount++;
                    }
                }

                if ($incorrectCount == 0 && $correctCount > 0) {
                    $score += $correctCount / $totalCorrectAnswers;
                }
            } else {
                $userAnswer = $answers[$index]['answer'] ?? null;
                if ($userAnswer !== null && $correctAnswers[$userAnswer] == 1) {
                    $score++;
                }
            }

            $userAnswers[] = [
                'question' => $question->question,
                'options' => $question->options,
                'user_answer' => $userAnswer,
                'correct_answers' => $correctAnswers,
            ];
        }

        UserTest::create([
            'user_id' => Auth::id(),
            'test_id' => $test->id,
            'score' => $score,
            'answers' => $userAnswers,
        ]);

        return redirect()->route('tests.results');
    }

    public function results()
    {
        $userTests = UserTest::with('test.topic', 'test.questions')->where('user_id', auth()->id())->get();
        return view('tests.results', compact('userTests'));
    }
}


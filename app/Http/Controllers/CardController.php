<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter');
        
        if ($filter == 'new') {
            $cards = Card::where('user_id', $user->id)->whereNull('next_reminder')->paginate(10);
        } elseif ($filter == 'pending') {
            $cards = Card::where('user_id', $user->id)->whereDate('next_reminder', now()->toDateString())->paginate(10);
        } else {
            $cards = Card::where('user_id', $user->id)->paginate(10);
        }

        return view('cards.index', compact('cards'));
    }


    public function create()
    {
        $topics = Topic::all();
        return view('cards.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'fragment' => 'required',
            'question' => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        Card::create($data);

        return redirect()->route('cards.index');
    }

    public function show(Card $card)
    {
        return view('cards.show', compact('card'));
    }

    public function edit(Card $card)
    {
        $topics = Topic::all();
        return view('cards.create', compact('card', 'topics'));
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'fragment' => 'required',
            'question' => 'required',
        ]);

        $card->update($request->all());

        return redirect()->route('cards.index');
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return redirect()->route('cards.index');
    }
    
    public function updateNextReminder(Request $request, Card $card)
    {
        $date = now()->addDays($request->input('days'))->toDateString();
        $card->next_reminder = $date;
        $card->save();

        return response()->json(['success' => true]);
    }

}

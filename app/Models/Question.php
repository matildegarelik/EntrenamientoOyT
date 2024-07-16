<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['test_id', 'question', 'options', 'correct_answers'];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}

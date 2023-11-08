<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Category;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Discussion;

class PagesController extends Controller
{
    public function home()
    {
        $quizzes = Quiz::where('user_id', auth()->user()->id)
        ->orderBy('id', 'desc')
        ->get();

        return Inertia::render('Home', [
            'quizzes' => $quizzes
        ]);
    }

    public function subjects()
    {
        $subjects = Subject::with('quizzes')->get();

        return Inertia::render('Subjects', [
            'subjects' => $subjects
        ]);
    }
}

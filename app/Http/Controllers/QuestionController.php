<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Category;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Answer;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Question::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();

        return Inertia::render('Question/Index', [
            'quizzes' => $quizzes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $subjects = Subject::all();

        return Inertia::render('Question/Create', [
            'categories' => $categories,
            'subjects' => $subjects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => ['required'],
            'question' => ['required', 'max:200'],
            'type' => ['required'],
            'score' => ['required'],
            'option1' => ['nullable'],
            'option2' => ['nullable'],
            'option3' => ['nullable'],
            'option4' => ['nullable'],
            'answer' => ['nullable']
        ]);

        $question = Question::create([
            'quiz_id' => $validated['quiz_id'],
            'text' => $validated['question'],
            'type' => $validated['type'],
            'score' => $validated['score']
        ]);

        $answer = null;
        if($validated['answer'] == 'option1') { $answer = $validated['option1']; }
        if($validated['answer'] == 'option2') { $answer = $validated['option2']; }
        if($validated['answer'] == 'option3') { $answer = $validated['option3']; }
        if($validated['answer'] == 'option4') { $answer = $validated['option4']; }

        $choices = [];
        if($validated['option1']) { array_push($choices, $validated['option1']); }
        if($validated['option2']) { array_push($choices, $validated['option2']); }
        if($validated['option3']) { array_push($choices, $validated['option3']); }
        if($validated['option4']) { array_push($choices, $validated['option4']); }

        Answer::create([
            'question_id' => $question->id,
            'choices' => json_encode($choices),
            'answer' => $answer
        ]);
  
        return back()->with([
            'data' => 'Question created',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Category::all();
        $subjects = Subject::all();
        $quiz = Question::where('id', $id)->first();

        return Inertia::render('Question/Show', [
            'categories' => $categories,
            'subjects' => $subjects,
            'quiz' => $quiz
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
        Question::where('id', $id)->delete();

        return back()->with([
            'data' => 'Question deleted',
        ]);
    }
}

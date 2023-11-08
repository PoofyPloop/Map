<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Category;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();

        return Inertia::render('Quiz/Index', [
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

        return Inertia::render('Quiz/Create', [
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
            'title' => ['required', 'max:200'],
            'subject' => ['required'],
            'category' => ['required'],
        ]);

        Quiz::create([
            'user_id' => auth()->user()->id,
            'title' => $validated['title'],
            'subject_id' => $validated['subject'],
            'category_id' => $validated['category']
        ]);
  
        return back()->with([
            'data' => 'Quiz created',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Category::all();
        $subjects = Subject::all();
        $quiz = Quiz::where('id', $id)->with('questions.answer')->first();

        return Inertia::render('Quiz/Show', [
            'categories' => $categories,
            'subjects' => $subjects,
            'quiz' => $quiz
        ]);
    }

    public function test(string $id)
    {
        $categories = Category::all();
        $subjects = Subject::all();
        $quiz = Quiz::where('id', $id)->with('questions.answer')->first();

        return Inertia::render('Quiz', [
            'categories' => $categories,
            'subjects' => $subjects,
            'quiz' => $quiz
        ]);
    }

    public function result(Request $request, string $id)
    {
        $quiz = Quiz::where('id', $id)->with('questions.answer')->first();
        $score = 0;
        $totalScore = 0;
        $i = 0;

        foreach($quiz->questions as $ques) {
            if($ques->answer->answer == $request->answers[$i]) {
                $score = $score + $ques->score;
            }
            $totalScore = $totalScore + $ques->score;
            $i++;
        }

        return Inertia::render('Quiz/Result', [
            'quiz' => $quiz,
            'score' => $score,
            'totalScore' => $totalScore
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $subjects = Subject::all();
        $quiz = Quiz::where('id', $id)->with('questions.answer')->first();

        return Inertia::render('Quiz/Edit', [
            'categories' => $categories,
            'subjects' => $subjects,
            'quiz' => $quiz
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:200'],
            'subject' => ['required'],
            'category' => ['required'],
        ]);

        Quiz::where('id', $id)->update([
            'title' => $validated['title'],
            'subject_id' => $validated['subject'],
            'category_id' => $validated['category']
        ]);
  
        return back()->with([
            'data' => 'Quiz updated',
        ]);
    }

    public function demo(Request $request)
    {
        $math = Subject::where('title', 'Math')->first();
        $algebra = Category::where('title', 'Algebra')->first();

        // create quiz 1
        $quiz1 = Quiz::create([
            'user_id' => auth()->user()->id,
            'title' => 'Algebra 1 Test',
            'subject_id' => $math->id,
            'category_id' => $algebra->id
        ]);

        // add quiz 1 question and answers
        $question = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'What is the solution to the equation 2x+5=15?',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question->id,
            'choices' => json_encode([
                'x=5', 'x=10', 'x=7', 'x=8'
            ]),
            'answer' => 'x=5'
        ]);

        $question2 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'Simplify the expression 3x^2+2x−7 when x=2.',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question2->id,
            'choices' => json_encode([
                '5', '12', '9', '17'
            ]),
            'answer' => '9'
        ]);

        $question3 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'Which of the following is the equation of a line in slope-intercept form?',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question3->id,
            'choices' => json_encode([
                '2x-3y=7', 'y=4x+2', '3x+2y=8', 'x-5y=10'
            ]),
            'answer' => 'y=4x+2'
        ]);

        $question4 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'If 3(x−4)=21, what is the value of x?',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question4->id,
            'choices' => json_encode([
                '5', '7', '9', '11'
            ]),
            'answer' => '11'
        ]);

        $question5 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'Solve for y in the equation 2y+3=7.',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question5->id,
            'choices' => json_encode([
                'y=2', 'y=13', 'y=4', 'y=5'
            ]),
            'answer' => 'y=2'
        ]);

        $question6 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'In the equation 5x+8=2x-4, x=4.',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question6->id,
            'choices' => json_encode([
                'True', 'False'
            ]),
            'answer' => 'False'
        ]);

        $question7 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'The quadratic equation x^2+4x−5=0 has two real roots.',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question7->id,
            'choices' => json_encode([
                'True', 'False'
            ]),
            'answer' => 'True'
        ]);

        $question8 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'The slope of a horizontal line is zero.',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question8->id,
            'choices' => json_encode([
                'True', 'False'
            ]),
            'answer' => 'True'
        ]);

        $question9 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'The absolute value of any real number is always positive.',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question9->id,
            'choices' => json_encode([
                'True', 'False'
            ]),
            'answer' => 'True'
        ]);

        $question10 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'The solution to the equation 3(2x+1)=2(3x−4) is x=−5.',
            'type' => 'mcq',
            'score' => 1
        ]);
        Answer::create([
            'question_id' => $question10->id,
            'choices' => json_encode([
                'True', 'False'
            ]),
            'answer' => 'False'
        ]);
  
        return back()->with([
            'data' => 'Quiz updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Quiz::where('id', $id)->delete();

        return back()->with([
            'data' => 'Quiz created',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        $quizzesByCourse = [
            'Introduction to Web Development' => [
                [
                    'title' => 'HTML & CSS Basics',
                    'passing_score' => 60,
                    'time_limit' => 15,
                    'questions' => [
                        ['question' => 'What does HTML stand for?', 'correct_answer' => 'HyperText Markup Language', 'marks' => 10],
                        ['question' => 'Which CSS property is used to change the text color?', 'correct_answer' => 'color', 'marks' => 10],
                        ['question' => 'What is the correct HTML tag for a hyperlink?', 'correct_answer' => '<a>', 'marks' => 10],
                        ['question' => 'Which CSS property controls the layout direction in Flexbox?', 'correct_answer' => 'flex-direction', 'marks' => 10],
                    ],
                ],
            ],
            'Advanced Laravel: Build Real-World Apps' => [
                [
                    'title' => 'Laravel Fundamentals',
                    'passing_score' => 70,
                    'time_limit' => 20,
                    'questions' => [
                        ['question' => 'Which artisan command creates a new controller?', 'correct_answer' => 'make:controller', 'marks' => 10],
                        ['question' => 'What is the default Eloquent ORM namespace?', 'correct_answer' => 'App\Models', 'marks' => 10],
                        ['question' => 'Which method is used to define a one-to-many relationship?', 'correct_answer' => 'hasMany', 'marks' => 10],
                    ],
                ],
            ],
            'UI/UX Design Masterclass' => [
                [
                    'title' => 'Design Principles',
                    'passing_score' => 50,
                    'time_limit' => 10,
                    'questions' => [
                        ['question' => 'What does UX stand for?', 'correct_answer' => 'User Experience', 'marks' => 10],
                        ['question' => 'Which tool is commonly used for wireframing?', 'correct_answer' => 'Figma', 'marks' => 10],
                        ['question' => 'What color model is used for digital screens?', 'correct_answer' => 'RGB', 'marks' => 10],
                    ],
                ],
            ],
            'Python for Data Science' => [
                [
                    'title' => 'Python Data Structures',
                    'passing_score' => 60,
                    'time_limit' => 15,
                    'questions' => [
                        ['question' => 'Which library is used for numerical computing in Python?', 'correct_answer' => 'NumPy', 'marks' => 10],
                        ['question' => 'What Pandas data structure is a 2D labeled data structure?', 'correct_answer' => 'DataFrame', 'marks' => 10],
                        ['question' => 'Which method is used to read a CSV file in Pandas?', 'correct_answer' => 'read_csv', 'marks' => 10],
                    ],
                ],
            ],
        ];

        foreach ($courses as $course) {
            $quizzes = $quizzesByCourse[$course->title] ?? [];

            foreach ($quizzes as $quizData) {
                $questions = $quizData['questions'];
                unset($quizData['questions']);

                $optionsByQuestion = [
                    'What does HTML stand for?' => ['HyperText Markup Language', 'HyperTransfer Markup Language', 'Home Tool Markup Language', 'None of the above'],
                    'Which CSS property is used to change the text color?' => ['color', 'font-color', 'text-color', 'background-color'],
                    'What is the correct HTML tag for a hyperlink?' => ['<a>', '<link>', '<href>', '<url>'],
                    'Which CSS property controls the layout direction in Flexbox?' => ['flex-direction', 'direction', 'layout', 'flex-layout'],
                    'Which artisan command creates a new controller?' => ['make:controller', 'create:controller', 'new:controller', 'generate:controller'],
                    'What is the default Eloquent ORM namespace?' => ['App\Models', 'App\Model', 'App\ORM', 'App\Eloquent'],
                    'Which method is used to define a one-to-many relationship?' => ['hasMany', 'belongsTo', 'hasOne', 'belongsToMany'],
                    'What does UX stand for?' => ['User Experience', 'User Extension', 'Universal Experience', 'Unique Xperience'],
                    'Which tool is commonly used for wireframing?' => ['Figma', 'Photoshop', 'Illustrator', 'After Effects'],
                    'What color model is used for digital screens?' => ['RGB', 'CMYK', 'HSL', 'HEX'],
                    'Which library is used for numerical computing in Python?' => ['NumPy', 'Pandas', 'Matplotlib', 'Scikit-learn'],
                    'What Pandas data structure is a 2D labeled data structure?' => ['DataFrame', 'Series', 'Array', 'Matrix'],
                    'Which method is used to read a CSV file in Pandas?' => ['read_csv', 'load_csv', 'import_csv', 'open_csv'],
                ];

                $quiz = Quiz::updateOrCreate(
                    ['course_id' => $course->id, 'title' => $quizData['title']],
                    [
                        'instructions' => "Answer all questions. Passing score: {$quizData['passing_score']}%.",
                        'time_limit' => $quizData['time_limit'],
                        'passing_score' => $quizData['passing_score'],
                        'status' => 'published',
                    ]
                );

                $totalMarks = 0;
                foreach ($questions as $i => $qData) {
                    $options = $optionsByQuestion[$qData['question']] ?? ['Option A', 'Option B', 'Option C', 'Option D'];
                    $totalMarks += $qData['marks'];
                    QuizQuestion::updateOrCreate(
                        ['quiz_id' => $quiz->id, 'question' => $qData['question']],
                        [
                            'type' => 'multiple_choice',
                            'options' => $options,
                            'correct_answer' => $qData['correct_answer'],
                            'marks' => $qData['marks'],
                            'order' => $i + 1,
                        ]
                    );
                }
                $quiz->update(['total_marks' => $totalMarks]);
            }
        }
    }
}

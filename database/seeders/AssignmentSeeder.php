<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        $assignmentsByCourse = [
            'Introduction to Web Development' => [
                'title' => 'Build a Personal Portfolio Page',
                'description' => 'Create a personal portfolio webpage using HTML and CSS. The page should include a header with navigation, an about section, a projects section showcasing at least 3 projects, and a contact form.',
                'instructions' => "1. Create an HTML file with semantic markup\n2. Style it with external CSS\n3. Make it responsive using media queries\n4. Use Flexbox or Grid for layout\n5. Add a contact form with validation\n6. Deploy using GitHub Pages or any hosting platform",
                'total_marks' => 100,
            ],
            'Advanced Laravel: Build Real-World Apps' => [
                'title' => 'Build a Task Management API',
                'description' => 'Build a RESTful API for a task management application using Laravel. The API should support user authentication, CRUD operations for tasks, and team collaboration.',
                'instructions' => "1. Set up authentication with Laravel Sanctum\n2. Create Task model with migration\n3. Implement CRUD endpoints\n4. Add authorization with policies\n5. Write feature tests\n6. Document endpoints using API resources",
                'total_marks' => 100,
            ],
            'UI/UX Design Masterclass' => [
                'title' => 'Redesign a Mobile App Interface',
                'description' => 'Choose an existing mobile app and redesign its user interface. Create wireframes, high-fidelity mockups, and an interactive prototype in Figma.',
                'instructions' => "1. Select an app to redesign\n2. Conduct a heuristic evaluation\n3. Create low-fidelity wireframes\n4. Design high-fidelity mockups\n5. Build an interactive prototype\n6. Write a case study explaining your design decisions",
                'total_marks' => 100,
            ],
            'Python for Data Science' => [
                'title' => 'Exploratory Data Analysis Project',
                'description' => 'Perform an exploratory data analysis on a dataset of your choice. Use Python, Pandas, and Matplotlib to clean, analyze, and visualize the data.',
                'instructions' => "1. Choose a dataset from Kaggle or other source\n2. Load and inspect the data\n3. Clean missing values and outliers\n4. Perform statistical analysis\n5. Create at least 5 visualizations\n6. Summarize your findings in a Jupyter notebook",
                'total_marks' => 100,
            ],
        ];

        foreach ($courses as $course) {
            $data = $assignmentsByCourse[$course->title] ?? null;
            if (!$data) continue;

            Assignment::updateOrCreate(
                ['course_id' => $course->id, 'title' => $data['title']],
                [
                    'description' => $data['description'],
                    'instructions' => $data['instructions'],
                    'due_date' => now()->addMonths(3),
                    'total_marks' => $data['total_marks'],
                    'status' => 'published',
                ]
            );
        }
    }
}

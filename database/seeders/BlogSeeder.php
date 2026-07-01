<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        // Create blog categories
        $categories = ['Tutorials', 'Industry News', 'Career Advice'];
        foreach ($categories as $name) {
            BlogCategory::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'status' => 'active',
            ]);
        }

        $blogCategories = BlogCategory::all();

        // Create blog posts
        $posts = [
            [
                'title' => 'Getting Started with Laravel: A Beginners Guide',
                'content' => "Laravel has become one of the most popular PHP frameworks for web development. In this comprehensive guide, we will walk you through everything you need to know to get started with Laravel.\n\nFirst, you will need to install Laravel using Composer. Once installed, you can start building your application using Laravels elegant syntax and powerful features. The framework follows the MVC architectural pattern, making it easy to organize your code.\n\nOne of the best features of Laravel is Eloquent ORM, which provides a beautiful Active Record implementation for working with your database. You can define relationships between models, use query scopes, and leverage eager loading to optimize your queries.\n\nLaravel also includes a powerful routing system, middleware for filtering HTTP requests, and a robust authentication system out of the box. The Blade templating engine allows you to create dynamic views with ease.\n\nWhether you are building a simple blog or a complex enterprise application, Laravel provides the tools and flexibility you need to succeed.",
                'excerpt' => 'A complete beginner-friendly guide to getting started with Laravel PHP framework, covering installation, MVC architecture, Eloquent ORM, and more.',
            ],
            [
                'title' => 'Top 10 Web Development Trends in 2026',
                'content' => "The web development landscape continues to evolve at a rapid pace. Here are the top trends shaping the industry in 2026.\n\n1. AI-Powered Development: Artificial intelligence is transforming how we build web applications. From code generation to automated testing, AI tools are becoming essential parts of the development workflow.\n\n2. WebAssembly: WebAssembly continues to gain traction, enabling high-performance applications in the browser. Languages like Rust and Go are increasingly being used for web development.\n\n3. Edge Computing: Serverless and edge computing are changing how we deploy and scale applications. Services like Cloudflare Workers and AWS Lambda@Edge enable faster response times.\n\n4. Progressive Web Apps: PWAs continue to blur the line between web and native applications, offering offline capabilities, push notifications, and native-like experiences.\n\n5. Microservices Architecture: More organizations are adopting microservices to build scalable and maintainable applications.",
                'excerpt' => 'Discover the top 10 web development trends for 2026, including AI-powered development, WebAssembly, edge computing, and more.',
            ],
            [
                'title' => 'How to Build a Successful Online Learning Platform',
                'content' => "Building an online learning platform is an exciting venture that requires careful planning and execution. Here are the key steps to success.\n\n1. Define Your Niche: Identify your target audience and the specific subjects you want to teach. A focused approach often works better than trying to cover everything.\n\n2. Choose the Right Technology: Your platform needs to be scalable, secure, and user-friendly. An LMS built with Laravel provides a solid foundation with its robust ecosystem.\n\n3. Create Quality Content: The success of your platform depends on the quality of your courses. Invest in professional video production, well-structured curriculum, and engaging assignments.\n\n4. Implement Features Users Love: Features like progress tracking, certificates, quizzes, and community forums keep learners engaged and motivated.\n\n5. Marketing and Growth: Use content marketing, social media, and partnerships to reach your target audience.",
                'excerpt' => 'Learn the key steps to building a successful online learning platform, from defining your niche to marketing and growth strategies.',
            ],
            [
                'title' => 'Understanding the MVC Pattern in Web Development',
                'content' => "The Model-View-Controller (MVC) pattern is a fundamental architectural pattern in web development. Understanding it is crucial for building maintainable applications.\n\nThe Model represents the data and business logic of your application. In Laravel, models are typically Eloquent models that interact with your database. They handle data validation, relationships, and business rules.\n\nThe View is responsible for presenting data to the user. In Laravel, Blade templates are used to create dynamic HTML views. Views should be simple and focused on presentation only.\n\nThe Controller acts as an intermediary between Models and Views. It handles user requests, processes data through models, and returns appropriate views. Controllers keep your application organized and maintainable.\n\nBy separating concerns, MVC makes your code more modular, testable, and easier to maintain. Laravel implements MVC beautifully, making it a great choice for web developers.",
                'excerpt' => 'A comprehensive explanation of the Model-View-Controller (MVC) pattern and how it is implemented in Laravel for web development.',
            ],
            [
                'title' => 'Career Paths in Data Science: What You Need to Know',
                'content' => "Data science continues to be one of the most in-demand fields in technology. Here is what you need to know about pursuing a career in data science.\n\nData science combines statistics, programming, and domain expertise to extract insights from data. The field offers various career paths including data analyst, data engineer, machine learning engineer, and data scientist.\n\nTo get started, you should learn Python programming, statistics fundamentals, and data manipulation libraries like Pandas and NumPy. Data visualization skills with Matplotlib and Seaborn are also essential.\n\nMachine learning knowledge is increasingly important. Start with supervised learning algorithms like linear regression and decision trees, then move on to more advanced topics like neural networks.\n\nBuilding a portfolio of projects is crucial for landing your first data science role. Participate in Kaggle competitions, work on real-world datasets, and share your findings on GitHub.",
                'excerpt' => 'Explore the various career paths in data science and learn what skills, tools, and education you need to succeed in this growing field.',
            ],
        ];

        foreach ($posts as $i => $postData) {
            Blog::updateOrCreate(['slug' => Str::slug($postData['title'])], [
                'title' => $postData['title'],
                'content' => $postData['content'],
                'excerpt' => $postData['excerpt'],
                'blog_category_id' => $blogCategories[$i % count($blogCategories)]->id,
                'user_id' => $admin->id,
                'status' => 'published',
            ]);
        }
    }
}

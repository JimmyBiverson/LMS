-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 26, 2026 at 06:46 PM
-- Server version: 8.4.7-7
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--
CREATE DATABASE IF NOT EXISTS `lms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `lms`;

-- --------------------------------------------------------

--
-- Table structure for table `achievement_badges`
--

CREATE TABLE `achievement_badges` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '#5F3EED',
  `points` int NOT NULL DEFAULT '0',
  `criteria_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criteria` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `achievement_badges`
--

INSERT INTO `achievement_badges` (`id`, `name`, `slug`, `description`, `icon`, `icon_color`, `points`, `criteria_type`, `criteria`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'First Steps', 'first-steps', 'Completed your first lesson', 'ri-footprint-line', '#5F3EED', 10, 'first_lesson', '{\"lesson_count\":1}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(2, 'Eager Learner', 'eager-learner', 'Completed 10 lessons', 'ri-book-open-line', '#5F3EED', 50, 'ten_lessons', '{\"lesson_count\":10}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(3, 'Course Conqueror', 'course-conqueror', 'Completed your first course', 'ri-graduation-cap-line', '#5F3EED', 100, 'course_complete', '{\"course_count\":1}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(4, 'Perfect Score', 'perfect-score', 'Scored 100% on a quiz', 'ri-award-line', '#5F3EED', 75, 'perfect_quiz', '{\"perfect_score\":true}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(5, 'Welcome Aboard', 'welcome-aboard', 'Signed up for an account', 'ri-user-smile-line', '#5F3EED', 5, 'first_login', '{\"login_count\":1}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(6, 'Star Reviewer', 'star-reviewer', 'Reviewed 5 courses', 'ri-star-line', '#5F3EED', 30, 'reviewer', '{\"review_count\":5}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(7, 'Dedicated Scholar', 'dedicated-scholar', 'Studied for 30 days in a row', 'ri-fire-line', '#5F3EED', 200, 'streak', '{\"streak_days\":30}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(8, 'Knowledge Seeker', 'knowledge-seeker', 'Enrolled in 5 courses', 'ri-database-2-line', '#5F3EED', 40, 'enrollment_milestone', '{\"course_count\":5}', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text COLLATE utf8mb4_unicode_ci,
  `due_date` date DEFAULT NULL,
  `total_marks` int NOT NULL DEFAULT '100',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `instructions_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_limit_minutes` int DEFAULT NULL,
  `max_file_size_mb` int NOT NULL DEFAULT '10',
  `allowed_file_types` text COLLATE utf8mb4_unicode_ci,
  `late_submission_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `late_penalty_percent` decimal(10,2) DEFAULT NULL,
  `grading_rubric` text COLLATE utf8mb4_unicode_ci,
  `available_from` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `course_id`, `user_id`, `title`, `description`, `instructions`, `due_date`, `total_marks`, `status`, `created_at`, `updated_at`, `instructions_file`, `time_limit_minutes`, `max_file_size_mb`, `allowed_file_types`, `late_submission_allowed`, `late_penalty_percent`, `grading_rubric`, `available_from`) VALUES
(1, 1, NULL, 'Build a Personal Portfolio Page', 'Create a personal portfolio webpage using HTML and CSS. The page should include a header with navigation, an about section, a projects section showcasing at least 3 projects, and a contact form.', '1. Create an HTML file with semantic markup\n2. Style it with external CSS\n3. Make it responsive using media queries\n4. Use Flexbox or Grid for layout\n5. Add a contact form with validation\n6. Deploy using GitHub Pages or any hosting platform', '2026-09-28', 100, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL, 10, NULL, 0, NULL, NULL, NULL),
(2, 2, NULL, 'Build a Task Management API', 'Build a RESTful API for a task management application using Laravel. The API should support user authentication, CRUD operations for tasks, and team collaboration.', '1. Set up authentication with Laravel Sanctum\n2. Create Task model with migration\n3. Implement CRUD endpoints\n4. Add authorization with policies\n5. Write feature tests\n6. Document endpoints using API resources', '2026-09-28', 100, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL, 10, NULL, 0, NULL, NULL, NULL),
(3, 3, NULL, 'Redesign a Mobile App Interface', 'Choose an existing mobile app and redesign its user interface. Create wireframes, high-fidelity mockups, and an interactive prototype in Figma.', '1. Select an app to redesign\n2. Conduct a heuristic evaluation\n3. Create low-fidelity wireframes\n4. Design high-fidelity mockups\n5. Build an interactive prototype\n6. Write a case study explaining your design decisions', '2026-09-28', 100, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL, 10, NULL, 0, NULL, NULL, NULL),
(4, 4, NULL, 'Exploratory Data Analysis Project', 'Perform an exploratory data analysis on a dataset of your choice. Use Python, Pandas, and Matplotlib to clean, analyze, and visualize the data.', '1. Choose a dataset from Kaggle or other source\n2. Load and inspect the data\n3. Clean missing values and outliers\n4. Perform statistical analysis\n5. Create at least 5 visualizations\n6. Summarize your findings in a Jupyter notebook', '2026-09-28', 100, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL, 10, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `id` int NOT NULL,
  `assignment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `submission_text` text COLLATE utf8mb4_unicode_ci,
  `file_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` int NOT NULL,
  `class_id` int NOT NULL,
  `student_id` int NOT NULL,
  `course_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_category_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `content`, `excerpt`, `blog_category_id`, `user_id`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Getting Started with Laravel: A Beginners Guide', 'getting-started-with-laravel-a-beginners-guide', 'Laravel has become one of the most popular PHP frameworks for web development. In this comprehensive guide, we will walk you through everything you need to know to get started with Laravel.\n\nFirst, you will need to install Laravel using Composer. Once installed, you can start building your application using Laravels elegant syntax and powerful features. The framework follows the MVC architectural pattern, making it easy to organize your code.\n\nOne of the best features of Laravel is Eloquent ORM, which provides a beautiful Active Record implementation for working with your database. You can define relationships between models, use query scopes, and leverage eager loading to optimize your queries.\n\nLaravel also includes a powerful routing system, middleware for filtering HTTP requests, and a robust authentication system out of the box. The Blade templating engine allows you to create dynamic views with ease.\n\nWhether you are building a simple blog or a complex enterprise application, Laravel provides the tools and flexibility you need to succeed.', 'A complete beginner-friendly guide to getting started with Laravel PHP framework, covering installation, MVC architecture, Eloquent ORM, and more.', 1, 1, NULL, 'published', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(2, 'Top 10 Web Development Trends in 2026', 'top-10-web-development-trends-in-2026', 'The web development landscape continues to evolve at a rapid pace. Here are the top trends shaping the industry in 2026.\n\n1. AI-Powered Development: Artificial intelligence is transforming how we build web applications. From code generation to automated testing, AI tools are becoming essential parts of the development workflow.\n\n2. WebAssembly: WebAssembly continues to gain traction, enabling high-performance applications in the browser. Languages like Rust and Go are increasingly being used for web development.\n\n3. Edge Computing: Serverless and edge computing are changing how we deploy and scale applications. Services like Cloudflare Workers and AWS Lambda@Edge enable faster response times.\n\n4. Progressive Web Apps: PWAs continue to blur the line between web and native applications, offering offline capabilities, push notifications, and native-like experiences.\n\n5. Microservices Architecture: More organizations are adopting microservices to build scalable and maintainable applications.', 'Discover the top 10 web development trends for 2026, including AI-powered development, WebAssembly, edge computing, and more.', 2, 1, NULL, 'published', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(3, 'How to Build a Successful Online Learning Platform', 'how-to-build-a-successful-online-learning-platform', 'Building an online learning platform is an exciting venture that requires careful planning and execution. Here are the key steps to success.\n\n1. Define Your Niche: Identify your target audience and the specific subjects you want to teach. A focused approach often works better than trying to cover everything.\n\n2. Choose the Right Technology: Your platform needs to be scalable, secure, and user-friendly. An LMS built with Laravel provides a solid foundation with its robust ecosystem.\n\n3. Create Quality Content: The success of your platform depends on the quality of your courses. Invest in professional video production, well-structured curriculum, and engaging assignments.\n\n4. Implement Features Users Love: Features like progress tracking, certificates, quizzes, and community forums keep learners engaged and motivated.\n\n5. Marketing and Growth: Use content marketing, social media, and partnerships to reach your target audience.', 'Learn the key steps to building a successful online learning platform, from defining your niche to marketing and growth strategies.', 3, 1, NULL, 'published', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(4, 'Understanding the MVC Pattern in Web Development', 'understanding-the-mvc-pattern-in-web-development', 'The Model-View-Controller (MVC) pattern is a fundamental architectural pattern in web development. Understanding it is crucial for building maintainable applications.\n\nThe Model represents the data and business logic of your application. In Laravel, models are typically Eloquent models that interact with your database. They handle data validation, relationships, and business rules.\n\nThe View is responsible for presenting data to the user. In Laravel, Blade templates are used to create dynamic HTML views. Views should be simple and focused on presentation only.\n\nThe Controller acts as an intermediary between Models and Views. It handles user requests, processes data through models, and returns appropriate views. Controllers keep your application organized and maintainable.\n\nBy separating concerns, MVC makes your code more modular, testable, and easier to maintain. Laravel implements MVC beautifully, making it a great choice for web developers.', 'A comprehensive explanation of the Model-View-Controller (MVC) pattern and how it is implemented in Laravel for web development.', 1, 1, NULL, 'published', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(5, 'Career Paths in Data Science: What You Need to Know', 'career-paths-in-data-science-what-you-need-to-know', 'Data science continues to be one of the most in-demand fields in technology. Here is what you need to know about pursuing a career in data science.\n\nData science combines statistics, programming, and domain expertise to extract insights from data. The field offers various career paths including data analyst, data engineer, machine learning engineer, and data scientist.\n\nTo get started, you should learn Python programming, statistics fundamentals, and data manipulation libraries like Pandas and NumPy. Data visualization skills with Matplotlib and Seaborn are also essential.\n\nMachine learning knowledge is increasingly important. Start with supervised learning algorithms like linear regression and decision trees, then move on to more advanced topics like neural networks.\n\nBuilding a portfolio of projects is crucial for landing your first data science role. Participate in Kaggle competitions, work on real-world datasets, and share your findings on GitHub.', 'Explore the various career paths in data science and learn what skills, tools, and education you need to succeed in this growing field.', 2, 1, NULL, 'published', '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tutorials', 'tutorials', 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(2, 'Industry News', 'industry-news', 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(3, 'Career Advice', 'career-advice', 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `bundles`
--

CREATE TABLE `bundles` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(10,2) DEFAULT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bundles`
--

INSERT INTO `bundles` (`id`, `title`, `slug`, `description`, `price`, `sale_price`, `level`, `thumbnail`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Web Development Bundle', 'web-development-bundle', 'Master both frontend and backend web development with this comprehensive bundle covering HTML, CSS, JavaScript, and Laravel.', 129.99, 79.99, NULL, NULL, 'active', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(2, 'Design & Data Science Bundle', 'design-data-science-bundle', 'Combine creative design skills with data science expertise to become a versatile tech professional.', 99.99, 69.99, NULL, NULL, 'active', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `bundle_course`
--

CREATE TABLE `bundle_course` (
  `id` int NOT NULL,
  `bundle_id` int NOT NULL,
  `course_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bundle_course`
--

INSERT INTO `bundle_course` (`id`, `bundle_id`, `course_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `cart_id` int NOT NULL,
  `item_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Web Development', 'web-development', 'Courses related to Web Development', 'active', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(2, 'Data Science', 'data-science', 'Courses related to Data Science', 'active', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(3, 'Design', 'design', 'Courses related to Design', 'active', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(4, 'Mobile Development', 'mobile-development', 'Courses related to Mobile Development', 'active', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(5, 'Business', 'business', 'Courses related to Business', 'active', '2026-06-28 16:52:58', '2026-06-28 16:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_templates`
--

CREATE TABLE `certificate_templates` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `background_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'top-center',
  `title_font` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sans-serif',
  `title_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#111827',
  `body_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#374151',
  `layout` text COLLATE utf8mb4_unicode_ci,
  `include_qr` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `state_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Los Angeles', 1, 'active', NULL, NULL),
(2, 'San Francisco', 1, 'active', NULL, NULL),
(3, 'Austin', 2, 'active', NULL, NULL),
(4, 'London', 3, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `room` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int NOT NULL DEFAULT '30',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'contact',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `instructor_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `type`, `is_read`, `created_at`, `updated_at`, `instructor_id`) VALUES
(1, 'Biverson Jimmy', 'Gyorgy.feher@pilbara.eu', '0776000909', 'dfdg', 'test', 'instructor_contact', 0, '2026-06-30 06:53:37', '2026-06-30 06:53:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'United States', 'US', 'active', NULL, NULL),
(2, 'United Kingdom', 'GB', 'active', NULL, NULL),
(3, 'Canada', 'CA', 'active', NULL, NULL),
(4, 'Australia', 'AU', 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `max_uses` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `min_amount` decimal(10,2) DEFAULT NULL,
  `expires_at` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount`, `discount_type`, `max_uses`, `used_count`, `min_amount`, `expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME20', 20.00, 'percentage', 100, 5, 0.00, '2027-06-28', 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `outcomes` text COLLATE utf8mb4_unicode_ci,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `instructor_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `level_id` int DEFAULT NULL,
  `preview_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_video_duration` int DEFAULT NULL,
  `thumbnail_updated_at` timestamp NULL DEFAULT NULL,
  `enrollment_count` int NOT NULL DEFAULT '0',
  `average_rating` decimal(10,2) DEFAULT NULL,
  `completion_rate` decimal(10,2) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `metadata` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `user_id`, `title`, `description`, `category`, `price`, `duration`, `status`, `thumbnail`, `created_at`, `updated_at`, `outcomes`, `requirements`, `slug`, `sale_price`, `payment_type`, `instructor_id`, `category_id`, `level_id`, `preview_video`, `preview_video_duration`, `thumbnail_updated_at`, `enrollment_count`, `average_rating`, `completion_rate`, `is_featured`, `metadata`) VALUES
(1, 6, 'Introduction to Web Development', 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for complete beginners.', 'Web Development', 0.00, '6h 30m', 'Active', NULL, '2026-06-28 16:52:58', '2026-06-28 16:52:58', 'Build responsive web pages using HTML5 and CSS3\nWrite JavaScript programs to add interactivity to websites\nUnderstand the client-server architecture of the web\nUse developer tools to debug and inspect web pages\nDeploy a static website to production', 'A computer with internet access\nNo prior coding experience required\nWillingness to learn and practice', 'introduction-to-web-development', NULL, 'free', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(2, 6, 'Advanced Laravel: Build Real-World Apps', 'Master Laravel by building a complete real-world application from scratch. Covers authentication, APIs, testing, and deployment.', 'Web Development', 49.99, '12h 45m', 'Active', NULL, '2026-06-28 16:52:58', '2026-06-28 16:52:58', 'Build a complete Laravel application from scratch\nImplement authentication and authorization\nCreate RESTful APIs with Laravel\nWrite feature and unit tests\nDeploy a Laravel app to production', 'Basic PHP knowledge\nFamiliarity with MVC pattern\nLaravel installed on your machine', 'advanced-laravel-build-real-world-apps', NULL, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(3, 6, 'UI/UX Design Masterclass', 'Learn professional UI/UX design principles, Figma workflows, and portfolio-building techniques.', 'Design', 79.99, '8h 20m', 'Active', NULL, '2026-06-28 16:52:58', '2026-06-28 16:52:58', 'Apply UX research methods to understand user needs\nCreate wireframes and interactive prototypes in Figma\nDesign accessible and inclusive user interfaces\nBuild a professional design portfolio', 'A computer with Figma installed (free tier is fine)\nNo prior design experience required\nA creative mindset', 'ui-ux-design-masterclass', 39.99, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(4, 6, 'Python for Data Science', 'Get started with Python programming for data analysis, visualization, and machine learning.', 'Data Science', 0.00, '5h 00m', 'Active', NULL, '2026-06-28 16:52:58', '2026-06-28 16:52:58', 'Write Python programs using core language features\nManipulate data with Pandas DataFrames\nCreate visualizations with Matplotlib and Seaborn\nBuild and evaluate basic machine learning models', 'Basic computer literacy\nNo programming experience required\nPython 3 installed on your machine', 'python-for-data-science', NULL, 'free', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(5, 21, 'marketing', 'To reach how to market', 'Business', 0.00, '4 hours', 'Active', 'courses/thumbnails/1782724319_FKqcc6vs.webp', '2026-06-29 06:11:59', '2026-06-29 06:11:59', 'how to merket the products', 'without any knowledge', 'marketing-59o3g', NULL, 'free', NULL, 5, 3, 'courses/preview-videos/1782724319_nlRxakkn.mp4', NULL, NULL, 0, NULL, NULL, 0, NULL),
(6, 7, 'test', 'test description', 'Data Science', 0.00, '2 hours', 'Active', 'courses/thumbnails/1784056089_sglTfESq.webp', '2026-07-14 16:08:10', '2026-07-14 16:08:10', 'test learnign outcomes', 'test requirements', 'test-UoGxl', NULL, 'free', NULL, 2, 2, 'courses/preview-videos/1784056090_nKfgOWBU.mp4', NULL, NULL, 0, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_analytics`
--

CREATE TABLE `course_analytics` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `date` date NOT NULL,
  `views_count` int NOT NULL DEFAULT '0',
  `enrollments_count` int NOT NULL DEFAULT '0',
  `completions_count` int NOT NULL DEFAULT '0',
  `average_rating` decimal(10,2) DEFAULT NULL,
  `total_revenue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_discussions`
--

CREATE TABLE `course_discussions` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `user_id` int NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_solved` tinyint(1) NOT NULL DEFAULT '0',
  `upvotes` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_discussions`
--

INSERT INTO `course_discussions` (`id`, `course_id`, `user_id`, `body`, `parent_id`, `created_at`, `updated_at`, `title`, `is_solved`, `upvotes`) VALUES
(1, 5, 20, 'hello there can we start', NULL, '2026-06-29 16:09:40', '2026-06-29 16:09:40', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `course_notes`
--

CREATE TABLE `course_notes` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '1',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `allow_download` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_notes`
--

INSERT INTO `course_notes` (`id`, `course_id`, `user_id`, `title`, `summary`, `content`, `attachment_path`, `external_link`, `display_order`, `status`, `allow_download`, `created_at`, `updated_at`) VALUES
(1, 6, 7, 'Introduction', 'test description', 'test rich content here', 'course-notes/wyBj4JwaaPr1WBFw6vNsG9oa8r3MCgh3XTdjo8qM.docx', NULL, 1, 'published', 1, '2026-07-13 21:00:00', '2026-07-14 16:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `course_prerequisite`
--

CREATE TABLE `course_prerequisite` (
  `course_id` int NOT NULL,
  `prerequisite_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_tag`
--

CREATE TABLE `course_tag` (
  `course_id` int NOT NULL,
  `tag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` decimal(10,2) NOT NULL DEFAULT '1.00',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `rate`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', 1.00, 1, 'active', NULL, NULL),
(2, 'Euro', 'EUR', '€', 0.92, 0, 'active', NULL, NULL),
(3, 'Pound', 'GBP', '£', 0.79, 0, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `subject`, `body`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Welcome', 'Welcome to EduLab', 'Welcome to EduLab! We are excited to have you onboard.', 'active', NULL, NULL),
(2, 'Reset Password', 'Password Reset Request', 'Click the link below to reset your password.', 'active', NULL, NULL),
(3, 'Enrollment', 'Enrollment Confirmation', 'You have been successfully enrolled in the course.', 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method_id` int DEFAULT NULL,
  `payment_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `amount_paid`, `status`, `completed_at`, `created_at`, `updated_at`, `payment_method_id`, `payment_provider`, `payment_reference`, `payment_status`) VALUES
(1, 10, 1, 0.00, 'in_progress', NULL, '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL, NULL, NULL),
(2, 10, 3, 39.99, 'completed', '2026-06-23 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL, NULL, NULL),
(3, 10, 4, 0.00, 'in_progress', NULL, '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL, NULL, NULL),
(4, 20, 5, 0.00, 'in_progress', NULL, '2026-06-29 16:02:57', '2026-06-29 16:02:57', NULL, NULL, NULL, NULL),
(5, 22, 5, 0.00, 'in_progress', NULL, '2026-06-29 17:00:19', '2026-06-29 17:00:19', NULL, NULL, NULL, NULL),
(6, 20, 6, 0.00, 'in_progress', NULL, '2026-07-14 16:12:16', '2026-07-14 16:12:16', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_id` int NOT NULL,
  `class_id` int DEFAULT NULL,
  `exam_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `total_marks` int NOT NULL DEFAULT '100',
  `passing_marks` decimal(10,2) NOT NULL DEFAULT '50.00',
  `exam_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midterm',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` int NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'How do I enroll in a course?', 'Simply create an account, browse our course catalog, and click \"Enroll\" on any course. Free courses are immediately accessible, and paid courses require payment before access.', 'General', 1, 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(2, 'Can I get a certificate after completing a course?', 'Yes! When you complete all lessons in a course, a certificate of completion is automatically generated. You can download it from your dashboard.', 'Certificates', 1, 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(3, 'What payment methods are accepted?', 'We accept credit/debit cards (Visa, MasterCard, Amex), PayPal, and bank transfers for offline payments. All transactions are secure and encrypted.', 'Payments', 1, 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `hero_sections`
--

CREATE TABLE `hero_sections` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_sections`
--

INSERT INTO `hero_sections` (`id`, `title`, `subtitle`, `description`, `page`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Learn Without Limits', 'Unlock Your Potential', 'Access thousands of expert-led courses and take your skills to the next level. Learn at your own pace with lifetime access to course materials.', 'home', NULL, 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `icon_providers`
--

CREATE TABLE `icon_providers` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `icon_providers`
--

INSERT INTO `icon_providers` (`id`, `name`, `url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Remixicon', 'https://remixicon.com', 'active', NULL, NULL),
(2, 'Font Awesome', 'https://fontawesome.com', 'active', NULL, NULL),
(3, 'Bootstrap Icons', 'https://icons.getbootstrap.com', 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` int NOT NULL,
  `reserved_at` int DEFAULT NULL,
  `available_at` int NOT NULL,
  `created_at` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_reminders`
--

CREATE TABLE `learning_reminders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `reminder_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily',
  `remind_at` timestamp NULL DEFAULT NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_free_preview` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `video_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `content`, `video_url`, `duration`, `order`, `is_free_preview`, `status`, `created_at`, `updated_at`, `video_file`, `document_file`) VALUES
(1, 1, 'Welcome to the Course', 'In this lesson, we will introduce you to the world of web development. You will learn what to expect from this course and how to make the most of it.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '10:00', 1, 1, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(2, 1, 'Setting Up Your Development Environment', 'Learn how to install and configure all the tools you need to start building websites, including code editors, browsers, and developer tools.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '15:30', 2, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(3, 1, 'HTML Fundamentals', 'Dive into HTML5 and learn about elements, attributes, semantic markup, forms, tables, and best practices for structuring web content.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '25:00', 3, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(4, 1, 'CSS Styling Basics', 'Master the fundamentals of CSS including selectors, box model, flexbox, grid, responsive design, and CSS custom properties.', 'https://www.w3schools.com/html/mov_bbb.mp4', '20:00', 4, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(5, 1, 'JavaScript Introduction', 'Get started with JavaScript programming covering variables, functions, DOM manipulation, events, and ES6+ features.', 'https://www.w3schools.com/html/mov_bbb.mp4', '30:00', 5, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(6, 1, 'Building Your First Web Page', 'Apply everything you have learned by building a complete multi-section landing page from scratch.', 'https://www.w3schools.com/html/mov_bbb.mp4', '18:45', 6, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(7, 2, 'Course Overview & Prerequisites', 'An overview of what we will build in this course and a review of the prerequisites you need to be successful.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '08:00', 1, 1, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(8, 2, 'Laravel Architecture Deep Dive', 'Explore Laravels internal architecture including the service container, facades, providers, and the request lifecycle.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '22:00', 2, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(9, 2, 'Authentication & Authorization', 'Implement complete authentication with Laravel Breeze/Fortify and role-based authorization using gates and policies.', 'https://www.w3schools.com/html/mov_bbb.mp4', '35:00', 3, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(10, 2, 'Building RESTful APIs', 'Design and build RESTful APIs with Laravel including resource controllers, API resources, rate limiting, and API versioning.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '40:00', 4, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(11, 2, 'Testing Your Application', 'Learn to write feature tests, unit tests, and browser tests using PHPUnit and Laravel Dusk.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '28:00', 5, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(12, 2, 'Deployment to Production', 'Learn how to deploy a Laravel application to production using Forge, Vapor, or traditional VPS setups.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '25:00', 6, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(13, 3, 'What is UI/UX Design?', 'Understand the difference between UI and UX design, the design thinking process, and the role of a designer in product development.', 'https://www.w3schools.com/html/mov_bbb.mp4', '12:00', 1, 1, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(14, 3, 'User Research Methods', 'Learn various user research methods including interviews, surveys, usability testing, and how to synthesize findings.', 'https://www.w3schools.com/html/mov_bbb.mp4', '28:00', 2, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(15, 3, 'Wireframing & Prototyping in Figma', 'Master Figma for creating wireframes, interactive prototypes, design systems, and collaborative design workflows.', 'https://www.w3schools.com/html/mov_bbb.mp4', '32:00', 3, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(16, 3, 'Visual Design Principles', 'Learn color theory, typography, layout, spacing, and visual hierarchy to create beautiful and functional designs.', 'https://www.w3schools.com/html/mov_bbb.mp4', '22:00', 4, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(17, 3, 'Building Your Design Portfolio', 'Learn how to showcase your work effectively, write case studies, and present your designs to stakeholders and employers.', 'https://www.w3schools.com/html/mov_bbb.mp4', '15:00', 5, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(18, 4, 'Python Setup & First Steps', 'Install Python, set up Jupyter notebooks, and write your first Python programs with hands-on exercises.', 'https://www.w3schools.com/html/mov_bbb.mp4', '10:00', 1, 1, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(19, 4, 'Python Data Structures & Control Flow', 'Master Python lists, dictionaries, sets, tuples, loops, conditionals, and list comprehensions.', 'https://www.w3schools.com/html/mov_bbb.mp4', '20:00', 2, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(20, 4, 'NumPy for Numerical Computing', 'Learn NumPy arrays, vectorized operations, broadcasting, and linear algebra operations for data analysis.', 'https://www.w3schools.com/html/mov_bbb.mp4', '25:00', 3, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(21, 4, 'Pandas for Data Manipulation', 'Master Pandas DataFrames for data cleaning, transformation, grouping, merging, and time series analysis.', 'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4', '30:00', 4, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(22, 4, 'Data Visualization with Matplotlib', 'Create publication-quality charts and plots using Matplotlib and Seaborn for exploratory data analysis.', 'https://www.w3schools.com/html/mov_bbb.mp4', '20:00', 5, 0, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, NULL),
(23, 5, 'marketing', 'this i s the best free marketing couse', NULL, '4 hours', 1, 1, 'published', '2026-06-29 06:14:06', '2026-06-29 06:14:06', 'lessons/videos/1782724446_esxG965B.mp4', 'lessons/documents/1782724446_8e60hDgV.pptx'),
(24, 6, 'test title', 'test title', NULL, '2 hours', 1, 1, 'published', '2026-07-14 16:09:23', '2026-07-14 16:09:23', 'lessons/videos/1784056163_x25wNAtc.mp4', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_completions`
--

CREATE TABLE `lesson_completions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `course_id` int NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_watched_position` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lesson_completions`
--

INSERT INTO `lesson_completions` (`id`, `user_id`, `lesson_id`, `course_id`, `completed_at`, `created_at`, `updated_at`, `last_watched_position`) VALUES
(1, 10, 1, 1, '2026-06-28 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL),
(2, 10, 2, 1, '2026-06-28 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL),
(3, 10, 13, 3, '2026-06-19 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL),
(4, 10, 14, 3, '2026-06-07 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL),
(5, 10, 15, 3, '2026-06-14 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL),
(6, 10, 16, 3, '2026-06-13 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL),
(7, 10, 17, 3, '2026-06-21 16:52:58', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL),
(8, 20, 23, 5, NULL, '2026-06-29 16:56:57', '2026-06-29 16:56:57', 5),
(9, 22, 23, 5, NULL, '2026-06-29 17:00:35', '2026-06-30 08:29:28', 5);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `name`, `slug`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Beginner', 'beginner', 1, '2026-06-28 16:52:57', '2026-06-28 16:52:57'),
(2, 'Intermediate', 'intermediate', 2, '2026-06-28 16:52:57', '2026-06-28 16:52:57'),
(3, 'Advanced', 'advanced', 3, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(4, 'Expert', 'expert', 4, '2026-06-28 16:52:58', '2026-06-28 16:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `meet_providers`
--

CREATE TABLE `meet_providers` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_26_183242_add_role_and_profile_fields_to_users_table', 1),
(5, '2026_05_26_200231_create_courses_table', 1),
(6, '2026_05_27_075153_create_lessons_table', 1),
(7, '2026_05_27_075522_create_support_tickets_table', 1),
(8, '2026_05_27_075523_create_enrollments_table', 1),
(9, '2026_05_27_075524_create_certificates_table', 1),
(10, '2026_05_27_120000_add_bio_to_users_table', 1),
(11, '2026_05_27_120001_create_categories_table', 1),
(12, '2026_05_27_120002_create_faqs_table', 1),
(13, '2026_05_27_120003_create_sliders_table', 1),
(14, '2026_05_27_120004_create_testimonials_table', 1),
(15, '2026_05_27_120005_create_hero_sections_table', 1),
(16, '2026_05_27_120006_create_subjects_table', 1),
(17, '2026_05_27_120007_create_blog_categories_table', 1),
(18, '2026_05_27_120008_create_blogs_table', 1),
(19, '2026_05_27_120009_create_pages_table', 1),
(20, '2026_05_27_130000_create_contact_messages_table', 1),
(21, '2026_05_27_140000_create_quizzes_table', 1),
(22, '2026_05_27_140001_create_quiz_questions_table', 1),
(23, '2026_05_27_140002_create_quiz_results_table', 1),
(24, '2026_05_27_140003_create_assignments_table', 1),
(25, '2026_05_27_140004_create_assignment_submissions_table', 1),
(26, '2026_05_27_144801_change_enrollment_status_default_to_in_progress', 1),
(27, '2026_05_27_144926_create_lesson_completions_table', 1),
(28, '2026_05_27_145855_add_outcomes_and_requirements_to_courses_table', 1),
(29, '2026_05_27_150053_add_slug_to_courses_table', 1),
(30, '2026_05_27_150930_create_coupons_table', 1),
(31, '2026_05_27_150931_create_notifications_table', 1),
(32, '2026_05_27_150931_create_payment_methods_table', 1),
(33, '2026_05_27_150932_create_wishlists_table', 1),
(34, '2026_05_27_201000_add_payment_type_to_courses_table', 1),
(35, '2026_05_27_202000_fix_relationships_and_missing_columns', 1),
(36, '2026_05_28_125137_create_settings_table', 1),
(37, '2026_05_28_125350_add_category_id_to_courses_table', 1),
(38, '2026_05_28_125919_create_reviews_table', 1),
(39, '2026_05_28_130203_create_noticeboards_table', 1),
(40, '2026_05_28_133230_create_bundles_table', 1),
(41, '2026_05_28_133816_add_level_id_to_courses_table', 1),
(42, '2026_05_28_133816_create_course_tag_table', 1),
(43, '2026_05_28_133816_create_levels_table', 1),
(44, '2026_05_28_133816_create_tags_table', 1),
(45, '2026_05_28_135116_create_ticket_replies_table', 1),
(46, '2026_05_28_135348_create_notification_logs_table', 1),
(47, '2026_06_05_152531_add_attempts_limit_to_quizzes', 1),
(48, '2026_06_05_152532_create_course_prerequisite_table', 1),
(49, '2026_06_05_152533_add_last_watched_position_to_lesson_completions', 1),
(50, '2026_06_05_152851_create_payouts_table', 1),
(51, '2026_06_05_152852_create_carts_table', 1),
(52, '2026_06_05_155412_create_course_discussions_table', 1),
(53, '2026_06_05_155413_create_notification_preferences_table', 1),
(54, '2026_06_09_000001_add_video_and_document_to_lessons_table', 1),
(55, '2026_06_09_115105_add_video_and_document_to_lessons_table', 1),
(56, '2026_06_10_000001_create_meet_providers_table', 1),
(57, '2026_06_10_000002_create_subscriptions_table', 1),
(58, '2026_06_10_000003_create_support_ticket_categories_table', 1),
(59, '2026_06_13_170539_add_course_id_to_support_tickets_table', 1),
(60, '2026_06_13_add_quiz_features', 1),
(61, '2026_06_14_193438_add_instructions_file_to_assignments_table', 1),
(62, '2026_06_14_193512_add_instructions_file_to_quizzes_table', 1),
(63, '2026_06_16_075907_add_profile_and_activity_columns_to_users_table', 1),
(64, '2026_06_16_080455_add_enhancement_columns_to_courses_table', 1),
(65, '2026_06_16_080500_add_enhancement_columns_to_assignments_table', 1),
(66, '2026_06_16_080505_add_quiz_enhancement_columns_to_quizzes_table', 1),
(67, '2026_06_16_080510_create_activity_logs_table', 1),
(68, '2026_06_16_080515_create_quiz_attempts_table', 1),
(69, '2026_06_16_080520_create_site_content_table', 1),
(70, '2026_06_16_080525_create_course_analytics_table', 1),
(71, '2026_06_16_153518_create_achievement_badges_table', 1),
(72, '2026_06_16_153518_create_user_badges_table', 1),
(73, '2026_06_16_153519_add_forum_enhancement_fields_to_course_discussions_table', 1),
(74, '2026_06_16_153520_add_org_branding_fields_to_users_table', 1),
(75, '2026_06_16_153520_create_certificate_templates_table', 1),
(76, '2026_06_16_153520_create_learning_reminders_table', 1),
(77, '2026_06_16_153521_add_meta_fields_to_site_content_table', 1),
(78, '2026_06_17_000001_create_currencies_table', 1),
(79, '2026_06_17_000002_create_site_languages_table', 1),
(80, '2026_06_17_000003_create_email_templates_table', 1),
(81, '2026_06_17_000004_create_timezones_table', 1),
(82, '2026_06_17_000005_create_countries_table', 1),
(83, '2026_06_17_000006_create_states_table', 1),
(84, '2026_06_17_000007_create_cities_table', 1),
(85, '2026_06_17_000008_create_icon_providers_table', 1),
(86, '2026_06_17_000009_add_unique_index_to_quiz_results', 1),
(87, '2026_06_17_000010_add_performance_indexes', 1),
(88, '2026_06_18_115428_add_duration_to_sliders_table', 1),
(89, '2026_06_28_142500_add_instructor_approval_to_users_table', 1),
(90, '2026_06_28_142501_create_school_settings_table', 1),
(91, '2026_06_28_142502_create_classes_table', 1),
(92, '2026_06_28_142503_create_attendances_table', 1),
(93, '2026_06_28_142504_create_exams_table', 1),
(94, '2026_06_28_142505_create_results_table', 1),
(95, '2026_06_28_142506_create_timetables_table', 1),
(96, '2026_06_28_142507_create_parent_student_table', 1),
(97, '2026_06_28_150000_add_class_id_to_users_table', 1),
(98, '2026_06_28_160000_add_provider_to_payment_methods_table', 1),
(99, '2026_06_28_160001_add_payment_fields_to_enrollments_table', 1),
(100, '2026_06_28_170000_add_link_to_notification_logs_table', 1),
(101, '2026_06_29_192740_create_announcements_table', 2),
(102, '2026_06_29_193945_add_is_exam_to_quizzes_table', 3),
(103, '2026_06_29_202903_add_scheduling_to_quizzes', 4),
(104, '2026_06_29_202904_add_scheduling_to_assignments', 4),
(105, '2026_06_30_100000_add_instructor_id_to_contact_messages_table', 5),
(106, '2026_07_14_000001_create_course_notes_table', 6),
(107, '2026_07_14_000002_add_course_id_to_existing_quizzes_and_assignments', 7),
(108, '2026_07_14_000000_create_missing_bundle_tables_if_not_exists', 8),
(109, '2026_07_14_000001_add_missing_user_columns_if_not_exists', 9);

-- --------------------------------------------------------

--
-- Table structure for table `noticeboards`
--

CREATE TABLE `noticeboards` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `notification_template_id` int DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_app',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_app',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_logs`
--

INSERT INTO `notification_logs` (`id`, `user_id`, `notification_template_id`, `type`, `subject`, `body`, `channel`, `is_read`, `sent_at`, `created_at`, `updated_at`, `link`) VALUES
(1, 1, NULL, 'in_app', 'New Instructor Registration', 'dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 1, '2026-06-29 04:16:18', '2026-06-29 04:16:18', '2026-07-22 10:27:20', 'http://127.0.0.1:8000/admin/settings/approve-instructors'),
(2, 2, NULL, 'in_app', 'New Instructor Registration', 'dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-06-29 04:16:18', '2026-06-29 04:16:18', '2026-06-29 04:16:18', 'http://127.0.0.1:8000/admin/settings/approve-instructors'),
(3, 3, NULL, 'in_app', 'New Instructor Registration', 'dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-06-29 04:16:18', '2026-06-29 04:16:18', '2026-06-29 04:16:18', 'http://127.0.0.1:8000/admin/settings/approve-instructors'),
(4, 4, NULL, 'in_app', 'New Instructor Registration', 'dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-06-29 04:16:18', '2026-06-29 04:16:18', '2026-06-29 04:16:18', 'http://127.0.0.1:8000/admin/settings/approve-instructors'),
(5, 5, NULL, 'in_app', 'New Instructor Registration', 'dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-06-29 04:16:18', '2026-06-29 04:16:18', '2026-06-29 04:16:18', 'http://127.0.0.1:8000/admin/settings/approve-instructors'),
(6, 11, NULL, 'in_app', 'New Instructor Registration', 'dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-06-29 04:16:18', '2026-06-29 04:16:18', '2026-06-29 04:16:18', 'http://127.0.0.1:8000/admin/settings/approve-instructors'),
(7, 21, NULL, 'in_app', 'Instructor Account Approved', 'Congratulations dimits dimits! Your instructor account has been approved. You can now create courses and manage students.', 'in_app', 0, '2026-06-29 04:17:57', '2026-06-29 04:17:57', '2026-06-29 04:17:57', 'http://127.0.0.1:8000/instructor'),
(8, 20, NULL, 'in_app', 'Enrolled in marketing', 'You have successfully enrolled in \"marketing\". Start learning today!', 'in_app', 0, '2026-06-29 16:02:57', '2026-06-29 16:02:57', '2026-06-29 16:02:57', 'http://127.0.0.1:8000/dashboard/courses/5'),
(9, 20, NULL, 'in_app', 'Quiz Result: quizz one', 'You scored 0/0 on \"quizz one\".', 'in_app', 1, '2026-06-29 16:31:59', '2026-06-29 16:31:59', '2026-06-29 16:57:48', NULL),
(10, 22, NULL, 'in_app', 'Enrolled in marketing', 'You have successfully enrolled in \"marketing\". Start learning today!', 'in_app', 0, '2026-06-29 17:00:19', '2026-06-29 17:00:19', '2026-06-29 17:00:19', 'http://127.0.0.1:8000/dashboard/courses/5'),
(11, 20, NULL, 'in_app', 'New Quiz: dfgdfg', 'A new quiz \"dfgdfg\" has been published in your course.', 'in_app', 0, '2026-06-29 18:15:43', '2026-06-29 18:15:43', '2026-06-29 18:15:43', NULL),
(12, 22, NULL, 'in_app', 'New Quiz: dfgdfg', 'A new quiz \"dfgdfg\" has been published in your course.', 'in_app', 0, '2026-06-29 18:15:43', '2026-06-29 18:15:43', '2026-06-29 18:15:43', NULL),
(13, 21, NULL, 'in_app', 'Testing Subject', 'Testing this chat\n\n— From: jim jim', 'in_app', 0, '2026-06-30 14:20:44', '2026-06-30 14:20:44', '2026-06-30 14:20:44', 'http://127.0.0.1:8000/dashboard/notifications'),
(14, 20, NULL, 'in_app', 'Enrolled in test', 'You have successfully enrolled in \"test\". Start learning today!', 'in_app', 0, '2026-07-14 16:12:16', '2026-07-14 16:12:16', '2026-07-14 16:12:16', 'http://127.0.0.1:8000/dashboard/courses/6'),
(15, 1, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyimanamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 1, '2026-07-21 04:55:40', '2026-07-21 04:55:40', '2026-07-22 10:27:19', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(16, 2, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyimanamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:55:40', '2026-07-21 04:55:40', '2026-07-21 04:55:40', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(17, 3, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyimanamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:55:40', '2026-07-21 04:55:40', '2026-07-21 04:55:40', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(18, 4, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyimanamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:55:40', '2026-07-21 04:55:40', '2026-07-21 04:55:40', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(19, 5, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyimanamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:55:40', '2026-07-21 04:55:40', '2026-07-21 04:55:40', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(20, 11, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyimanamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:55:40', '2026-07-21 04:55:40', '2026-07-21 04:55:40', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(21, 1, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 1, '2026-07-21 04:56:36', '2026-07-21 04:56:36', '2026-07-22 10:27:17', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(22, 2, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:56:36', '2026-07-21 04:56:36', '2026-07-21 04:56:36', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(23, 3, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:56:36', '2026-07-21 04:56:36', '2026-07-21 04:56:36', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(24, 4, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:56:36', '2026-07-21 04:56:36', '2026-07-21 04:56:36', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(25, 5, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:56:36', '2026-07-21 04:56:36', '2026-07-21 04:56:36', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(26, 11, NULL, 'in_app', 'New Instructor Registration', 'Hamenyimana Manaseh (hamenyamanaseh@gmail.com) has registered as an instructor and is pending your approval.', 'in_app', 0, '2026-07-21 04:56:36', '2026-07-21 04:56:36', '2026-07-21 04:56:36', 'https://lms-sample.duckdns.org/admin/settings/approve-instructors'),
(27, 23, NULL, 'in_app', 'Instructor Account Approved', 'Congratulations Hamenyimana Manaseh! Your instructor account has been approved. You can now create courses and manage students.', 'in_app', 0, '2026-07-21 05:01:11', '2026-07-21 05:01:11', '2026-07-21 05:01:11', 'https://lms-sample.duckdns.org/instructor'),
(28, 24, NULL, 'in_app', 'Instructor Account Approved', 'Congratulations Hamenyimana Manaseh! Your instructor account has been approved. You can now create courses and manage students.', 'in_app', 1, '2026-07-21 05:03:09', '2026-07-21 05:03:09', '2026-07-21 05:06:11', 'https://lms-sample.duckdns.org/instructor');

-- --------------------------------------------------------

--
-- Table structure for table `notification_preferences`
--

CREATE TABLE `notification_preferences` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_app',
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_app',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
  `template_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'about-us', 'EduLab is a leading online learning platform dedicated to providing high-quality education to learners worldwide. Founded in 2024, our mission is to make education accessible, affordable, and effective for everyone.\n\nOur platform features expert-led courses across multiple disciplines including web development, data science, design, and business. We believe in learning by doing, which is why our courses emphasize hands-on projects and real-world applications.\n\nWith a community of thousands of learners and hundreds of courses, EduLab is committed to helping you achieve your learning goals and advance your career.', 'published', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(2, 'Privacy Policy', 'privacy-policy', 'Your privacy is important to us. This Privacy Policy explains how EduLab collects, uses, and protects your personal information.\n\nWe collect information you provide when creating an account, enrolling in courses, and interacting with our platform. This includes your name, email address, and payment information.\n\nWe use this information to provide and improve our services, process payments, send course updates, and communicate with you about your learning progress.\n\nWe implement industry-standard security measures to protect your data. We do not share your personal information with third parties except as necessary to provide our services.', 'published', '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `parent_student`
--

CREATE TABLE `parent_student` (
  `id` int NOT NULL,
  `parent_id` int NOT NULL,
  `student_id` int NOT NULL,
  `relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Online',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `type`, `status`, `created_at`, `updated_at`, `provider`) VALUES
(1, 'PayPal', 'Online', 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59', NULL),
(2, 'Airtel Money', 'Offline', 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59', 'airtel'),
(3, 'MTN Mobile Money', 'Offline', 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59', 'mtn');

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank',
  `account_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text COLLATE utf8mb4_unicode_ci,
  `time_limit` int DEFAULT NULL,
  `passing_score` int NOT NULL DEFAULT '50',
  `total_marks` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attempts_limit` int DEFAULT NULL,
  `shuffle_questions` tinyint(1) NOT NULL DEFAULT '0',
  `shuffle_options` tinyint(1) NOT NULL DEFAULT '0',
  `show_answers_after` tinyint(1) NOT NULL DEFAULT '1',
  `show_score_immediately` tinyint(1) NOT NULL DEFAULT '1',
  `question_pool` int DEFAULT NULL,
  `questions_per_attempt` int DEFAULT NULL,
  `grading_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'best_score',
  `instructions_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `randomize_options` tinyint(1) NOT NULL DEFAULT '0',
  `show_results_immediately` tinyint(1) NOT NULL DEFAULT '0',
  `certificate_on_pass` tinyint(1) NOT NULL DEFAULT '0',
  `proctoring_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_exam` tinyint(1) NOT NULL DEFAULT '0',
  `class_id` int DEFAULT NULL,
  `available_from` timestamp NULL DEFAULT NULL,
  `results_released_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `user_id`, `title`, `instructions`, `time_limit`, `passing_score`, `total_marks`, `status`, `created_at`, `updated_at`, `attempts_limit`, `shuffle_questions`, `shuffle_options`, `show_answers_after`, `show_score_immediately`, `question_pool`, `questions_per_attempt`, `grading_method`, `instructions_file`, `randomize_options`, `show_results_immediately`, `certificate_on_pass`, `proctoring_required`, `is_exam`, `class_id`, `available_from`, `results_released_at`) VALUES
(1, 1, NULL, 'HTML & CSS Basics', 'Answer all questions. Passing score: 60%.', 15, 60, 40, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, 0, 0, 1, 1, NULL, NULL, 'best_score', NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(2, 2, NULL, 'Laravel Fundamentals', 'Answer all questions. Passing score: 70%.', 20, 70, 30, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, 0, 0, 1, 1, NULL, NULL, 'best_score', NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(3, 3, NULL, 'Design Principles', 'Answer all questions. Passing score: 50%.', 10, 50, 30, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, 0, 0, 1, 1, NULL, NULL, 'best_score', NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(4, 4, NULL, 'Python Data Structures', 'Answer all questions. Passing score: 60%.', 15, 60, 30, 'published', '2026-06-28 16:52:58', '2026-06-28 16:52:58', NULL, 0, 0, 1, 1, NULL, NULL, 'best_score', NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(5, 5, 21, 'quizz one', 'ghhjgkjhg', 60, 50, 1, 'published', '2026-06-29 15:57:49', '2026-06-29 17:05:10', 2, 0, 0, 1, 1, NULL, NULL, 'best_score', 'quizzes/instructions/FyiM9h6yRtYdQY3M1ONKylSyU0Yt5ll9SCwpdTxp.pdf', 0, 0, 0, 0, 0, NULL, NULL, NULL),
(6, 5, 21, 'dfgdfg', 'fdgfdg', 30, 50, 0, 'published', '2026-06-29 18:15:43', '2026-06-29 18:15:43', 3, 0, 0, 1, 1, NULL, NULL, 'best_score', 'quizzes/instructions/W2Hi4orzV5f9QiHsC4clYBp1eAJeJvzfIltndOpv.docx', 0, 0, 0, 0, 0, NULL, '2026-06-24 21:19:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `user_id` int NOT NULL,
  `started_at` timestamp NOT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `score` decimal(10,2) DEFAULT NULL,
  `answers` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `attempt_number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `user_id`, `started_at`, `submitted_at`, `expires_at`, `score`, `answers`, `is_completed`, `attempt_number`, `created_at`, `updated_at`) VALUES
(1, 5, 20, '2026-06-29 16:55:04', NULL, '2026-06-29 17:55:04', NULL, '[]', 0, 1, '2026-06-29 16:55:04', '2026-06-29 16:55:04'),
(2, 5, 22, '2026-06-29 17:01:14', NULL, '2026-06-29 18:01:14', NULL, '[]', 0, 1, '2026-06-29 17:01:14', '2026-06-29 17:01:14'),
(3, 5, 22, '2026-06-30 06:32:30', NULL, '2026-06-30 07:32:30', NULL, '[]', 0, 2, '2026-06-30 06:32:30', '2026-06-30 06:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'multiple_choice',
  `options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marks` int NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question`, `type`, `options`, `correct_answer`, `marks`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'What does HTML stand for?', 'multiple_choice', '[\"HyperText Markup Language\",\"HyperTransfer Markup Language\",\"Home Tool Markup Language\",\"None of the above\"]', 'HyperText Markup Language', 10, 1, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(2, 1, 'Which CSS property is used to change the text color?', 'multiple_choice', '[\"color\",\"font-color\",\"text-color\",\"background-color\"]', 'color', 10, 2, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(3, 1, 'What is the correct HTML tag for a hyperlink?', 'multiple_choice', '[\"<a>\",\"<link>\",\"<href>\",\"<url>\"]', '<a>', 10, 3, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(4, 1, 'Which CSS property controls the layout direction in Flexbox?', 'multiple_choice', '[\"flex-direction\",\"direction\",\"layout\",\"flex-layout\"]', 'flex-direction', 10, 4, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(5, 2, 'Which artisan command creates a new controller?', 'multiple_choice', '[\"make:controller\",\"create:controller\",\"new:controller\",\"generate:controller\"]', 'make:controller', 10, 1, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(6, 2, 'What is the default Eloquent ORM namespace?', 'multiple_choice', '[\"App\\\\Models\",\"App\\\\Model\",\"App\\\\ORM\",\"App\\\\Eloquent\"]', 'App\\Models', 10, 2, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(7, 2, 'Which method is used to define a one-to-many relationship?', 'multiple_choice', '[\"hasMany\",\"belongsTo\",\"hasOne\",\"belongsToMany\"]', 'hasMany', 10, 3, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(8, 3, 'What does UX stand for?', 'multiple_choice', '[\"User Experience\",\"User Extension\",\"Universal Experience\",\"Unique Xperience\"]', 'User Experience', 10, 1, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(9, 3, 'Which tool is commonly used for wireframing?', 'multiple_choice', '[\"Figma\",\"Photoshop\",\"Illustrator\",\"After Effects\"]', 'Figma', 10, 2, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(10, 3, 'What color model is used for digital screens?', 'multiple_choice', '[\"RGB\",\"CMYK\",\"HSL\",\"HEX\"]', 'RGB', 10, 3, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(11, 4, 'Which library is used for numerical computing in Python?', 'multiple_choice', '[\"NumPy\",\"Pandas\",\"Matplotlib\",\"Scikit-learn\"]', 'NumPy', 10, 1, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(12, 4, 'What Pandas data structure is a 2D labeled data structure?', 'multiple_choice', '[\"DataFrame\",\"Series\",\"Array\",\"Matrix\"]', 'DataFrame', 10, 2, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(13, 4, 'Which method is used to read a CSV file in Pandas?', 'multiple_choice', '[\"read_csv\",\"load_csv\",\"import_csv\",\"open_csv\"]', 'read_csv', 10, 3, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(14, 5, 'whats bloaw', 'multiple_select', '[\"A ug\",\"B hj\",\"C ojo\",\"D hkh\"]', '', 1, 1, '2026-06-29 17:05:10', '2026-06-29 17:05:10');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `user_id` int NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `total_marks` int NOT NULL DEFAULT '0',
  `answers` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `passed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `quiz_id`, `user_id`, `score`, `total_marks`, `answers`, `started_at`, `completed_at`, `passed`, `created_at`, `updated_at`) VALUES
(1, 5, 20, 0, 0, '[]', NULL, '2026-06-29 16:31:59', 0, '2026-06-29 16:31:59', '2026-06-29 16:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int NOT NULL,
  `exam_id` int NOT NULL,
  `student_id` int NOT NULL,
  `course_id` int NOT NULL,
  `marks` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_marks` decimal(10,2) NOT NULL DEFAULT '100.00',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `rating` int NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `course_id`, `rating`, `review`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 4, 'Great introduction to web development. Would recommend to anyone starting out.', 1, '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(2, 10, 2, 4, 'Comprehensive coverage of advanced topics. The API building section was particularly helpful.', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(3, 10, 3, 5, 'Amazing design course! The Figma tutorials were outstanding and the portfolio project was a game-changer.', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(4, 10, 4, 5, 'Perfect for beginners in data science. Clear explanations and hands-on exercises.', 1, '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `school_settings`
--

CREATE TABLE `school_settings` (
  `id` int NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_address` text COLLATE utf8mb4_unicode_ci,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$',
  `currency_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `currency_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#5F3EED',
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#F4B826',
  `accent_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#1AEBC5',
  `custom_css` text COLLATE utf8mb4_unicode_ci,
  `slider_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_settings`
--

INSERT INTO `school_settings` (`id`, `school_name`, `school_email`, `school_phone`, `school_address`, `currency_symbol`, `currency_code`, `currency_position`, `timezone`, `language`, `favicon`, `site_logo`, `primary_color`, `secondary_color`, `accent_color`, `custom_css`, `slider_video`, `created_at`, `updated_at`) VALUES
(1, 'LMS', 'LMS@gmail.com', '+256756371377', 'Nakawa', 'UGX', 'UGX', 'right', 'Africa/Kampala', 'en', 'settings/XcPcPO6HWW0UBcPJkAp31v0bNBEc2ELWx6qIVyIM.png', 'settings/ks4QcOiG0akPKC5Tqel3k509AFoN9DFWx3y3LgwZ.png', '#5f3eed', '#f4b826', '#1aebc5', NULL, 'settings/videos/GqTcPN0nWxGwz7vLcarNS7E19X5flXEAIdRJiIp9.mp4', '2026-06-28 16:46:49', '2026-07-15 02:35:31');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_content`
--

CREATE TABLE `site_content` (
  `id` int NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` int NOT NULL DEFAULT '0',
  `metadata` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `page_section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_languages`
--

CREATE TABLE `site_languages` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_languages`
--

INSERT INTO `site_languages` (`id`, `name`, `code`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, 'active', NULL, NULL),
(2, 'Spanish', 'es', 0, 'active', NULL, NULL),
(3, 'French', 'fr', 0, 'active', NULL, NULL),
(4, 'Arabic', 'ar', 0, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `btn_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `duration` int NOT NULL DEFAULT '6'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `subtitle`, `description`, `btn_text`, `btn_link`, `image`, `order`, `status`, `created_at`, `updated_at`, `duration`) VALUES
(1, 'Welcome to EduLab', 'Start Your Learning Journey', 'Join millions of learners worldwide and gain the skills you need to succeed.', 'Get Started', '/register', 'sliders/1784093927_GMbrrwoE.webp', 1, 'active', '2026-06-28 16:52:59', '2026-07-15 02:38:47', 6),
(2, 'Your Best E-learning platform', 'The home of technology', 'The home of technology in your learning', 'Get Started', '/register', 'sliders/1784094014_T0c6FEy0.webp', 0, 'active', '2026-07-15 02:40:01', '2026-07-15 02:40:14', 6),
(3, 'Enroll to start learning', 'Choose your best course', 'Enroll to join how the rest of the worlds advances', 'Get Started', '/register', 'sliders/1784094108_UJPRHIYz.webp', 0, 'active', '2026-07-15 02:41:48', '2026-07-15 02:41:48', 6);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `country_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'California', 1, 'active', NULL, NULL),
(2, 'Texas', 1, 'active', NULL, NULL),
(3, 'London', 2, 'active', NULL, NULL),
(4, 'Ontario', 3, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `duration_months` int NOT NULL DEFAULT '1',
  `features` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Medium',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `course_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_categories`
--

CREATE TABLE `support_ticket_categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'PHP', 'php', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(2, 'Laravel', 'laravel', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(3, 'JavaScript', 'javascript', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(4, 'Python', 'python', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(5, 'CSS', 'css', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(6, 'HTML', 'html', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(7, 'React', 'react', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(8, 'Vue.js', 'vuejs', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(9, 'Node.js', 'nodejs', '2026-06-28 16:52:58', '2026-06-28 16:52:58'),
(10, 'MySQL', 'mysql', '2026-06-28 16:52:58', '2026-06-28 16:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int NOT NULL DEFAULT '5',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `position`, `content`, `rating`, `avatar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sarah Johnson', 'Web Developer at Google', 'EduLab transformed my career. The courses are well-structured and the instructors are incredibly knowledgeable. I went from a complete beginner to a professional web developer in just 6 months.', 5, NULL, 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59'),
(2, 'Michael Chen', 'Data Analyst at Amazon', 'The Data Science course was exactly what I needed to transition into analytics. The hands-on projects and real-world examples made learning practical and enjoyable.', 5, NULL, 'active', '2026-06-28 16:52:59', '2026-06-28 16:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int NOT NULL,
  `support_ticket_id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `id` int NOT NULL,
  `class_id` int NOT NULL,
  `course_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `day_of_week` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gmt_offset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `name`, `gmt_offset`, `status`, `created_at`, `updated_at`) VALUES
(1, 'UTC', '+00:00', 'active', NULL, NULL),
(2, 'America/New_York', '-05:00', 'active', NULL, NULL),
(3, 'Europe/London', '+00:00', 'active', NULL, NULL),
(4, 'Asia/Dubai', '+04:00', 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `bio` text COLLATE utf8mb4_unicode_ci,
  `organization_id` int DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `preferences` text COLLATE utf8mb4_unicode_ci,
  `activity_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '#5F3EED',
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '#F4B826',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_at` timestamp NULL DEFAULT NULL,
  `class_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `first_name`, `last_name`, `phone`, `role`, `designation`, `address`, `status`, `bio`, `organization_id`, `profile_image`, `last_activity_at`, `preferences`, `activity_notifications`, `logo`, `primary_color`, `secondary_color`, `is_approved`, `approved_at`, `class_id`) VALUES
(1, 'System Admin', 'admin@gmail.com', '2026-06-28 16:52:51', '$2y$12$gyD0uwyzwq.iu8/nuNW8vOjyRR0b.1eIFmyUPheWivNPQbDUBQ/hW', NULL, '2026-06-28 16:52:51', '2026-06-28 16:52:51', 'System', 'Admin', '+1234567890', 'admin', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(2, 'James Biverson', 'james.biverson@edulab.test', '2026-06-28 16:52:52', '$2y$12$r1oKLHR2/44ltZZPrxWYaeQ2eLTADOgMAXM6uUhrwJPAUzxa5.PL.', NULL, '2026-06-28 16:52:52', '2026-06-28 16:52:52', 'James', 'Biverson', '+1234567894', 'admin', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(3, 'Sarah Admin', 'sarah.admin@edulab.test', '2026-06-28 16:52:52', '$2y$12$qs0b8ezpA2Vo98PA6yw2EOh92TNNWyDGWT.fSrD.VJK8ffJNzW0r.', NULL, '2026-06-28 16:52:52', '2026-06-28 16:52:52', 'Sarah', 'Admin', '+1234567895', 'admin', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(4, 'IT Admin', 'it.admin@edulab.test', '2026-06-28 16:52:53', '$2y$12$ar9rJOiH/jkUfD6ZfoL8wOTwF/kQ8rE1vETn5wMNckiCDUOW84tJW', NULL, '2026-06-28 16:52:53', '2026-06-28 16:52:53', 'IT', 'Admin', '+1234567896', 'admin', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(5, 'Admin User', 'admin@edulab.test', '2026-06-28 16:52:53', '$2y$12$G0eA0jy83y2TdOb2g7HZsus3MfLxUVvbzNylW0nC3uIw79OiAtbN.', NULL, '2026-06-28 16:52:53', '2026-06-28 16:52:53', 'Admin', 'User', '+1234567890', 'admin', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(6, 'Robert Smith', 'instructor@edulab.test', '2026-06-28 16:52:53', '$2y$12$C/qFZQM3yIfijTKBzC2Qwe0NGzGih3vF6c.iRdNRcwY2slT1N4ZkK', NULL, '2026-06-28 16:52:53', '2026-06-28 16:52:53', 'Robert', 'Smith', '+1234567891', 'instructor', 'Senior Web Developer', NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 1, NULL, NULL),
(7, 'John Instructor', 'instructor@gmail.com', '2026-06-28 16:52:54', '$2y$12$Co2md1OrMhtMJF.bvwIEyuR9WGsk7q/SDFYXZ5l37rj/sSXAPN/cG', NULL, '2026-06-28 16:52:54', '2026-06-28 16:52:54', 'John', 'Instructor', '+1234567897', 'instructor', 'Instructor Specialist', NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 1, NULL, NULL),
(8, 'Codexshapper', 'org@edulab.test', '2026-06-28 16:52:54', '$2y$12$0mY2RApDzYU4FP3JmcmHXuID3dNfHv9FwmC9pQs4aibZqFw.OXU1C', NULL, '2026-06-28 16:52:54', '2026-06-28 16:52:54', NULL, NULL, '+1234567892', 'organization', NULL, 'Toronto, Canada', 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(9, 'Apex Organization', 'org@gmail.com', '2026-06-28 16:52:54', '$2y$12$M9GYyEwO./XUzPPZdqidaeDGzKsZlRn6bMKr1BJB2H7z9WJo6aVUu', NULL, '2026-06-28 16:52:54', '2026-06-28 16:52:54', NULL, NULL, '+1234567898', 'organization', NULL, 'Kampala, Uganda', 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(10, 'John Doe', 'student@edulab.test', '2026-06-28 16:52:55', '$2y$12$aVbKgJdDQhaKKAourKz6WOr12kfB7ZWhGuYRMWxEj7mgUVWhUii.2', NULL, '2026-06-28 16:52:55', '2026-06-28 16:52:55', 'John', 'Doe', '+1234567893', 'student', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(11, 'Admin User', 'admin@lms.test', '2026-06-28 16:52:55', '$2y$12$N1JvL7xKdddJM4niA3P2peS2QazYEzqe/P47YE6j6RX9au.Hgvhia', NULL, '2026-06-28 16:52:55', '2026-06-28 16:52:55', 'Admin', 'User', '256700000001', 'admin', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(12, 'Dr. Sarah Katende', 'instructor@lms.test', '2026-06-28 16:52:55', '$2y$12$W9plOqNfqjS393NSD3oEsu0aFtjNKUeL.ryCRxHnB55MRm/Lypqg6', NULL, '2026-06-28 16:52:55', '2026-06-28 16:52:55', 'Sarah', 'Katende', '256700000002', 'instructor', 'Senior Software Engineer', NULL, 'active', 'Passionate about web development and mentoring students in East Africa.', NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(13, 'Eng. David Ouma', 'instructor2@lms.test', '2026-06-28 16:52:56', '$2y$12$yG73xHP1DWDMaVIm1zYDu.Bfyaiuvr2pQNJG/Cfo4iRX8tarWEf/m', NULL, '2026-06-28 16:52:56', '2026-06-28 16:52:56', 'David', 'Ouma', '256700000003', 'instructor', 'Mobile Development Specialist', NULL, 'active', 'Specializing in mobile application development for African markets.', NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(14, 'Makerere University IT Department', 'organization@lms.test', '2026-06-28 16:52:56', '$2y$12$LP4j8o3rV0kHxswEV3z7puZItZyWgFk9jvB5JVU6YTfGgnfXlEu2O', NULL, '2026-06-28 16:52:56', '2026-06-28 16:52:56', NULL, NULL, '256700000004', 'organization', NULL, 'Kampala, Uganda', 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(15, 'Alice Nakato', 'student1@lms.test', '2026-06-28 16:52:56', '$2y$12$Tl8yfhSbXnslOv5v5gZhL.es16lqEDYGCH26nZ.bDNAdZRkBoYx1.', NULL, '2026-06-28 16:52:56', '2026-06-28 16:52:56', 'Alice', 'Nakato', '256700000005', 'student', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(16, 'Brian Ssewanyana', 'student2@lms.test', '2026-06-28 16:52:57', '$2y$12$qM0cPw/8SSZpjecR3t1y0..pd7/Ic/KisFXey1cb44QDFa2zkcTPK', NULL, '2026-06-28 16:52:57', '2026-06-28 16:52:57', 'Brian', 'Ssewanyana', '256700000006', 'student', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(17, 'Carol Mwase', 'student3@lms.test', '2026-06-28 16:52:57', '$2y$12$hXClxXo5T7RZi2FR/BWw5.5Enp27S3F1mwdWhc/o/ep03IUy7m62q', NULL, '2026-06-28 16:52:57', '2026-06-28 16:52:57', 'Carol', 'Mwase', '256700000007', 'student', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(18, 'Daniel Nyamari', 'student4@lms.test', '2026-06-28 16:52:57', '$2y$12$ydBvyelTaFf1KoCPxnLheu/fKVlzPamSOk/ka9ykWlXhLDCyPTd.2', NULL, '2026-06-28 16:52:57', '2026-06-28 16:52:57', 'Daniel', 'Nyamari', '256700000008', 'student', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(19, 'Emily Kipchoge', 'student5@lms.test', '2026-06-28 16:52:57', '$2y$12$VRIOnTFYLxzOWphkeW..ze5mCvFpF7PnFbPENScL4xN9u1pMMMEcm', NULL, '2026-06-28 16:52:57', '2026-06-28 16:52:57', 'Emily', 'Kipchoge', '256700000009', 'student', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(20, 'std std', 'student@gmail.com', '2026-06-28 16:55:16', '$2y$12$odhVzrRfi0HlNGn0bNvvCuVfA.8hbVNuocBGNQ5U5xQT4rP.aJQKW', NULL, '2026-06-28 16:55:16', '2026-06-28 16:55:16', 'std', 'std', '3456', 'student', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(21, 'dimits dimits', 'dimits@gmail.com', '2026-06-29 04:16:18', '$2y$12$rE.2RBcPL2ZLHeikzEA3LO6/BYfCNkr1fBzKw5RfDMC2BMnsVzllO', NULL, '2026-06-29 04:16:18', '2026-06-30 07:22:48', 'dimits', 'dimits', '76544567', 'instructor', 'Kampala', NULL, 'active', NULL, NULL, 'profiles/images/1782814968_wU4e8zBG.JPG', NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 1, '2026-06-29 04:17:57', NULL),
(22, 'jim jim', 'jimmy2@gmail.com', '2026-06-29 16:58:48', '$2y$12$acHKmN5p2.Co/O.23ASqOucKF3OZ3wMCC8/3.MctKBtD4rptNwoTq', NULL, '2026-06-29 16:58:48', '2026-07-01 03:36:53', 'jim', 'jim', '345676543', 'student', NULL, NULL, 'active', NULL, NULL, 'profiles/images/1782887809_t5Q0OkvT.png', NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 0, NULL, NULL),
(23, 'Hamenyimana Manaseh', 'hamenyimanamanaseh@gmail.com', '2026-07-21 04:55:40', '$2y$12$uXIQAjcKzjeXM4EyiHOhV.VWCQA6myySPzaZGDHnNDYtQ1epNWQv6', NULL, '2026-07-21 04:55:40', '2026-07-21 05:01:11', 'Hamenyimana', 'Manaseh', '0731009352', 'instructor', 'St Gideon Junior School', NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 1, '2026-07-21 05:01:11', NULL),
(24, 'Hamenyimana Manaseh', 'hamenyamanaseh@gmail.com', '2026-07-21 04:56:36', '$2y$12$qkNjaxgNjp36Z4PVorb5DeJgohajBv5aHv7c9PSfSHlPwZVPC1vD2', NULL, '2026-07-21 04:56:36', '2026-07-21 05:03:09', 'Hamenyimana', 'Manaseh', '0731009352', 'instructor', 'St Gideon Junior School', NULL, 'active', NULL, NULL, NULL, NULL, NULL, 1, NULL, '#5F3EED', '#F4B826', 1, '2026-07-21 05:03:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `achievement_badge_id` int NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE `user_subscriptions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `subscription_plan_id` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `starts_at` timestamp NOT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `course_id`, `created_at`, `updated_at`) VALUES
(1, 20, 5, '2026-06-29 16:02:44', '2026-06-29 16:02:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievement_badges`
--
ALTER TABLE `achievement_badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `blog_category_id` (`blog_category_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bundles`
--
ALTER TABLE `bundles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bundle_course`
--
ALTER TABLE `bundle_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `bundle_id` (`bundle_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `certificate_templates`
--
ALTER TABLE `certificate_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level_id` (`level_id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `course_analytics`
--
ALTER TABLE `course_analytics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_discussions`
--
ALTER TABLE `course_discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_notes`
--
ALTER TABLE `course_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_prerequisite`
--
ALTER TABLE `course_prerequisite`
  ADD PRIMARY KEY (`course_id`,`prerequisite_id`),
  ADD KEY `prerequisite_id` (`prerequisite_id`);

--
-- Indexes for table `course_tag`
--
ALTER TABLE `course_tag`
  ADD PRIMARY KEY (`course_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_sections`
--
ALTER TABLE `hero_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `icon_providers`
--
ALTER TABLE `icon_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning_reminders`
--
ALTER TABLE `learning_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meet_providers`
--
ALTER TABLE `meet_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `noticeboards`
--
ALTER TABLE `noticeboards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_template_id` (`notification_template_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `school_settings`
--
ALTER TABLE `school_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_content`
--
ALTER TABLE `site_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_languages`
--
ALTER TABLE `site_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `support_ticket_categories`
--
ALTER TABLE `support_ticket_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `support_ticket_id` (`support_ticket_id`);

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achievement_badge_id` (`achievement_badge_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_plan_id` (`subscription_plan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievement_badges`
--
ALTER TABLE `achievement_badges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bundles`
--
ALTER TABLE `bundles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bundle_course`
--
ALTER TABLE `bundle_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_templates`
--
ALTER TABLE `certificate_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course_analytics`
--
ALTER TABLE `course_analytics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_discussions`
--
ALTER TABLE `course_discussions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_notes`
--
ALTER TABLE `course_notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hero_sections`
--
ALTER TABLE `hero_sections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `icon_providers`
--
ALTER TABLE `icon_providers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_reminders`
--
ALTER TABLE `learning_reminders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `meet_providers`
--
ALTER TABLE `meet_providers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `noticeboards`
--
ALTER TABLE `noticeboards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parent_student`
--
ALTER TABLE `parent_student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `school_settings`
--
ALTER TABLE `school_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_content`
--
ALTER TABLE `site_content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_languages`
--
ALTER TABLE `site_languages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_categories`
--
ALTER TABLE `support_ticket_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD CONSTRAINT `assignment_submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_submissions_ibfk_2` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blogs_ibfk_2` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bundles`
--
ALTER TABLE `bundles`
  ADD CONSTRAINT `bundles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bundle_course`
--
ALTER TABLE `bundle_course`
  ADD CONSTRAINT `bundle_course_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bundle_course_ibfk_2` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_analytics`
--
ALTER TABLE `course_analytics`
  ADD CONSTRAINT `course_analytics_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_discussions`
--
ALTER TABLE `course_discussions`
  ADD CONSTRAINT `course_discussions_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `course_discussions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_discussions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_discussions_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_notes`
--
ALTER TABLE `course_notes`
  ADD CONSTRAINT `course_notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_notes_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_prerequisite`
--
ALTER TABLE `course_prerequisite`
  ADD CONSTRAINT `course_prerequisite_ibfk_1` FOREIGN KEY (`prerequisite_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_prerequisite_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_tag`
--
ALTER TABLE `course_tag`
  ADD CONSTRAINT `course_tag_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_tag_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_reminders`
--
ALTER TABLE `learning_reminders`
  ADD CONSTRAINT `learning_reminders_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_reminders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  ADD CONSTRAINT `lesson_completions_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_completions_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_completions_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `noticeboards`
--
ALTER TABLE `noticeboards`
  ADD CONSTRAINT `noticeboards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD CONSTRAINT `notification_logs_ibfk_1` FOREIGN KEY (`notification_template_id`) REFERENCES `notification_templates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notification_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD CONSTRAINT `notification_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD CONSTRAINT `parent_student_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parent_student_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quizzes_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_3` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_replies_ibfk_2` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timetables`
--
ALTER TABLE `timetables`
  ADD CONSTRAINT `timetables_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`achievement_badge_id`) REFERENCES `achievement_badges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD CONSTRAINT `user_subscriptions_ibfk_1` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_subscriptions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

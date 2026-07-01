-- MySQL dump converted from SQLite
-- Target: MySQL 8.0+ / MariaDB 10.3+
--
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
START TRANSACTION;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (`id` integer primary key AUTO_INCREMENT not null, `migration` varchar(255) not null, `batch` integer not null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` VALUES(4,'2026_05_26_183242_add_role_and_profile_fields_to_users_table',1);
INSERT INTO `migrations` VALUES(5,'2026_05_26_200231_create_courses_table',1);
INSERT INTO `migrations` VALUES(6,'2026_05_27_075153_create_lessons_table',1);
INSERT INTO `migrations` VALUES(7,'2026_05_27_075522_create_support_tickets_table',1);
INSERT INTO `migrations` VALUES(8,'2026_05_27_075523_create_enrollments_table',1);
INSERT INTO `migrations` VALUES(9,'2026_05_27_075524_create_certificates_table',1);
INSERT INTO `migrations` VALUES(10,'2026_05_27_120000_add_bio_to_users_table',1);
INSERT INTO `migrations` VALUES(11,'2026_05_27_120001_create_categories_table',1);
INSERT INTO `migrations` VALUES(12,'2026_05_27_120002_create_faqs_table',1);
INSERT INTO `migrations` VALUES(13,'2026_05_27_120003_create_sliders_table',1);
INSERT INTO `migrations` VALUES(14,'2026_05_27_120004_create_testimonials_table',1);
INSERT INTO `migrations` VALUES(15,'2026_05_27_120005_create_hero_sections_table',1);
INSERT INTO `migrations` VALUES(16,'2026_05_27_120006_create_subjects_table',1);
INSERT INTO `migrations` VALUES(17,'2026_05_27_120007_create_blog_categories_table',1);
INSERT INTO `migrations` VALUES(18,'2026_05_27_120008_create_blogs_table',1);
INSERT INTO `migrations` VALUES(19,'2026_05_27_120009_create_pages_table',1);
INSERT INTO `migrations` VALUES(20,'2026_05_27_130000_create_contact_messages_table',1);
INSERT INTO `migrations` VALUES(21,'2026_05_27_140000_create_quizzes_table',1);
INSERT INTO `migrations` VALUES(22,'2026_05_27_140001_create_quiz_questions_table',1);
INSERT INTO `migrations` VALUES(23,'2026_05_27_140002_create_quiz_results_table',1);
INSERT INTO `migrations` VALUES(24,'2026_05_27_140003_create_assignments_table',1);
INSERT INTO `migrations` VALUES(25,'2026_05_27_140004_create_assignment_submissions_table',1);
INSERT INTO `migrations` VALUES(26,'2026_05_27_144801_change_enrollment_status_default_to_in_progress',1);
INSERT INTO `migrations` VALUES(27,'2026_05_27_144926_create_lesson_completions_table',1);
INSERT INTO `migrations` VALUES(28,'2026_05_27_145855_add_outcomes_and_requirements_to_courses_table',1);
INSERT INTO `migrations` VALUES(29,'2026_05_27_150053_add_slug_to_courses_table',1);
INSERT INTO `migrations` VALUES(30,'2026_05_27_150930_create_coupons_table',1);
INSERT INTO `migrations` VALUES(31,'2026_05_27_150931_create_notifications_table',1);
INSERT INTO `migrations` VALUES(32,'2026_05_27_150931_create_payment_methods_table',1);
INSERT INTO `migrations` VALUES(33,'2026_05_27_150932_create_wishlists_table',1);
INSERT INTO `migrations` VALUES(34,'2026_05_27_201000_add_payment_type_to_courses_table',1);
INSERT INTO `migrations` VALUES(35,'2026_05_27_202000_fix_relationships_and_missing_columns',1);
INSERT INTO `migrations` VALUES(36,'2026_05_28_125137_create_settings_table',1);
INSERT INTO `migrations` VALUES(37,'2026_05_28_125350_add_category_id_to_courses_table',1);
INSERT INTO `migrations` VALUES(38,'2026_05_28_125919_create_reviews_table',1);
INSERT INTO `migrations` VALUES(39,'2026_05_28_130203_create_noticeboards_table',1);
INSERT INTO `migrations` VALUES(40,'2026_05_28_133230_create_bundles_table',1);
INSERT INTO `migrations` VALUES(41,'2026_05_28_133816_add_level_id_to_courses_table',1);
INSERT INTO `migrations` VALUES(42,'2026_05_28_133816_create_course_tag_table',1);
INSERT INTO `migrations` VALUES(43,'2026_05_28_133816_create_levels_table',1);
INSERT INTO `migrations` VALUES(44,'2026_05_28_133816_create_tags_table',1);
INSERT INTO `migrations` VALUES(45,'2026_05_28_135116_create_ticket_replies_table',1);
INSERT INTO `migrations` VALUES(46,'2026_05_28_135348_create_notification_logs_table',1);
INSERT INTO `migrations` VALUES(47,'2026_06_05_152531_add_attempts_limit_to_quizzes',1);
INSERT INTO `migrations` VALUES(48,'2026_06_05_152532_create_course_prerequisite_table',1);
INSERT INTO `migrations` VALUES(49,'2026_06_05_152533_add_last_watched_position_to_lesson_completions',1);
INSERT INTO `migrations` VALUES(50,'2026_06_05_152851_create_payouts_table',1);
INSERT INTO `migrations` VALUES(51,'2026_06_05_152852_create_carts_table',1);
INSERT INTO `migrations` VALUES(52,'2026_06_05_155412_create_course_discussions_table',1);
INSERT INTO `migrations` VALUES(53,'2026_06_05_155413_create_notification_preferences_table',1);
INSERT INTO `migrations` VALUES(54,'2026_06_09_000001_add_video_and_document_to_lessons_table',1);
INSERT INTO `migrations` VALUES(55,'2026_06_09_115105_add_video_and_document_to_lessons_table',1);
INSERT INTO `migrations` VALUES(56,'2026_06_10_000001_create_meet_providers_table',1);
INSERT INTO `migrations` VALUES(57,'2026_06_10_000002_create_subscriptions_table',1);
INSERT INTO `migrations` VALUES(58,'2026_06_10_000003_create_support_ticket_categories_table',1);
INSERT INTO `migrations` VALUES(59,'2026_06_13_170539_add_course_id_to_support_tickets_table',1);
INSERT INTO `migrations` VALUES(60,'2026_06_13_add_quiz_features',1);
INSERT INTO `migrations` VALUES(61,'2026_06_14_193438_add_instructions_file_to_assignments_table',1);
INSERT INTO `migrations` VALUES(62,'2026_06_14_193512_add_instructions_file_to_quizzes_table',1);
INSERT INTO `migrations` VALUES(63,'2026_06_16_075907_add_profile_and_activity_columns_to_users_table',1);
INSERT INTO `migrations` VALUES(64,'2026_06_16_080455_add_enhancement_columns_to_courses_table',1);
INSERT INTO `migrations` VALUES(65,'2026_06_16_080500_add_enhancement_columns_to_assignments_table',1);
INSERT INTO `migrations` VALUES(66,'2026_06_16_080505_add_quiz_enhancement_columns_to_quizzes_table',1);
INSERT INTO `migrations` VALUES(67,'2026_06_16_080510_create_activity_logs_table',1);
INSERT INTO `migrations` VALUES(68,'2026_06_16_080515_create_quiz_attempts_table',1);
INSERT INTO `migrations` VALUES(69,'2026_06_16_080520_create_site_content_table',1);
INSERT INTO `migrations` VALUES(70,'2026_06_16_080525_create_course_analytics_table',1);
INSERT INTO `migrations` VALUES(71,'2026_06_16_153518_create_achievement_badges_table',1);
INSERT INTO `migrations` VALUES(72,'2026_06_16_153518_create_user_badges_table',1);
INSERT INTO `migrations` VALUES(73,'2026_06_16_153519_add_forum_enhancement_fields_to_course_discussions_table',1);
INSERT INTO `migrations` VALUES(74,'2026_06_16_153520_add_org_branding_fields_to_users_table',1);
INSERT INTO `migrations` VALUES(75,'2026_06_16_153520_create_certificate_templates_table',1);
INSERT INTO `migrations` VALUES(76,'2026_06_16_153520_create_learning_reminders_table',1);
INSERT INTO `migrations` VALUES(77,'2026_06_16_153521_add_meta_fields_to_site_content_table',1);
INSERT INTO `migrations` VALUES(78,'2026_06_17_000001_create_currencies_table',1);
INSERT INTO `migrations` VALUES(79,'2026_06_17_000002_create_site_languages_table',1);
INSERT INTO `migrations` VALUES(80,'2026_06_17_000003_create_email_templates_table',1);
INSERT INTO `migrations` VALUES(81,'2026_06_17_000004_create_timezones_table',1);
INSERT INTO `migrations` VALUES(82,'2026_06_17_000005_create_countries_table',1);
INSERT INTO `migrations` VALUES(83,'2026_06_17_000006_create_states_table',1);
INSERT INTO `migrations` VALUES(84,'2026_06_17_000007_create_cities_table',1);
INSERT INTO `migrations` VALUES(85,'2026_06_17_000008_create_icon_providers_table',1);
INSERT INTO `migrations` VALUES(86,'2026_06_17_000009_add_unique_index_to_quiz_results',1);
INSERT INTO `migrations` VALUES(87,'2026_06_17_000010_add_performance_indexes',1);
INSERT INTO `migrations` VALUES(88,'2026_06_18_115428_add_duration_to_sliders_table',1);
INSERT INTO `migrations` VALUES(89,'2026_06_28_142500_add_instructor_approval_to_users_table',1);
INSERT INTO `migrations` VALUES(90,'2026_06_28_142501_create_school_settings_table',1);
INSERT INTO `migrations` VALUES(91,'2026_06_28_142502_create_classes_table',1);
INSERT INTO `migrations` VALUES(92,'2026_06_28_142503_create_attendances_table',1);
INSERT INTO `migrations` VALUES(93,'2026_06_28_142504_create_exams_table',1);
INSERT INTO `migrations` VALUES(94,'2026_06_28_142505_create_results_table',1);
INSERT INTO `migrations` VALUES(95,'2026_06_28_142506_create_timetables_table',1);
INSERT INTO `migrations` VALUES(96,'2026_06_28_142507_create_parent_student_table',1);
INSERT INTO `migrations` VALUES(97,'2026_06_28_150000_add_class_id_to_users_table',1);
INSERT INTO `migrations` VALUES(98,'2026_06_28_160000_add_provider_to_payment_methods_table',1);
INSERT INTO `migrations` VALUES(99,'2026_06_28_160001_add_payment_fields_to_enrollments_table',1);
INSERT INTO `migrations` VALUES(100,'2026_06_28_170000_add_link_to_notification_logs_table',1);
INSERT INTO `migrations` VALUES(101,'2026_06_29_192740_create_announcements_table',2);
INSERT INTO `migrations` VALUES(102,'2026_06_29_193945_add_is_exam_to_quizzes_table',3);
INSERT INTO `migrations` VALUES(103,'2026_06_29_202903_add_scheduling_to_quizzes',4);
INSERT INTO `migrations` VALUES(104,'2026_06_29_202904_add_scheduling_to_assignments',4);
INSERT INTO `migrations` VALUES(105,'2026_06_30_100000_add_instructor_id_to_contact_messages_table',5);

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` datetime, primary key (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (`id` varchar(255) not null, `user_id` integer, `ip_address` varchar(255), `user_agent` text, `payload` text not null, `last_activity` integer not null, primary key (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (`key` varchar(255) not null, `value` text not null, `expiration` integer not null, primary key (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (`key` varchar(255) not null, `owner` varchar(255) not null, `expiration` integer not null, primary key (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (`id` integer primary key AUTO_INCREMENT not null, `queue` varchar(255) not null, `payload` text not null, `attempts` integer not null, `reserved_at` integer, `available_at` integer not null, `created_at` integer not null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (`id` varchar(255) not null, `name` varchar(255) not null, `total_jobs` integer not null, `pending_jobs` integer not null, `failed_jobs` integer not null, `failed_job_ids` text not null, `options` text, `cancelled_at` integer, `created_at` integer not null, `finished_at` integer, primary key (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (`id` integer primary key AUTO_INCREMENT not null, `uuid` varchar(255) not null, `connection` varchar(255) not null, `queue` varchar(255) not null, `payload` text not null, `exception` text not null, `failed_at` datetime not null default CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons` (`id` integer primary key AUTO_INCREMENT not null, `course_id` integer not null, `title` varchar(255) not null, `content` text, `video_url` varchar(255), `duration` varchar(255), `order` integer not null default '0', `is_free_preview` tinyint(1) not null default '0', `status` varchar(255) not null default 'published', `created_at` datetime, `updated_at` datetime, `video_file` varchar(255), `document_file` varchar(255), foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lessons` VALUES(1,1,'Welcome to the Course','In this lesson, we will introduce you to the world of web development. You will learn what to expect from this course and how to make the most of it.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','10:00',1,1,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(2,1,'Setting Up Your Development Environment','Learn how to install and configure all the tools you need to start building websites, including code editors, browsers, and developer tools.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','15:30',2,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(3,1,'HTML Fundamentals','Dive into HTML5 and learn about elements, attributes, semantic markup, forms, tables, and best practices for structuring web content.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','25:00',3,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(4,1,'CSS Styling Basics','Master the fundamentals of CSS including selectors, box model, flexbox, grid, responsive design, and CSS custom properties.','https://www.w3schools.com/html/mov_bbb.mp4','20:00',4,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(5,1,'JavaScript Introduction','Get started with JavaScript programming covering variables, functions, DOM manipulation, events, and ES6+ features.','https://www.w3schools.com/html/mov_bbb.mp4','30:00',5,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(6,1,'Building Your First Web Page','Apply everything you have learned by building a complete multi-section landing page from scratch.','https://www.w3schools.com/html/mov_bbb.mp4','18:45',6,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(7,2,'Course Overview & Prerequisites','An overview of what we will build in this course and a review of the prerequisites you need to be successful.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','08:00',1,1,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(8,2,'Laravel Architecture Deep Dive','Explore Laravels internal architecture including the service container, facades, providers, and the request lifecycle.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','22:00',2,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(9,2,'Authentication & Authorization','Implement complete authentication with Laravel Breeze/Fortify and role-based authorization using gates and policies.','https://www.w3schools.com/html/mov_bbb.mp4','35:00',3,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(10,2,'Building RESTful APIs','Design and build RESTful APIs with Laravel including resource controllers, API resources, rate limiting, and API versioning.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','40:00',4,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(11,2,'Testing Your Application','Learn to write feature tests, unit tests, and browser tests using PHPUnit and Laravel Dusk.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','28:00',5,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(12,2,'Deployment to Production','Learn how to deploy a Laravel application to production using Forge, Vapor, or traditional VPS setups.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','25:00',6,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(13,3,'What is UI/UX Design?','Understand the difference between UI and UX design, the design thinking process, and the role of a designer in product development.','https://www.w3schools.com/html/mov_bbb.mp4','12:00',1,1,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(14,3,'User Research Methods','Learn various user research methods including interviews, surveys, usability testing, and how to synthesize findings.','https://www.w3schools.com/html/mov_bbb.mp4','28:00',2,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(15,3,'Wireframing & Prototyping in Figma','Master Figma for creating wireframes, interactive prototypes, design systems, and collaborative design workflows.','https://www.w3schools.com/html/mov_bbb.mp4','32:00',3,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(16,3,'Visual Design Principles','Learn color theory, typography, layout, spacing, and visual hierarchy to create beautiful and functional designs.','https://www.w3schools.com/html/mov_bbb.mp4','22:00',4,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(17,3,'Building Your Design Portfolio','Learn how to showcase your work effectively, write case studies, and present your designs to stakeholders and employers.','https://www.w3schools.com/html/mov_bbb.mp4','15:00',5,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(18,4,'Python Setup & First Steps','Install Python, set up Jupyter notebooks, and write your first Python programs with hands-on exercises.','https://www.w3schools.com/html/mov_bbb.mp4','10:00',1,1,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(19,4,'Python Data Structures & Control Flow','Master Python lists, dictionaries, sets, tuples, loops, conditionals, and list comprehensions.','https://www.w3schools.com/html/mov_bbb.mp4','20:00',2,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(20,4,'NumPy for Numerical Computing','Learn NumPy arrays, vectorized operations, broadcasting, and linear algebra operations for data analysis.','https://www.w3schools.com/html/mov_bbb.mp4','25:00',3,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(21,4,'Pandas for Data Manipulation','Master Pandas DataFrames for data cleaning, transformation, grouping, merging, and time series analysis.','https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4','30:00',4,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(22,4,'Data Visualization with Matplotlib','Create publication-quality charts and plots using Matplotlib and Seaborn for exploratory data analysis.','https://www.w3schools.com/html/mov_bbb.mp4','20:00',5,0,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL);
INSERT INTO `lessons` VALUES(23,5,'marketing','this i s the best free marketing couse',NULL,'4 hours',1,1,'published','2026-06-29 09:14:06','2026-06-29 09:14:06','lessons/videos/1782724446_esxG965B.mp4','lessons/documents/1782724446_8e60hDgV.pptx');

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `description` text, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES(1,'Web Development','web-development','Courses related to Web Development','active','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `categories` VALUES(2,'Data Science','data-science','Courses related to Data Science','active','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `categories` VALUES(3,'Design','design','Courses related to Design','active','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `categories` VALUES(4,'Mobile Development','mobile-development','Courses related to Mobile Development','active','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `categories` VALUES(5,'Business','business','Courses related to Business','active','2026-06-28 19:52:58','2026-06-28 19:52:58');

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (`id` integer primary key AUTO_INCREMENT not null, `question` varchar(255) not null, `answer` text not null, `category` varchar(255), `order` integer not null default '0', `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `faqs` VALUES(1,'How do I enroll in a course?','Simply create an account, browse our course catalog, and click "Enroll" on any course. Free courses are immediately accessible, and paid courses require payment before access.','General',1,'active','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `faqs` VALUES(2,'Can I get a certificate after completing a course?','Yes! When you complete all lessons in a course, a certificate of completion is automatically generated. You can download it from your dashboard.','Certificates',1,'active','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `faqs` VALUES(3,'What payment methods are accepted?','We accept credit/debit cards (Visa, MasterCard, Amex), PayPal, and bank transfers for offline payments. All transactions are secure and encrypted.','Payments',1,'active','2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders` (`id` integer primary key AUTO_INCREMENT not null, `title` varchar(255) not null, `subtitle` varchar(255), `description` text, `btn_text` varchar(255), `btn_link` varchar(255), `image` varchar(255), `order` integer not null default '0', `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, `duration` integer not null default '6') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sliders` VALUES(1,'Welcome to EduLab','Start Your Learning Journey','Join millions of learners worldwide and gain the skills you need to succeed.','Get Started','/register',NULL,1,'active','2026-06-28 19:52:59','2026-06-28 19:52:59',6);

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `position` varchar(255), `content` text not null, `rating` integer not null default '5', `avatar` varchar(255), `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `testimonials` VALUES(1,'Sarah Johnson','Web Developer at Google','EduLab transformed my career. The courses are well-structured and the instructors are incredibly knowledgeable. I went from a complete beginner to a professional web developer in just 6 months.',5,NULL,'active','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `testimonials` VALUES(2,'Michael Chen','Data Analyst at Amazon','The Data Science course was exactly what I needed to transition into analytics. The hands-on projects and real-world examples made learning practical and enjoyable.',5,NULL,'active','2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `hero_sections`;
CREATE TABLE `hero_sections` (`id` integer primary key AUTO_INCREMENT not null, `title` varchar(255) not null, `subtitle` varchar(255), `description` text, `page` varchar(255) not null default 'home', `image` varchar(255), `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hero_sections` VALUES(1,'Learn Without Limits','Unlock Your Potential','Access thousands of expert-led courses and take your skills to the next level. Learn at your own pace with lifetime access to course materials.','home',NULL,'active','2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `category_id` integer, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, foreign key(`category_id`) references `categories`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE `blog_categories` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_categories` VALUES(1,'Tutorials','tutorials','active','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `blog_categories` VALUES(2,'Industry News','industry-news','active','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `blog_categories` VALUES(3,'Career Advice','career-advice','active','2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (`id` integer primary key AUTO_INCREMENT not null, `title` varchar(255) not null, `slug` varchar(255) not null, `content` text not null, `excerpt` varchar(255), `blog_category_id` integer, `user_id` integer not null, `image` varchar(255), `status` varchar(255) not null default 'draft', `created_at` datetime, `updated_at` datetime, foreign key(`blog_category_id`) references `blog_categories`(`id`) on delete set null, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blogs` VALUES(1,'Getting Started with Laravel: A Beginners Guide','getting-started-with-laravel-a-beginners-guide','Laravel has become one of the most popular PHP frameworks for web development. In this comprehensive guide, we will walk you through everything you need to know to get started with Laravel.

First, you will need to install Laravel using Composer. Once installed, you can start building your application using Laravels elegant syntax and powerful features. The framework follows the MVC architectural pattern, making it easy to organize your code.

One of the best features of Laravel is Eloquent ORM, which provides a beautiful Active Record implementation for working with your database. You can define relationships between models, use query scopes, and leverage eager loading to optimize your queries.

Laravel also includes a powerful routing system, middleware for filtering HTTP requests, and a robust authentication system out of the box. The Blade templating engine allows you to create dynamic views with ease.

Whether you are building a simple blog or a complex enterprise application, Laravel provides the tools and flexibility you need to succeed.','A complete beginner-friendly guide to getting started with Laravel PHP framework, covering installation, MVC architecture, Eloquent ORM, and more.',1,1,NULL,'published','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `blogs` VALUES(2,'Top 10 Web Development Trends in 2026','top-10-web-development-trends-in-2026','The web development landscape continues to evolve at a rapid pace. Here are the top trends shaping the industry in 2026.

1. AI-Powered Development: Artificial intelligence is transforming how we build web applications. From code generation to automated testing, AI tools are becoming essential parts of the development workflow.

2. WebAssembly: WebAssembly continues to gain traction, enabling high-performance applications in the browser. Languages like Rust and Go are increasingly being used for web development.

3. Edge Computing: Serverless and edge computing are changing how we deploy and scale applications. Services like Cloudflare Workers and AWS Lambda@Edge enable faster response times.

4. Progressive Web Apps: PWAs continue to blur the line between web and native applications, offering offline capabilities, push notifications, and native-like experiences.

5. Microservices Architecture: More organizations are adopting microservices to build scalable and maintainable applications.','Discover the top 10 web development trends for 2026, including AI-powered development, WebAssembly, edge computing, and more.',2,1,NULL,'published','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `blogs` VALUES(3,'How to Build a Successful Online Learning Platform','how-to-build-a-successful-online-learning-platform','Building an online learning platform is an exciting venture that requires careful planning and execution. Here are the key steps to success.

1. Define Your Niche: Identify your target audience and the specific subjects you want to teach. A focused approach often works better than trying to cover everything.

2. Choose the Right Technology: Your platform needs to be scalable, secure, and user-friendly. An LMS built with Laravel provides a solid foundation with its robust ecosystem.

3. Create Quality Content: The success of your platform depends on the quality of your courses. Invest in professional video production, well-structured curriculum, and engaging assignments.

4. Implement Features Users Love: Features like progress tracking, certificates, quizzes, and community forums keep learners engaged and motivated.

5. Marketing and Growth: Use content marketing, social media, and partnerships to reach your target audience.','Learn the key steps to building a successful online learning platform, from defining your niche to marketing and growth strategies.',3,1,NULL,'published','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `blogs` VALUES(4,'Understanding the MVC Pattern in Web Development','understanding-the-mvc-pattern-in-web-development','The Model-View-Controller (MVC) pattern is a fundamental architectural pattern in web development. Understanding it is crucial for building maintainable applications.

The Model represents the data and business logic of your application. In Laravel, models are typically Eloquent models that interact with your database. They handle data validation, relationships, and business rules.

The View is responsible for presenting data to the user. In Laravel, Blade templates are used to create dynamic HTML views. Views should be simple and focused on presentation only.

The Controller acts as an intermediary between Models and Views. It handles user requests, processes data through models, and returns appropriate views. Controllers keep your application organized and maintainable.

By separating concerns, MVC makes your code more modular, testable, and easier to maintain. Laravel implements MVC beautifully, making it a great choice for web developers.','A comprehensive explanation of the Model-View-Controller (MVC) pattern and how it is implemented in Laravel for web development.',1,1,NULL,'published','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `blogs` VALUES(5,'Career Paths in Data Science: What You Need to Know','career-paths-in-data-science-what-you-need-to-know','Data science continues to be one of the most in-demand fields in technology. Here is what you need to know about pursuing a career in data science.

Data science combines statistics, programming, and domain expertise to extract insights from data. The field offers various career paths including data analyst, data engineer, machine learning engineer, and data scientist.

To get started, you should learn Python programming, statistics fundamentals, and data manipulation libraries like Pandas and NumPy. Data visualization skills with Matplotlib and Seaborn are also essential.

Machine learning knowledge is increasingly important. Start with supervised learning algorithms like linear regression and decision trees, then move on to more advanced topics like neural networks.

Building a portfolio of projects is crucial for landing your first data science role. Participate in Kaggle competitions, work on real-world datasets, and share your findings on GitHub.','Explore the various career paths in data science and learn what skills, tools, and education you need to succeed in this growing field.',2,1,NULL,'published','2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (`id` integer primary key AUTO_INCREMENT not null, `title` varchar(255) not null, `slug` varchar(255) not null, `content` text not null, `status` varchar(255) not null default 'draft', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pages` VALUES(1,'About Us','about-us','EduLab is a leading online learning platform dedicated to providing high-quality education to learners worldwide. Founded in 2024, our mission is to make education accessible, affordable, and effective for everyone.

Our platform features expert-led courses across multiple disciplines including web development, data science, design, and business. We believe in learning by doing, which is why our courses emphasize hands-on projects and real-world applications.

With a community of thousands of learners and hundreds of courses, EduLab is committed to helping you achieve your learning goals and advance your career.','published','2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `pages` VALUES(2,'Privacy Policy','privacy-policy','Your privacy is important to us. This Privacy Policy explains how EduLab collects, uses, and protects your personal information.

We collect information you provide when creating an account, enrolling in courses, and interacting with our platform. This includes your name, email address, and payment information.

We use this information to provide and improve our services, process payments, send course updates, and communicate with you about your learning progress.

We implement industry-standard security measures to protect your data. We do not share your personal information with third parties except as necessary to provide our services.','published','2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `quiz_questions`;
CREATE TABLE `quiz_questions` (`id` integer primary key AUTO_INCREMENT not null, `quiz_id` integer not null, `question` text not null, `type` varchar(255) not null default 'multiple_choice', `options` text not null, `correct_answer` varchar(255) not null, `marks` integer not null default '1', `order` integer not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`quiz_id`) references `quizzes`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quiz_questions` VALUES(1,1,'What does HTML stand for?','multiple_choice','["HyperText Markup Language","HyperTransfer Markup Language","Home Tool Markup Language","None of the above"]','HyperText Markup Language',10,1,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(2,1,'Which CSS property is used to change the text color?','multiple_choice','["color","font-color","text-color","background-color"]','color',10,2,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(3,1,'What is the correct HTML tag for a hyperlink?','multiple_choice','["<a>","<link>","<href>","<url>"]','<a>',10,3,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(4,1,'Which CSS property controls the layout direction in Flexbox?','multiple_choice','["flex-direction","direction","layout","flex-layout"]','flex-direction',10,4,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(5,2,'Which artisan command creates a new controller?','multiple_choice','["make:controller","create:controller","new:controller","generate:controller"]','make:controller',10,1,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(6,2,'What is the default Eloquent ORM namespace?','multiple_choice','["App\\Models","App\\Model","App\\ORM","App\\Eloquent"]','App\Models',10,2,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(7,2,'Which method is used to define a one-to-many relationship?','multiple_choice','["hasMany","belongsTo","hasOne","belongsToMany"]','hasMany',10,3,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(8,3,'What does UX stand for?','multiple_choice','["User Experience","User Extension","Universal Experience","Unique Xperience"]','User Experience',10,1,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(9,3,'Which tool is commonly used for wireframing?','multiple_choice','["Figma","Photoshop","Illustrator","After Effects"]','Figma',10,2,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(10,3,'What color model is used for digital screens?','multiple_choice','["RGB","CMYK","HSL","HEX"]','RGB',10,3,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(11,4,'Which library is used for numerical computing in Python?','multiple_choice','["NumPy","Pandas","Matplotlib","Scikit-learn"]','NumPy',10,1,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(12,4,'What Pandas data structure is a 2D labeled data structure?','multiple_choice','["DataFrame","Series","Array","Matrix"]','DataFrame',10,2,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(13,4,'Which method is used to read a CSV file in Pandas?','multiple_choice','["read_csv","load_csv","import_csv","open_csv"]','read_csv',10,3,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `quiz_questions` VALUES(14,5,'whats bloaw','multiple_select','["A ug","B hj","C ojo","D hkh"]','',1,1,'2026-06-29 20:05:10','2026-06-29 20:05:10');

DROP TABLE IF EXISTS `quiz_results`;
CREATE TABLE `quiz_results` (`id` integer primary key AUTO_INCREMENT not null, `quiz_id` integer not null, `user_id` integer not null, `score` integer not null default '0', `total_marks` integer not null default '0', `answers` text, `started_at` datetime, `completed_at` datetime, `passed` tinyint(1) not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`quiz_id`) references `quizzes`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quiz_results` VALUES(1,5,20,0,0,'[]',NULL,'2026-06-29 19:31:59',0,'2026-06-29 19:31:59','2026-06-29 19:31:59');

DROP TABLE IF EXISTS `assignments`;
CREATE TABLE `assignments` (`id` integer primary key AUTO_INCREMENT not null, `course_id` integer not null, `user_id` integer, `title` varchar(255) not null, `description` text not null, `instructions` text, `due_date` date, `total_marks` integer not null default '100', `status` varchar(255) not null default 'draft', `created_at` datetime, `updated_at` datetime, `instructions_file` varchar(255), `time_limit_minutes` integer, `max_file_size_mb` integer not null default '10', `allowed_file_types` text, `late_submission_allowed` tinyint(1) not null default '0', `late_penalty_percent` decimal(8,2), `grading_rubric` text, `available_from` datetime, foreign key(`course_id`) references `courses`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `assignments` VALUES(1,1,NULL,'Build a Personal Portfolio Page','Create a personal portfolio webpage using HTML and CSS. The page should include a header with navigation, an about section, a projects section showcasing at least 3 projects, and a contact form.','1. Create an HTML file with semantic markup
2. Style it with external CSS
3. Make it responsive using media queries
4. Use Flexbox or Grid for layout
5. Add a contact form with validation
6. Deploy using GitHub Pages or any hosting platform','2026-09-28 19:52:58',100,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL,10,NULL,0,NULL,NULL,NULL);
INSERT INTO `assignments` VALUES(2,2,NULL,'Build a Task Management API','Build a RESTful API for a task management application using Laravel. The API should support user authentication, CRUD operations for tasks, and team collaboration.','1. Set up authentication with Laravel Sanctum
2. Create Task model with migration
3. Implement CRUD endpoints
4. Add authorization with policies
5. Write feature tests
6. Document endpoints using API resources','2026-09-28 19:52:58',100,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL,10,NULL,0,NULL,NULL,NULL);
INSERT INTO `assignments` VALUES(3,3,NULL,'Redesign a Mobile App Interface','Choose an existing mobile app and redesign its user interface. Create wireframes, high-fidelity mockups, and an interactive prototype in Figma.','1. Select an app to redesign
2. Conduct a heuristic evaluation
3. Create low-fidelity wireframes
4. Design high-fidelity mockups
5. Build an interactive prototype
6. Write a case study explaining your design decisions','2026-09-28 19:52:58',100,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL,10,NULL,0,NULL,NULL,NULL);
INSERT INTO `assignments` VALUES(4,4,NULL,'Exploratory Data Analysis Project','Perform an exploratory data analysis on a dataset of your choice. Use Python, Pandas, and Matplotlib to clean, analyze, and visualize the data.','1. Choose a dataset from Kaggle or other source
2. Load and inspect the data
3. Clean missing values and outliers
4. Perform statistical analysis
5. Create at least 5 visualizations
6. Summarize your findings in a Jupyter notebook','2026-09-28 19:52:58',100,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL,10,NULL,0,NULL,NULL,NULL);

DROP TABLE IF EXISTS `assignment_submissions`;
CREATE TABLE `assignment_submissions` (`id` integer primary key AUTO_INCREMENT not null, `assignment_id` integer not null, `user_id` integer not null, `submission_text` text, `file_url` varchar(255), `score` integer, `feedback` text, `status` varchar(255) not null default 'submitted', `submitted_at` datetime not null default CURRENT_TIMESTAMP, `graded_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`assignment_id`) references `assignments`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `lesson_completions`;
CREATE TABLE `lesson_completions` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `lesson_id` integer not null, `course_id` integer not null, `completed_at` datetime, `created_at` datetime, `updated_at` datetime, `last_watched_position` integer, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`lesson_id`) references `lessons`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lesson_completions` VALUES(1,10,1,1,'2026-06-28 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL);
INSERT INTO `lesson_completions` VALUES(2,10,2,1,'2026-06-28 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL);
INSERT INTO `lesson_completions` VALUES(3,10,13,3,'2026-06-19 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL);
INSERT INTO `lesson_completions` VALUES(4,10,14,3,'2026-06-07 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL);
INSERT INTO `lesson_completions` VALUES(5,10,15,3,'2026-06-14 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL);
INSERT INTO `lesson_completions` VALUES(6,10,16,3,'2026-06-13 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL);
INSERT INTO `lesson_completions` VALUES(7,10,17,3,'2026-06-21 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL);
INSERT INTO `lesson_completions` VALUES(8,20,23,5,NULL,'2026-06-29 19:56:57','2026-06-29 19:56:57',5);
INSERT INTO `lesson_completions` VALUES(9,22,23,5,NULL,'2026-06-29 20:00:35','2026-06-30 11:29:28',5);

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (`id` integer primary key AUTO_INCREMENT not null, `code` varchar(255) not null, `discount` decimal(8,2) not null, `discount_type` varchar(255) check (`discount_type` in ('percentage', 'fixed')) not null default 'percentage', `max_uses` integer, `used_count` integer not null default '0', `min_amount` decimal(8,2), `expires_at` date, `status` varchar(255) check (`status` in ('active', 'inactive')) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `coupons` VALUES(1,'WELCOME20',20,'percentage',100,5,0,'2027-06-28 19:52:59','active','2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `notification_templates`;
CREATE TABLE `notification_templates` (`id` integer primary key AUTO_INCREMENT not null, `type` varchar(255) not null default 'email', `template_name` varchar(255) not null, `subject` varchar(255), `body` text, `status` varchar(255) check (`status` in ('active', 'inactive')) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE `payment_methods` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `type` varchar(255) check (`type` in ('Online', 'Offline')) not null default 'Online', `status` varchar(255) check (`status` in ('active', 'inactive')) not null default 'active', `created_at` datetime, `updated_at` datetime, `provider` varchar(255)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payment_methods` VALUES(1,'PayPal','Online','active','2026-06-28 19:52:59','2026-06-28 19:52:59',NULL);
INSERT INTO `payment_methods` VALUES(2,'Airtel Money','Offline','active','2026-06-28 19:52:59','2026-06-28 19:52:59','airtel');
INSERT INTO `payment_methods` VALUES(3,'MTN Mobile Money','Offline','active','2026-06-28 19:52:59','2026-06-28 19:52:59','mtn');

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `course_id` integer not null, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `wishlists` VALUES(1,20,5,'2026-06-29 19:02:44','2026-06-29 19:02:44');

DROP TABLE IF EXISTS `certificates`;
CREATE TABLE `certificates` (`id` integer primary key AUTO_INCREMENT not null, `course_id` integer not null, `title` varchar(255) not null, `description` text, `created_at` datetime, `updated_at` datetime, `user_id` integer, foreign key(`course_id`) references `courses`(`id`) on delete cascade on update no action, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (`id` integer primary key AUTO_INCREMENT not null, `key` varchar(255) not null, `value` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `course_id` integer not null, `rating` integer not null, `review` text, `is_approved` tinyint(1) not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` VALUES(1,10,1,4,'Great introduction to web development. Would recommend to anyone starting out.',1,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `reviews` VALUES(2,10,2,4,'Comprehensive coverage of advanced topics. The API building section was particularly helpful.',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `reviews` VALUES(3,10,3,5,'Amazing design course! The Figma tutorials were outstanding and the portfolio project was a game-changer.',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `reviews` VALUES(4,10,4,5,'Perfect for beginners in data science. Clear explanations and hands-on exercises.',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `noticeboards`;
CREATE TABLE `noticeboards` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `title` varchar(255) not null, `content` text, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `bundles`;
CREATE TABLE `bundles` (`id` integer primary key AUTO_INCREMENT not null, `title` varchar(255) not null, `slug` varchar(255) not null, `description` text, `price` decimal(8,2) not null default '0', `sale_price` decimal(8,2), `level` varchar(255), `thumbnail` varchar(255), `status` varchar(255) not null default 'active', `user_id` integer, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bundles` VALUES(1,'Web Development Bundle','web-development-bundle','Master both frontend and backend web development with this comprehensive bundle covering HTML, CSS, JavaScript, and Laravel.',129.99,79.99,NULL,NULL,'active',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `bundles` VALUES(2,'Design & Data Science Bundle','design-data-science-bundle','Combine creative design skills with data science expertise to become a versatile tech professional.',99.99,69.99,NULL,NULL,'active',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `bundle_course`;
CREATE TABLE `bundle_course` (`id` integer primary key AUTO_INCREMENT not null, `bundle_id` integer not null, `course_id` integer not null, foreign key(`bundle_id`) references `bundles`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bundle_course` VALUES(1,1,1);
INSERT INTO `bundle_course` VALUES(2,1,2);
INSERT INTO `bundle_course` VALUES(3,2,3);
INSERT INTO `bundle_course` VALUES(4,2,4);

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `title` varchar(255) not null, `description` text, `category` varchar(255), `price` decimal(8,2) not null default '0', `duration` varchar(255), `status` varchar(255) not null default 'draft', `thumbnail` varchar(255), `created_at` datetime, `updated_at` datetime, `outcomes` text, `requirements` text, `slug` varchar(255), `sale_price` decimal(8,2), `payment_type` varchar(255) not null default 'free', `instructor_id` integer, `category_id` integer, `level_id` integer, `preview_video` varchar(255), `preview_video_duration` integer, `thumbnail_updated_at` datetime, `enrollment_count` integer not null default '0', `average_rating` decimal(8,2), `completion_rate` decimal(8,2), `is_featured` tinyint(1) not null default '0', `metadata` text, foreign key(`category_id`) references `categories`(`id`) on delete set null on update no action, foreign key(`user_id`) references `users`(`id`) on delete cascade on update no action, foreign key(`instructor_id`) references `users`(`id`) on delete set null on update no action, foreign key(`level_id`) references `levels`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `courses` VALUES(1,6,'Introduction to Web Development','Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for complete beginners.','Web Development',0,'6h 30m','Active',NULL,'2026-06-28 19:52:58','2026-06-28 19:52:58','Build responsive web pages using HTML5 and CSS3
Write JavaScript programs to add interactivity to websites
Understand the client-server architecture of the web
Use developer tools to debug and inspect web pages
Deploy a static website to production','A computer with internet access
No prior coding experience required
Willingness to learn and practice','introduction-to-web-development',NULL,'free',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL);
INSERT INTO `courses` VALUES(2,6,'Advanced Laravel: Build Real-World Apps','Master Laravel by building a complete real-world application from scratch. Covers authentication, APIs, testing, and deployment.','Web Development',49.99,'12h 45m','Active',NULL,'2026-06-28 19:52:58','2026-06-28 19:52:58','Build a complete Laravel application from scratch
Implement authentication and authorization
Create RESTful APIs with Laravel
Write feature and unit tests
Deploy a Laravel app to production','Basic PHP knowledge
Familiarity with MVC pattern
Laravel installed on your machine','advanced-laravel-build-real-world-apps',NULL,'paid',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL);
INSERT INTO `courses` VALUES(3,6,'UI/UX Design Masterclass','Learn professional UI/UX design principles, Figma workflows, and portfolio-building techniques.','Design',79.99,'8h 20m','Active',NULL,'2026-06-28 19:52:58','2026-06-28 19:52:58','Apply UX research methods to understand user needs
Create wireframes and interactive prototypes in Figma
Design accessible and inclusive user interfaces
Build a professional design portfolio','A computer with Figma installed (free tier is fine)
No prior design experience required
A creative mindset','ui-ux-design-masterclass',39.99,'paid',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL);
INSERT INTO `courses` VALUES(4,6,'Python for Data Science','Get started with Python programming for data analysis, visualization, and machine learning.','Data Science',0,'5h 00m','Active',NULL,'2026-06-28 19:52:58','2026-06-28 19:52:58','Write Python programs using core language features
Manipulate data with Pandas DataFrames
Create visualizations with Matplotlib and Seaborn
Build and evaluate basic machine learning models','Basic computer literacy
No programming experience required
Python 3 installed on your machine','python-for-data-science',NULL,'free',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0,NULL);
INSERT INTO `courses` VALUES(5,21,'marketing','To reach how to market','Business',0,'4 hours','Active','courses/thumbnails/1782724319_FKqcc6vs.webp','2026-06-29 09:11:59','2026-06-29 09:11:59','how to merket the products','without any knowledge','marketing-59o3g',NULL,'free',NULL,5,3,'courses/preview-videos/1782724319_nlRxakkn.mp4',NULL,NULL,0,NULL,NULL,0,NULL);

DROP TABLE IF EXISTS `course_tag`;
CREATE TABLE `course_tag` (`course_id` integer not null, `tag_id` integer not null, foreign key(`course_id`) references `courses`(`id`) on delete cascade, foreign key(`tag_id`) references `tags`(`id`) on delete cascade, primary key (`course_id`, `tag_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `order` integer not null default '0', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `levels` VALUES(1,'Beginner','beginner',1,'2026-06-28 19:52:57','2026-06-28 19:52:57');
INSERT INTO `levels` VALUES(2,'Intermediate','intermediate',2,'2026-06-28 19:52:57','2026-06-28 19:52:57');
INSERT INTO `levels` VALUES(3,'Advanced','advanced',3,'2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `levels` VALUES(4,'Expert','expert',4,'2026-06-28 19:52:58','2026-06-28 19:52:58');

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tags` VALUES(1,'PHP','php','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(2,'Laravel','laravel','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(3,'JavaScript','javascript','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(4,'Python','python','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(5,'CSS','css','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(6,'HTML','html','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(7,'React','react','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(8,'Vue.js','vuejs','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(9,'Node.js','nodejs','2026-06-28 19:52:58','2026-06-28 19:52:58');
INSERT INTO `tags` VALUES(10,'MySQL','mysql','2026-06-28 19:52:58','2026-06-28 19:52:58');

DROP TABLE IF EXISTS `ticket_replies`;
CREATE TABLE `ticket_replies` (`id` integer primary key AUTO_INCREMENT not null, `support_ticket_id` integer not null, `user_id` integer not null, `message` text not null, `created_at` datetime, `updated_at` datetime, foreign key(`support_ticket_id`) references `support_tickets`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `notification_logs`;
CREATE TABLE `notification_logs` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `notification_template_id` integer, `type` varchar(255) not null default 'in_app', `subject` varchar(255) not null, `body` text, `channel` varchar(255) not null default 'in_app', `is_read` tinyint(1) not null default '0', `sent_at` datetime, `created_at` datetime, `updated_at` datetime, `link` varchar(255), foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`notification_template_id`) references `notification_templates`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notification_logs` VALUES(1,1,NULL,'in_app','New Instructor Registration','dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.','in_app',0,'2026-06-29 07:16:18','2026-06-29 07:16:18','2026-06-29 07:16:18','http://127.0.0.1:8000/admin/settings/approve-instructors');
INSERT INTO `notification_logs` VALUES(2,2,NULL,'in_app','New Instructor Registration','dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.','in_app',0,'2026-06-29 07:16:18','2026-06-29 07:16:18','2026-06-29 07:16:18','http://127.0.0.1:8000/admin/settings/approve-instructors');
INSERT INTO `notification_logs` VALUES(3,3,NULL,'in_app','New Instructor Registration','dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.','in_app',0,'2026-06-29 07:16:18','2026-06-29 07:16:18','2026-06-29 07:16:18','http://127.0.0.1:8000/admin/settings/approve-instructors');
INSERT INTO `notification_logs` VALUES(4,4,NULL,'in_app','New Instructor Registration','dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.','in_app',0,'2026-06-29 07:16:18','2026-06-29 07:16:18','2026-06-29 07:16:18','http://127.0.0.1:8000/admin/settings/approve-instructors');
INSERT INTO `notification_logs` VALUES(5,5,NULL,'in_app','New Instructor Registration','dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.','in_app',0,'2026-06-29 07:16:18','2026-06-29 07:16:18','2026-06-29 07:16:18','http://127.0.0.1:8000/admin/settings/approve-instructors');
INSERT INTO `notification_logs` VALUES(6,11,NULL,'in_app','New Instructor Registration','dimits dimits (dimits@gmail.com) has registered as an instructor and is pending your approval.','in_app',0,'2026-06-29 07:16:18','2026-06-29 07:16:18','2026-06-29 07:16:18','http://127.0.0.1:8000/admin/settings/approve-instructors');
INSERT INTO `notification_logs` VALUES(7,21,NULL,'in_app','Instructor Account Approved','Congratulations dimits dimits! Your instructor account has been approved. You can now create courses and manage students.','in_app',0,'2026-06-29 07:17:57','2026-06-29 07:17:57','2026-06-29 07:17:57','http://127.0.0.1:8000/instructor');
INSERT INTO `notification_logs` VALUES(8,20,NULL,'in_app','Enrolled in marketing','You have successfully enrolled in "marketing". Start learning today!','in_app',0,'2026-06-29 19:02:57','2026-06-29 19:02:57','2026-06-29 19:02:57','http://127.0.0.1:8000/dashboard/courses/5');
INSERT INTO `notification_logs` VALUES(9,20,NULL,'in_app','Quiz Result: quizz one','You scored 0/0 on "quizz one".','in_app',1,'2026-06-29 19:31:59','2026-06-29 19:31:59','2026-06-29 19:57:48',NULL);
INSERT INTO `notification_logs` VALUES(10,22,NULL,'in_app','Enrolled in marketing','You have successfully enrolled in "marketing". Start learning today!','in_app',0,'2026-06-29 20:00:19','2026-06-29 20:00:19','2026-06-29 20:00:19','http://127.0.0.1:8000/dashboard/courses/5');
INSERT INTO `notification_logs` VALUES(11,20,NULL,'in_app','New Quiz: dfgdfg','A new quiz "dfgdfg" has been published in your course.','in_app',0,'2026-06-29 21:15:43','2026-06-29 21:15:43','2026-06-29 21:15:43',NULL);
INSERT INTO `notification_logs` VALUES(12,22,NULL,'in_app','New Quiz: dfgdfg','A new quiz "dfgdfg" has been published in your course.','in_app',0,'2026-06-29 21:15:43','2026-06-29 21:15:43','2026-06-29 21:15:43',NULL);
INSERT INTO `notification_logs` VALUES(13,21,NULL,'in_app','Testing Subject','Testing this chat

â€” From: jim jim','in_app',0,'2026-06-30 17:20:44','2026-06-30 17:20:44','2026-06-30 17:20:44','http://127.0.0.1:8000/dashboard/notifications');

DROP TABLE IF EXISTS `course_prerequisite`;
CREATE TABLE `course_prerequisite` (`course_id` integer not null, `prerequisite_id` integer not null, foreign key(`course_id`) references `courses`(`id`) on delete cascade, foreign key(`prerequisite_id`) references `courses`(`id`) on delete cascade, primary key (`course_id`, `prerequisite_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payouts`;
CREATE TABLE `payouts` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `amount` decimal(8,2) not null, `method` varchar(255) not null default 'bank', `account_details` varchar(255), `notes` text, `status` varchar(255) not null default 'pending', `paid_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `coupon_code` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (`id` integer primary key AUTO_INCREMENT not null, `cart_id` integer not null, `item_type` varchar(255) not null, `item_id` integer not null, `created_at` datetime, `updated_at` datetime, foreign key(`cart_id`) references `carts`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `course_discussions`;
CREATE TABLE `course_discussions` (`id` integer primary key AUTO_INCREMENT not null, `course_id` integer not null, `user_id` integer not null, `body` text not null, `parent_id` integer, `created_at` datetime, `updated_at` datetime, `title` varchar(255), `is_solved` tinyint(1) not null default '0', `upvotes` integer not null default '0', foreign key(`course_id`) references `courses`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`parent_id`) references `course_discussions`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `course_discussions` VALUES(1,5,20,'hello there can we start',NULL,'2026-06-29 19:09:40','2026-06-29 19:09:40',NULL,0,0);

DROP TABLE IF EXISTS `notification_preferences`;
CREATE TABLE `notification_preferences` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `type` varchar(255) not null default 'in_app', `channel` varchar(255) not null default 'in_app', `enabled` tinyint(1) not null default '1', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `meet_providers`;
CREATE TABLE `meet_providers` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `description` text, `api_key` varchar(255), `config` text, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `subscription_plans`;
CREATE TABLE `subscription_plans` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `description` text, `price` decimal(8,2) not null default '0', `duration` varchar(255) not null default 'monthly', `duration_months` integer not null default '1', `features` text, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_subscriptions`;
CREATE TABLE `user_subscriptions` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `subscription_plan_id` integer not null, `status` varchar(255) not null default 'active', `starts_at` datetime not null, `ends_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`subscription_plan_id`) references `subscription_plans`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `support_ticket_categories`;
CREATE TABLE `support_ticket_categories` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `support_tickets`;
CREATE TABLE `support_tickets` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `subject` varchar(255) not null, `category` varchar(255) not null, `priority` varchar(255) not null default 'Medium', `message` text not null, `status` varchar(255) not null default 'Open', `created_at` datetime, `updated_at` datetime, `course_id` integer, foreign key(`user_id`) references `users`(`id`) on delete cascade on update no action, foreign key(`course_id`) references `courses`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `action` varchar(255) not null, `subject_type` varchar(255), `subject_id` integer, `metadata` text, `ip_address` varchar(255), `user_agent` text, `created_at` datetime not null, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `quiz_attempts`;
CREATE TABLE `quiz_attempts` (`id` integer primary key AUTO_INCREMENT not null, `quiz_id` integer not null, `user_id` integer not null, `started_at` datetime not null, `submitted_at` datetime, `expires_at` datetime, `score` decimal(8,2), `answers` text not null, `is_completed` tinyint(1) not null default '0', `attempt_number` integer not null, `created_at` datetime, `updated_at` datetime, foreign key(`quiz_id`) references `quizzes`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quiz_attempts` VALUES(1,5,20,'2026-06-29 19:55:04',NULL,'2026-06-29 20:55:04',NULL,'[]',0,1,'2026-06-29 19:55:04','2026-06-29 19:55:04');
INSERT INTO `quiz_attempts` VALUES(2,5,22,'2026-06-29 20:01:14',NULL,'2026-06-29 21:01:14',NULL,'[]',0,1,'2026-06-29 20:01:14','2026-06-29 20:01:14');
INSERT INTO `quiz_attempts` VALUES(3,5,22,'2026-06-30 09:32:30',NULL,'2026-06-30 10:32:30',NULL,'[]',0,2,'2026-06-30 09:32:30','2026-06-30 09:32:30');

DROP TABLE IF EXISTS `site_content`;
CREATE TABLE `site_content` (`id` integer primary key AUTO_INCREMENT not null, `key` varchar(255) not null, `value` text not null, `type` varchar(255) check (`type` in ('text', 'html', 'json', 'image', 'video')) not null default 'text', `category` varchar(255), `is_active` tinyint(1) not null default '1', `display_order` integer not null default '0', `metadata` text, `created_at` datetime, `updated_at` datetime, `page_section` varchar(255), `icon` varchar(255), `button_text` varchar(255), `button_link` varchar(255), `sort_order` integer not null default '0') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `course_analytics`;
CREATE TABLE `course_analytics` (`id` integer primary key AUTO_INCREMENT not null, `course_id` integer not null, `date` date not null, `views_count` integer not null default '0', `enrollments_count` integer not null default '0', `completions_count` integer not null default '0', `average_rating` decimal(8,2), `total_revenue` decimal(8,2) not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `achievement_badges`;
CREATE TABLE `achievement_badges` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `description` text, `icon` varchar(255), `icon_color` varchar(255) default '#5F3EED', `points` integer not null default '0', `criteria_type` varchar(255) not null, `criteria` text, `is_active` tinyint(1) not null default '1', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `achievement_badges` VALUES(1,'First Steps','first-steps','Completed your first lesson','ri-footprint-line','#5F3EED',10,'first_lesson','{"lesson_count":1}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `achievement_badges` VALUES(2,'Eager Learner','eager-learner','Completed 10 lessons','ri-book-open-line','#5F3EED',50,'ten_lessons','{"lesson_count":10}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `achievement_badges` VALUES(3,'Course Conqueror','course-conqueror','Completed your first course','ri-graduation-cap-line','#5F3EED',100,'course_complete','{"course_count":1}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `achievement_badges` VALUES(4,'Perfect Score','perfect-score','Scored 100% on a quiz','ri-award-line','#5F3EED',75,'perfect_quiz','{"perfect_score":true}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `achievement_badges` VALUES(5,'Welcome Aboard','welcome-aboard','Signed up for an account','ri-user-smile-line','#5F3EED',5,'first_login','{"login_count":1}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `achievement_badges` VALUES(6,'Star Reviewer','star-reviewer','Reviewed 5 courses','ri-star-line','#5F3EED',30,'reviewer','{"review_count":5}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `achievement_badges` VALUES(7,'Dedicated Scholar','dedicated-scholar','Studied for 30 days in a row','ri-fire-line','#5F3EED',200,'streak','{"streak_days":30}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');
INSERT INTO `achievement_badges` VALUES(8,'Knowledge Seeker','knowledge-seeker','Enrolled in 5 courses','ri-database-2-line','#5F3EED',40,'enrollment_milestone','{"course_count":5}',1,'2026-06-28 19:52:59','2026-06-28 19:52:59');

DROP TABLE IF EXISTS `user_badges`;
CREATE TABLE `user_badges` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `achievement_badge_id` integer not null, `earned_at` datetime not null default CURRENT_TIMESTAMP, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`achievement_badge_id`) references `achievement_badges`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `certificate_templates`;
CREATE TABLE `certificate_templates` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `slug` varchar(255) not null, `description` text, `background_image` varchar(255), `logo_position` varchar(255) not null default 'top-center', `title_font` varchar(255) not null default 'sans-serif', `title_color` varchar(255) not null default '#111827', `body_color` varchar(255) not null default '#374151', `layout` text, `include_qr` tinyint(1) not null default '1', `is_active` tinyint(1) not null default '1', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `learning_reminders`;
CREATE TABLE `learning_reminders` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `course_id` integer, `title` varchar(255) not null, `message` text, `reminder_type` varchar(255) not null default 'daily', `remind_at` datetime, `is_sent` tinyint(1) not null default '0', `sent_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `code` varchar(255) not null, `symbol` varchar(255) not null, `rate` decimal(8,2) not null default '1', `is_default` tinyint(1) not null default '0', `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `currencies` VALUES(1,'US Dollar','USD','$',1,1,'active',NULL,NULL);
INSERT INTO `currencies` VALUES(2,'Euro','EUR','â‚¬',0.92,0,'active',NULL,NULL);
INSERT INTO `currencies` VALUES(3,'Pound','GBP','Â£',0.79,0,'active',NULL,NULL);

DROP TABLE IF EXISTS `site_languages`;
CREATE TABLE `site_languages` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `code` varchar(255) not null, `is_default` tinyint(1) not null default '0', `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_languages` VALUES(1,'English','en',1,'active',NULL,NULL);
INSERT INTO `site_languages` VALUES(2,'Spanish','es',0,'active',NULL,NULL);
INSERT INTO `site_languages` VALUES(3,'French','fr',0,'active',NULL,NULL);
INSERT INTO `site_languages` VALUES(4,'Arabic','ar',0,'active',NULL,NULL);

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `subject` varchar(255) not null, `body` text, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `email_templates` VALUES(1,'Welcome','Welcome to EduLab','Welcome to EduLab! We are excited to have you onboard.','active',NULL,NULL);
INSERT INTO `email_templates` VALUES(2,'Reset Password','Password Reset Request','Click the link below to reset your password.','active',NULL,NULL);
INSERT INTO `email_templates` VALUES(3,'Enrollment','Enrollment Confirmation','You have been successfully enrolled in the course.','active',NULL,NULL);

DROP TABLE IF EXISTS `timezones`;
CREATE TABLE `timezones` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `gmt_offset` varchar(255) not null, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `timezones` VALUES(1,'UTC','+00:00','active',NULL,NULL);
INSERT INTO `timezones` VALUES(2,'America/New_York','-05:00','active',NULL,NULL);
INSERT INTO `timezones` VALUES(3,'Europe/London','+00:00','active',NULL,NULL);
INSERT INTO `timezones` VALUES(4,'Asia/Dubai','+04:00','active',NULL,NULL);

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `code` varchar(255) not null, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `countries` VALUES(1,'United States','US','active',NULL,NULL);
INSERT INTO `countries` VALUES(2,'United Kingdom','GB','active',NULL,NULL);
INSERT INTO `countries` VALUES(3,'Canada','CA','active',NULL,NULL);
INSERT INTO `countries` VALUES(4,'Australia','AU','active',NULL,NULL);

DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `country_id` integer not null, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, foreign key(`country_id`) references `countries`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `states` VALUES(1,'California',1,'active',NULL,NULL);
INSERT INTO `states` VALUES(2,'Texas',1,'active',NULL,NULL);
INSERT INTO `states` VALUES(3,'London',2,'active',NULL,NULL);
INSERT INTO `states` VALUES(4,'Ontario',3,'active',NULL,NULL);

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `state_id` integer not null, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, foreign key(`state_id`) references `states`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cities` VALUES(1,'Los Angeles',1,'active',NULL,NULL);
INSERT INTO `cities` VALUES(2,'San Francisco',1,'active',NULL,NULL);
INSERT INTO `cities` VALUES(3,'Austin',2,'active',NULL,NULL);
INSERT INTO `cities` VALUES(4,'London',3,'active',NULL,NULL);

DROP TABLE IF EXISTS `icon_providers`;
CREATE TABLE `icon_providers` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `url` varchar(255) not null, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `icon_providers` VALUES(1,'Remixicon','https://remixicon.com','active',NULL,NULL);
INSERT INTO `icon_providers` VALUES(2,'Font Awesome','https://fontawesome.com','active',NULL,NULL);
INSERT INTO `icon_providers` VALUES(3,'Bootstrap Icons','https://icons.getbootstrap.com','active',NULL,NULL);

DROP TABLE IF EXISTS `school_settings`;
CREATE TABLE `school_settings` (`id` integer primary key AUTO_INCREMENT not null, `school_name` varchar(255), `school_email` varchar(255), `school_phone` varchar(255), `school_address` text, `currency_symbol` varchar(255) not null default '$', `currency_code` varchar(255) not null default 'USD', `currency_position` varchar(255) not null default 'left', `timezone` varchar(255) not null default 'UTC', `language` varchar(255) not null default 'en', `favicon` varchar(255), `site_logo` varchar(255), `primary_color` varchar(255) not null default '#5F3EED', `secondary_color` varchar(255) not null default '#F4B826', `accent_color` varchar(255) not null default '#1AEBC5', `custom_css` text, `slider_video` varchar(255), `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `school_settings` VALUES(1,'Walkthrough Test','LMS@gmail.com','+256756371377','Nakawa','UGX','UGX','right','Africa/Kampala','en',NULL,NULL,'#5f3eed','#f4b826','#1aebc5',NULL,NULL,'2026-06-28 19:46:49','2026-07-01 15:33:03');

DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `grade` varchar(255) not null, `section` varchar(255), `teacher_id` integer, `course_id` integer, `room` varchar(255), `capacity` integer not null default '30', `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, foreign key(`teacher_id`) references `users`(`id`) on delete set null, foreign key(`course_id`) references `courses`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (`id` integer primary key AUTO_INCREMENT not null, `class_id` integer not null, `student_id` integer not null, `course_id` integer, `date` date not null, `status` varchar(255) check (`status` in ('present', 'absent', 'late', 'excused')) not null default 'present', `remarks` text, `created_at` datetime, `updated_at` datetime, foreign key(`class_id`) references `classes`(`id`) on delete cascade, foreign key(`student_id`) references `users`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams` (`id` integer primary key AUTO_INCREMENT not null, `title` varchar(255) not null, `course_id` integer not null, `class_id` integer, `exam_date` date not null, `start_time` time, `end_time` time, `total_marks` integer not null default '100', `passing_marks` decimal(8,2) not null default '50', `exam_type` varchar(255) not null default 'midterm', `description` text, `status` varchar(255) not null default 'scheduled', `created_at` datetime, `updated_at` datetime, foreign key(`course_id`) references `courses`(`id`) on delete cascade, foreign key(`class_id`) references `classes`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `results`;
CREATE TABLE `results` (`id` integer primary key AUTO_INCREMENT not null, `exam_id` integer not null, `student_id` integer not null, `course_id` integer not null, `marks` decimal(8,2) not null default '0', `total_marks` decimal(8,2) not null default '100', `remarks` text, `grade` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`exam_id`) references `exams`(`id`) on delete cascade, foreign key(`student_id`) references `users`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `timetables`;
CREATE TABLE `timetables` (`id` integer primary key AUTO_INCREMENT not null, `class_id` integer not null, `course_id` integer not null, `teacher_id` integer not null, `day_of_week` varchar(255) check (`day_of_week` in ('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')) not null, `start_time` time not null, `end_time` time not null, `room` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`class_id`) references `classes`(`id`) on delete cascade, foreign key(`course_id`) references `courses`(`id`) on delete cascade, foreign key(`teacher_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `parent_student`;
CREATE TABLE `parent_student` (`id` integer primary key AUTO_INCREMENT not null, `parent_id` integer not null, `student_id` integer not null, `relationship` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`parent_id`) references `users`(`id`) on delete cascade, foreign key(`student_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `email` varchar(255) not null, `email_verified_at` datetime, `password` varchar(255) not null, `remember_token` varchar(255), `created_at` datetime, `updated_at` datetime, `first_name` varchar(255), `last_name` varchar(255), `phone` varchar(255), `role` varchar(255) not null default 'student', `designation` varchar(255), `address` varchar(255), `status` varchar(255) not null default 'active', `bio` text, `organization_id` integer, `profile_image` varchar(255), `last_activity_at` datetime, `preferences` text, `activity_notifications` tinyint(1) not null default '1', `logo` varchar(255), `primary_color` varchar(255) default '#5F3EED', `secondary_color` varchar(255) default '#F4B826', `is_approved` tinyint(1) not null default '0', `approved_at` datetime, `class_id` integer, foreign key(`organization_id`) references `users`(`id`) on delete set null on update no action, foreign key(`class_id`) references `classes`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES(1,'System Admin','admin@gmail.com','2026-06-28 19:52:51','$2y$12$gyD0uwyzwq.iu8/nuNW8vOjyRR0b.1eIFmyUPheWivNPQbDUBQ/hW',NULL,'2026-06-28 19:52:51','2026-06-28 19:52:51','System','Admin','+1234567890','admin',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(2,'James Biverson','james.biverson@edulab.test','2026-06-28 19:52:52','$2y$12$r1oKLHR2/44ltZZPrxWYaeQ2eLTADOgMAXM6uUhrwJPAUzxa5.PL.',NULL,'2026-06-28 19:52:52','2026-06-28 19:52:52','James','Biverson','+1234567894','admin',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(3,'Sarah Admin','sarah.admin@edulab.test','2026-06-28 19:52:52','$2y$12$qs0b8ezpA2Vo98PA6yw2EOh92TNNWyDGWT.fSrD.VJK8ffJNzW0r.',NULL,'2026-06-28 19:52:52','2026-06-28 19:52:52','Sarah','Admin','+1234567895','admin',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(4,'IT Admin','it.admin@edulab.test','2026-06-28 19:52:53','$2y$12$ar9rJOiH/jkUfD6ZfoL8wOTwF/kQ8rE1vETn5wMNckiCDUOW84tJW',NULL,'2026-06-28 19:52:53','2026-06-28 19:52:53','IT','Admin','+1234567896','admin',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(5,'Admin User','admin@edulab.test','2026-06-28 19:52:53','$2y$12$G0eA0jy83y2TdOb2g7HZsus3MfLxUVvbzNylW0nC3uIw79OiAtbN.',NULL,'2026-06-28 19:52:53','2026-06-28 19:52:53','Admin','User','+1234567890','admin',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(6,'Robert Smith','instructor@edulab.test','2026-06-28 19:52:53','$2y$12$C/qFZQM3yIfijTKBzC2Qwe0NGzGih3vF6c.iRdNRcwY2slT1N4ZkK',NULL,'2026-06-28 19:52:53','2026-06-28 19:52:53','Robert','Smith','+1234567891','instructor','Senior Web Developer',NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',1,NULL,NULL);
INSERT INTO `users` VALUES(7,'John Instructor','instructor@gmail.com','2026-06-28 19:52:54','$2y$12$Co2md1OrMhtMJF.bvwIEyuR9WGsk7q/SDFYXZ5l37rj/sSXAPN/cG',NULL,'2026-06-28 19:52:54','2026-06-28 19:52:54','John','Instructor','+1234567897','instructor','Instructor Specialist',NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',1,NULL,NULL);
INSERT INTO `users` VALUES(8,'Codexshapper','org@edulab.test','2026-06-28 19:52:54','$2y$12$0mY2RApDzYU4FP3JmcmHXuID3dNfHv9FwmC9pQs4aibZqFw.OXU1C',NULL,'2026-06-28 19:52:54','2026-06-28 19:52:54',NULL,NULL,'+1234567892','organization',NULL,'Toronto, Canada','active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(9,'Apex Organization','org@gmail.com','2026-06-28 19:52:54','$2y$12$M9GYyEwO./XUzPPZdqidaeDGzKsZlRn6bMKr1BJB2H7z9WJo6aVUu',NULL,'2026-06-28 19:52:54','2026-06-28 19:52:54',NULL,NULL,'+1234567898','organization',NULL,'Kampala, Uganda','active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(10,'John Doe','student@edulab.test','2026-06-28 19:52:55','$2y$12$aVbKgJdDQhaKKAourKz6WOr12kfB7ZWhGuYRMWxEj7mgUVWhUii.2',NULL,'2026-06-28 19:52:55','2026-06-28 19:52:55','John','Doe','+1234567893','student',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(11,'Admin User','admin@lms.test','2026-06-28 19:52:55','$2y$12$N1JvL7xKdddJM4niA3P2peS2QazYEzqe/P47YE6j6RX9au.Hgvhia',NULL,'2026-06-28 19:52:55','2026-06-28 19:52:55','Admin','User','256700000001','admin',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(12,'Dr. Sarah Katende','instructor@lms.test','2026-06-28 19:52:55','$2y$12$W9plOqNfqjS393NSD3oEsu0aFtjNKUeL.ryCRxHnB55MRm/Lypqg6',NULL,'2026-06-28 19:52:55','2026-06-28 19:52:55','Sarah','Katende','256700000002','instructor','Senior Software Engineer',NULL,'active','Passionate about web development and mentoring students in East Africa.',NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(13,'Eng. David Ouma','instructor2@lms.test','2026-06-28 19:52:56','$2y$12$yG73xHP1DWDMaVIm1zYDu.Bfyaiuvr2pQNJG/Cfo4iRX8tarWEf/m',NULL,'2026-06-28 19:52:56','2026-06-28 19:52:56','David','Ouma','256700000003','instructor','Mobile Development Specialist',NULL,'active','Specializing in mobile application development for African markets.',NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(14,'Makerere University IT Department','organization@lms.test','2026-06-28 19:52:56','$2y$12$LP4j8o3rV0kHxswEV3z7puZItZyWgFk9jvB5JVU6YTfGgnfXlEu2O',NULL,'2026-06-28 19:52:56','2026-06-28 19:52:56',NULL,NULL,'256700000004','organization',NULL,'Kampala, Uganda','active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(15,'Alice Nakato','student1@lms.test','2026-06-28 19:52:56','$2y$12$Tl8yfhSbXnslOv5v5gZhL.es16lqEDYGCH26nZ.bDNAdZRkBoYx1.',NULL,'2026-06-28 19:52:56','2026-06-28 19:52:56','Alice','Nakato','256700000005','student',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(16,'Brian Ssewanyana','student2@lms.test','2026-06-28 19:52:57','$2y$12$qM0cPw/8SSZpjecR3t1y0..pd7/Ic/KisFXey1cb44QDFa2zkcTPK',NULL,'2026-06-28 19:52:57','2026-06-28 19:52:57','Brian','Ssewanyana','256700000006','student',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(17,'Carol Mwase','student3@lms.test','2026-06-28 19:52:57','$2y$12$hXClxXo5T7RZi2FR/BWw5.5Enp27S3F1mwdWhc/o/ep03IUy7m62q',NULL,'2026-06-28 19:52:57','2026-06-28 19:52:57','Carol','Mwase','256700000007','student',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(18,'Daniel Nyamari','student4@lms.test','2026-06-28 19:52:57','$2y$12$ydBvyelTaFf1KoCPxnLheu/fKVlzPamSOk/ka9ykWlXhLDCyPTd.2',NULL,'2026-06-28 19:52:57','2026-06-28 19:52:57','Daniel','Nyamari','256700000008','student',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(19,'Emily Kipchoge','student5@lms.test','2026-06-28 19:52:57','$2y$12$VRIOnTFYLxzOWphkeW..ze5mCvFpF7PnFbPENScL4xN9u1pMMMEcm',NULL,'2026-06-28 19:52:57','2026-06-28 19:52:57','Emily','Kipchoge','256700000009','student',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(20,'std std','student@gmail.com','2026-06-28 19:55:16','$2y$12$odhVzrRfi0HlNGn0bNvvCuVfA.8hbVNuocBGNQ5U5xQT4rP.aJQKW',NULL,'2026-06-28 19:55:16','2026-06-28 19:55:16','std','std','3456','student',NULL,NULL,'active',NULL,NULL,NULL,NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);
INSERT INTO `users` VALUES(21,'dimits dimits','dimits@gmail.com','2026-06-29 07:16:18','$2y$12$rE.2RBcPL2ZLHeikzEA3LO6/BYfCNkr1fBzKw5RfDMC2BMnsVzllO',NULL,'2026-06-29 07:16:18','2026-06-30 10:22:48','dimits','dimits','76544567','instructor','Kampala',NULL,'active',NULL,NULL,'profiles/images/1782814968_wU4e8zBG.JPG',NULL,NULL,1,NULL,'#5F3EED','#F4B826',1,'2026-06-29 07:17:57',NULL);
INSERT INTO `users` VALUES(22,'jim jim','jimmy2@gmail.com','2026-06-29 19:58:48','$2y$12$acHKmN5p2.Co/O.23ASqOucKF3OZ3wMCC8/3.MctKBtD4rptNwoTq',NULL,'2026-06-29 19:58:48','2026-07-01 06:36:53','jim','jim','345676543','student',NULL,NULL,'active',NULL,NULL,'profiles/images/1782887809_t5Q0OkvT.png',NULL,NULL,1,NULL,'#5F3EED','#F4B826',0,NULL,NULL);

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE `enrollments` (`id` integer primary key AUTO_INCREMENT not null, `user_id` integer not null, `course_id` integer not null, `amount_paid` decimal(8,2) not null default '0', `status` varchar(255) not null default 'Active', `completed_at` datetime, `created_at` datetime, `updated_at` datetime, `payment_method_id` integer, `payment_provider` varchar(255), `payment_reference` varchar(255), `payment_status` varchar(255), foreign key(`course_id`) references `courses`(`id`) on delete cascade on update no action, foreign key(`user_id`) references `users`(`id`) on delete cascade on update no action, foreign key(`payment_method_id`) references `payment_methods`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `enrollments` VALUES(1,10,1,0,'in_progress',NULL,'2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL,NULL,NULL);
INSERT INTO `enrollments` VALUES(2,10,3,39.99,'completed','2026-06-23 19:52:58','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL,NULL,NULL);
INSERT INTO `enrollments` VALUES(3,10,4,0,'in_progress',NULL,'2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,NULL,NULL,NULL);
INSERT INTO `enrollments` VALUES(4,20,5,0,'in_progress',NULL,'2026-06-29 19:02:57','2026-06-29 19:02:57',NULL,NULL,NULL,NULL);
INSERT INTO `enrollments` VALUES(5,22,5,0,'in_progress',NULL,'2026-06-29 20:00:19','2026-06-29 20:00:19',NULL,NULL,NULL,NULL);

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (`id` integer primary key AUTO_INCREMENT not null, `course_id` integer not null, `user_id` integer not null, `title` varchar(255) not null, `body` text not null, `created_at` datetime, `updated_at` datetime, foreign key(`course_id`) references `courses`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (`id` integer primary key AUTO_INCREMENT not null, `course_id` integer not null, `user_id` integer, `title` varchar(255) not null, `instructions` text, `time_limit` integer, `passing_score` integer not null default '50', `total_marks` integer not null default '0', `status` varchar(255) not null default 'draft', `created_at` datetime, `updated_at` datetime, `attempts_limit` integer, `shuffle_questions` tinyint(1) not null default '0', `shuffle_options` tinyint(1) not null default '0', `show_answers_after` tinyint(1) not null default '1', `show_score_immediately` tinyint(1) not null default '1', `question_pool` integer, `questions_per_attempt` integer, `grading_method` varchar(255) not null default 'best_score', `instructions_file` varchar(255), `randomize_options` tinyint(1) not null default '0', `show_results_immediately` tinyint(1) not null default '0', `certificate_on_pass` tinyint(1) not null default '0', `proctoring_required` tinyint(1) not null default '0', `is_exam` tinyint(1) not null default '0', `class_id` integer, `available_from` datetime, `results_released_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete set null on update no action, foreign key(`course_id`) references `courses`(`id`) on delete cascade on update no action, foreign key(`class_id`) references `classes`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `quizzes` VALUES(1,1,NULL,'HTML & CSS Basics','Answer all questions. Passing score: 60%.',15,60,40,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,0,0,1,1,NULL,NULL,'best_score',NULL,0,0,0,0,0,NULL,NULL,NULL);
INSERT INTO `quizzes` VALUES(2,2,NULL,'Laravel Fundamentals','Answer all questions. Passing score: 70%.',20,70,30,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,0,0,1,1,NULL,NULL,'best_score',NULL,0,0,0,0,0,NULL,NULL,NULL);
INSERT INTO `quizzes` VALUES(3,3,NULL,'Design Principles','Answer all questions. Passing score: 50%.',10,50,30,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,0,0,1,1,NULL,NULL,'best_score',NULL,0,0,0,0,0,NULL,NULL,NULL);
INSERT INTO `quizzes` VALUES(4,4,NULL,'Python Data Structures','Answer all questions. Passing score: 60%.',15,60,30,'published','2026-06-28 19:52:58','2026-06-28 19:52:58',NULL,0,0,1,1,NULL,NULL,'best_score',NULL,0,0,0,0,0,NULL,NULL,NULL);
INSERT INTO `quizzes` VALUES(5,5,21,'quizz one','ghhjgkjhg',60,50,1,'published','2026-06-29 18:57:49','2026-06-29 20:05:10',2,0,0,1,1,NULL,NULL,'best_score','quizzes/instructions/FyiM9h6yRtYdQY3M1ONKylSyU0Yt5ll9SCwpdTxp.pdf',0,0,0,0,0,NULL,NULL,NULL);
INSERT INTO `quizzes` VALUES(6,5,21,'dfgdfg','fdgfdg',30,50,0,'published','2026-06-29 21:15:43','2026-06-29 21:15:43',3,0,0,1,1,NULL,NULL,'best_score','quizzes/instructions/W2Hi4orzV5f9QiHsC4clYBp1eAJeJvzfIltndOpv.docx',0,0,0,0,0,NULL,'2026-06-25 00:19:00',NULL);

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (`id` integer primary key AUTO_INCREMENT not null, `name` varchar(255) not null, `email` varchar(255) not null, `phone` varchar(255) not null, `subject` varchar(255) not null, `message` text not null, `type` varchar(255) not null default 'contact', `is_read` tinyint(1) not null default '0', `created_at` datetime, `updated_at` datetime, `instructor_id` integer, foreign key(`instructor_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact_messages` VALUES(1,'Biverson Jimmy','Gyorgy.feher@pilbara.eu','0776000909','dfdg','test','instructor_contact',0,'2026-06-30 09:53:37','2026-06-30 09:53:37',NULL);


COMMIT;
SET UNIQUE_CHECKS = 1;
SET FOREIGN_KEY_CHECKS = 1;
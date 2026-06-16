-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2026 at 01:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `user_id`, `title`, `content`, `image`, `video`, `created_at`) VALUES
(20, 10, 'vds', 'dds', '1762981270_Gemini_Generated_Image_5l9nh55l9nh55l9n.png', '1762981270_Snapchat-1106274265.mp4', '2025-11-12 21:01:10'),
(22, 1, 'czxc', 'bbb', '1762983943_Gemini_Generated_Image_fve3z7fve3z7fve3.png', '1762983943_Snapchat-1106274265.mp4', '2025-11-12 21:45:43'),
(26, 11, 'suraj', 'satyam', '1762986813_Gemini_Generated_Image_iq16u7iq16u7iq16.png', '1762986813_istockphoto-2148118261-640_adpp_is.mp4', '2025-11-12 22:33:33'),
(28, 21, 'climate change', '“Climate Change: Causes, Impacts, and What We Can Still Do to Save Our Planet”', '1763004300_suraj.jpg', '1763004300_istockphoto-2148118261-640_adpp_is.mp4', '2025-11-13 03:25:00'),
(29, 14, 'health', 'be healthy', '1763006682_WhatsApp-Image-2024-12-10-at-11.46.24-AM.jpeg', '1763006782_Snapchat-1106274265.mp4', '2025-11-13 04:04:42');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `replied` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `subject`, `message`, `replied`, `created_at`) VALUES
(14, 21, 'amod', 'yamod9821@gmail.com', 'web development', 'can u help me for the project .', 1, '2025-11-13 03:55:56'),
(15, 10, 'suraj', 'yadavsuraj2600@gmail.com', 'i need web developer', 'hello suraj', 0, '2025-11-13 04:08:13'),
(16, 10, 'sanjay', 'yadavsuraj2600@gmail.com', 'devlopment', 'i need full stack developer', 1, '2025-11-13 04:10:16'),
(17, 10, 'rajan', 'sandeepsyadav7724@gmial.com', 'development', 'hello sandeep', 1, '2025-11-13 09:04:50'),
(19, 11, 'dfagf', 'shivaydv2600@gmail.com', 'fef', 'vads', 1, '2025-11-14 13:41:44'),
(20, 11, 'sandeep', 'sandeepsyadav784@gmial.com', 'deveklper', 'heillo', 1, '2025-11-14 13:43:33'),
(21, 21, 'Satyam Vishwakarma', 'satyamv122005@gmail.com', 'frontend development', 'hello jarvis', 1, '2025-11-15 02:35:20'),
(22, 23, 'Satyam Vishwakarma', 'mrsatyam322@gmail.com', '.net developer', 'i need .net developer', 0, '2026-04-27 06:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `homepage`
--

CREATE TABLE `homepage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage`
--

INSERT INTO `homepage` (`id`, `user_id`, `name`, `tagline`, `about`, `profile_image`, `updated_at`) VALUES
(1, 5, 'karan', 'vfv', 'vdvd', '1762892192_Untitled design (2).png', '2025-11-11 20:16:32');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `title`, `description`, `link`, `image`, `created_at`) VALUES
(25, 1, 'Smart Personal Portfolio with Admin Control', 'A dynamic and user-friendly personal portfolio system where users can manage their projects, blogs, and profile using an admin dashboard.\r\nIncludes features like secure login, CRUD operations, contact form integration, and responsive design using PHP, MySQL, HTML, CSS, and JavaScript.', 'https://studentskillsphere.in', NULL, '2025-11-12 18:04:13'),
(26, 1, 'Online Job Portal', 'A web-based system that connects job seekers and recruiters on one platform.\r\nUsers can create profiles, upload resumes, and apply for jobs.\r\nEmployers can post job openings and manage applications through an admin dashboard.\r\nTechnologies used: PHP, MySQL, HTML, CSS, JavaScript.', 'https://myjobportal.in', NULL, '2025-11-12 18:05:04'),
(27, 1, 'Smart Attendance Management System', 'An automated system for managing student attendance in schools and colleges.\r\nUses QR code scanning for quick and accurate attendance.\r\nAdmin can generate reports and monitor daily/weekly records.\r\nBuilt with PHP, MySQL, and Bootstrap for responsive UI.', 'https://smartattendance.live', NULL, '2025-11-12 18:06:47'),
(28, 1, 'Online Shopping Website', 'An e-commerce website for buying and selling products online.\r\nIncludes product listing, shopping cart, order management, and payment gateway integration.\r\nAdmin can add or remove products and view customer orders.\r\nBuilt using PHP, MySQL, HTML, CSS, and JavaScript.', 'https://shopnowhub.in', NULL, '2025-11-12 18:08:06'),
(29, 1, 'Library Management System', 'A web-based system that helps librarians manage books, members, and transactions.\r\nFeatures include book search, issue/return management, and late fine calculation.\r\nAdmin can add, edit, and remove books, and generate reports.\r\nTechnologies: PHP, MySQL, HTML, CSS, Bootstrap.', 'https://smartlibrarysystem.in', NULL, '2025-11-12 18:09:02'),
(30, 10, 'Smart Grocery Store', 'An online grocery platform where users can browse, add to cart, and order groceries.\r\nIncludes admin panel for product management and order tracking.\r\nResponsive UI for mobile and desktop users.\r\nTech Stack: PHP, MySQL, HTML, CSS, JavaScript.', 'https://smartgrocery.in', NULL, '2025-11-12 18:11:28'),
(31, 10, 'Real-Time Chat System', 'Project Description:\r\nA live chat web app where users can communicate instantly.\r\nIncludes private and group chat options with message encryption.\r\nUses PHP backend with AJAX and JavaScript for real-time communication.', 'https://chatsphere.in', NULL, '2025-11-12 18:13:09'),
(32, 10, 'E-Clinic Management System', 'A digital platform for managing hospital records, appointments, and billing.\r\nDoctors can view patient details, and patients can book appointments online.\r\nBuilt using PHP, MySQL, HTML, CSS, JavaScript.', 'https://eclinic.in', NULL, '2025-11-12 18:13:44'),
(33, 10, 'Smart Expense Tracker', 'A personal finance management system that tracks daily income and expenses.\r\nDisplays reports and graphs for spending habits.\r\nSecure login and responsive dashboard interface.\r\nTech Stack: PHP, MySQL, HTML, CSS, JS.', 'https://expensetracker.live', NULL, '2025-11-12 18:14:16'),
(34, 10, 'To-Do Management App', 'A simple and interactive task management app for students and professionals.\r\nUsers can add, update, delete, and mark tasks as completed.\r\nData stored securely in database for future access.\r\nTechnologies: PHP, MySQL, HTML, CSS, JavaScript.', 'https://taskify.in', NULL, '2025-11-12 18:14:45'),
(35, 11, 'Smart Notes Sharing', 'A web portal for students to share and download study materials.\r\nIncludes categories by subject, upload/download features, and admin approval.\r\nResponsive and user-friendly UI.\r\nTech Stack: PHP, MySQL, HTML, CSS, JS.', 'https://notehub.in', NULL, '2025-11-12 18:16:22'),
(36, 11, 'Smart Time Table Generator', 'Project Description:\r\nAutomates timetable creation for colleges and schools.\r\nAdmin inputs teachers, subjects, and time slots — system generates an optimized schedule.\r\nBuilt using PHP, MySQL, HTML, CSS, and JavaScript.', 'https://timetablegen.in', NULL, '2025-11-12 18:16:55'),
(37, 11, 'AI Resume Builder', 'A web-based system that helps users create professional resumes dynamically.\r\nAllows customization of templates, auto-formatting, and PDF export.\r\nTech: PHP, MySQL, HTML, CSS, JavaScript.', 'https://airesumebuilder.in', NULL, '2025-11-12 18:17:24'),
(38, 11, 'WeatherScope – Real-Time Weather App', 'Displays live weather details like temperature, humidity, and wind speed for any city.\r\nUses OpenWeatherMap API to fetch live data.\r\nResponsive UI with search option and dynamic background.\r\nTech Stack: HTML, CSS, JS, API Integration.', 'https://weatherscope.live', NULL, '2025-11-12 18:18:00'),
(39, 11, 'Smart Feedback System', 'A platform for collecting and analyzing student feedback on teachers or services.\r\nProvides admin dashboard for viewing ratings and suggestions.\r\nEnsures anonymity and secure data storage.\r\nTech: PHP, MySQL, HTML, CSS, JavaScript.', 'https://smartfeedback.in', NULL, '2025-11-12 18:18:38'),
(49, 21, 'E-Voting', 'This is online voting system', 'https://google.com', NULL, '2025-11-13 03:20:24'),
(51, 14, 'Online Voting system', 'this is my ty project.', 'https://google.com', NULL, '2025-11-13 03:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `percentage` int(3) NOT NULL CHECK (`percentage` between 0 and 100),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `user_id`, `skill_name`, `percentage`, `created_at`) VALUES
(12, 10, 'bgffg', 5, '2025-11-12 21:08:23'),
(13, 11, 'suraj', 98, '2025-11-12 21:43:24'),
(14, 11, 'pooja', 50, '2025-11-12 21:55:12'),
(16, 21, 'JAVASCRIPT', 75, '2025-11-13 03:22:03'),
(17, 14, 'JAVA', 100, '2025-11-13 03:59:50'),
(18, 14, 'softwere testing', 100, '2025-11-25 10:18:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `about_me` text DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `resume_pdf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`, `about_me`, `education`, `profile_photo`, `resume_pdf`) VALUES
(1, 'Admin', 'admin@portfolio.com', 'admin123', 'admin', 'active', '2025-11-11 20:53:11', NULL, NULL, NULL, NULL),
(10, 'satyam', 'satyam@gmail.com', '$2y$10$TKtnsINoCwX98WdBFk6fIu1z8T3/lUqvVc/g9z2jtOr4MDuJECYkS', 'user', 'active', '2025-11-12 18:10:29', '\"I am a dedicated BSc IT student with a passion for developing smart, efficient and user-friendly applications. Over time, I have worked on different technologies including HTML, CSS, JavaScript, PHP, MySQL, and the MERN Stack. I am also exploring Software Testing, automation concepts, and tools that help ensure application quality.\r\n\r\nI love building projects that solve real-world problems, especially personal portfolio systems, admin dashboards, and smart management tools.\r\nMy strengths include problem-solving, fast learning ability, attention to detail, and a strong desire to improve my skills continuously.\r\n\r\nMy career goal is to become a professional Software Tester or Full-Stack Developer while contributing to impactful products.\"', 'BSc.IT ', 'profile_10_1762998917.jpg', 'resume_10_1762998917.pdf'),
(11, 'sandeep', 'sandeep@gmail.com', '$2y$10$U0IVNhCvYWP5jghpvmGtmOobhgCBTr.lFtIYusS6vUJ716OS8B5Le', 'user', 'active', '2025-11-12 18:15:42', '\"I\'m a passionate IT student with strong interest in web development, software testing, and modern technologies. I love creating clean, functional and user-friendly digital solutions. My goal is to build smart systems that solve real-life problems while improving my skills step by step.\"', 'BSC IT ', 'profile_11_1762998794.jpg', 'resume_11_1762998794.pdf'),
(14, 'karan', 'karan@gmail.com', '$2y$10$vvhbH7ti8pyrGW4KYR1XSOjved39DDsEi02QFEXTwFF7yf2L3Taii', 'user', 'active', '2025-11-13 00:03:16', '\"I am an IT student currently exploring Web Development, Software Testing, and Full-Stack technologies. I enjoy transforming ideas into real projects using modern tools.\r\nI believe in smart work, clean coding and continuous learning.\r\nMy focus is to build impactful projects that help me grow technically and professionally.\"', 'BCA', 'profile_14_1762999208.jpg', 'resume_14_1762999145.pdf'),
(21, 'Jarvis', 'jarvis@gmail.com', '$2y$10$hx0QgJux2B77eP9sk3iw2Osykfj0oWUputh4ZAOWjNlnOSc6VAcxO', 'user', 'active', '2025-11-13 03:18:24', 'hello my name is jarvis am a full stack web developer.', 'B.tech', 'profile_21_1763004092.jpg', 'resume_21_1763004092.pdf'),
(23, 'Satyam Vishwakarmaq', 'Mrsatyam0322@gmail.com', '$2y$10$4A4JCqkNbRVkswTgmmOw5OudBsCeLRE0zdU8rEWWQiDtD073EWKl2', 'user', 'active', '2026-04-27 05:49:45', 'hello am a .net developer', 'BSC IT', 'profile_23_1777269585.png', 'resume_23_1777269585.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homepage`
--
ALTER TABLE `homepage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `homepage`
--
ALTER TABLE `homepage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

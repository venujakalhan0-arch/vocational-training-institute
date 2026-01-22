-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 11:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skillprovk`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `course_des` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `image` varchar(255) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `mode` enum('Online','On-site') NOT NULL,
  `location` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `category`, `course_des`, `user_id`, `duration`, `start_date`, `image`, `fee`, `mode`, `location`, `created_at`) VALUES
(1, 'ICT', 'IT', 'Learn essential computer skills, networking, and digital tools for modern workplaces.', 23, 8, '2025-10-25', 'includes/uploads/courses/68e1327b80494_68e0cba990ba2_ict.jpg', 25000.00, 'Online', 'Kandy', '2025-10-03 19:09:38'),
(2, 'Plumbing', 'Plumbing', 'Gain hands-on skills in installing, repairing, and maintaining plumbing systems safely.', 19, 9, '2025-10-30', 'includes/uploads/courses/68e1329f4dda7_1759524252_plumbing.jpg', 20000.00, 'On-site', 'Kandy', '2025-10-04 08:37:17'),
(3, 'Welding', 'welding', 'Master key welding techniques used in construction, manufacturing, and metalwork.', 24, 10, '2025-11-14', 'includes/uploads/courses/1759602973_welding.jpg', 26000.00, 'Online', 'Colombo', '2025-10-04 18:36:13'),
(6, 'Hotel Management', 'hotel management', 'Develop practical skills in hospitality, customer service, and hotel operations.', 26, 12, '2025-11-01', 'includes/uploads/courses/1759606149_hotel management.jpeg', 60000.00, 'On-site', 'Kandy', '2025-10-04 19:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enroll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enroll_id`, `user_id`, `course_id`, `enrolled_at`) VALUES
(1, 25, 1, '2025-10-05 10:25:17');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `event_des` text NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `image_event` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_des`, `event_date`, `event_time`, `image_event`) VALUES
(2, 'Digital Skills Bootcamp 2025', 'A one-day hands-on session covering Microsoft Office, basic coding, and internet safety for beginners.', '2025-10-20', '09:00:00', 'includes/uploads/events/1759655110_digibootcamps.jpg'),
(3, 'Water Systems Workshop', 'Hands-on plumbing training on pipe fitting, leak detection, and safety standards.', '2025-10-25', '14:30:00', 'includes/uploads/events/1759655476_watersystem.jpg'),
(4, 'Safety First: Welding Safety Training', 'A safety session for students focusing on protective gear, fire safety, and machine handling.', '2025-10-18', '13:00:00', 'includes/uploads/events/1759655631_weldingsafety.jpg'),
(5, 'Hospitality Career Fair', 'Meet top hotel recruiters and learn about internship and job opportunities.', '2025-12-05', '09:00:00', 'includes/uploads/events/1759655879_Careerfair.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiry_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `reply` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`inquiry_id`, `user_id`, `subject`, `message`, `reply`, `created_at`) VALUES
(7, 25, 'Forgot Password', 'I forgot my password; how can I reset it?', '', '2025-10-06 07:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `role` enum('Admin','Instructor','Student') NOT NULL DEFAULT 'Student',
  `email` varchar(150) NOT NULL,
  `phone_num` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Prefer not to say') NOT NULL,
  `image_user` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `role`, `email`, `phone_num`, `password`, `gender`, `image_user`, `created_at`) VALUES
(5, 'venuja', 'Admin', 'venujakalhan0@gmail.com', '0762481411', '$2y$10$O1S22y.Y53HMBtHQIUaNQezR/jR0pEwFk8dXZYkhZMc46Z5SoRWg2', 'Male', 'uploads/1759491382_Screenshot (175).png', '2025-10-03 08:06:22'),
(6, 'kalhan', 'Admin', 'Vkalhan273@gmail.com', '0762481411', '$2y$10$0e5qyJIh2mhJZ4Vt78pq6O40pumt6.ai7ne94fsur8U0JVzA9KPim', 'Male', 'uploads/1759495206_ggg.png', '2025-10-03 09:10:06'),
(19, 'Sofia', 'Instructor', 'sofia@gmail.com', '1234567890', '$2y$10$YTvZ8tNCE3ITaF1K7Rte0O778xjNaD/6sWxvK5ZNlim88t3nFiI96', 'Male', 'includes/uploads/68e0dc44e9c77_sofia.jpeg', '2025-10-04 08:35:16'),
(23, 'Dimitri', 'Instructor', 'dimithri@gmail.com', '256489', '$2y$10$e7Aa0GUIo7HnTD6xh01Rl.NW92S1qbsfOoXYP9LeQMY4qW7zU4Uo2', 'Male', 'includes/uploads/68e166cc45edf_Dimitri.jpeg', '2025-10-04 18:26:20'),
(24, 'Omar', 'Instructor', 'omar@gmail.com', '6598741232', '$2y$10$EGXvOFO8iSGO2y9zjJjsG.G1k1N6Ltk.WYl01QnA.1Jx4TceKn/92', 'Male', 'includes/uploads/68e168785697f_omar.jpeg', '2025-10-04 18:33:28'),
(25, 'Kalhan', 'Student', 'Kalhan@gmail.com', '5446518497', '$2y$10$S1XY/8Bbig9SKvHrkilYfePJWVFTnVtJHEA9x1Bjy3vNayRv.UVhG', 'Male', './includes/uploads/1759603502_kalhan.webp', '2025-10-04 15:15:02'),
(26, 'Thiago', 'Instructor', 'thiago@gmail.com', '2154784553', '$2y$10$MUEvqglgYe7zpNYDJABeP.j9xp7HhYDdQoeCxJeDCh3r3v/0C/AeG', 'Male', 'includes/uploads/68e174c177a49_thiago.jpeg', '2025-10-04 19:25:53'),
(27, 'Sachin', 'Admin', 'sachin@gmail.com', '232113', '$2y$10$yNfjg9ZDrgxRzuU63yvJT.3Q2iPDWVPztMHzUNJBKHceQiQzO8gBe', 'Male', './includes/uploads/1759730336_Screenshot__197_.png', '2025-10-06 02:28:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `instructor_id` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enroll_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiry_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

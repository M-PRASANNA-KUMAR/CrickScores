-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 08:14 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cricket_scoreboard_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$TxF.MtlhymPmWYOsO5CtE.sgUbwJda0pCkMBXNi5LtO/Vo5T0e5Gm');

-- --------------------------------------------------------

--
-- Table structure for table `innings1_scores`
--

CREATE TABLE `innings1_scores` (
  `score_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `ball_number` int(11) NOT NULL,
  `over_number` int(11) NOT NULL,
  `batting_player_id` varchar(20) NOT NULL,
  `bowling_player_id` varchar(20) NOT NULL,
  `score` int(11) NOT NULL,
  `wicket` tinyint(1) DEFAULT '0',
  `batter_out_player_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `innings1_scores`
--

INSERT INTO `innings1_scores` (`score_id`, `match_id`, `ball_number`, `over_number`, `batting_player_id`, `bowling_player_id`, `score`, `wicket`, `batter_out_player_id`) VALUES
(1, 1, 1, 1, '0', 'p004', 6, 0, NULL),
(2, 1, 2, 1, '0', 'p004', 0, 0, NULL),
(3, 1, 3, 1, '0', 'p004', 3, 0, NULL),
(4, 1, 4, 1, '0', 'p004', 5, 0, NULL),
(5, 1, 5, 1, '0', 'p004', 0, 0, NULL),
(6, 1, 6, 1, '0', 'p004', 0, 0, NULL),
(7, 2, 1, 1, '0', 'p004', 5, 0, NULL),
(8, 2, 2, 1, '0', 'p005', 4, 0, NULL),
(9, 2, 3, 1, '0', 'p004', 0, 1, 'p001'),
(10, 2, 4, 1, '0', 'p005', 0, 1, 'p002'),
(11, 2, 5, 1, '0', 'p004', 0, 1, 'p003'),
(12, 3, 1, 1, '0', 'p004', 2, 1, 'p001'),
(13, 4, 1, 1, '0', 'p004', 4, 0, NULL),
(14, 4, 2, 1, '0', 'p004', 6, 0, NULL),
(15, 4, 3, 1, '0', 'p004', 0, 0, NULL),
(16, 4, 4, 1, '0', 'p004', 0, 0, NULL),
(17, 4, 5, 1, '0', 'p004', 0, 0, NULL),
(18, 4, 6, 1, '0', 'p004', 0, 1, 'p001'),
(19, 5, 1, 1, '0', 'p004', 6, 0, NULL),
(20, 5, 2, 1, '0', 'p004', 0, 1, 'p001');

-- --------------------------------------------------------

--
-- Table structure for table `innings2_scores`
--

CREATE TABLE `innings2_scores` (
  `score_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `ball_number` int(11) NOT NULL,
  `over_number` int(11) NOT NULL,
  `batting_player_id` varchar(20) NOT NULL,
  `bowling_player_id` varchar(20) NOT NULL,
  `score` int(11) NOT NULL,
  `wicket` tinyint(1) DEFAULT '0',
  `batter_out_player_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `innings2_scores`
--

INSERT INTO `innings2_scores` (`score_id`, `match_id`, `ball_number`, `over_number`, `batting_player_id`, `bowling_player_id`, `score`, `wicket`, `batter_out_player_id`) VALUES
(1, 1, 1, 1, '0', 'p001', 6, 0, NULL),
(2, 1, 2, 1, '0', 'p001', 6, 0, NULL),
(3, 1, 3, 1, '0', 'p001', 6, 0, NULL),
(4, 2, 1, 1, '0', 'p001', 6, 0, NULL),
(5, 2, 2, 1, '0', 'p001', 6, 0, NULL),
(6, 3, 1, 1, '0', 'p001', 2, 0, NULL),
(7, 3, 2, 1, '0', 'p001', 0, 1, 'p004'),
(8, 4, 1, 1, '0', 'p002', 0, 0, NULL),
(9, 4, 2, 1, '0', 'p001', 6, 0, NULL),
(10, 4, 3, 1, '0', 'p001', 6, 0, NULL),
(11, 5, 1, 1, '0', 'p001', 6, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `match_id` int(11) NOT NULL,
  `team1_name` varchar(255) NOT NULL,
  `team2_name` varchar(255) NOT NULL,
  `team1_players` text,
  `team2_players` text,
  `overs` int(11) NOT NULL,
  `wickets_allowed` int(11) NOT NULL,
  `toss_winner_team` varchar(255) DEFAULT NULL,
  `batting_team` varchar(255) DEFAULT NULL,
  `bowling_team` varchar(255) DEFAULT NULL,
  `match_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`match_id`, `team1_name`, `team2_name`, `team1_players`, `team2_players`, `overs`, `wickets_allowed`, `toss_winner_team`, `batting_team`, `bowling_team`, `match_date`) VALUES
(1, 'singam', 'puli', 'p001,p002,p003', 'p004,p005,p006', 1, 2, 'singam', 'singam', 'puli', '2025-03-07 10:46:31'),
(2, 'singam', 'puli', 'p001,p002,p003', 'p004,p005,p007', 1, 3, 'singam', 'singam', 'puli', '2025-03-09 21:21:00'),
(3, 'singam', 'puli', 'p001,p002', 'p004,p005', 1, 1, 'singam', 'singam', 'puli', '2025-03-13 18:14:37'),
(4, 'bp', 'ul', 'p001,p002,p003', 'p004,p005,p006', 1, 2, 'bp', 'bp', 'ul', '2025-03-13 18:44:31'),
(5, 'bp', 'ul', 'p001,p002', 'p004,p005', 1, 1, 'bp', 'bp', 'ul', '2025-03-13 19:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `player_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `player_id`, `name`, `age`, `height`, `weight`, `profile_pic`, `password`, `created_at`) VALUES
(1, 'P001', 'alwin', 19, '5\'3', '57', '../images/profile_pics/67cac705aef7a_download.jpg', '$2y$10$TxF.MtlhymPmWYOsO5CtE.sgUbwJda0pCkMBXNi5LtO/Vo5T0e5Gm', '2025-03-07 09:44:35'),
(2, 'P002', 'ronaldo', 20, '5\'0', '52', '', '$2y$10$LboAMgrqr2WDNxP5g22f4OYaKnGOyXB0XX47ZTnObq84z.rh45xxq', '2025-03-07 09:58:04'),
(3, 'P003', 'veera ', 19, '4\'9', '62', '', '$2y$10$BuIvuD1rYassNPQY2XZAX.nKPxXPhEws8HO4l5SFIZgAgXmuos5yS', '2025-03-07 09:59:53'),
(4, 'P004', 'JD', 19, '5\'6', '130', '', '$2y$10$vLBPD1Q6GRDEcY7N3zdDE.w/nqJzaxKjUnryZ8IY6IRNhj5bpEPi6', '2025-03-07 10:01:59'),
(5, 'P005', 'keerthivarman', 20, '5\'2', '75', '', '$2y$10$7uL/n9VY7W2HExJT43yfCuT4xN.QX/hBBXVrtTs0l2QqU8UO56j.G', '2025-03-07 10:04:01'),
(6, 'P006', 'danish mohamud ', 21, '5\'3', '62', '', '$2y$10$BpI5OufueJpM7/XO1oVOLeKqtSn3ToheXL/s3kyhCoL5gYAohoT6i', '2025-03-07 10:06:04'),
(7, 'P007', 'deepak', 19, '5\'3', '45', '', '$2y$10$zGXBgCP9wWea/TqnUTfjKOD6..CMTxGuDz8cBnGz9CdIOPdzQksi2', '2025-03-09 21:13:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `innings1_scores`
--
ALTER TABLE `innings1_scores`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `match_id` (`match_id`);

--
-- Indexes for table `innings2_scores`
--
ALTER TABLE `innings2_scores`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `match_id` (`match_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `player_id` (`player_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `innings1_scores`
--
ALTER TABLE `innings1_scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `innings2_scores`
--
ALTER TABLE `innings2_scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `innings1_scores`
--
ALTER TABLE `innings1_scores`
  ADD CONSTRAINT `innings1_scores_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `matches` (`match_id`);

--
-- Constraints for table `innings2_scores`
--
ALTER TABLE `innings2_scores`
  ADD CONSTRAINT `innings2_scores_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `matches` (`match_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

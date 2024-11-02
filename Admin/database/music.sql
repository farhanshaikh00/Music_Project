-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 13, 2024 at 06:47 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `music`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `password`, `profile_image`) VALUES
(1, 'Zubair', 'admin@mail.com', '202cb962ac59075b964b07152d234b70', ''),
(5, 'Farhan', 'admin@mailer.com', '202cb962ac59075b964b07152d234b70', 'talha.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

DROP TABLE IF EXISTS `artist`;
CREATE TABLE IF NOT EXISTS `artist` (
  `artist_id` int NOT NULL AUTO_INCREMENT,
  `artist_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `followers` bigint DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`artist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `artist`
--

INSERT INTO `artist` (`artist_id`, `artist_name`, `post`, `image`, `followers`, `category_id`) VALUES
(13, 'Talha Anjum', '0', 'talha.jpeg', NULL, 13),
(10, 'Atif Aslam', '1', 'atif.jpeg', NULL, 8),
(12, 'Jubin', '0', 'jubin.jpeg', NULL, 4),
(11, 'Arijit Singh', '1', 'arijit.jpeg', NULL, 5),
(9, 'Nusrat Fateh Ali Khan', '0', 'nusrat.jpg', NULL, 6);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post` int NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `post`) VALUES
(3, 'Rock', 0),
(4, 'Love', 0),
(5, 'Sad', 0),
(6, 'Ghazal', 0),
(7, 'HipHop', 0),
(8, 'Happy', 0),
(9, 'Romance', 0),
(10, 'Best', 2),
(13, 'Rap', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ratings_reviews`
--

DROP TABLE IF EXISTS `ratings_reviews`;
CREATE TABLE IF NOT EXISTS `ratings_reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `username` int NOT NULL,
  `song_id` int DEFAULT NULL,
  `rating` text,
  `review` text,
  PRIMARY KEY (`review_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ratings_reviews`
--

INSERT INTO `ratings_reviews` (`review_id`, `username`, `song_id`, `rating`, `review`) VALUES
(7, 8, 5, '5', 'ju');

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

DROP TABLE IF EXISTS `songs`;
CREATE TABLE IF NOT EXISTS `songs` (
  `song_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `release_on` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `artist` int NOT NULL,
  `thumbnail` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `music` varchar(255) NOT NULL,
  PRIMARY KEY (`song_id`),
  UNIQUE KEY `post_id` (`song_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `title`, `description`, `category`, `release_on`, `artist`, `thumbnail`, `music`) VALUES
(6, 'hyh', 'ghnfgh', '10', '2006', 11, '1728801796-kali_kali_zulfon.jpeg', '01-Monk-Turner-Fascinoma-Its-Your-Birthday(chosic.com).mp3'),
(5, 'Tajdar e haram', 'kuch bh', '10', '2005', 10, '1728801550-thumbnail_tajdar_e_haram.jpg', 'Tajdar_e_Haram.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `email` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password`) VALUES
(8, 'Zubair', 'Bhai', 'Sherry', 'admin@atf.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `user_follow`
--

DROP TABLE IF EXISTS `user_follow`;
CREATE TABLE IF NOT EXISTS `user_follow` (
  `follow_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `artist_id` int NOT NULL,
  PRIMARY KEY (`follow_id`),
  UNIQUE KEY `user_id` (`user_id`,`artist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_follow`
--

INSERT INTO `user_follow` (`follow_id`, `user_id`, `artist_id`) VALUES
(17, 1, 5),
(19, 1, 3),
(14, 1, 4),
(4, 1, 7),
(18, 1, 6),
(16, 8, 6);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

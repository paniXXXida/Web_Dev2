-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: mysql
-- Время создания: Апр 05 2025 г., 12:07
-- Версия сервера: 11.5.2-MariaDB-ubu2404
-- Версия PHP: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `developmentdb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `description`, `available`, `created_at`) VALUES
(1, 'The Silent Patient', 'Alex Michaelides', 'An epic story of love, loss, and destiny.', 1, '2025-03-31 20:26:28'),
(2, 'Atomic Habits', 'James Clear', 'A thrilling mystery set in a haunted mansion.', 1, '2025-03-31 20:26:28'),
(3, 'The Midnight Library', 'Matt Haig', 'A heartwarming tale about friendship and courage.', 1, '2025-03-31 20:26:28'),
(4, 'Educated', 'Tara Westover', 'An adventure through time and space, full of surprises.', 1, '2025-03-31 20:26:28'),
(5, 'The Alchemist', 'Paulo Coelho', 'A gripping historical novel based on real events.', 1, '2025-03-31 20:26:28'),
(6, 'Dune', 'Frank Herbert', 'A magical fantasy journey with unforgettable characters.', 1, '2025-03-31 20:26:28'),
(7, 'Where the Crawdads Sing', 'Delia Owens', 'A touching romance that will stay with you forever.', 1, '2025-03-31 20:26:28'),
(8, 'Becoming', 'Michelle Obama', 'A science fiction epic about survival and humanity.', 1, '2025-03-31 20:26:28'),
(9, 'Normal People', 'Sally Rooney', 'A dark comedy with a twist at every corner.', 1, '2025-03-31 20:26:28'),
(10, 'The Book Thief', 'Markus Zusak', 'A detective’s last case before retirement turns deadly.', 1, '2025-03-31 20:26:28'),
(11, 'Circe', 'Madeline Miller', NULL, 1, '2025-03-31 20:26:28'),
(12, 'The Night Circus', 'Erin Morgenstern', NULL, 1, '2025-03-31 20:26:28'),
(13, 'The Vanishing Half', 'Brit Bennett', NULL, 1, '2025-03-31 20:26:28'),
(14, 'Project Hail Mary', 'Andy Weir', NULL, 1, '2025-03-31 20:26:28'),
(15, 'The Seven Husbands of Evelyn Hugo', 'Taylor Jenkins Reid', NULL, 1, '2025-03-31 20:26:28'),
(16, 'It Ends With Us', 'Colleen Hoover', NULL, 1, '2025-03-31 20:26:28'),
(17, 'A Man Called Ove', 'Fredrik Backman', NULL, 1, '2025-03-31 20:26:28'),
(18, 'The Song of Achilles', 'Madeline Miller', NULL, 1, '2025-03-31 20:26:28'),
(19, 'Verity', 'Colleen Hoover', NULL, 1, '2025-03-31 20:26:28'),
(20, 'The House in the Cerulean Sea', 'TJ Klune', NULL, 1, '2025-03-31 20:26:28'),
(21, 'Ищщифифиф', 'я', 'хцоащцтщцтщоатцй', 1, '2025-04-04 15:27:42');

-- --------------------------------------------------------

--
-- Структура таблицы `book_requests`
--

CREATE TABLE `book_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `book_requests`
--

INSERT INTO `book_requests` (`id`, `user_id`, `book_id`, `status`, `requested_at`) VALUES
(1, 2, 20, 'approved', '2025-04-01 14:38:39'),
(2, 2, 20, 'pending', '2025-04-01 15:58:49'),
(3, 2, 20, 'approved', '2025-04-01 18:08:57'),
(4, 3, 20, 'rejected', '2025-04-02 17:09:05'),
(5, 3, 18, 'approved', '2025-04-02 17:09:17'),
(6, 2, 20, 'pending', '2025-04-02 17:22:48'),
(7, 2, 19, 'pending', '2025-04-02 17:22:50'),
(8, 2, 18, 'pending', '2025-04-02 17:22:51'),
(9, 2, 17, 'pending', '2025-04-02 17:22:52'),
(10, 2, 16, 'cancelled', '2025-04-02 17:22:54'),
(11, 3, 18, 'approved', '2025-04-02 19:46:26'),
(12, 3, 20, 'cancelled', '2025-04-02 20:53:45'),
(13, 3, 20, 'pending', '2025-04-03 14:22:34'),
(14, 3, 19, 'approved', '2025-04-03 14:22:36'),
(15, 2, 21, 'approved', '2025-04-04 15:28:02'),
(16, 4, 21, 'pending', '2025-04-04 15:40:26');

-- --------------------------------------------------------

--
-- Структура таблицы `book_reviews`
--

CREATE TABLE `book_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `book_reviews`
--

INSERT INTO `book_reviews` (`id`, `user_id`, `book_id`, `rating`, `comment`, `created_at`) VALUES
(1, 3, 20, NULL, '1212', '2025-04-04 13:19:46'),
(2, 3, 19, NULL, 'Книга хорошая', '2025-04-04 13:22:44'),
(3, 3, 19, 2, '1212', '2025-04-04 14:40:07'),
(4, 3, 19, 1, 'А мне не понравилось!!!', '2025-04-04 14:46:01');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'John Doe', 'john@example.com', '$2y$12$YPBfE4NPn95SYYOSqZgh.eELcnnA96bMbhv.6jeEKFXTKY0u8jQeO', 'customer', '2025-03-31 19:56:54'),
(2, 'Rienat Zhuravlov', 'meravej04@gmail.com', '$2y$12$PiztO0Awee0JBT.HFZdTneEuvqRaoMAiwBpQUGI2fcbHtaSuvTjQC', 'admin', '2025-03-31 20:08:44'),
(3, 'Bob Johnson', 'emailfortest@look.up', '$2y$12$TPXI1xVPVeYLcGy.2a1H8epSS0TCQWlcidi5qFF6/Dp9A79pgUmBq', 'customer', '2025-04-02 12:55:39'),
(4, 'Martin Garrix', 'msi777@pisya.popa', '$2y$12$uQkAZESGbmio8sQIJf8yjuDvQSWHzr/VMtAOZZf6WT0tflTV6KYVu', 'customer', '2025-04-04 15:40:16');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `book_requests`
--
ALTER TABLE `book_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Индексы таблицы `book_reviews`
--
ALTER TABLE `book_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `book_requests`
--
ALTER TABLE `book_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `book_reviews`
--
ALTER TABLE `book_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `book_requests`
--
ALTER TABLE `book_requests`
  ADD CONSTRAINT `book_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_requests_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `book_reviews`
--
ALTER TABLE `book_reviews`
  ADD CONSTRAINT `book_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_reviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

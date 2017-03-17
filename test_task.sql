-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 17 2017 г., 19:44
-- Версия сервера: 5.5.53
-- Версия PHP: 7.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_task`
--

-- --------------------------------------------------------

--
-- Структура таблицы `News`
--

CREATE TABLE `News` (
  `ID` int(11) NOT NULL,
  `ParticipantId` int(11) NOT NULL,
  `NewsTitle` varchar(255) NOT NULL,
  `NewsMessage` text NOT NULL,
  `LikesCounter` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `News`
--

INSERT INTO `News` (`ID`, `ParticipantId`, `NewsTitle`, `NewsMessage`, `LikesCounter`) VALUES
(1, 1, 'New agenda!', 'Please visit our site!', 0),
(2, 3, 'Hello Friend', 'This is me', 141),
(3, 15, 'Hello Friend', 'How are you', 0),
(4, 15, 'I changed my mail', 'At last, my friends', 0),
(5, 13, 'Мои новости', 'Привет, мир!', 123);

-- --------------------------------------------------------

--
-- Структура таблицы `Participant`
--

CREATE TABLE `Participant` (
  `ID` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `Participant`
--

INSERT INTO `Participant` (`ID`, `Email`, `Name`) VALUES
(1, 'airmail@code-pilots.com', 'The first user'),
(2, 'heisenberg@yahoo.com', 'The One Who Knocks'),
(3, 'batman@waynecorps.com', 'The Dark Knight'),
(4, 'darklord494@deathstar.com', 'Lord Wayder'),
(5, 'ghandi56@peace.com', 'Ghandi'),
(6, 'cosmonaut2@mks.com', 'Major Tom'),
(7, 'haron@styx.com', 'Last Ferryman'),
(8, 'mrlebowski1@yahoo.com', 'The Dude'),
(9, 'ghandalf333@izengard', 'White Sage'),
(10, 'zeus656@olymp.com', 'Mighty Lighting'),
(11, 'coloneljack@army.com', 'Its Sir Colonel Jack To You'),
(12, 'cooldude113@undermine.com', 'Great Papyrus'),
(13, 'flash@centralcity.com', 'Fastest Man Alive'),
(14, 'missingno@h1?2.com', '?#12J'),
(15, 'a@a.com', 'Error');

-- --------------------------------------------------------

--
-- Структура таблицы `Session`
--

CREATE TABLE `Session` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `TimeOfEvent` datetime NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `Session`
--

INSERT INTO `Session` (`ID`, `Name`, `TimeOfEvent`, `Description`) VALUES
(1, 'Собрание1', '2017-03-16 00:00:00', 'Собрание1'),
(2, 'Собрание2', '2017-03-17 00:00:00', 'Собрание2');

-- --------------------------------------------------------

--
-- Структура таблицы `SessionParticipant`
--

CREATE TABLE `SessionParticipant` (
  `ID` int(11) NOT NULL,
  `SessionID` int(11) NOT NULL,
  `ParticipantID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `SessionParticipant`
--

INSERT INTO `SessionParticipant` (`ID`, `SessionID`, `ParticipantID`) VALUES
(2, 1, 1),
(5, 1, 2),
(6, 1, 3),
(7, 1, 4),
(9, 1, 6),
(10, 1, 7),
(11, 1, 8),
(12, 1, 9),
(13, 1, 10),
(15, 1, 11),
(16, 1, 15),
(17, 2, 15);

-- --------------------------------------------------------

--
-- Структура таблицы `SessionSpeaker`
--

CREATE TABLE `SessionSpeaker` (
  `ID` int(11) NOT NULL,
  `SessionID` int(11) NOT NULL,
  `SpeakerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `SessionSpeaker`
--

INSERT INTO `SessionSpeaker` (`ID`, `SessionID`, `SpeakerID`) VALUES
(1, 1, 1),
(2, 1, 2),
(5, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `Speaker`
--

CREATE TABLE `Speaker` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `Speaker`
--

INSERT INTO `Speaker` (`ID`, `Name`) VALUES
(1, 'Watson'),
(2, 'Arnold');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `News`
--
ALTER TABLE `News`
  ADD KEY `ID` (`ID`) USING BTREE;

--
-- Индексы таблицы `Participant`
--
ALTER TABLE `Participant`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `Session`
--
ALTER TABLE `Session`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `SessionParticipant`
--
ALTER TABLE `SessionParticipant`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `SessionSpeaker`
--
ALTER TABLE `SessionSpeaker`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `Speaker`
--
ALTER TABLE `Speaker`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `News`
--
ALTER TABLE `News`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `Participant`
--
ALTER TABLE `Participant`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `Session`
--
ALTER TABLE `Session`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `SessionParticipant`
--
ALTER TABLE `SessionParticipant`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT для таблицы `SessionSpeaker`
--
ALTER TABLE `SessionSpeaker`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `Speaker`
--
ALTER TABLE `Speaker`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

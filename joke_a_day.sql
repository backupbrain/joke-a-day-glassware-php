CREATE TABLE IF NOT EXISTS `User_Settings` (
  `googleplus_id` varchar(128) NOT NULL,
  `access_token` varchar(128) NOT NULL,
  `token_type` varchar(128) NOT NULL,
  `timezone` int(10) unsigned NOT NULL,
  `message_time` int(10) unsigned NOT NULL
);


CREATE TABLE IF NOT EXISTS `Joke2Timeline` (
  `jokeID` int(10) unsigned NOT NULL,
  `timelineID` int(10) unsigned NOT NULL,
  `liked` tinyint(1) NOT NULL
);


CREATE TABLE `Joke` (
  `jokeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `joke` text NOT NULL,
  PRIMARY KEY (`jokeID`)
) AUTO_INCREMENT=9 ;

INSERT INTO `Joke` (`jokeID`, `joke`) VALUES
(1, 'I totally understand how batteries feel because I’m rarely ever included in things either.'),
(2, 'It’s hard to explain puns to kleptos cuz they take things literally.'),
(3, 'What does a nosey pepper do? Get jalapeño business.'),
(4, 'What is Bruce Lee’s favorite drink? Wataaaaah!'),
(5, 'Atheism is a non-prophet organization.'),
(6, 'Pampered cows produce spoiled milk.'),
(7, 'Learn sign language, it’s very handy.'),
(8, 'Dry erase boards are remarkable.');


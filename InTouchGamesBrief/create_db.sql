DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `apikeys`;
--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` DATE,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;--
--
-- Table structure for table `apikeys`
--
CREATE TABLE `apikeys` (
  `keyid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apikey` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isvalid` BOOLEAN NOT NULL,
  `dategenerated` DATE,
  PRIMARY KEY(`keyid`)
)ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

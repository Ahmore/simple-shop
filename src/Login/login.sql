CREATE TABLE IF NOT EXISTS `users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `sol` varchar(8) NOT NULL,
	`email` varchar(128) NOT NULL,
    `type` tinyint(1) NOT NULL DEFAULT "0",
    `blockade` tinyint(1) NOT NULL DEFAULT "0",
	`token` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)

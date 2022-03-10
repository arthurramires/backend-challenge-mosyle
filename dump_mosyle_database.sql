
CREATE TABLE IF NOT EXISTS `user` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `email` varchar(200) NOT NULL,
                                      `password` varchar(300) NOT NULL,
                                      `name` varchar(100) DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_drink` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
                                            `drinks` int(11) NOT NULL,
                                            `user_id` int(11) unsigned NOT NULL,
                                            PRIMARY KEY (`id`),
                                            KEY `fk_user_drink_1_idx` (`user_id`),
                                            CONSTRAINT `fk_user_drink_1_idx` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;



INSERT INTO `user` (`id`, `email`, `password`, `name`) VALUES
                                                           (1, 'rafa@gmail.com', '123', 'Rafa'),
                                                           (2, 'bruno@gmail.com', '123', 'asdasd');



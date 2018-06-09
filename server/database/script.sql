create database `mundowaptest`;

use `mundowaptest`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ean` varchar(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `qtd` int(11) NOT NULL DEFAULT '0',
  `fabricated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ean_UNIQUE` (`ean`)
) ENGINE=InnoDB AUTO_INCREMENT=11003 DEFAULT CHARSET=latin1;

INSERT INTO `users` (`username`, `password`) VALUES ("marciojustino", "a0f4de0148373df52eaa33d0c90ea264");
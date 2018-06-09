CREATE DATABASE mundowaptest;

USE mundowaptest;

CREATE TABLE users (
  id         INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id            INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ean           VARCHAR(6)    NOT NULL,
  name          VARCHAR(255)  NOT NULL,
  price         DECIMAL(5, 2) NOT NULL DEFAULT 0,
  qtd           INT           NOT NULL DEFAULT 0,
  fabricated_at DATETIME      NULL,
  UNIQUE INDEX `ean_UNIQUE` (`ean` ASC )
);

INSERT INTO users (username, password) VALUES ("marciojustino", "a0f4de0148373df52eaa33d0c90ea264");

INSERT INTO `mundowaptest`.`products` (`ean`, `name`, `price`, `qtd`) VALUES ('123123', 'Produto 1', '140,20', '10');
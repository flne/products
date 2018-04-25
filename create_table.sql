-- Использовать кодировку UTF-8
SET NAMES utf8;
-- Создаём таблицу если она не существует
-- :name_table это не псевдопеременная, это просто текст написанный в стиле псевдопеременной,
-- который будет заменён на название таблицы
CREATE TABLE IF NOT EXISTS :name_table (
id INT(11) NOT NULL AUTO_INCREMENT,  
articul TINYTEXT NOT NULL,
name TEXT NOT NULL,
brand TEXT NOT NULL,
type TINYTEXT NOT NULL,
color TINYTEXT NOT NULL,
price INT(11) NOT NULL,
sale INT(11) NOT NULL,
created_at DATE NOT NULL,
PRIMARY KEY (id)
);

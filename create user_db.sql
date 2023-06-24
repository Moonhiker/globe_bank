-- Create user

CREATE USER 'Daniel'@'localhost' IDENTIFIED BY "123456"

-- Set permission
GRANT ALL PRIVILEGES ON db_name.* TO 'Daniel'@'localhost'


-- Create table

USE db_name;
CREATE TABLE pages(
    pid INT(11) NOT NULL AUTO_INCREMENT,
    menu_name VARCHAR(255),
    position INT(3), 
    visible TINYINT(1),
    PRIMERY KEY (pid)
);
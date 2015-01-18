CREATE TABLE IF NOT EXISTS  users (
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  facebook_id VARCHAR( 100 ) NOT NULL UNIQUE,
  gender ENUM('male','female') NOT NULL,
  first_name VARCHAR( 100 ) NOT NULL ,
  last_name VARCHAR( 100 ) NOT NULL,
  email_address VARCHAR( 100 ) NOT NULL UNIQUE,
  login_password VARCHAR( 30 )
) ENGINE = INNODB;
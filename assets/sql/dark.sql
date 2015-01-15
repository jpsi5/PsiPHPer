# One user can have many connections
# One user can vote on many connections
# One connection can have one vote from a user
# One connection can have many votes

CREATE TABLE IF NOT EXISTS  users (
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  facebook_id VARCHAR( 100 ) NOT NULL UNIQUE,
  gender ENUM('male','female') NOT NULL,
  first_name VARCHAR( 100 ) NOT NULL ,
  last_name VARCHAR( 100 ) NOT NULL,
  email_address VARCHAR( 100 ) NOT NULL UNIQUE,
  login_password VARCHAR( 30 )
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS connections (
  connection_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  linked_user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (linked_user_id) REFERENCES users(user_id)
)ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS votes (
  connection_id INT NOT NULL,
  user_id INT NOT NULL,
  vote BOOLEAN NOT NULL,
  FOREIGN KEY (connection_id) REFERENCES connections(connection_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  CONSTRAINT pk_UserConnection PRIMARY KEY (connection_id,user_id)
)ENGINE = INNODB;




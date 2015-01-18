CREATE TABLE IF NOT EXISTS votes (
  connection_id INT NOT NULL,
  user_id INT NOT NULL,
  vote BOOLEAN NOT NULL,
  FOREIGN KEY (connection_id) REFERENCES connections(connection_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  CONSTRAINT pk_UserConnection PRIMARY KEY (connection_id,user_id)
)ENGINE = INNODB;
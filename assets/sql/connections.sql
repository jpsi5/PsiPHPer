CREATE TABLE IF NOT EXISTS connections (
  connection_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  linked_user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (linked_user_id) REFERENCES users(user_id)
)ENGINE = INNODB;
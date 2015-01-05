CREATE TABLE  customers (
  customer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  gender ENUM('male','female') NOT NULL,
  first_name VARCHAR( 100 ) NOT NULL ,
  middle_initial CHAR(1),
  last_name VARCHAR( 100 ) NOT NULL,
  email_address VARCHAR( 100 ) NOT NULL,
  login_name VARCHAR( 30 ),
  login_password VARCHAR( 30 ),
  phone_number VARCHAR( 100 ) NOT NULL,
  address_line_1 VARCHAR( 100 ) NOT NULL,
  address_line_2 VARCHAR( 100 ),
  address_line_3 VARCHAR( 100 ),
  address_line_4 VARCHAR( 100 ),
  city VARCHAR( 100 ) NOT NULL,
  state VARCHAR( 50 ) NOT NULL,
  country VARCHAR( 100 ) NOT NULL
) ENGINE = INNODB;

CREATE TABLE ref_payment_methods (
  payment_method_code VARCHAR( 6 ) NOT NULL PRIMARY KEY,
  payment_method_description VARCHAR( 50 ) NOT NULL
) ENGINE = INNODB;


CREATE TABLE ref_order_status_codes (
  order_status_code INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_status_description VARCHAR( 100 ) NOT NULL
) ENGINE = INNODB;

CREATE TABLE ref_invoice_status_codes (
  invoice_status_code INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  invoice_status_description VARCHAR( 100 ) NOT NULL
) ENGINE = INNODB;

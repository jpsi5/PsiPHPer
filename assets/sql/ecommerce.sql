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

CREATE TABLE ref_order_item_status_codes (
  order_item_status_code INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_item_status_description VARCHAR( 100 ) NOT NULL
) ENGINE = INNODB;

CREATE TABLE ref_product_types (
  product_type_code INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  parent_product_type_code INT,
  product_type_description VARCHAR( 200 ) NOT NULL,
  FOREIGN KEY (parent_product_type_code) REFERENCES ref_product_types(product_type_code)
) ENGINE = INNODB;

CREATE TABLE products (
  product_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  product_type_code INT NOT NULL ,
  return_merchandise_authorization_nr INT,
  product_name VARCHAR( 100 ) NOT NULL,
  product_price FLOAT NOT NULL,
  product_color VARCHAR( 30 ),
  product_size VARCHAR( 30 ),
  product_description VARCHAR( 500 ),
  other_product_details VARCHAR( 500 ),
  FOREIGN KEY (product_type_code) REFERENCES ref_product_types(product_type_code)
)ENGINE = INNODB;

CREATE TABLE orders (
  order_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  order_status_code INT NOT NULL,
  date_order_placed TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  order_details VARCHAR( 200 ),
  FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
  FOREIGN KEY (order_status_code) REFERENCES ref_order_status_codes(order_status_code)
)ENGINE = INNODB;

CREATE TABLE customer_payment_methods (
  customer_payment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  payment_method_code VARCHAR( 6 ) NOT NULL,
  payment_method_details VARCHAR( 200 ),
  FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
  FOREIGN KEY (payment_method_code) REFERENCES ref_payment_methods(payment_method_code)
)ENGINE = INNODB;

CREATE TABLE invoices (
  invoice_number INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  invoice_status_code INT NOT NULL,
  invoice_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  invoice_details VARCHAR( 300 ),
  FOREIGN KEY (order_id) REFERENCES  orders(order_id),
  FOREIGN KEY (invoice_status_code) REFERENCES ref_invoice_status_codes(invoice_status_code)
)ENGINE = INNODB;

CREATE TABLE payments (
  payment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  invoice_number INT NOT NULL,
  payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  payment_amount FLOAT NOT NULL,
  FOREIGN KEY (invoice_number) REFERENCES invoices(invoice_number)
)ENGINE = INNODB;

CREATE TABLE shipments (
  shipment_id INT NOT NULL PRIMARY KEY,
  order_id INT NOT NULL,
  invoice_number INT NOT NULL,
  shipment_tracking_number VARCHAR( 100 ),
  shipment_date DATE NOT NULL,
  other_shipment_details VARCHAR( 300 ),
  FOREIGN KEY (order_id) REFERENCES orders(order_id),
  FOREIGN KEY (invoice_number) REFERENCES invoices(invoice_number)
)ENGINE = INNODB;

CREATE TABLE order_items (
  order_item_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  order_id INT NOT NULL,
  order_item_status_code INT NOT NULL,
  order_item_quantity INT NOT NULL,
  order_item_price FLOAT NOT NULL,
  rma_number INT,
  rma_issued_by VARCHAR( 100 ),
  rma_issued_date DATETIME,
  other_order_item_details VARCHAR( 300 ),
  FOREIGN KEY (product_id) REFERENCES products(product_id),
  FOREIGN KEY (order_id) REFERENCES orders(order_id),
  FOREIGN KEY (order_item_status_code) REFERENCES ref_order_item_status_codes(order_item_status_code)
)ENGINE  = INNODB;

CREATE TABLE shipment_items (
  shipment_id INT NOT NULL,
  order_item_id INT NOT NULL,
  FOREIGN KEY (shipment_id) REFERENCES shipments(shipment_id),
  FOREIGN KEY (order_item_id) REFERENCES order_items(order_item_id)
)ENGINE = INNODB;

CREATE TABLE shipment_items (
  shipment_id INT NOT NULL,
  order_item_id INT NOT NULL,
  PRIMARY KEY (shipment_id,order_item_id),
  FOREIGN KEY (shipment_id) REFERENCES shipments(shipment_id),
  FOREIGN KEY (order_item_id) REFERENCES order_items(order_item_id)
)ENGINE = INNODB;

SELECT i.TABLE_NAME, i.CONSTRAINT_TYPE, i.CONSTRAINT_NAME, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME
FROM information_schema.TABLE_CONSTRAINTS i
  LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
      AND i.TABLE_SCHEMA = DATABASE()
      AND i.TABLE_NAME = 'order_items'
      AND k.REFERENCED_COLUMN_NAME = 'product_id';





CREATE DATABASE karaj_restaurant;
USE karaj_restaurant;

CREATE TABLE foods (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image VARCHAR(255),
  description VARCHAR(255),
  name VARCHAR(255),
  ingredients VARCHAR(255),
  quantity INT,
  price DECIMAL(10,2)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE,
  username VARCHAR(255),
  password VARCHAR(255),
  location VARCHAR(255),
  is_admin TINYINT(1) DEFAULT 0
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  total_price DECIMAL(10,2),
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  rating INT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  food_id INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (food_id) REFERENCES foods(id)
);

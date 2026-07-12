-- =========================================================
-- FEDCO LAUNDRY HUB - Database Schema
-- Import this file sa phpMyAdmin o sa MySQL command line:
--   mysql -u root -p < database.sql
-- =========================================================

CREATE DATABASE IF NOT EXISTS fedco_laundry_hub
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE fedco_laundry_hub;

-- ---------------------------------------------------------
-- USERS TABLE (customers AT admin, pinagsama sa role column)
-- ---------------------------------------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  reward_points INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- ORDERS TABLE
-- ---------------------------------------------------------
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_code VARCHAR(20) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  service_type VARCHAR(50) NOT NULL,
  weight_kg VARCHAR(20) NOT NULL,
  payment_method VARCHAR(30) NOT NULL,
  pickup_date DATE NOT NULL,
  delivery_address VARCHAR(255) NOT NULL,
  special_instruction VARCHAR(255) DEFAULT NULL,
  base_price DECIMAL(10,2) DEFAULT 0,
  service_fee DECIMAL(10,2) DEFAULT 0,
  delivery_fee DECIMAL(10,2) DEFAULT 50,
  total_amount DECIMAL(10,2) DEFAULT 0,
  status ENUM('pending','processing','done','cancelled') NOT NULL DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- RESCHEDULE REQUESTS
-- ---------------------------------------------------------
CREATE TABLE reschedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  user_id INT NOT NULL,
  new_date DATE NOT NULL,
  new_time TIME NOT NULL,
  reason VARCHAR(255) DEFAULT NULL,
  status ENUM('pending','approved','declined') NOT NULL DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- REPORTED ISSUES / REPORTS
-- ---------------------------------------------------------
CREATE TABLE reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  user_id INT NOT NULL,
  issue_type VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('open','resolved') NOT NULL DEFAULT 'open',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- CANCELLATIONS (log ng mga cancelled orders)
-- ---------------------------------------------------------
CREATE TABLE cancellations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  user_id INT NOT NULL,
  reason VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- FEEDBACK / SUGGESTIONS
-- ---------------------------------------------------------
CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comments TEXT DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tapos na ang schema. HUWAG mag-INSERT ng admin account dito.
-- Pumunta sa seed_admin.php pagkatapos i-import ang file na ito
-- para gumawa ng Admin account (gumagamit ito ng PHP password_hash
-- para ligtas/secure ang naka-store na password).
-- ---------------------------------------------------------

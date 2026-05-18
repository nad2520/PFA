-- =========================
-- 🔹 Création base de données
-- =========================
CREATE DATABASE tp_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tp_testing;

-- =========================
-- 🔐 Table USERS
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('USER', 'ADMIN') DEFAULT 'USER',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- 📦 Table PRODUCTS
-- =========================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- 🧾 Table ORDERS (optionnel bonus)
-- =========================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =========================
-- 📄 Table ORDER_ITEMS
-- =========================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- =========================
-- 🚀 Données de test
-- =========================

-- Users
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@test.com', '1234', 'ADMIN'),
('user1', 'user1@test.com', '1234', 'USER'),
('user2', 'user2@test.com', '1234', 'USER');

-- Products
INSERT INTO products (name, description, price, stock) VALUES
('Laptop', 'High performance laptop', 2500.00, 10),
('Phone', 'Smartphone latest model', 1200.00, 20),
('Headphones', 'Noise cancelling headphones', 300.00, 15),
('Keyboard', 'Mechanical keyboard', 150.00, 25),
('Mouse', 'Wireless mouse', 80.00, 30);
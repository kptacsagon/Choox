CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    units_available INT NOT NULL,
    units_sold INT NOT NULL,
    status ENUM('Low', 'Normal', 'High') NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL
);

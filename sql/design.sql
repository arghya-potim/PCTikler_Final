drop database if EXISTS pctikler;

create database if NOT EXISTS pctikler;

use pctikler;

CREATE TABLE IF NOT EXISTS person (
    personID INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    passwordRepeat VARCHAR(255) NOT NULL,
    points INT NOT NULL,
    user_type ENUM('customer', 'service_man') DEFAULT 'customer',
    customer_type ENUM('bronze', 'gold') DEFAULT 'bronze'
);

CREATE TABLE Components (
    component_id INT AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL,
    specifications TEXT,
    compatibility TEXT,
    discount INT,
    warrenty_years INT
);
INSERT INTO Components (component_id, price, stock_quantity, specifications, compatibility, discount, warrenty_years)
VALUES
(1, 199.99, 50, 'Intel Core i9-13900K, 24 cores, 5.8GHz', 'LGA 1700', 10, 3),
(2, 149.99, 75, 'AMD Ryzen 7 7800X3D, 8 cores, 5.0GHz', 'AM5', 5, 3),
(3, 89.99, 120, 'Corsair Vengeance RGB 32GB DDR5-5600', 'DDR5', 15, 5),
(4, 129.99, 80, 'Samsung 980 Pro 1TB NVMe SSD', 'M.2 NVMe', 0, 5),
(5, 299.99, 30, 'NVIDIA RTX 4070 Ti 12GB GDDR6X', 'PCIe 4.0', 5, 2),
(6, 199.99, 40, 'ASUS ROG Strix B650-E Gaming', 'AM5', 10, 3),
(7, 79.99, 150, 'Seagate Barracuda 2TB HDD 7200RPM', 'SATA 6Gb/s', 0, 2),
(8, 129.99, 60, 'Corsair RM750x 750W 80+ Gold', 'ATX', 5, 10),
(9, 89.99, 90, 'Noctua NH-D15 CPU Cooler', 'Multiple sockets', 0, 6),
(10, 59.99, 200, 'Fractal Design Meshify C', 'ATX Mid Tower', 15, 2),
(11, 249.99, 25, 'LG 27GL850-B 27" 144Hz QHD Nano IPS', 'HDMI, DisplayPort', 20, 1),
(12, 39.99, 180, 'Logitech G502 HERO Gaming Mouse', 'USB', 0, 2),
(13, 99.99, 70, 'Razer BlackWidow V3 Mechanical Keyboard', 'USB', 10, 2),
(14, 149.99, 45, 'Creative Sound BlasterX AE-5 Plus', 'PCIe', 5, 3),
(15, 79.99, 110, 'TP-Link Archer TX50E WiFi 6 PCIe Card', 'PCIe', 0, 3),
(16, 34.99, 250, 'Corsair LL120 RGB 120mm Fan', '120mm', 20, 2),
(17, 159.99, 35, 'Elgato 4K60 Pro MK.2 Capture Card', 'PCIe', 0, 2),
(18, 69.99, 85, 'HyperX Cloud II Gaming Headset', '3.5mm, USB', 10, 2),
(19, 499.99, 15, 'AMD Radeon RX 7900 XT 20GB', 'PCIe 4.0', 5, 2),
(20, 129.99, 55, 'G.Skill Trident Z5 RGB 32GB DDR5-6000', 'DDR5', 0, 5),
(21, 89.99, 95, 'Crucial P5 Plus 1TB NVMe SSD', 'M.2 NVMe', 15, 5),
(22, 179.99, 40, 'ASUS TUF Gaming X670E-Plus', 'AM5', 5, 3),
(23, 49.99, 160, 'EVGA 600 BR 600W 80+ Bronze', 'ATX', 0, 3),
(24, 59.99, 120, 'Cooler Master Hyper 212 RGB', 'Multiple sockets', 10, 2),
(25, 109.99, 65, 'NZXT H510 Flow Compact ATX', 'ATX Mid Tower', 5, 2),
(26, 349.99, 20, 'Alienware AW3423DW 34" QD-OLED', 'HDMI, DisplayPort', 0, 3),
(27, 29.99, 220, 'Redragon S101 Gaming Keyboard Mouse Combo', 'USB', 25, 1),
(28, 199.99, 30, 'ASUS ROG Swift PG279Q 27" 165Hz', 'HDMI, DisplayPort', 10, 2),
(29, 79.99, 75, 'WD Blue SN570 1TB NVMe SSD', 'M.2 NVMe', 5, 5),
(30, 149.99, 50, 'MSI MAG B660 Tomahawk WiFi', 'LGA 1700', 0, 3);

CREATE TABLE Merchandise (
merchandise_id INT AUTO_INCREMENT PRIMARY KEY,
merch_name text not null,
price DECIMAL(10,2) NOT NULL,
stock_quantity INT NOT NULL
);
INSERT INTO Merchandise (merchandise_id, merch_name, price, stock_quantity)
VALUES
(1, 'Gaming T-Shirt', 24.99, 150),
(2, 'Logo Hoodie', 49.99, 80),
(3, 'Branded Cap', 19.99, 200),
(4, 'Limited Edition Mousepad', 29.99, 120),
(5, 'Collector''s Pin Set', 14.99, 300),
(6, 'Gaming Socks', 12.99, 250),
(7, 'Logo Water Bottle', 17.99, 180),
(8, 'Keyboard Wrist Rest', 22.99, 90),
(9, 'Branded Backpack', 59.99, 60),
(10, 'Gaming Gloves', 34.99, 110),
(11, 'LED Desk Lamp', 39.99, 75),
(12, 'Wireless Charger', 27.99, 130),
(13, 'Logo Sticker Pack', 9.99, 500),
(14, 'Limited Edition Poster', 15.99, 200),
(15, 'Gaming Chair Cushion', 32.99, 85),
(16, 'Branded Face Mask', 8.99, 400),
(17, 'Phone Pop Socket', 6.99, 350),
(18, 'Logo Keychain', 5.99, 600),
(19, 'Gaming Lanyard', 7.99, 280),
(20, 'RGB Mouse Bungee', 18.99, 95);

CREATE TABLE Services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2),
    service_type TEXT
);

CREATE TABLE ServiceMan (
    serviceman_id INT AUTO_INCREMENT PRIMARY KEY,
    specialization VARCHAR(100),
    rating INT
);

CREATE TABLE ServiceHistory (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_email varchar(155),
    serviceman_id INT NOT NULL,
    service_id INT NOT NULL,
    service_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Sell_Used_Products (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    description TEXT,
    sale_price DECIMAL(10,2),
    conditions VARCHAR(50),
    seller_email VARCHAR(255) NOT NULL
);




create TABLE Hire_to_Build(
    hire_id INT AUTO_INCREMENT PRIMARY KEY,  
    hire_date DATE,
    hire_cost DECIMAL(12,2),
    serviceman_id INT NOT NULL
);

INSERT INTO Hire_to_Build (hire_date, hire_cost, serviceman_id)
VALUES
( '2023-10-01', 1500.00,1),
( '2023-10-02', 2000.00,2),
( '2023-10-03', 2500.00,3),
( '2023-10-04', 800.00,4),
( '2023-10-05', 1200.00,5),
( '2023-10-06', 3000.00,6),
( '2023-10-07', 1000.00,7),
( '2023-10-08', 1800.00,8),
( '2023-10-09', 4000.00,9),
( '2023-10-10', 2200.00,10);

CREATE TABLE hire_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_email varchar(155) NOT NULL,
    serviceman_id INT NOT NULL,
    confirmation_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

create table Buy_Components (
customer_id INT NOT NULL,
customer_email varchar(155) NOT NULL,
component_id INT NOT NULL,
purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP);

create table buy_merchandise (
customer_id INT NOT NULL,
customer_email varchar(155) NOT NULL,
merchandise_id INT NOT NULL,
purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

create table used_product_sell_history(
seller_email varchar(155) NOT NULL,
buyer_email varchar(155) NOT NULL
);
create table expo(
    expo_ID INT AUTO_INCREMENT PRIMARY KEY,
    expo_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    description TEXT
);

create table expo_participation_history(
    expo_ID INT NOT NULL,
    customer_email varchar(155) NOT NULL,
    Participation_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO expo (expo_name, location, start_date, end_date, description)
VALUES ('Tech Innovators Expo 2025', 'Dhaka International Convention Center', '2025-06-10', '2025-06-12', 'A showcase of the latest innovations in consumer technology.');

INSERT INTO expo (expo_name, location, start_date, end_date, description)
VALUES ('Green Energy Expo', 'Chittagong Trade Center', '2025-07-01', '2025-07-03', 'Focuses on renewable energy solutions and sustainable technologies.');

INSERT INTO expo (expo_name, location, start_date, end_date, description)
VALUES ('Startup Bangladesh 2025', 'Bangabandhu International Conference Center, Dhaka', '2025-08-15', '2025-08-17', 'An expo for local startups to connect with investors and partners.');

INSERT INTO expo (expo_name, location, start_date, end_date, description)
VALUES ('HealthTech Expo', 'Sylhet Medical Expo Grounds', '2025-09-05', '2025-09-07', 'Featuring advances in healthcare technologies and medical equipment.');

INSERT INTO expo (expo_name, location, start_date, end_date, description)
VALUES ('Education & Career Fair', 'Rajshahi University Auditorium', '2025-10-20', '2025-10-22', 'Connecting students with education and career opportunities across the country.');
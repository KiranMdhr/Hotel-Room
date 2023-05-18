Index ma hotel ko name huncha
When clicked Book room passes the hotel_id : Soaltee 1, Hyatt 2
There is a form that opens the register form that links to the room id of the hotel_id
Each hotel has its own table:
hotels_table

```sql

CREATE DATABASE hotel_db;
USE hotel_db;
CREATE TABLE `account_details` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(100) NOT NULL,
`username` VARCHAR(50) NOT NULL,
`password` VARCHAR(50) NOT NULL,
`account_type` ENUM('admin', 'user') NOT NULL
);
INSERT INTO `account_details` (`id`, `name`, `username`, `password`, `account_type`)
VALUES
    (1, 'Kiran Manandhar', 'kiran_admin', 'admin123', 'admin'),
    (2, 'Romit Manandhar', 'romit_admin', 'admin123', 'admin'),
    (3, 'Chandan Shakya', 'chandan_user', 'user123', 'user'),
    (4, 'Asha Subedi', 'asha_user', 'user123', 'user'),
    (5, 'Nabin Rai', 'nabin_user', 'user123', 'user'),
    (6, 'Saraswati Bhandari', 'saraswati_user', 'user123', 'user'),
    (7, 'Ramesh Gurung', 'ramesh_user', 'user123', 'user'),
    (8, 'Sunita Thapa', 'sunita_user', 'user123', 'user'),
    (9, 'Bijay Tamang', 'bijay_user', 'user123', 'user'),
    (10, 'Anita Acharya', 'anita_user', 'user123', 'user');

CREATE TABLE `hotel_details` (
`hotel_id` INT AUTO_INCREMENT PRIMARY KEY,
`hotel_name` VARCHAR(50),
`location` VARCHAR(50),
`description` TEXT
);
INSERT INTO `hotel_details` (`hotel_id`, `hotel_name`, `location`, `description`)
VALUES
(1, 'Soaltee Hotel', 'Soalteemode, Kathmandu', 'Featuring rooms with a private bathroom, Soaltee Hotel is located at Soalteemode, the bustling tourist hub of Kathmandu.'),
(2, 'Hyatt Hotel', 'Taragaon, Kathmandu', 'Featuring rooms with a private bathroom, Hyatt Hotel is located at Soalteemode, the bustling tourist hub of Kathmandu.'),
(3, 'Yak & Yeti Hotel', 'Durbar Marg, Kathmandu', 'Featuring rooms with a private bathroom, Yak & Yeti Hotel is located at Soalteemode, the bustling tourist hub of Kathmandu.'),
(4, 'Malla Hotel', 'Lekhnath Marg, Kathmandu', 'Conveniently located in Kathmandu, Malla Hotel provides air-conditioned rooms with free WiFi, free private parking, and room service.');

CREATE TABLE `hotel_room_details` (
`room_id` INT PRIMARY KEY,
`hotel_id` INT,
`room_number` VARCHAR(10),
`room_name` VARCHAR(30),
`room_type` VARCHAR(20),
`adult_price` DECIMAL(10,2),
`kid_price` DECIMAL(10,2),
`description` TEXT,
`is_booked` BOOLEAN DEFAULT 0,
FOREIGN KEY (`hotel_id`) REFERENCES `hotel_details`(`hotel_id`) ON DELETE CASCADE
);
-- Insert room details for Soaltee Hotel
INSERT INTO hotel_room_details (room_id, hotel_id, room_number, room_name, room_type, adult_price, kid_price, description, is_booked)
VALUES
    (1, 1, '101', 'Deluxe Room', 'Standard', 100.00, 50.00, 'Spacious room with a comfortable bed.', 0),
    (2, 1, '102', 'Executive Suite', 'Suite', 200.00, 100.00, 'Luxurious suite with separate living area.', 0),
    (3, 1, '201', 'Family Room', 'Standard', 150.00, 75.00, 'Ideal for families with multiple beds.', 0),
    (4, 1, '202', 'Presidential Suite', 'Suite', 500.00, 250.00, 'Opulent suite with a private jacuzzi.', 0),
    (5, 1, '301', 'Standard Room', 'Standard', 80.00, 40.00, 'Comfortable room with essential amenities.', 0),
    (6, 1, '302', 'Deluxe Suite', 'Suite', 250.00, 125.00, 'Elegant suite with a separate bedroom and living area.', 0);

-- Insert room details for Hyatt Hotel
INSERT INTO hotel_room_details (room_id, hotel_id, room_number, room_name, room_type, adult_price, kid_price, description, is_booked)
VALUES
    (7, 2, '103', 'Deluxe Room', 'Standard', 120.00, 60.00, 'Well-appointed room with modern amenities.', 0),
    (8, 2, '104', 'Club Room', 'Standard', 150.00, 75.00, 'Access to exclusive club facilities.', 0),
    (9, 2, '203', 'Executive Suite', 'Suite', 300.00, 150.00, 'Luxury suite with a separate living area.', 0),
    (10, 2, '204', 'Presidential Suite', 'Suite', 600.00, 300.00, 'Impeccably designed suite with panoramic views.', 0),
    (11, 2, '303', 'Standard Room', 'Standard', 100.00, 50.00, 'Comfortable room with contemporary decor.', 0),
    (12, 2, '304', 'Junior Suite', 'Suite', 200.00, 100.00, 'Spacious suite with a sitting area and work desk.', 0);

-- Insert room details for Yak & Yeti Hotel
INSERT INTO hotel_room_details (room_id, hotel_id, room_number, room_name, room_type, adult_price, kid_price, description, is_booked)
VALUES
    (13, 3, '105', 'Standard Room', 'Standard', 90.00, 45.00, 'Cozy room with all essential amenities.', 0),
    (14, 3, '106', 'Deluxe Room', 'Standard', 110.00, 55.00, 'Comfortable room with a seating area.', 0),
    (15, 3, '205', 'Executive Suite', 'Suite', 280.00, 140.00, 'Stylish suite with separate living and sleeping areas.', 0),
    (16, 3, '206','Family Room', 'Standard', 160.00, 80.00, 'Spacious room suitable for families.', 0),
    (17, 3, '305', 'Presidential Suite', 'Suite', 550.00, 275.00, 'Grand suite with luxurious amenities.', 0),
    (18, 3, '306', 'Junior Suite', 'Suite', 220.00, 110.00, 'Well-appointed suite with a sitting area.', 0);

-- Insert room details for Malla Hotel
INSERT INTO hotel_room_details (room_id, hotel_id, room_number, room_name, room_type, adult_price, kid_price, description, is_booked)
VALUES
    (19, 4, '107', 'Deluxe Room', 'Standard', 95.00, 47.50, 'Comfortable room with modern decor.', 0),
    (20, 4, '108', 'Superior Room', 'Standard', 105.00, 52.50, 'Spacious room with a city view.', 0),
    (21, 4, '207', 'Executive Suite', 'Suite', 260.00, 130.00, 'Elegant suite with separate living and sleeping areas.', 0),
    (22, 4, '208', 'Family Room', 'Standard', 140.00, 70.00, 'Ideal room for families or small groups.', 0),
    (23, 4, '307', 'Presidential Suite', 'Suite', 520.00, 260.00, 'Opulent suite with luxurious amenities.', 0),
    (24, 4, '308', 'Junior Suite', 'Suite', 190.00, 95.00, 'Spacious suite with a comfortable seating area.', 0);

CREATE TABLE `bookings_details` (
`booking_id` INT AUTO_INCREMENT PRIMARY KEY,
`user_id` INT NOT NULL,
`hotel_id` INT NOT NULL,
`room_id` INT NOT NULL,
`name` VARCHAR(50) NOT NULL,
`email` VARCHAR(50) NOT NULL,
`number` VARCHAR(10) NOT NULL,
`check_in` DATE NOT NULL,
`check_out` DATE NOT NULL,
`adults` INT(1) NOT NULL,
`childs` INT(1) NOT NULL,
`total_price` DECIMAL(10,2) NOT NULL,
INDEX `user_booking_index`(`user_id`),
INDEX `hotel_booking_index`(`hotel_id`),
INDEX `room_booking_index`(`room_id`),
FOREIGN KEY (`user_id`) REFERENCES `account_details`(`id`) ON DELETE CASCADE,
FOREIGN KEY (`hotel_id`) REFERENCES `hotel_details`(`hotel_id`) ON DELETE CASCADE,
FOREIGN KEY (`room_id`) REFERENCES `hotel_room_details`(`room_id`) ON DELETE CASCADE
);

CREATE TABLE `review_details` (
`review_id` INT PRIMARY KEY,
`booking_id` INT NOT NULL,
`rating` INT NOT NULL,
`comment` TEXT,
INDEX `booking_review_index`(`booking_id`),
FOREIGN KEY (`booking_id`) REFERENCES `bookings_details`(`booking_id`) ON DELETE CASCADE
);

CREATE TABLE `messages` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` VARCHAR(50) NOT NULL,
`email` VARCHAR(50) NOT NULL,
`number` VARCHAR(10) NOT NULL,
`message` VARCHAR(2000) NOT NULL,
`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `site_reviews` (
`review_id` INT PRIMARY KEY,
`user_id` INT NOT NULL,
`rating` INT NOT NULL,
`comment` TEXT,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (`user_id`) REFERENCES `account_details`(`id`) ON DELETE CASCADE
);
INSERT INTO `site_reviews` (`review_id`, `user_id`, `rating`, `comment`)
VALUES
    (1, 3, 4, 'I booked a hotel through this website and had a pleasant experience.'),
    (2, 5, 5, 'The hotel booking process was quick and hassle-free.'),
    (3, 6, 3, 'I encountered some issues while searching for available hotels.'),
    (4, 7, 4, 'The website provided a wide selection of hotels to choose from.'),
    (5, 8, 5, 'I found great deals on hotel bookings through this website.'),
    (6, 9, 4, 'The booking confirmation was received promptly via email.'),
    (7, 10, 2, 'I faced difficulties while making changes to my hotel reservation.'),
    (8, 3, 5, 'The hotel reviews and ratings helped me make an informed decision.'),
    (9, 6, 4, 'The website offered competitive prices for hotel bookings.'),
    (10, 7, 3, 'The user interface could be more intuitive for hotel search and selection.');

CREATE VIEW `available_rooms` AS
SELECT `h`.`hotel_id`, `h`.`hotel_name`, `h`.`location`, `hrd`.`room_number`, `hrd`.`room_type`, `hrd`.`adult_price`, `hrd`.`kid_price`
FROM `hotel_details` `h`
INNER JOIN `hotel_room_details` `hrd` ON `h`.`hotel_id` = `hrd`.`hotel_id`
WHERE `hrd`.`is_booked` = 0;

CREATE VIEW `booking_details_with_username` AS
SELECT `b`.`booking_id`, `b`.`hotel_id`, `b`.`room_id`, `b`.`name`, `b`.`email`, `b`.`number`, `b`.`check_in`, `b`.`check_out`, `b`.`adults`, `b`.`childs`, `b`.`total_price`, `u`.`name` AS `username`
FROM `bookings_details` `b`
INNER JOIN `account_details` `u` ON `b`.`user_id` = `u`.`id`;
```

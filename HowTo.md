Index ma hotel ko name huncha
When clicked Book room passes the hotel_id : Soaltee 1, Hyatt 2
There is a form that opens the register form that links to the room id of the hotel_id
Each hotel has its own table:
hotels_table

CREATE TABLE `account_details` (
  `id` VARCHAR(20) PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  `account_type` ENUM('admin', 'user') NOT NULL
);

CREATE TABLE `hotel_details` (
    `hotel_id` INT PRIMARY KEY,
    `hotel_name` VARCHAR(50),
    `location` VARCHAR(50),
    `description` TEXT
);

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

CREATE TABLE `bookings_details` (
  `booking_id` VARCHAR(20) PRIMARY KEY,
  `user_id` VARCHAR(20) NOT NULL,
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
  `booking_id` VARCHAR(20) NOT NULL,
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
  `user_id` VARCHAR(20) NOT NULL,
  `rating` INT NOT NULL,
  `comment` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `account_details`(`id`) ON DELETE CASCADE
);


CREATE VIEW `available_rooms` AS
SELECT `h`.`hotel_name`, `h`.`location`, `hrd`.`room_number`, `hrd`.`room_type`, `hrd`.`adult_price`, `hrd`.`kid_price`
FROM `hotel_details` `h`
INNER JOIN `hotel_room_details` `hrd` ON `h`.`hotel_id` = `hrd`.`hotel_id`
WHERE `hrd`.`is_booked` = 0;

CREATE VIEW `booking_details_with_username` AS
SELECT `b`.`booking_id`, `b`.`hotel_id`, `b`.`room_id`, `b`.`name`, `b`.`email`, `b`.`number`, `b`.`check_in`, `b`.`check_out`, `b`.`adults`, `b`.`childs`, `b`.`total_price`, `u`.`name` AS `username`
FROM `bookings_details` `b`
INNER JOIN `account_details` `u` ON `b`.`user_id` = `u`.`id`;



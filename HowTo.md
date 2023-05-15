Index ma hotel ko name huncha
When clicked Book room passes the hotel_id : Soaltee 1, Hyatt 2
There is a form that opens the register form that links to the room id of the hotel_id
Each hotel has its own table:
hotels_table

CREATE TABLE `accounts` (
  `id` VARCHAR(20) PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  `account_type` ENUM('admin', 'user') NOT NULL
);

CREATE TABLE `Hotel` (
    `hotel_id` INT PRIMARY KEY,
    `hotel_name` VARCHAR(50),
    `location` VARCHAR(50),
    `description` TEXT
);
CREATE TABLE Hotel_Room (
    `room_id` INT PRIMARY KEY,
    `hotel_id` INT,
    `room_number` VARCHAR(10),
    `room_type` VARCHAR(20),
    `adult_price` DECIMAL(10,2),
    `kid_price` DECIMAL(10,2),
    `description` TEXT
    FOREIGN KEY (hotel_id) REFERENCES Hotel(hotel_id) ON DELETE CASCADE
);




CREATE TABLE `bookings` (
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
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`hotel_id`) REFERENCES `Hotel`(`hotel_id`) ON DELETE CASCADE,
  FOREIGN KEY (`room_id`) REFERENCES `Hotel_Room`(`room_id`) ON DELETE CASCADE
);


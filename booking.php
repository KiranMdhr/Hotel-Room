<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <style>
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-block-end: 0.5em;
        }
    </style>

<body>
    <?php
    include 'components/user_header.php';
    include 'components/connect.php';

    $room_id = $_GET['room_id'];

    // Fetch room details based on the selected room_id
    $fetch_room = $conn->prepare("SELECT * FROM hotel_room_details WHERE room_id = :room_id");
    $fetch_room->bindParam(':room_id', $room_id);
    $fetch_room->execute();

    if ($fetch_room->rowCount() > 0) {
        $room = $fetch_room->fetch(PDO::FETCH_ASSOC);
        $room_name = $room['room_name'];
        $adult_price = $room['adult_price'];
        $kid_price = $room['kid_price'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $number = $_POST['number'];
            $check_in = $_POST['check_in'];
            $check_out = $_POST['check_out'];
            $adults = $_POST['adults'];
            $childs = $_POST['childs'];

            // Calculate total price based on the number of adults and children
            $total_price = ($adult_price * $adults) + ($kid_price * $childs);

            // Generate a unique booking ID
            $booking_id = uniqid();

            // Insert the booking details into the database
            $insert_booking = $conn->prepare("INSERT INTO bookings_details (booking_id, user_id, hotel_id, room_id, name, email, number, check_in, check_out, adults, childs, total_price)
            VALUES (:booking_id, :user_id, :hotel_id, :room_id, :name, :email, :number, :check_in, :check_out, :adults, :childs, :total_price)");
            $insert_booking->bindParam(':booking_id', $booking_id);
            $insert_booking->bindParam(':user_id', $_SESSION['user_id']); // Assuming you have a session for the user ID
            $insert_booking->bindParam(':hotel_id', $room['hotel_id']);
            $insert_booking->bindParam(':room_id', $room_id);
            $insert_booking->bindParam(':name', $name);
            $insert_booking->bindParam(':email', $email);
            $insert_booking->bindParam(':number', $number);
            $insert_booking->bindParam(':check_in', $check_in);
            $insert_booking->bindParam(':check_out', $check_out);
            $insert_booking->bindParam(':adults', $adults);
            $insert_booking->bindParam(':childs', $childs);
            $insert_booking->bindParam(':total_price', $total_price);

            if ($insert_booking->execute()) {
                // Booking successful
                echo '<p>Booking successful! Your booking ID is: ' . $booking_id . '</p>';
                echo '<p>Total Price: $' . $total_price . '</p>';
            } else {
                // Booking failed
                echo '<p>Booking failed. Please try again later.</p>';
            }
        }
    } else {
        echo '<p>Invalid room selected</p>';
    }
    ?>


    <section class="contact" id="contact">
        <div class="container">
            <h2>Room Booking - <?php echo $room_name; ?></h2>
            <form method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="number">Contact Number:</label>
                <input type="text" id="number" name="number" required><br>

                <label for="check_in">Check-in Date:</label>
                <input type="date" id="check_in" name="check_in" required><br>

                <label for="check_out">Check-out Date:</label>
                <input type="date" id="check_out" name="check_out" required><br>

                <label for="adults">Number of Adults:</label>
                <input type="number" id="adults" name="adults" min="1" required><br>

                <label for="childs">Number of Children:</label>
                <input type="number" id="childs" name="childs" min="0" required><br>

                <button type="submit">Book Now</button>
            </form>
        </div>
    </section>
    <?php include 'components/footer.php'; ?>

</body>

</html>
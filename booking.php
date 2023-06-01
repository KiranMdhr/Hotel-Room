<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

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
    <script>
        $(function() {
            var dateFormat = "yy-mm-dd",
                from = $("#check_in")
                .datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 3,
                    dateFormat: "yy-mm-dd"
                })
                .on("change", function() {
                    to.datepicker("option", "minDate", getDate(this));
                }),
                to = $("#check_out").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2,
                    dateFormat: "yy-mm-dd"
                })
                .on("change", function() {
                    from.datepicker("option", "maxDate", getDate(this));
                });

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }
        });
    </script>

</head>

<body>
    <?php
    session_start();
    include 'components/connect.php';
    include 'components/user_header.php';

    $room_id = $_GET['room_id'];
    $hotel_name = $_GET['hotel_name'];
    $fetch_dates = $conn->prepare("SELECT check_in, check_out FROM bookings_details WHERE room_id = :room_id");
    $fetch_dates->bindParam(':room_id', $room_id);
    $fetch_dates->execute();
    $dates = array();
    while ($row = $fetch_dates->fetch(PDO::FETCH_ASSOC)) {
        $dates[] = $row;
    }
    // Fetch room details based on the selected room_id
    $fetch_room = $conn->prepare("SELECT * FROM hotel_room_details WHERE room_id = :room_id");
    $fetch_room->bindParam(':room_id', $room_id);
    $fetch_room->execute();

    if ($fetch_room->rowCount() > 0) {
        $room = $fetch_room->fetch(PDO::FETCH_ASSOC);
        $room_number = $room['room_number'];
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

            // Check if the room is already booked for the specified period
            $check_booking = $conn->prepare("SELECT * FROM bookings_details WHERE room_id = :room_id AND (check_in <= :check_out AND check_out >= :check_in)");
            $check_booking->bindParam(':room_id', $room_id);
            $check_booking->bindParam(':check_in', $check_in);
            $check_booking->bindParam(':check_out', $check_out);
            $check_booking->execute();

            if ($check_booking->rowCount() > 0) {
                // Room is already booked for the specified period
                echo '<p>The room is already booked for the specified period. Please choose different dates.</p>';
            } else {
                // Calculate total price based on the number of adults and children
                $total_price = ($adult_price * $adults) + ($kid_price * $childs);

                // Insert the booking details into the database
                $insert_booking = $conn->prepare("INSERT INTO bookings_details (user_id, hotel_id, room_id, name, email, number, check_in, check_out, adults, childs, total_price)
            VALUES (:user_id, :hotel_id, :room_id, :name, :email, :number, :check_in, :check_out, :adults, :childs, :total_price)");
                $insert_booking->bindParam(':user_id', $_SESSION['id']); // Assuming you have a session for the user ID
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
                    $booking_id = $conn->lastInsertId(); // Get the last inserted booking ID

                    // Redirect to book_success.php with booking ID and total price as query parameters
                    header('Location: book_success.php?booking_id=' . $booking_id . '&total_price=' . $total_price);
                    exit();
                } else {
                    // Booking failed
                    echo '<p>Booking failed. Please try again later.</p>';
                }
            }
        }
    } else {
        echo '<p>Invalid room selected</p>';
    }
    ?>


    <section class="contact" id="contact">
        <div class="container">
            <h2>Room No: <?php echo $room_number; ?> - <?php echo $room_name; ?> of <?php echo $hotel_name; ?></h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="number" class="form-label">Contact Number:</label>
                    <input type="text" class="form-control" id="number" name="number" required>
                </div>
                <div class="mb-3">
                    <label for="check_in">Check-in Date:</label>
                    <input type="text" id="check_in" name="check_in" required>
                    <label for="check_out">Check-out Date:</label>
                    <input type="text" id="check_out" name="check_out" required>
                </div>
                <div class="mb-3">
                    <label for="adults" class="form-label">Number of Adults:</label>
                    <input type="number" class="form-control" id="adults" name="adults" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="childs" class="form-label">Number of Children:</label>
                    <input type="number" class="form-control" id="childs" name="childs" min="0" required>
                </div>
                <button type="submit" class="btn btn-primary">Book Now</button>
            </form>
        </div>
    </section>


    <script src="js/booking.js">

    </script>
    <?php include 'components/footer.php'; ?>

</body>

</html>
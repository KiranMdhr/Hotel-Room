<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RK Hotel</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="design.css"> -->
</head>

<body>
<?php include 'components/user_header.php'; ?>

    <?php
    include 'components/connect.php';

    $hotel_id = $_POST['hotel_id'];

    // Fetch hotel details based on the selected hotel_id
    $fetch_hotel = $conn->prepare("SELECT * FROM hotel_details WHERE hotel_id = :hotel_id");
    $fetch_hotel->bindParam(':hotel_id', $hotel_id);
    $fetch_hotel->execute();

    if ($fetch_hotel->rowCount() > 0) {
        $hotel = $fetch_hotel->fetch(PDO::FETCH_ASSOC);
        $hotel_name = $hotel['hotel_name'];

        // Fetch room details based on the selected hotel_id
        $fetch_rooms = $conn->prepare("SELECT * FROM hotel_room_details WHERE hotel_id = :hotel_id");
        $fetch_rooms->bindParam(':hotel_id', $hotel_id);
        $fetch_rooms->execute();

        if ($fetch_rooms->rowCount() > 0) {
            echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Rooms - ' . $hotel_name . '</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="container">
                <h2>' . $hotel_name . ' - Room Selection</h2>
                <div class="row">';

            while ($room = $fetch_rooms->fetch(PDO::FETCH_ASSOC)) {
                $room_number = $room['room_number'];
                $room_name = $room['room_name'];
                $room_type = $room['room_type'];
                $adult_price = $room['adult_price'];
                $kid_price = $room['kid_price'];
                $description = $room['description'];

                // Generate the content for each room card
                echo '
            <div class="col-md-3 col-sm-6">
                <div class="card card-block">
                    <img src="images/' . $hotel_name . '.jpg" alt="Photo of sunset">
                    <h5 class="card-title mt-3 mb-3">' . $room_name . '</h5>
                    <p class="card-text">' . $description . '</p>
                    <p class="card-location">Room Number: ' . $room_number . '</p>
                    <p class="card-location">Room Type: ' . $room_type . '</p>
                    <p class="card-location">Adult Price: ' . $adult_price . '</p>
                    <p class="card-location">Kid Price: ' . $kid_price . '</p>
                    <button onclick="window.location.href=\'booking.php?room_id=' . $room['room_id'] . '\';">
                        Book Now
                    </button>
                </div>
            </div>';
            }

            echo '</div>
            </div>
        </body>
        </html>';
        } else {
            echo '<p>No rooms available for ' . $hotel_name . '</p>';
        }
    } else {
        echo '<p>Invalid hotel selected</p>';
    }
    ?>
   <?php include 'components/footer.php'; ?>

</body>

</html>
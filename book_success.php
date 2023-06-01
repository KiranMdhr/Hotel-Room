<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10;url=bookings.php">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2>Booking Successful!</h2>
        <?php
        $booking_id = $_GET['booking_id'];
        $total_price = $_GET['total_price'];

        echo '<p>Booking ID: ' . $booking_id . '</p>';
        echo '<p>Total Price: $' . $total_price . '</p>';
        ?>
        <p>You will be redirected to the bookings page in 5 seconds...</p>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = 'bookings.php';
        }, 5000);
    </script>
</body>

</html>

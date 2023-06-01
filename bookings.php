<?php

include 'components/connect.php';
session_start();
if (isset($_SESSION['id'])) {
   $user_id = $_SESSION['id'];
} else {
   header('Location: login_register.php');
   exit();
}

if (isset($_POST['cancel'])) {

   $booking_id = $_POST['booking_id'];
   $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

   $verify_booking = $conn->prepare("SELECT * FROM `bookings_details` WHERE booking_id = ?");
   $verify_booking->execute([$booking_id]);

   if ($verify_booking->rowCount() > 0) {
      $delete_booking = $conn->prepare("DELETE FROM `bookings_details` WHERE booking_id = ?");
      $delete_booking->execute([$booking_id]);
      $success_msg[] = 'booking cancelled successfully!';
   } else {
      $warning_msg[] = 'booking cancelled already!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>bookings</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
   <!-- <link rel="stylesheet" href="design.css"> -->


   <style>
      * {
         font-family: 'poppins', sans-serif;
      }

      .h-font {
         font-family: 'Merinda', cursive;
         color: black;
      }

      .custom-bg {
         background-color: #279aBc;

      }

      .bookingbox {
         /* padding-left:50%;  */
         border: 3px solid black;
         height: 400x;
         width: 400px;
         box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
      }

      .heading {
         padding-left: 0%;
         text-align: center;
      }

      .zoom {
         /* padding: 50px; */
         background-color: green;
         transition: transform .2s;
         /* Animation */
         width: 400px;
         /* height: 400px; */
         margin: 0 auto;
      }

      .zoom:hover {
         transform: scale(1.5);
         /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
      }
   </style>

</head>

<body class="bg-light">

   <?php include 'components/user_header.php'; ?>

   <!-- booking section starts  -->

   <section class="bookings">

      <h1 class="heading">My Bookings</h1>

      <div class="box-container p-5">

         <?php
         $select_bookings = $conn->prepare("SELECT * FROM `bookings_details` WHERE user_id = ?");
         $select_bookings->execute([$user_id]);
         if ($select_bookings->rowCount() > 0) {
            while ($fetch_booking = $select_bookings->fetch(PDO::FETCH_ASSOC)) {
               $room_id = $fetch_booking['room_id'];
               // Retrieve room details
               $select_room = $conn->prepare("SELECT * FROM `hotel_room_details` WHERE room_id = ?");
               $select_room->execute([$room_id]);
               $room_details = $select_room->fetch(PDO::FETCH_ASSOC);

               $room_number = $room_details['room_number'];
               $room_name = $room_details['room_name'];
               $hotel_id = $room_details['hotel_id'];
               $select_hotel = $conn ->prepare("SELECT * FROM `hotel_details` WHERE hotel_id = ?");
               $select_hotel->execute([$hotel_id]);
               $hotel_details = $select_hotel->fetch(PDO::FETCH_ASSOC);
         ?>
               <div class="bookingbox zoom bg-light mb-5 p-1 text-center">
                  <p>Hotel: <span><?php echo $hotel_details['hotel_name']; ?></span></p>
                  <p>Room No: <span><?php echo $room_number; ?></span></p>
                  <p>Room Name: <span><?php echo $room_name; ?></span></p>
                  <p>Name: <span><?= $fetch_booking['name']; ?></span></p>
                  <p>Email: <span><?= $fetch_booking['email']; ?></span></p>
                  <p>Number: <span><?= $fetch_booking['number']; ?></span></p>
                  <p>Check-in: <span><?= $fetch_booking['check_in']; ?></span></p>
                  <p>Check-out: <span><?= $fetch_booking['check_out']; ?></span></p>
                  <p>Adults: <span><?= $fetch_booking['adults']; ?></span></p>
                  <p>Children: <span><?= $fetch_booking['childs']; ?></span></p>
                  <p>Booking ID: <span><?= $fetch_booking['booking_id']; ?></span></p>
                  <form action="" method="POST">
                     <input type="hidden" name="booking_id" value="<?= $fetch_booking['booking_id']; ?>">
                     <input type="submit" value="Cancel Booking" name="cancel" class="btn custom-bg" onclick="return confirm('Cancel this booking?');">
                  </form>
               </div>
            <?php
            }
         } else {
            ?>
            <div class="bookingbox" style="text-align: center;">
               <p style="padding-bottom: .5rem; text-transform: capitalize;">No bookings found!</p>
               <a href="index.php#reservation" class="btn">Book Now</a>
            </div>
         <?php
         }
         ?>

      </div>

   </section>

   <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

   <?php include 'components/message.php'; ?>
   <?php include 'components/footer.php'; ?>
   
</body>

</html>
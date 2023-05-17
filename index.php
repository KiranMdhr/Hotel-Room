<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   setcookie('user_id', create_unique_id(), time() + 60 * 60 * 24 * 30, '/');
   header('location:index.php');
}

if (isset($_POST['check'])) {

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
      $total_rooms += $fetch_bookings['rooms'];
   }

   // if the hotel has total 30 rooms 
   if ($total_rooms >= 30) {
      $warning_msg[] = 'rooms are not available';
   } else {
      $success_msg[] = 'rooms are available';
   }
}

if (isset($_POST['book'])) {

   $booking_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $rooms = $_POST['rooms'];
   $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
   $check_out = $_POST['check_out'];
   $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
   $adults = $_POST['adults'];
   $adults = filter_var($adults, FILTER_SANITIZE_STRING);
   $childs = $_POST['childs'];
   $childs = filter_var($childs, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
      $total_rooms += $fetch_bookings['rooms'];
   }

   if ($total_rooms >= 30) {
      $warning_msg[] = 'rooms are not available';
   } else {

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      if ($verify_bookings->rowCount() > 0) {
         $warning_msg[] = 'room booked alredy!';
      } else {
         $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'room booked successfully!';
      }
   }
}

if (isset($_POST['send'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->execute([$name, $email, $number, $message]);

   if ($verify_message->rowCount() > 0) {
      $warning_msg[] = 'Message already sent!';
   } else {
      $insert_message = $conn->prepare("INSERT INTO `messages` (name, email, number, message) VALUES (?, ?, ?, ?)");
      $insert_message->execute([$name, $email, $number, $message]);
      $success_msg[] = 'Message sent successfully!';
   }
}


?>

<!DOCTYPE html>
<html lang="en">

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

      form {
         display: flex;
         flex-direction: column;
         align-items: center;
         width: 100%;
      }

      #fcen {

         width: 320px;
         padding: 10px;
         border: 5px solid gray;
         margin: 0;
      }

      .flex-container {
         display: flex;
      }

      .card img {
         width: 100%;
         height: 400px;
         /* set the desired height for the images */
         object-fit: cover;
         /* use object-fit to resize the images while preserving aspect ratio */
      }

      .card {
         border: none;
      }

      .row {
         justify-content: center;
      }
      .gallery-image {
   max-width: 400px;
   max-height: 300px;
}

   </style>
</head>

<body class="bg-light">

   <?php include 'components/user_header.php'; ?>

   <!-- home section starts  -->

   <section class="home shadow mb-4" id="home">

      <div class="swiper home-slider">

         <div class="swiper-wrapper">

            <div class="box swiper-slide">
               <img src="images/home-img-1.jpg" width="100%">
               <!-- <div class="flex">
               <h3>luxurious rooms</h3>
               <a href="#availability" class="btn">check availability</a>
            </div> -->
            </div>

            <div class="box swiper-slide">
               <img src="images/home-img-2.jpg" width="100%">
               <!-- <div class="flex">
               <h3>foods and drinks</h3>
               <a href="#reservation" class="btn">make a reservation</a>
            </div> -->
            </div>

            <div class="box swiper-slide">
               <img src="images/home-img-3.jpg" width="100%">
               <!-- <div class="flex">
               <h3>luxurious halls</h3>
               <a href="#contact" class="btn">contact us</a>
            </div> -->
            </div>

         </div>

         <div class="swiper-button-next"></div>
         <div class="swiper-button-prev"></div>

      </div>

   </section>

   <!-- home section ends -->

   <!-- availability section starts  -->

   <section class="availability" id="availability">
      <div class="row">
         <div class="col-lg-12 bg-white shadow p-4 mb-4 mt-0 ms-2 me-50 rounded">
            <h2 class="mb-3">Check Booking</h2>
            <form action="" method="post">
               <div class="row align-items-end">
                  <div class="col-lg-3 mb-3">
                     <label class="form-label" style="font-weight:500;">Check-in</label>
                     <input type="date" name="check_in" class="input shadow-none" required>
                  </div>

                  <div class="col-lg-3 mb-3 box">
                     <label class="form-label" style="font-weight:500;">Check-out</label>
                     <input type="date" name="check_out" class="input shadow-none" required>
                  </div>
                  <div class="col-lg-3 mb-3 box">
                     <label class="form-label" style="font-weight:500;">Adult</label>
                     <select name="adults" class="input shadow-none" required>
                        <option value="1">1 adult</option>
                        <option value="2">2 adults</option>
                        <option value="3">3 adults</option>
                        <option value="4">4 adults</option>
                        <option value="5">5 adults</option>
                        <option value="6">6 adults</option>
                     </select>
                  </div>
                  <div class="col-lg-3 mb-3 ">
                     <label class="form-label" style="font-weight:500;">Children</label>
                     <select name="childs" class="input shadow-none" required>
                        <option value="-">0 child</option>
                        <option value="1">1 child</option>
                        <option value="2">2 childs</option>
                        <option value="3">3 childs</option>
                        <option value="4">4 childs</option>
                        <option value="5">5 childs</option>
                        <option value="6">6 childs</option>
                     </select>
                  </div>
                  <!-- <div class="col-lg-3 mb-3 ">
               <label class="form-label" style ="font-weight:500;">Rooms</label>
                  <select name="rooms" class="input" required>
                     <option value="1">1 room</option>
                     <option value="2">2 rooms</option>
                     <option value="3">3 rooms</option>
                     <option value="4">4 rooms</option>
                     <option value="5">5 rooms</option>
                     <option value="6">6 rooms</option>
                  </select>
               </div> -->
               </div>
               <div class="col-lg-1 mb-lg-3 mt-2">
                  <input type="submit" value="check availability" name="check" class="btn text-white shadow-none custom-bg">
               </div>
         </div>
         </form>
      </div>
   </section>



   <!-- availability section ends -->

   <!-- about section starts  -->

   <section class="about mb-4" id="about">

      <div class="container">
         <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-2">
               <h3 class="mb-3">Lorem ipsum dolor sit.</h3>
               <p>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi maxime nesciunt illo enim voluptatibus eveniet?
               </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-1"></div>
            <img src="images/about.jpg" class="w-100">
         </div>
      </div>



      <div class="container">
         <div class="row">
            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/roomm.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ ROOMS</h5>
               </div>
            </div>

            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/customer.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ Customers</h5>
               </div>
            </div>

            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/support.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ Staffs</h5>
               </div>
            </div>


            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/review.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ Reviews</h5>
               </div>
            </div>

            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/roomm.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Food & Drinks</h5>
               </div>
            </div>

            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/pool.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Swimming</h5>
               </div>
            </div>

            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/support.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Beach View</h5>
               </div>
            </div>


            <div class="card shadow me-4 mb-4" style="width: 18rem;">
               <img src="images/gym.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Gym<Span></Span></h5>
               </div>
            </div>
         </div>
      </div>
   </section>

   <!-- about section ends -->

   <!-- gallery section starts  -->

   <section class="gallery shadow mb-4" id="gallery">

      <div class="swiper gallery-slider">
         <div class="swiper-wrapper">
            <img src="images/gallery-img-1.jpg" class="swiper-slide gallery-image" alt="">
            <img src="images/gallery-img-2.webp" class="swiper-slide gallery-image" alt="">
            <img src="images/gallery-img-3.webp" class="swiper-slide gallery-image" alt="">
            <img src="images/gallery-img-4.webp" class="swiper-slide gallery-image" alt="">
            <img src="images/gallery-img-5.webp" class="swiper-slide gallery-image" alt="">
            <img src="images/gallery-img-6.webp" class="swiper-slide gallery-image" alt="">
         </div>
         <div class="swiper-pagination"></div>
      </div>

   </section>

   <!-- gallery section ends -->

   <!-- contact section starts  -->

   <section class="contact" id="contact">
      <div class="container">
         <div class="row bg-white shadow">
            <form action="" method="post" class="mb-3">
               <h3 class="bold mt-4">Send Us a Message</h3>
               <div class="row align-items-end">
                  <div class="col-md-6">
                     <label class="form-label" style="font-weight: 500;">Check-in</label>
                     <input type="date" class="form-control shadow-none">
                  </div>
                  <div class="col-md-6">
                     <label for="name" style="font-weight: 500;">Name</label>
                     <input type="text" name="name" required maxlength="50" class="form-control">
                  </div>
                  <div class="col-md-6">
                     <label for="email" style="font-weight: 500;">Email</label>
                     <input type="email" name="email" required maxlength="50" class="form-control">
                  </div>
                  <div class="col-md-6">
                     <label for="number" style="font-weight: 500;">Phone No</label>
                     <input type="tel" name="number" required maxlength="10" class="form-control">
                  </div>
                  <div class="col-md-12">
                     <label for="message" style="font-weight: 500;">Message</label>
                     <textarea name="message" class="form-control" required maxlength="1000" rows="5"></textarea>
                  </div>
                  <div class="col-md-12 mt-3">
                     <input type="submit" value="Submit" name="send" class="btn btn-primary">
                  </div>
               </div>
            </form>
         </div>
      </div>
   </section>



   <!-- contact section ends -->

   <section class="hotels mt-5" id="hotels">
      <div class="container mt-2">
         <div class="row">
            <?php
            // Fetch hotel details from the database
            $fetch_hotels = $conn->query("SELECT * FROM hotel_details");
            while ($hotel = $fetch_hotels->fetch(PDO::FETCH_ASSOC)) {
               $hotel_id = $hotel['hotel_id'];
               $hotel_name = $hotel['hotel_name'];
               $location = $hotel['location'];
               $description = $hotel['description'];
            ?>
               <div class="col-md-3 col-sm-6 shadow">
                  <div class="card card-block">
                     <img src="images/<?php echo $hotel_name; ?>.jpg" alt="Photo of sunset">
                     <h5 class="card-title m-3"><?php echo $hotel_name; ?></h5>
                     <p class="card-text m-3"><?php echo $description; ?></p>
                     <p class="card-location m-3">Location: <?php echo $location; ?></p>
                     <form action="cards.php" method="post">
                        <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
                        <button type="submit" class="btn btn-primary m-3">View Rooms</button>
                     </form>
                  </div>
               </div>
            <?php
            }
            ?>
         </div>
      </div>
   </section>




   <!-- reviews section starts  -->

   <section class="reviews" id="reviews">
      <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reviews</h2>

      <div class="container">

         <div class="swiper testimonials">
            <div class="swiper-wrapper mb-5">

               <?php
               // Prepare and execute the query
               $sql = "SELECT s.review_id, a.name, s.rating, s.comment
                    FROM site_reviews s
                    INNER JOIN account_details a ON s.user_id = a.id";
               $stmt = $conn->prepare($sql);
               $stmt->execute();

               // Fetch reviews
               $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

               // Display reviews
               foreach ($reviews as $row) {
                  $reviewId = $row['review_id'];
                  $username = $row['name'];
                  $rating = $row['rating'];
                  $comment = $row['comment'];
               ?>
                  <div class="swiper-slide bg-white p-4">
                     <div class="profiles d-flex align-items-center p-4">
                        <img src="" width="30px">
                        <h6 class="m-0 ms-2"><?php echo $username; ?></h6>
                     </div>
                     <p>
                        <?php echo $comment; ?>
                     </p>
                     <div class="rating">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                           if ($i <= $rating) {
                              echo '<i class="bi bi-star-fill text-warning"></i>';
                           } else {
                              echo '<i class="bi bi-star"></i>';
                           }
                        }
                        ?>
                     </div>
                  </div>
               <?php
               }
               ?>

            </div>
            <div class="swiper-pagination"></div>
         </div>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>
   <!-- reviews section ends  -->


   <script>
      var swiper = new Swiper(".testimonials", {
         effect: "coverflow",
         grabCursor: true,
         centeredSlides: true,
         slidesPerView: "3",
         loop: true,
         coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
         },
         pagination: {
            el: ".swiper-pagination",
         },
         autoplay: {
            delay: 2000,
            disableOnInteraction: false,
         },
         breakpoints: {
            320: {
               slidesPerView: 1,
            },
            640: {
               slidesPerView: 1,
            },
            768: {
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 3,
            },
         },
      });
      var swiper = new Swiper(".gallery-slider", {
         loop: true,
         effect: "coverflow",
         slidesPerView: "auto",
         centeredSlides: true,
         grabCursor: true,
         coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 100,
            modifier: 2,
            slideShadows: true,
         },
         pagination: {
            el: ".swiper-pagination",
         },
         autoplay: {
            delay: 1000,
            disableOnInteraction: false,
         },
      });
   </script>

   <script>
      function toggleCard(event) {
         const card = event.target.closest('.card');
         const cardBody = card.querySelector('.card-body');
         cardBody.classList.toggle('active');
      }
   </script>

   <?php include 'components/message.php'; ?>


</body>

</html>
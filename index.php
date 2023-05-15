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

   $id = create_unique_id();
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
      $warning_msg[] = 'message sent already!';
   } else {
      $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $number, $message]);
      $success_msg[] = 'message send successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

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
   </style>
</head>

<body class="bg-light">

   <?php include 'components/user_header.php'; ?>

   <!-- home section starts  -->

   <section class="home" id="home">

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

   <section class="about" id="about">

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
            <div class="card" style="width: 18rem;">
               <img src="images/roomm.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ ROOMS</h5>
               </div>
            </div>

            <div class="card" style="width: 18rem;">
               <img src="images/customer.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ Customers</h5>
               </div>
            </div>

            <div class="card" style="width: 18rem;">
               <img src="images/support.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ Staffs</h5>
               </div>
            </div>


            <div class="card" style="width: 18rem;">
               <img src="images/review.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">100+ Reviews</h5>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="card" style="width: 18rem;">
               <img src="images/roomm.jpg" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Food & Drinks</h5>
               </div>
            </div>

            <div class="card" style="width: 18rem;">
               <img src="images/pool.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Swimming</h5>
               </div>
            </div>

            <div class="card" style="width: 18rem;">
               <img src="images/support.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Beach View</h5>
               </div>
            </div>


            <div class="card" style="width: 18rem;">
               <img src="images/gym.png" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Gym<Span></Span></h5>
               </div>
            </div>
         </div>




      </div>
   </section>

   <!-- about section ends -->

   <!-- services section starts  -->

   <!-- <section class="services">

   <div class="box-container">

      <div class="box">
         <img src="images/icon-1.png" alt="">
         <h3>food & drinks</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon-2.png" alt="">
         <h3>outdoor dining</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon-3.png" alt="">
         <h3>beach view</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon-4.png" alt="">
         <h3>decorations</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon-5.png" alt="">
         <h3>swimming pool</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon-6.png" alt="">
         <h3>resort beach</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

   </div>

</section> -->

   <!-- services section ends -->

   <!-- reservation section starts  -->

   <section class="reservation" id="reservation">

      <form action="" method="post">
         <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" required class="input">
         </div>
         <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="email" maxlength="50" required class="input">
         </div>
         <div class="form-group">
            <label for="Phoneno" class="form-label">Phone No</label>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required class="input">
         </div>

         <div class="form-group">
            <label for="exampleFormControlSelect1"></label>Select Rooms</label>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
         <div class="form-group">
            <p>Check In<span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="form-group">
            <p>Check Out<span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>

         <div class="form-group">
            <p>Adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>

         <div class="form-group">
            <p>Childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
         <input type="submit" value="book now" name="book" class="btn text-white shadow-none custom-bg">
      </form>

      <!-- 
   <form action="" method="post"class>
      <h3>make a reservation</h3>
         <div class="mb-3"> 
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" required  class="input">
         </div>
         <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" name="email" maxlength="50" required  class="input">
  </div>
  <div class="mb-3">
    <label for="Phoneno" class="form-label">Phone No</label>
    <input type="number" name="number" maxlength="10" min="0" max="9999999999" required " class="input">
  </div>
        
         <div class=" mb-3 box">
            <p>Rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
         <div class="mb-3 box">
            <p>Check In<span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>Check Out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="mb-3 box">
            <p>Adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="mb-3 box">
            <p>Childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
      </div>
      <input type="submit" value="book now" name="book" class="btn text-white shadow-none custom-bg">
   </form> -->

   </section>

   <!-- reservation section ends -->

   <!-- gallery section starts  -->

   <section class="gallery" id="gallery">

      <div class="swiper gallery-slider">
         <div class="swiper-wrapper">
            <img src="images/gallery-img-1.jpg" class="swiper-slide" alt="">
            <img src="images/gallery-img-2.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-3.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-4.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-5.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-6.webp" class="swiper-slide" alt="">
         </div>
         <div class="swiper-pagination"></div>
      </div>

   </section>

   <!-- gallery section ends -->

   <!-- contact section starts  -->

   <section class="contact" id="contact">

      <div class="row bg-white shadow ">

         <form action="" method="post" class="mb-3 ms-5">

            <h3 class="bold mt-4">Send Us Message</h3>
            <div class="row align-items-end">
               <div>
                  <label class="form-label" style="font-weight:500;">Check-in</label>
                  <input type="date" class="form=control shadow-none">
               </div>
               <label for="name" style="font-weight:500;">Name</label>
               <input type="text" name="name" required maxlength="50" class="box">
               <label for="name" style="font-weight:500;">Email</label>
               <input type="email" name="email" required maxlength="50" class="box">
               <label for="phoneno" style="font-weight:500;">PhoneNo</label>
               <input type="number" name="number" required maxlength="10" min="0" max="9999999999" class="box">
               <label for="message" style="font-weight:500;">Message</label>
               <textarea name="message" class="box" required maxlength="1000" cols="30" rows="10"></textarea>
               <input type="submit" value="Submit" name="send" class="btn shadow-none custom-bg" style="font-weight:500;">

            </div>
         </form>

      </div>

   </section>

   <!-- contact section ends -->

   <!-- Hotel section starts -->
   <section class="hotels mt-5" id="hotels">
      <div class="container mt-2">
         <div class="row">
            <div class="col-md-3 col-sm-6 ">
               <div class="card card-block">
                  <img src="images/soaltee.jpg" alt="Photo of sunset">
                  <h5 class="card-title mt-3 mb-3">Soaltee Hotel</h5>
                  <p class="card-text">Featuring rooms with a private bathroom, Soaltee Hotel is located at Soalteemode, the bustling tourist hub of Kathmandu. </p>
                  <button onclick="window.location.href='cards.php';">
                     View Rooms
                  </button>
               </div>
            </div>
            <div class="col-md-3 col-sm-6">
               <div class="card card-block">
                  <img src="images/hyatt.jpg" alt="Photo of sunset">
                  <h5 class="card-title  mt-3 mb-3">Hyatt Hotel</h5>
                  <p class="card-text">Luxurious accommodations in Kathmandu is provided at Hyatt Hotel, it, spread over 12 acres of peaceful gardens.</p>
               </div>
            </div>
            <div class="col-md-3 col-sm-6">
               <div class="card card-block">

                  <img src="images/yak.jpg" alt="Photo of sunset">
                  <h5 class="card-title  mt-3 mb-3">Yak & Yeti Hotel</h5>
                  <p class="card-text">Well located in the center of Kathmandu, Yak & Yeti Hotel provides air-conditioned rooms, a shared lounge, free WiFi and a restaurant.</p>
               </div>
            </div>
            <div class="col-md-3 col-sm-6">
               <div class="card card-block">

                  <img src="images/malla.jpg" alt="Photo of sunset">
                  <h5 class="card-title  mt-3 mb-3">Malla Hotel</h5>
                  <p class="card-text">Conveniently located in Kathmandu, Malla Hotel provides air-conditioned rooms with free WiFi, free private parking and room service.</p>
               </div>
            </div>
         </div>
      </div>
   </section>


   <!-- reviews section starts  -->

   <section class="reviews" id="reviews">
      <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font ">Reviews</h2>

      <div class="container">

         <div class="swiper testimonials">
            <div class="swiper-wrapper mb-5">

               <div class="swiper-slide bg-white p-4">
                  <div class="profiles d-flex align-items-center p-4">
                     <img src="" width="30px">
                     <h6 class="m-0 ms-2">Random Users</h6>
                  </div>
                  <p>
                     Lorem ipsum dolor sit amet consectetur adipisicing elit.
                     Hic culpa adipisci nisi praesentium velit dolor veritatis consectetur.
                     Facilis dolorem quas expedita, impedit aliquam labore quaerat quod nam error sint atque.
                  </p>
                  <div class="rating">
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>


                  </div>
               </div>
               <div class="swiper-slide bg-white p-4">
                  <div class="profiles d-flex align-items-center p-4">
                     <img src="" width="30px">
                     <h6 class="m-0 ms-2">Random Users</h6>
                  </div>
                  <p>
                     Lorem ipsum dolor sit amet consectetur adipisicing elit.
                     Hic culpa adipisci nisi praesentium velit dolor veritatis consectetur.
                     Facilis dolorem quas expedita, impedit aliquam labore quaerat quod nam error sint atque.
                  </p>
                  <div class="rating">
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>


                  </div>
               </div>
               <div class="swiper-slide bg-white p-4">
                  <div class="profiles d-flex align-items-center mb-3">
                     <img src="" width="30px">
                     <h6 class="m-0 ms-2">Random Users</h6>
                  </div>
                  <p>
                     Lorem ipsum dolor sit amet consectetur adipisicing elit.
                     Hic culpa adipisci nisi praesentium velit dolor veritatis consectetur.
                     Facilis dolorem quas expedita, impedit aliquam labore quaerat quod nam error sint atque.
                  </p>
                  <div class="rating">
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>
                     <i class="bi bi-star-fill text-warning"></i>

                     
                  </div>
               </div>

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
            }

         }
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
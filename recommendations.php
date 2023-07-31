<!DOCTYPE html>
<html>

<head>
  <title>Room Recommendations</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <h2 class="text-center">Room Recommendations</h2>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Selected Preferences:</h5>
            <?php
            include_once 'components/connect.php';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $priceRange = $_POST['price_range'];
              list($priceMin, $priceMax) = explode('-', $priceRange);

              // Trim any leading or trailing spaces
              $priceMin = trim($priceMin);
              $priceMax = trim($priceMax);

              $roomAttributes = isset($_POST['attributes']) ? $_POST['attributes'] : [];
              $viewType = $_POST['view_type'];

              echo "<p><strong>Price Range:</strong> " . $priceRange . "</p>";
              echo "<p><strong>Room Attributes:</strong> " . implode(", ", $roomAttributes) . "</p>";
              echo "<p><strong>View Type:</strong> " . $viewType . "</p>";

              // Assuming you have already established a PDO database connection

              // Fetch the room details from the database
              $query = "SELECT * FROM hotel_room_details";
              $stmt = $conn->query($query);

              // Initialize an empty array to store the recommendations
              $recommendations = array();

              // Loop through the result set
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Create a new recommendation entry
                $recommendation = array(
                  'hotel_name' => '',
                  'hotel_room' => $row['room_id'],
                  'price' => $row['adult_price'],
                  'attributes' => array(),
                  'view_type' => $row['view_type']
                );

                // Retrieve the hotel name based on hotel_id
                $hotelQuery = "SELECT hotel_name FROM hotel_details WHERE hotel_id = :hotel_id";
                $hotelStmt = $conn->prepare($hotelQuery);
                $hotelStmt->bindValue(':hotel_id', $row['hotel_id']);
                $hotelStmt->execute();
                $hotelRow = $hotelStmt->fetch(PDO::FETCH_ASSOC);
                if ($hotelRow) {
                  $recommendation['hotel_name'] = $hotelRow['hotel_name'];
                }
                $attributes = array(
                  'wifi_available' => 'WiFi',
                  'parking_available' => 'Parking',
                  'breakfast_included' => 'Breakfast Included',
                  'room_service_available' => 'Room Service Available',
                  'gym_access' => 'Gym Access',
                  'swimming_pool_access' => 'Swimming Pool Access',
                  'spa_services' => 'Spa Services',
                  'laundry_services' => 'Laundry Services',
                  'tv_available' => 'TV Available',
                  'air_conditioning' => 'Air Conditioning',
                  'mini_bar_available' => 'Mini Bar Available',
                  'safe_deposit_box' => 'Safe Deposit Box',
                  'private_bathroom' => 'Private Bathroom',
                  'hairdryer_available' => 'Hairdryer Available',
                  'ironing_facilities' => 'Ironing Facilities',
                  'accessible' => 'accessible'
                );

                // Set the attributes based on the room details
                foreach ($attributes as $column => $attribute) {
                  if ($row[$column]) {
                    $recommendation['attributes'][] = $attribute;
                  }
                }

                // Add the recommendation to the array
                $recommendations[] = $recommendation;
              }

              // Filter recommendations based on user preferences
              $filteredRecommendations = array();

              foreach ($recommendations as $recommendation) {
                // Calculate similarity score based on selected attributes
                $similarity = 0;
                foreach ($roomAttributes as $attribute) {
                  if (in_array($attribute, $recommendation['attributes'])) {
                    $similarity++;
                  }
                }

                // Adjust similarity score based on price range
                if ($recommendation['price'] >= $priceMin && $recommendation['price'] <= $priceMax) {
                  $similarity += 2; // You can adjust the weight for price similarity here
                }

                // Adjust similarity score based on view type
                if ($viewType === $recommendation['view_type']) {
                  $similarity += 1; // You can adjust the weight for view type similarity here
                }

                // Add similarity score to the recommendation array
                $recommendation['similarity'] = $similarity;

                // Add the recommendation to the array
                $filteredRecommendations[] = $recommendation;
              }
              usort($filteredRecommendations, function ($a, $b) {
                return $b['similarity'] - $a['similarity'];
              });

              // Display the recommendations
              if (!empty($filteredRecommendations)) {
                echo '<div class="alert alert-success">Here are your recommendations:</div>';
                foreach ($filteredRecommendations as $recommendation) {
                  echo '<div class="card mb-3">';
                  echo '<div class="card-header"><strong>Hotel Name:</strong> ' . $recommendation['hotel_name'] . '</div>';
                  echo '<div class="card-body">';
                  echo '<p class="card-text"><strong>Hotel Room Number:</strong> ' . $recommendation['hotel_room'] . '</p>';
                  echo '<p class="card-text"><strong>Room Type:</strong> ' . $recommendation['view_type'] . '</p>';
                  // Fetch room name based on room_id
                  $roomNameQuery = "SELECT room_name FROM hotel_room_details WHERE room_id = :room_id";
                  $roomNameStmt = $conn->prepare($roomNameQuery);
                  $roomNameStmt->bindValue(':room_id', $recommendation['hotel_room']);
                  $roomNameStmt->execute();
                  $roomNameRow = $roomNameStmt->fetch(PDO::FETCH_ASSOC);
                  if ($roomNameRow) {
                    echo '<p class="card-text"><strong>Hotel Room Name:</strong> ' . $roomNameRow['room_name'] . '</p>';
                  }
                  // Generate URL for booking page
                  $bookingUrl = "http://localhost/Hotel-Room/booking.php?room_id=" . $recommendation['hotel_room'] . "&hotel_name=" . urlencode($recommendation['hotel_name']);

                  // Display the button with the generated URL
                  echo '<a href="' . $bookingUrl . '" class="btn btn-primary">Book Now</a>';

                  echo '</div>'; // Close card-body
                  echo '</div>'; // Close card
                }
              } else {
                echo '<div class="alert alert-warning">No recommendations found based on your preferences.</div>';
              }
            } else {
              echo '<div class="alert alert-danger">Invalid request.</div>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
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
                  'hotel_name' => $row['hotel_id'], // Retrieve the hotel name based on hotel_id
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
                  // Add more attributes here if needed
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

              // Print the generated recommendations array
              print_r($recommendations);

              // Filter recommendations based on user preferences
              $filteredRecommendations = array();

              foreach ($recommendations as $recommendation) {
                $matchingAttributes = array_intersect($roomAttributes, $recommendation['attributes']);
                if ($recommendation['price'] >= $priceMin && $recommendation['price'] <= $priceMax && $viewType === $recommendation['view_type'] && count($matchingAttributes) === count($attributes)) {
                  $filteredRecommendations[] = $recommendation;
                }
              }

              // Display the recommendations
              if (!empty($filteredRecommendations)) {
                echo '<div class="alert alert-success">Here are your recommendations:</div>';
                echo '<ul class="list-group">';
                foreach ($filteredRecommendations as $recommendation) {
                  echo '<li class="list-group-item">' . $recommendation['hotel_name'] . '</li>';
                }
                echo '</ul>';
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
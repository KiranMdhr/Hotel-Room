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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $priceRange = $_POST['price_range'];
              $roomAttributes = isset($_POST['attributes']) ? $_POST['attributes'] : [];
              $viewType = $_POST['view_type'];

              echo "<p><strong>Price Range:</strong> " . $priceRange . "</p>";
              echo "<p><strong>Room Attributes:</strong> " . implode(", ", $roomAttributes) . "</p>";
              echo "<p><strong>View Type:</strong> " . $viewType . "</p>";

              // Assuming you have already established a PDO database connection

              // Fetch the room details from the database
              $query = "SELECT * FROM hotel_room_details";
              $stmt = $pdo->query($query);

              // Initialize an empty array to store the recommendations
              $recommendations = array();

              // Loop through the result set
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Create a new recommendation entry
                $recommendation = array(
                  'hotel_name' => '', // Retrieve the hotel name based on hotel_id
                  'price_range' => '$' . $row['adult_price'] . ' - $' . $row['kid_price'],
                  'attributes' => array(),
                  'view_type' => $row['view_type']
                );

                // Retrieve the hotel name based on hotel_id
                $hotelQuery = "SELECT hotel_name FROM hotel_details WHERE hotel_id = :hotel_id";
                $hotelStmt = $pdo->prepare($hotelQuery);
                $hotelStmt->bindValue(':hotel_id', $row['hotel_id']);
                $hotelStmt->execute();
                $hotelRow = $hotelStmt->fetch(PDO::FETCH_ASSOC);
                if ($hotelRow) {
                  $recommendation['hotel_name'] = $hotelRow['hotel_name'];
                }

                // Set the attributes based on the room details
                if ($row['wifi_available']) {
                  $recommendation['attributes'][] = 'WiFi';
                }
                if ($row['parking_available']) {
                  $recommendation['attributes'][] = 'Parking';
                }
                if ($row['breakfast_included']) {
                  $recommendation['attributes'][] = 'Breakfast Included';
                }
                if ($row['swimming_pool_access']) {
                  $recommendation['attributes'][] = 'Swimming Pool Access';
                }
                if ($row['gym_access']) {
                  $recommendation['attributes'][] = 'Gym Access';
                }

                // Add the recommendation to the array
                $recommendations[] = $recommendation;
              }

              // Print the generated recommendations array
              print_r($recommendations);

              // Filter recommendations based on user preferences
              $filteredRecommendations = array();

              foreach ($recommendations as $recommendation) {
                if ($recommendation['price_range'] === $priceRange && in_array($viewType, $recommendation['view_type']) && count(array_intersect($attributes, $recommendation['attributes'])) === count($attributes)) {
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
<!DOCTYPE html>
<html>

<head>
  <title>Hotel Room Preferences</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="row justify-content-center d-flex align-items-center">
      <div class="col-6">
        <h2 class="text-center">Hotel Room Preferences</h2>
        <form action="recommendations.php" method="post">
          <div class="form-group">
            <label for="price_range">Price Range:</label>
            <select class="form-control" name="price_range" id="price_range">
              <option value="0-100">$0 - $50</option>
              <option value="101-200">$101 - $200</option>
              <option value="201-300">$201 - $300</option>
              <option value="301-400">$301 - $400</option>
              <option value="401-500">$401 - $500</option>
              <option value="501-600">$501 - $600</option>
              <option value="601-700">$601 - $700</option>
            </select>
          </div>
          <div class="form-group">
            <label for="attributes">Room Attributes:</label><br>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="WiFi" id="wifi">
              <label class="form-check-label" for="wifi">WiFi</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="Parking" id="parking">
              <label class="form-check-label" for="parking">Parking</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="Breakfast Included" id="breakfast">
              <label class="form-check-label" for="breakfast">Breakfast Included</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="Room Service Available" id="room_service">
              <label class="form-check-label" for="room_service">Room Service Available</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="Gym Access" id="gym_access">
              <label class="form-check-label" for="gym_access">Gym Access</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="Swimming Pool Access" id="swimming_pool">
              <label class="form-check-label" for="swimming_pool">Swimming Pool Access</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="spa_services" id="spa_services">
              <label class="form-check-label" for="spa_services">Spa Services</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="laundry_services" id="laundry_services">
              <label class="form-check-label" for="laundry_services">Laundry Services</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="tv" id="tv">
              <label class="form-check-label" for="tv">TV Available</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="air_conditioning" id="air_conditioning">
              <label class="form-check-label" for="air_conditioning">Air Conditioning</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="mini_bar" id="mini_bar">
              <label class="form-check-label" for="mini_bar">Mini Bar Available</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="safe_deposit_box" id="safe_deposit_box">
              <label class="form-check-label" for="safe_deposit_box">Safe Deposit Box</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="private_bathroom" id="private_bathroom">
              <label class="form-check-label" for="private_bathroom">Private Bathroom</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="hairdryer" id="hairdryer">
              <label class="form-check-label" for="hairdryer">Hairdryer Available</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="ironing_facilities" id="ironing_facilities">
              <label class="form-check-label" for="ironing_facilities">Ironing Facilities</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="attributes[]" value="accessible" id="accessible">
              <label class="form-check-label" for="accessible">Accessibility</label>
            </div>

          </div>
          <div class="form-group">
            <label for="view_type">View Type:</label><br>
            <?php
            include_once 'components/connect.php';
            // Assuming you have a database connection established using PDO

            // Query to fetch distinct view types from the hotel_room_details table
            $query = "SELECT DISTINCT view_type FROM hotel_room_details";

            // Prepare and execute the query
            $stmt = $conn->query($query);

            // Check if the query was successful
            if ($stmt) {
              // Initialize an empty array to store the view types
              $viewTypes = array();

              // Fetch each row from the result set
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Add the view type to the array
                $viewTypes[] = $row['view_type'];
              }
              foreach ($viewTypes as $key => $viewType) {
                $checked = ($key === 0) ? 'checked' : ''; // Add 'checked' if it's the first iteration, otherwise empty string
                echo '<div class="form-check">
                        <input class="form-check-input" type="radio" name="view_type" value="' . $viewType . '" id="' . $viewType . '" ' . $checked . '>
                        <label class="form-check-label" for="' . $viewType . '">' . $viewType . '</label>
                      </div>';
              }
              
            } else {
              // Handle the query error
              echo "Error: " . $conn->errorInfo()[2];
            }


            ?>

          </div>
          <div class="text-center">
            <input class="btn btn-primary" type="submit" value="Submit">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
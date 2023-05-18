<?php
    session_start();
    if (isset($_SESSION['name'])) {
        // Redirect to index.php
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login/Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="design.css"> -->
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <?php
    include 'components/connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if it's a registration form submission
        if (isset($_POST['register'])) {
            // Process registration data and store in the database
            $fullName = $_POST['fullName'];
            $username = $_POST['registerUsername'];
            $password = $_POST['registerPassword'];

            $sql = "INSERT INTO account_details (name, username, password, account_type) VALUES (:name, :username, :password, 'user')";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $fullName);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                $userId = $conn->lastInsertId(); // Get the last inserted ID
                $_SESSION['id'] = $userId;
                $_SESSION['name'] = $username;
                header('Location: index.php');
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $stmt->errorInfo();
            }
        }
    }

        // Check if it's a login form submission
        if (isset($_POST['login'])) {
            // Process login data and validate against the database
            $username = $_POST['loginUsername'];
            $password = $_POST['loginPassword'];

                $sql = "SELECT * FROM account_details WHERE username = :username AND password = :password";

                // Prepare the statement
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $_SESSION['id'] = $user['id']; // Set the session variable 'id'
                    $_SESSION['name'] = $username;
                    header('Location: index.php');
                    exit();
                } else {
                    echo "Invalid username or password";
                }
        }
    ?>

    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <center>
                            <a class="fw-bold fs-3 h-font p-5" href="index.php"><img src="images/logo.svg" alt="Logo" style="width:20vh;"></a>
                        </center>
                        <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log" />
                        <label for="reg-log"></label>
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Log In</h4>
                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                                <div class="form-group">
                                                    <input type="text" class="form-style" placeholder="Username" name="loginUsername">
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" class="form-style" placeholder="Password" name="loginPassword">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" class="btn mt-4" name="login">Login</button>
                                            </form>
                                            <p class="mb-0 mt-4 text-center"><a href="index.php#contact" class="link">Forgot your password?</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-3 pb-3">Sign Up</h4>
                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                                <div class="form-group">
                                                    <input type="text" class="form-style" placeholder="Full Name" name="fullName">
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>

                                                <div class="form-group mt-2">
                                                    <input type="text" class="form-style" placeholder="Username" name="registerUsername">
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" class="form-style" placeholder="Password" name="registerPassword">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" class="btn mt-4" name="register">Register</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
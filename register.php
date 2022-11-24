<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary633 = $username = $password ="";
$name_err = $address_err = $salary633_err = $username_err =$password_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary633 = trim($_POST["salary633"]);
    if(empty($input_salary633)){
        $salary633_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary633)){
        $salary633_err = "Please enter a positive integer value.";
    } else{
        $salary633 = $input_salary633;
    }

    // Validate username
if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    $username_err = "Username can only contain letters, numbers, and underscores.";
} else{
    // Prepare a select statement
    $sql = "SELECT id FROM employees WHERE username = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Validate confirm password
if(empty(trim($_POST["password"]))){
    $password_err = "Please confirm password.";     
} else{
    $password = trim($_POST["password"]);
    if(empty($password_err) && ($password != $password)){
        $password_err = "Password did not match.";
    }
}
 
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary633_err)&& empty($username_err)&& empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, address, salary633, username, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiss", $param_name, $param_address, $param_salary633, $param_username, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary633 = $salary633;
            $param_username = $username;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: welcome.html");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Regna Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Poppins:300,400,500,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style1.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Regna - v4.9.1
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <a href="index.html"><img src="assets/img/logo.png" alt="width="150" height="180"></a>
</head>

<body>

  <!-- ======= Header ======= -->
  <nav>
    <ul>
      <li>
        <a href="index.html">Home</a>
      </li>
      <li>
        <a href="about.html">About</a>
      </li>
      <li>
        <a href="careers.html">Careers</a>
      </li>
      <li>
        <a href="orderonline.php">Order online</a>
      </li>
      <li>
        <a href="contact us.html">contact us</a>
      </li>
      <li>
        <a href="register.php">Register</a>
      </li>
    </ul>
  </nav>
 

    <!-- ======= Facts Section ======= -->
    <section id="facts">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                
                    <h2 class="mt-5">Create Record</h2>
                    
                    <p>Please fill this form and submit to add employee record to the database.</p>
                   
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"id="form">
                    
                   
                        <div class="form-group">
                          <p1>Name</p1>
                            <center><input type="text" style="width:300px;height:30px"name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>"></center>
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <p1>Address</p1>
                            <center><textarea name="address"style="width:300px;height:30px" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea></center>
                           <span class="invalid-feedback"><?php echo $address_err;?></span></center>
                        </div>
                        <div class="form-group">
                            <p1>Salary633</p1>
                            <center> <input type="text" style="width:300px;height:30px"name="salary633" class="form-control <?php echo (!empty($salary633_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary633; ?>"></center>
                            <span class="invalid-feedback"><?php echo $salary633_err;?></span>
                        </div>
                        <div class="form-group">
                            <p1>Username</p1>
                            <center><input type="text" style="width:300px;height:30px"name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"></center>
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <p1>Password</p1>
                            <center><input type="text" style="width:300px;height:30px"name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>"></center>
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit"style="width:100px;height:60px">
                        
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                    
                </div>
            </div>        
        </div>
    </div>

    </section><!-- End Facts Section -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">

      </div>
    </div>

    <div class="container">
      <div class="copyright">
        English name:Carroll<br>
            Chinese name:WangHuiyu<br>
            class name:20IT1<br>
            Student id:203190631
        &copy; Copyright <strong>Regna</strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!--
        All the links in the footer should remain intact.
        You can delete the links only if you purchased the pro version.
        Licensing information: https://bootstrapmade.com/license/
        Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Regna
      -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
<?php
session_start();
if (isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body {
            background-color: #0a192f;
            color: #ccd6f6;
            font-family: 'Courier New', monospace;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .container {
            background-color: #112240;
            border: 1px solid #64ffda;
            padding: 2rem;
            max-width: 600px;
            box-shadow: 0 0 20px rgba(100, 255, 218, 0.1);
        }
        .form-control {
            background-color: #0a192f;
            border: 1px solid #1e2a4a;
            color: #ccd6f6;
            border-radius: 0;
            margin-bottom: 1.5rem;
            padding: 0.75rem;
        }
        .form-control:focus {
            background-color: #0a192f;
            color: #ccd6f6;
            border-color: #64ffda;
            box-shadow: 0 0 0 0.25rem rgba(100, 255, 218, 0.25);
        }
        .btn-primary {
            background-color: transparent;
            border: 1px solid #64ffda;
            color: #64ffda;
            border-radius: 0;
            padding: 0.5rem 2rem;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #64ffda;
            color: #0a192f;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 0;
            margin-bottom: 1rem;
        }
        .alert-danger {
            background-color: rgba(255, 85, 85, 0.1);
            border: 1px solid #ff5555;
            color: #ff5555;
        }
        .alert-success {
            background-color: rgba(100, 255, 218, 0.1);
            border: 1px solid #64ffda;
            color: #64ffda;
        }
        a {
            color: #64ffda;
            text-decoration: none;
            transition: all 0.3s;
        }
        a:hover {
            color: #ccd6f6;
            text-decoration: underline;
        }
        h1 {
            color: #64ffda;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 1.8rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="bi bi-person-plus"></i> SYSTEM REGISTRATION</h1>
        <?php
        if (isset($_POST["submit"])) { 
           
           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $address = $_POST["address"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
          
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM person WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle'></i> $error</div>";
            }
           }else{
            
            $sql = "INSERT INTO person (full_name, email, address, password) VALUES ( ?, ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"ssss",$fullName, $email, $address, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'><i class='bi bi-check-circle'></i> You are registered successfully.</div>";
                session_start();
                $_SESSION["user"] = $user["full_name"];
                header("Location: index.php");
                die();
            }else{
                die("Something went wrong");
            }
           }
        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="address" class="form-control" name="address" placeholder="Address:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <div class="form-btn text-center">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div class="text-center mt-4">
            <p>Already Registered <a href="login.php">Login Here</a></p>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
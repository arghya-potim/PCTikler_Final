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
    <title>System Login</title>
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
            width: 100%;
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
        <h1><i class="bi bi-terminal"></i> SYSTEM ACCESS</h1>
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM person WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = $user["full_name"];
                    $_SESSION["email"] = $user["email"]; 
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div class='alert alert-danger'><i class='bi bi-shield-lock'></i> Password does not match</div>";
                }
            }else{
                echo "<div class='alert alert-danger'><i class='bi bi-envelope-x'></i> Email does not match</div>";
            }
        }
        ?>
      <form action="login.php" method="post">
        <div class="form-group">
            <input type="email" placeholder="Enter Email:" name="email" class="form-control">
        </div>
        <div class="form-group">
            <input type="password" placeholder="Enter Password:" name="password" class="form-control">
        </div>
        <div class="form-btn">
            <input type="submit" value="Authenticate" name="login" class="btn btn-primary">
        </div>
      </form>
     <div class="text-center mt-3"><p>Not registered yet? <a href="registration.php">Create Account</a></p></div>
    </div>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
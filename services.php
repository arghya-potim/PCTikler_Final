<?php
session_start(); 
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Access Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body {
            background-color: #0a192f;
            color: #ccd6f6;
            font-family: 'Courier New', monospace;
            height: 100vh;
        }
        .sidebar {
            background-color: #112240;
            border-right: 1px solid #1e2a4a;
        }
        .btn-outline-primary {
            color: #64ffda;
            border-color: #64ffda;
            border-radius: 0;
            text-align: left;
            transition: all 0.3s;
        }
        .btn-outline-primary:hover {
            background-color: #64ffda;
            color:rgb(21, 149, 199);
            transform: translateX(5px);
        }
        .btn-outline-danger {
            color: #ff5555;
            border-color: #ff5555;
            border-radius: 0;
            text-align: left;
        }
        .btn-outline-danger:hover {
            background-color: #ff5555;
            color: #0a192f;
        }
        h1, h5 {
            color: #64ffda;
        }
        .nav-heading {
            border-bottom: 1px solid #64ffda;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .card {
            background-color: #112240;
            border: 1px solid #64ffda;
            border-radius: 0;
            box-shadow: 0 0 20px rgba(100, 255, 218, 0.1);
        }
        .table {
            color: #ccd6f6;
            border-color: #64ffda;
        }
        .table th {
            border-color: #64ffda;
            color: #64ffda;
        }
        .table td {
            border-color: #1e2a4a;
            vertical-align: middle;
        }
        .btn-buy {
            background-color: #64ffda;
            color: #0a192f;
            border: none;
            border-radius: 0;
            padding: 5px 15px;
            transition: all 0.3s;
        }
        .btn-buy:hover {
            background-color: #52e0c4;
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="vh-100">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-3 sidebar p-4 d-flex flex-column gap-3">
                <h5 class="nav-heading">PCTIKLER CONTROL PANEL</h5>
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="bi bi-cloud"></i>  DASHBOARD
                </a>
                <a href="services.php" class="btn btn-outline-primary">
                    <i class="bi bi-cloud"></i>  Services
                </a>
                
                <a href="sell.php" class="btn btn-outline-primary">
                    <i class="bi bi-cpu"></i> Sell Used Products
                </a>
                <a href="hire.php" class="btn btn-outline-primary">
                    <i class="bi bi-code-square"></i> Hire to Build
                </a>
                <a href="expo.php" class="btn btn-outline-primary">
                    <i class="bi bi-easel"></i> Tech Expo
                </a>

                <a href="component.php" class="btn btn-outline-primary">
                    <i class="bi bi-lightning-charge"></i> Components
                </a>
                <a href="merchandise.php" class="btn btn-outline-primary">
                    <i class="bi bi-lightning-charge"></i> Merchandise
                </a>
                <a href="logout.php" class="btn btn-outline-danger ">
                    <i class="bi bi-power"></i> System Logout
                </a>
            </div>
 
            <div class="col-md-9 page-content">
                <div class="card shadow p-4">
                    <h1 class="mb-4 text-center"><i class="bi bi-terminal"></i> Services</h1>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Description</th>
                                <th>Base Price</th>
                                <th>Service Type</th>
                                <th>Service Man ID</th>
                                <th>Rating</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once "database.php";
                            $sql = "SELECT s.*, sm.serviceman_id, sm.rating 
                                    FROM Services s
                                    JOIN ServiceMan sm ON sm.serviceman_id = s.service_id"; 
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['description']}</td>";
                                echo "<td>\${$row['base_price']}</td>";
                                echo "<td>{$row['service_type']}</td>";
                                echo "<td>{$row['serviceman_id']}</td>";
                                echo "<td>{$row['rating']}</td>"; 
                                echo "<td>
                                        <form method='POST'>
                                            <input type='hidden' name='service_id' value='{$row['service_id']}'>
                                            <input type='hidden' name='serviceman_id' value='{$row['serviceman_id']}'>
                                            <button type='submit' name='confirm_service' class='btn btn-primary'>Confirm</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_service'])) {
                        $serviceId = $_POST['service_id'];
                        $servicemanId = $_POST['serviceman_id'];
                        $customerEmail = $_SESSION['email'];

                        $historySql = "INSERT INTO ServiceHistory(customer_email, serviceman_id) VALUES (?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $historySql)) {
                            mysqli_stmt_bind_param($stmt, "si", $customerEmail, $servicemanId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

                        $updateRatingQuery = "UPDATE ServiceMan SET rating = rating + 1 WHERE serviceman_id = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $updateRatingQuery)) {
                            mysqli_stmt_bind_param($stmt, "i", $servicemanId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

                        echo "<script>alert('Service confirmed successfully!');</script>";
                        echo "<script>window.location.href = 'services.php';</script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
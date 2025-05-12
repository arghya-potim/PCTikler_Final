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


            <div class="col-md-9 d-flex justify-content-center align-items-center">
                <div class="card shadow p-5 text-center" style="min-width: 300px;">
                    <h1 class="mb-4"><i class="bi bi-terminal"></i> Tech Expo</h1>
                    <?php
            
                    $conn = new mysqli("localhost", "root", "", "pctikler");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    
                    $sql = "SELECT * FROM expo";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Expo ID</th>
                                    <th>Expo Name</th>
                                    <th>Location</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['expo_ID']; ?></td>
                                        <td><?php echo $row['expo_name']; ?></td>
                                        <td><?php echo $row['location']; ?></td>
                                        <td><?php echo $row['start_date']; ?></td>
                                        <td><?php echo $row['end_date']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td>
                                            <form method="POST" action="expo.php">
                                                <input type="hidden" name="expo_id" value="<?php echo $row['expo_ID']; ?>">
                                                <button type="submit" name="participate" class="btn btn-primary">Participate</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No expos available at the moment.</p>
                    <?php endif; ?>

                    <?php

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['participate'])) {
                        $expo_id = $_POST['expo_id'];
                        $customer_email = $_SESSION["email"];
                       


                        $check_sql = "SELECT * FROM expo_participation_history WHERE expo_ID = ? AND customer_email = ?";
                        $stmt = $conn->prepare($check_sql);
                        $stmt->bind_param("is", $expo_id, $customer_email);
                        $stmt->execute();
                        $check_result = $stmt->get_result();

                        if ($check_result->num_rows > 0) {
                            echo "<script>alert('You have already participated in this expo.');</script>";
                        } else {

                            $insert_sql = "INSERT INTO expo_participation_history (expo_ID, customer_email) VALUES (?, ?)";
                            $stmt = $conn->prepare($insert_sql);
                            $stmt->bind_param("is", $expo_id, $customer_email);
                            if ($stmt->execute()) {
                                echo "<script>alert('Participation confirmed successfully!');</script>";
                                echo "<script>window.location.href = 'expo.php';</script>";
                            } else {
                                echo "<script>alert('Error occurred while confirming participation.');</script>";
                            }
                        }
                    }


                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
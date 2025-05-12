<?php
session_start(); 
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION["email"];
$userName = $_SESSION["user"];

require_once "database.php";
$sql = "SELECT customer_type, points FROM person WHERE email = ?";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $customerType, $points);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$buyComponentsQuery = "SELECT * FROM Buy_Components WHERE customer_email = ? ORDER BY purchase_date DESC LIMIT 5";
$stmt = $conn->prepare($buyComponentsQuery);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$buyComponentsResult = $stmt->get_result();


$buyMerchandiseQuery = "SELECT * FROM buy_merchandise WHERE customer_email = ? ORDER BY purchase_date DESC LIMIT 5";
$stmt = $conn->prepare($buyMerchandiseQuery);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$buyMerchandiseResult = $stmt->get_result();


$serviceHistoryQuery = "SELECT * FROM ServiceHistory WHERE customer_email = ? ORDER BY service_date DESC LIMIT 5";
$stmt = $conn->prepare($serviceHistoryQuery);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$serviceHistoryResult = $stmt->get_result();

$expoHistoryQuery = "SELECT * FROM expo_participation_history WHERE customer_email = ? ORDER BY expo_ID DESC LIMIT 5";
$stmt = $conn->prepare($expoHistoryQuery);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$expoHistoryResult = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Control Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
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
                <!-- <a href="hot.php" class="btn btn-outline-primary">
                    <i class="bi bi-lightning-charge"></i> Hot Deals
                </a> -->
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

        
            <div class="col-md-9 p-4">
                <?php
                
                $sql = "SELECT user_type, customer_type, points FROM person WHERE email = ?";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $userEmail);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $userType, $customerType, $points);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);
                }

                
                if ($userType === 'customer' && $points >= 100 && $customerType !== 'gold') {
                    $updateRankSql = "UPDATE person SET customer_type = 'gold' WHERE email = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $updateRankSql)) {
                        mysqli_stmt_bind_param($stmt, "s", $userEmail);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        $customerType = 'gold'; 
                    }
                }
                ?>
                <div class="card shadow-lg p-5 text-center">
                    <h1 class="mb-3">SYSTEM DASHBOARD</h1>
                    <h4 style="color: #64ffda; font-size: 1.5rem;">Welcome to PCTikler, <?php echo htmlspecialchars($userName); ?>!</h4> 
                    <?php if ($userType === 'service_man'): ?>
                        <p style="color: #64ffda; font-size: 1.2rem;">User Type: <strong>Service Man</strong></p>
                    <?php else: ?>
                        <p style="color: #64ffda; font-size: 1.2rem;">Points: <strong><?php echo htmlspecialchars($points); ?></strong></p>
                        <p style="color: #64ffda; font-size: 1.2rem;">Customer Rank: <strong><?php echo htmlspecialchars($customerType); ?></strong></p>
                    <?php endif; ?>
                </div>

                <div class="container mt-5">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Your Recent Purchases (Components)</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Component ID</th>
                                        <th>Purchase Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $buyComponentsResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['customer_id']; ?></td>
                                            <td><?php echo $row['component_id']; ?></td>
                                            <td><?php echo $row['purchase_date']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Your Recent Purchases (Merchandise)</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Merchandise ID</th>
                                        <th>Purchase Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $buyMerchandiseResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['customer_id']; ?></td>
                                            <td><?php echo $row['merchandise_id']; ?></td>
                                            <td><?php echo $row['purchase_date']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Your Recent Service History</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>History ID</th>
                                        <th>Serviceman ID</th>
                                        
                                        <th>Service Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $serviceHistoryResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['history_id']; ?></td>
                                            <td><?php echo $row['serviceman_id']; ?></td>
                                            <td><?php echo $row['service_date']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Your Recent Expo Participation</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Expo ID</th>
                                        <th>Customer Email</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $expoHistoryResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['expo_ID']; ?></td>
                                            <td><?php echo $row['customer_email']; ?></td>
                                            <td><?php echo $row['Participation_date']?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
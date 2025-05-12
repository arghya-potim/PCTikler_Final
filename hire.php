<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION["email"];

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

if ($customerType !== 'gold') {
    echo "<script>alert('Only a Gold ranked customer can access this page. Please go back.');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
} 

$query = "SELECT h.hire_id, h.hire_date, h.hire_cost, h.serviceman_id, sm.rating 
          FROM hire_to_build h
          JOIN ServiceMan sm ON h.serviceman_id = sm.serviceman_id";
$result = $conn->query($query);

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

            <div class="col-md-9 p-4">
                <div class="card shadow p-4">
                    <h1 class="mb-4 text-center"><i class="bi bi-terminal"></i> Hire to Build Projects</h1>
                    <?php if ($result->num_rows > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Hire ID</th>
                                    <th>Hire Date</th>
                                    <th>Hire Cost</th>
                                    <th>Serviceman ID</th>
                                    <th>Serviceman Rating</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['hire_id']; ?></td>
                                        <td><?php echo $row['hire_date']; ?></td>
                                        <td>$<?php echo $row['hire_cost']; ?></td>
                                        <td><?php echo $row['serviceman_id']; ?></td>
                                        <td><?php echo $row['rating']; ?></td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="hire_id" value="<?php echo $row['hire_id']; ?>">
                                                <button type="submit" name="confirm_hire" class="btn btn-buy">Confirm Hire</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No hire-to-build projects available at the moment.</p>
                    <?php endif; ?>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_hire'])) {
                        $hireId = $_POST['hire_id'];
                        $customerEmail = $_SESSION['email'];

                        $hireQuery = "SELECT serviceman_id FROM hire_to_build WHERE hire_id = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $hireQuery)) {
                            mysqli_stmt_bind_param($stmt, "i", $hireId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_bind_result($stmt, $servicemanId);
                            mysqli_stmt_fetch($stmt);
                            mysqli_stmt_close($stmt);
                        }

                        $historyQuery = "INSERT INTO hire_history (customer_email, serviceman_id) VALUES (?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $historyQuery)) {
                            mysqli_stmt_bind_param($stmt, "si", $customerEmail,  $servicemanId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

                        $updateRatingQuery = "UPDATE ServiceMan SET rating = rating + 2 WHERE serviceman_id = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $updateRatingQuery)) {
                            mysqli_stmt_bind_param($stmt, "i", $servicemanId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

                        echo "<script>alert('Hire confirmed successfully!');</script>";
                        echo "<script>window.location.href = 'hire.php';</script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
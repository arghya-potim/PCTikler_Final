<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
} else {
    $logged_in_email = $_SESSION["email"];
}


$conn = new mysqli('localhost', 'root', '', 'pctikler');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $description = $_POST['description'];
    $sale_price = $_POST['sale_price'];
    $conditions = $_POST['conditions'];
    $seller_email = $_POST['seller_email'];

    $stmt = $conn->prepare("SELECT personID FROM person WHERE email = ?");
    $stmt->bind_param("s", $logged_in_email);
    $stmt->execute();
    $stmt->bind_result($customer_id);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO Sell_Used_Products (customer_id, description, sale_price, conditions, seller_email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $customer_id, $description, $sale_price, $conditions, $logged_in_email);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Product Added successfully!');</script>";
    echo "<script>window.location.href = 'sell.php';</script>";
    exit();
}


if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $sale_id = $_GET['remove'];

    $stmt = $conn->prepare("SELECT personID FROM person WHERE email = ?");
    $stmt->bind_param("s", $logged_in_email);
    $stmt->execute();
    $stmt->bind_result($customer_id);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM Sell_Used_Products WHERE sale_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $sale_id, $customer_id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['buy']) && is_numeric($_GET['buy'])) {
    $sale_id = $_GET['buy'];

    $stmt = $conn->prepare("SELECT seller_email FROM Sell_Used_Products WHERE sale_id = ?");
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();
    $stmt->bind_result($seller_email);
    $stmt->fetch();
    $stmt->close();

    if ($seller_email != $logged_in_email) {
        $stmt = $conn->prepare("INSERT INTO used_product_sell_history (seller_email, buyer_email) VALUES (?, ?)");
        $stmt->bind_param("ss", $seller_email, $logged_in_email);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Sell_Used_Products WHERE sale_id = ?");
        $stmt->bind_param("i", $sale_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Purchase confirmed successfully!');</script>";
        echo "<script>window.location.href = 'sell.php';</script>";
    }
}


$result = $conn->query("SELECT * FROM Sell_Used_Products");
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
                    <h1 class="mb-4"><i class="bi bi-terminal"></i> Sell Used Products</h1>
                    <h5 class="text-muted">Logged in as: <?php echo $logged_in_email; ?></h5>
                    <form method="POST" class="mb-4">
                        <textarea name="description" class="form-control mb-2" placeholder="Product Description" required></textarea>
                        <input type="number" name="sale_price" class="form-control mb-2" placeholder="Sale Price" step="0.01" required>
                        <select name="conditions" class="form-control mb-2" required>
                            <option value="" disabled selected>Select Condition</option>
                            <option value="very bad condition">Very Bad condition</option>
                            <option value="bad condition">Bad condition</option>
                            <option value="good condition">Good condition</option>
                            <option value="better condition">Better condition</option>
                            <option value="best condition">Best condition</option>
                        </select>
                        <button type="submit" name="add_product" class="btn btn-outline-primary">Add Product</button>
                    </form>

                    <h5>Available Products</h5>
                    <ul class="list-group">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($row['description']); ?> - $<?php echo $row['sale_price']; ?> (<?php echo $row['conditions']; ?>)
                                <span class="text-muted">[Owner: <?php echo $row['seller_email']; ?>]</span>
                                <div>
                                    <?php if ($row['seller_email'] == $logged_in_email): ?>
                                        <a href="?remove=<?php echo $row['sale_id']; ?>" class="btn btn-outline-danger btn-sm">Remove</a>
                                    <?php else: ?>
                                        <a href="?buy=<?php echo $row['sale_id']; ?>" class="btn btn-outline-success btn-sm">Buy</a>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>
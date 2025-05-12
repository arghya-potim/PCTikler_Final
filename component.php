<?php
session_start();
require_once "database.php";
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION["email"];

$sql = "SELECT user_type, personID FROM person WHERE email = ?";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $customerType, $customerId);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if ($customerType !== 'customer' && $customerType !== 'service_man') {
    echo "<script>alert('You do not have access to this page.');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

$query = "SELECT * FROM Components";
$result = $conn->query($query);

if ($customerType === 'service_man') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_component'])) {
            
            $price = $_POST['price'];
            $stockQuantity = $_POST['stock_quantity'];
            $specifications = $_POST['specifications'];
            $compatibility = $_POST['compatibility'];
            $discount = $_POST['discount'];
            $warrantyYears = $_POST['warranty_years'];

            $addQuery = "INSERT INTO Components (price, stock_quantity, specifications, compatibility, discount, warrenty_years) VALUES ( ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $addQuery)) {
                mysqli_stmt_bind_param($stmt, "dissii", $price, $stockQuantity, $specifications, $compatibility, $discount, $warrantyYears);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            echo "<script>alert('Changes confirmed successfully!');</script>";
            echo "<script>window.location.href = 'component.php';</script>";
        } elseif (isset($_POST['restock_component'])) {
            
            $componentId = $_POST['component_id'];
            $restockQuantity = $_POST['restock_quantity'];

            $restockQuery = "UPDATE Components SET stock_quantity = stock_quantity + ? WHERE component_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $restockQuery)) {
                mysqli_stmt_bind_param($stmt, "ii", $restockQuantity, $componentId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            echo "<script>alert('Changes confirmed successfully!');</script>";
            echo "<script>window.location.href = 'component.php';</script>";
        } elseif (isset($_POST['update_discount'])) {
          
            $componentId = $_POST['component_id'];
            $newDiscount = $_POST['new_discount'];

            $updateDiscountQuery = "UPDATE Components SET discount = ? WHERE component_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $updateDiscountQuery)) {
                mysqli_stmt_bind_param($stmt, "ii", $newDiscount, $componentId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            echo "<script>alert('Changes confirmed successfully!');</script>";
            echo "<script>window.location.href = 'component.php';</script>";
        } elseif (isset($_POST['remove_component'])) {
            
            $componentId = $_POST['component_id'];

            $removeQuery = "DELETE FROM Components WHERE component_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $removeQuery)) {
                mysqli_stmt_bind_param($stmt, "i", $componentId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            echo "<script>alert('Changes confirmed successfully!');</script>";
            echo "<script>window.location.href = 'component.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Components</title>
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
                    <h1 class="mb-4 text-center"><i class="bi bi-lightning-charge"></i> Components</h1>
                    <?php if ($customerType === 'service_man'): ?>
                        <div class="mb-4">
                            <h3>Add New Component</h3>
                            <form method="POST" class='mt-4'>
                                <input type="number" step="0.01" name="price" placeholder="Price" class='form-control mb-2' required>
                                <input type="number" name="stock_quantity" placeholder="Stock Quantity" class='form-control mb-2' required>
                                <textarea name="specifications" placeholder="Specifications" class='form-control mb-2' required></textarea>
                                <input type="text" name="compatibility" placeholder="Compatibility" class='form-control mb-2' required>
                                <input type="number" name="discount" placeholder="Discount (%)" class='form-control mb-2' required>
                                <input type="number" name="warranty_years" placeholder="Warranty (Years)" class='form-control mb-2' required>
                                <button type="submit" name="add_component" class="btn btn-primary">Add Component</button>
                            </form>
                            
                            
                        </div>
                    <?php endif; ?>

                    <?php if ($result->num_rows > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Component ID</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Specifications</th>
                                    <th>Compatibility</th>
                                    <th>Discount</th>
                                    <th>Hot Deal</th>
                                    <th>Warranty (Years)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['component_id']; ?></td>
                                        <td>$<?php echo $row['price']; ?></td>
                                        <td><?php echo $row['stock_quantity']; ?></td>
                                        <td><?php echo $row['specifications']; ?></td>
                                        <td><?php echo $row['compatibility']; ?></td>
                                        <td><?php echo $row['discount']; ?>%</td>
                                        <td><?php echo $row['discount'] >= 50 ? 'True' : 'False'; ?></td>
                                        <td><?php echo $row['warrenty_years']; ?></td>
                                        <td>
                                            <?php if ($customerType === 'service_man'): ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="component_id" value="<?php echo $row['component_id']; ?>">
                                                    <input type="number" name="restock_quantity" placeholder="Restock Quantity" required>
                                                    <button type="submit" name="restock_component" class="btn btn-warning">Restock</button>
                                                </form>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="component_id" value="<?php echo $row['component_id']; ?>">
                                                    <input type="number" name="new_discount" placeholder="New Discount (%)" required>
                                                    <button type="submit" name="update_discount" class="btn btn-info">Update Discount</button>
                                                </form>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="component_id" value="<?php echo $row['component_id']; ?>">
                                                    <button type="submit" name="remove_component" class="btn btn-danger">Remove</button>
                                                </form>
                                            <?php else: ?>
                                                <form method="POST">
                                                    <input type="hidden" name="component_id" value="<?php echo $row['component_id']; ?>">
                                                    <button type="submit" name="buy_component" class="btn btn-buy" <?php echo $row['stock_quantity'] == 0 ? 'disabled' : ''; ?>>Buy</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No components available at the moment.</p>
                    <?php endif; ?>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_component'])) {
                        $componentId = $_POST['component_id'];
                        $customerEmail = $_SESSION['email'];

          
                        $updateStockQuery = "UPDATE Components SET stock_quantity = stock_quantity - 1 WHERE component_id = ? AND stock_quantity > 0";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $updateStockQuery)) {
                            mysqli_stmt_bind_param($stmt, "i", $componentId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

               
                        $purchaseQuery = "INSERT INTO Buy_Components (customer_id, customer_email, component_id) VALUES (?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $purchaseQuery)) {
                            mysqli_stmt_bind_param($stmt, "isi", $customerId, $customerEmail, $componentId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

            
                        $updatePointsQuery = "UPDATE person SET points = points + 1 WHERE personID = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $updatePointsQuery)) {
                            mysqli_stmt_bind_param($stmt, "i", $customerId);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

                        echo "<script>alert('Purchase confirmed successfully!');</script>";
                        echo "<script>window.location.href = 'component.php';</script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
<?php
// Include database connection
include 'db.php'; // Assuming db.php contains your database connection logic

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['farmer_id']) && !isset($_SESSION['email'])) {
    die("You must be logged in to view products.");
}

// Fetch products added by farmers
$product_sql = "SELECT id, name, image, quantity, price FROM products";
$product_result = $conn->query($product_sql);

// Fetch food items added by users
$email = $_SESSION['email'] ?? null; // Get email if available
$user_id = null;

if ($email) {
    $result = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
    } else {
        // Handle the case where no user is found
        echo "No user found with that email.";
    }
}

$food_sql = "SELECT id, food_name, quantity, price FROM food_items WHERE user_id = ?";
$food_stmt = $conn->prepare($food_sql);
$food_stmt->bind_param("i", $user_id);
$food_stmt->execute();
$food_result = $food_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products and Food Items - Shaaka</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Available Products and Food Items</h1>
        <nav>
            <ul>
                <li><a href="customer.html">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="products">
            <h2>Products from Farmers</h2>
            <?php
            if ($product_result->num_rows > 0) {
                while ($row = $product_result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<img src='images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' />";
                    echo "<p>Quantity: " . htmlspecialchars($row['quantity']) . "</p>";
                    echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                    echo "<form action='add_to_cart.php' method='POST'>";
                    echo "<input type='hidden' name='product_name' value='" . htmlspecialchars($row['name']) . "'>";
                    echo "<input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "'>";
                    echo "<input type='number' name='quantity' value='1' min='1' max='" . htmlspecialchars($row['quantity']) . "' required>";
                    echo "<button type='submit'>Add to Cart</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products available at the moment.</p>";
            }
            ?>
        </section>

        <section id="food-items">
            <h2>Your Food Items</h2>
            <?php
            if ($food_result->num_rows > 0) {
                while ($row = $food_result->fetch_assoc()) {
                    echo "<div class='food-item'>";
                    echo "<h3>" . htmlspecialchars($row['food_name']) . "</h3>";
                    echo "<p>Quantity: " . htmlspecialchars($row['quantity']) . "</p>";
                    echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                    echo "<form action='add_to_cart.php' method='POST'>";
                    echo "<input type='hidden' name='food_name' value='" . htmlspecialchars($row['food_name']) . "'>";
                    echo "<input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "'>";
                    echo "<input type='number' name='quantity' value='1' min='1' max='" . htmlspecialchars($row['quantity']) . "' required>";
                    echo "<button type='submit'>Add to Cart</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>You have not added any food items yet.</ p>";
            }
            ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 Shaaka. All rights reserved.</p>
    </footer>
</body>
</html>
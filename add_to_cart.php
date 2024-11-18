<?php
session_start();

// Check if the cart session variable is set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the product or food item data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_name'])) {
        $product_name = $_POST['product_name'];
        $quantity = intval($_POST['quantity']);
        $price = floatval($_POST['price']);

        // Add product to cart
        $item = [
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $quantity * $price
        ];
    } elseif (isset($_POST['food_name'])) {
        $food_name = $_POST['food_name'];
        $quantity = intval($_POST['quantity']);
        $price = floatval($_POST['price']);

        // Add food item to cart
        $item = [
            'name' => $food_name,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $quantity * $price
        ];
    }

    // Check if the item already exists in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['name'] === $item['name']) {
            $cart_item['quantity'] += $item['quantity'];
            $cart_item['total'] += $item['total'];
            $found = true;
            break;
        }
    }

    // If the item is not found, add it to the cart
    if (!$found) {
        $_SESSION['cart'][] = $item;
    }

    // Redirect back to the products page
    header("Location: get_products.php");
    exit();
}
?>
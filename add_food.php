<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shaaka";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to get the logged-in user's ID
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    die("You must be logged in to add food.");
}

// Get the user ID from the session (assuming you stored it during login)
$email = $_SESSION['email'];
$result = $conn->query("SELECT id FROM users WHERE email = '$email'");
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Get form data
$food_name = $_POST['food_name'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO food_items (user_id, food_name, quantity, price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isid", $user_id, $food_name, $quantity, $price);

// Execute the statement
if ($stmt->execute()) {
    echo "Food item added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
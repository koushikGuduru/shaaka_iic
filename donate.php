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

// Start session to get the logged-in donor's ID
session_start();

// Check if donor is logged in
if (!isset($_SESSION['donor_id'])) {
    die("You must be logged in to make a donation.");
}

// Get the donor ID from the session
$donor_id = $_SESSION['donor_id'];

// Get form data
$donation_type = $_POST['donation_type'];
$description = $_POST['description'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO donations (donor_id, donation_type , description) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $donor_id, $donation_type, $description);

// Execute the statement
if ($stmt->execute()) {
    echo "Donation made successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
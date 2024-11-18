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

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT id, password FROM donors WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($donor_id, $hashed_password);
    $stmt->fetch();
    
    // Verify password
    if (password_verify($password, $hashed_password)) {
        echo "Login successful!";
        // Start session and set user session variables as needed
        session_start();
        $_SESSION['donor_id'] = $donor_id; // Store donor ID in session
        $_SESSION['email'] = $email; // Store email in session
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email.";
}

// Close connections
$stmt->close();
$conn->close();
?>
<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $farmer_id = $_SESSION['farmer_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);

    $sql = "INSERT INTO products (farmer_id, name, image, quantity, price) VALUES ('$farmer_id', '$product_name', '$image', '$quantity', '$price')";

    if ($conn->query($sql) === TRUE) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        echo "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
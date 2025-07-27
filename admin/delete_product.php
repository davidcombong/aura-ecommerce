<?php
// Include the database connection
require_once('../config/db.php');

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare a delete statement to prevent SQL injection
    $sql = "DELETE FROM products WHERE product_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        
        // Execute the statement and redirect with a success message
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?status=deleted");
            exit();
        } else {
            header("Location: index.php?status=error");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
} else {
    // If no ID is provided, redirect back
    header("Location: index.php");
    exit();
}
?>
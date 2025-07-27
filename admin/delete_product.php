<?php

require_once('../config/db.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $sql = "DELETE FROM products WHERE product_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        
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
    header("Location: index.php");
    exit();
}
?>
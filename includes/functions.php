<?php

// Function to display products on the homepage
function getProducts($conn) {
    // Select the 6 most recently added products
    $sql = "SELECT * FROM products ORDER BY product_id DESC LIMIT 6";
    $result = mysqli_query($conn, $sql);

    // Loop through results and display each product
    while ($product = mysqli_fetch_assoc($result)) {
        echo "<a href='product.php?id={$product['product_id']}' class='product-card-link'>";
        echo "<div class='product-card'>";
        
        // Check if there is an image, otherwise show a placeholder
        if (!empty($product['product_image'])) {
            echo "<img src='uploads/{$product['product_image']}' alt='{$product['product_name']}' class='product-image'>";
        } else {
            echo "<div class='product-image-placeholder'></div>";
        }

        echo "<h3>" . htmlspecialchars($product['product_name']) . "</h3>";
        echo "<p class='product-price'>PHP " . number_format($product['product_price'], 2) . "</p>";
        echo "</div>";
        echo "</a>";
    }
}

// Function to display ALL products for the shop page
function getAllProducts($conn) {
    $sql = "SELECT * FROM products ORDER BY product_name ASC"; // Order alphabetically
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($product = mysqli_fetch_assoc($result)) {
            echo "<a href='product.php?id={$product['product_id']}' class='product-card-link'>";
            echo "<div class='product-card'>";
            
            if (!empty($product['product_image'])) {
                echo "<img src='uploads/{$product['product_image']}' alt='{$product['product_name']}' class='product-image'>";
            } else {
                echo "<div class='product-image-placeholder'></div>";
            }

            echo "<h3>" . htmlspecialchars($product['product_name']) . "</h3>";
            echo "<p class='product-price'>PHP " . number_format($product['product_price'], 2) . "</p>";
            echo "</div>";
            echo "</a>";
        }
    } else {
        echo "<p class='info-message'>No products found at this time.</p>";
    }
}

?>
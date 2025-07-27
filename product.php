<?php 
// Include header
include_once('includes/header.php');

// Initialize product variable
$product = null;

// Check if a product ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Fetch the specific product from the database
    $sql = "SELECT p.*, c.category_name, b.brand_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.category_id 
            LEFT JOIN brands b ON p.brand_id = b.brand_id 
            WHERE p.product_id = ?";
            
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $product = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="product-detail-section">
    <div class="container">
        <?php if ($product): ?>
            <div class="product-detail-layout">
                <div class="product-detail-image">
                    <img src="uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>
                <div class="product-detail-info">
                    <p class="product-detail-brand"><?php echo htmlspecialchars($product['brand_name']); ?></p>
                    <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
                    <p class="product-detail-price">PHP <?php echo number_format($product['product_price'], 2); ?></p>
                    <div class="product-detail-description">
                        <?php echo nl2br(htmlspecialchars($product['product_description'])); ?>
                    </div>
                    <a href="#" class="cta-button">Add to Cart</a>
                </div>
            </div>
        <?php else: ?>
            <p class="error-message">Product not found. Please return to the <a href="index.php">homepage</a>.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
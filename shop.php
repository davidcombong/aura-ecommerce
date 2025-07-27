<?php 
// Include necessary files
include_once('includes/header.php');
include_once('includes/functions.php');
?>

<div class="page-header">
    <div class="container">
        <h1>Our Collection</h1>
        <p>Browse all curated products available at Aura.</p>
    </div>
</div>

<section class="product-grid-section">
    <div class="container">
        <div class="product-grid">
            <?php getAllProducts($conn); ?>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
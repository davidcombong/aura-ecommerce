<?php 
include_once('includes/header.php');
include_once('includes/functions.php');
?>

    <section class="hero">
        <div class="container">
            <h1>Simplicity, Curated.</h1>
            <p>Discover a collection of thoughtfully designed goods that bring beauty and function to your everyday life.</p>
            <a href="shop.php" class="cta-button">Explore the Collection</a>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="feature-item">
                <img src="assets/img/icon-product.png" alt="Icon representing a unique product" class="feature-icon">
                <h3>Curated Products</h3>
                <p>Every item in our collection is hand-picked for its quality and timeless design.</p>
            </div>
            <div class="feature-item">
                <img src="assets/img/icon-brand.png" alt="Icon representing a trusted brand" class="feature-icon">
                <h3>Trusted Brands</h3>
                <p>We partner with artisans and brands who share our passion for craftsmanship.</p>
            </div>
            <div class="feature-item">
                <img src="assets/img/icon-experience.png" alt="Icon representing a seamless experience" class="feature-icon">
                <h3>Seamless Experience</h3>
                <p>Enjoy a smooth and delightful shopping journey from start to finish.</p>
            </div>
        </div>
    </section>

    <section class="product-grid-section">
        <div class="container">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <?php getProducts($conn); ?>
            </div>
        </div>
    </section>

<?php include_once('includes/footer.php'); ?>
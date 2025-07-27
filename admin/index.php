<?php
// (All the PHP logic at the top remains exactly the same as before)
require_once('../config/db.php');
$message = '';
$message_type = 'success';
if (isset($_GET['status'])) { if ($_GET['status'] == 'deleted') { $message = 'Product has been deleted successfully!'; } elseif ($_GET['status'] == 'updated') { $message = 'Product has been updated successfully!'; } elseif ($_GET['status'] == 'error') { $message = 'An error occurred. Please try again.'; $message_type = 'error'; } }
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) { $product_name = mysqli_real_escape_string($conn, $_POST['product_name']); $product_desc = mysqli_real_escape_string($conn, $_POST['product_description']); $product_price = mysqli_real_escape_string($conn, $_POST['product_price']); $category_id = mysqli_real_escape_string($conn, $_POST['category_id']); $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']); $product_image = ''; if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) { $target_dir = "../uploads/"; $image_name = time() . '_' . basename($_FILES["product_image"]["name"]); $target_file = $target_dir . $image_name; if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) { $product_image = $image_name; } else { $message = "Error: There was an error uploading your file."; $message_type = 'error'; } } if ($message == '') { $sql = "INSERT INTO products (product_name, product_description, product_price, category_id, brand_id, product_image) VALUES (?, ?, ?, ?, ?, ?)"; if ($stmt = mysqli_prepare($conn, $sql)) { mysqli_stmt_bind_param($stmt, "ssdiss", $product_name, $product_desc, $product_price, $category_id, $brand_id, $product_image); if (mysqli_stmt_execute($stmt)) { $message = "New product added successfully!"; } else { $message = "Error: " . mysqli_error($conn); $message_type = 'error'; } mysqli_stmt_close($stmt); } else { $message = "Error: " . mysqli_error($conn); $message_type = 'error'; } } }
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) { $product_id = mysqli_real_escape_string($conn, $_POST['product_id']); $product_name = mysqli_real_escape_string($conn, $_POST['product_name']); $product_desc = mysqli_real_escape_string($conn, $_POST['product_description']); $product_price = mysqli_real_escape_string($conn, $_POST['product_price']); $category_id = mysqli_real_escape_string($conn, $_POST['category_id']); $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']); $existing_image = mysqli_real_escape_string($conn, $_POST['existing_image']); $product_image = $existing_image; if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) { $target_dir = "../uploads/"; $image_name = time() . '_' . basename($_FILES["product_image"]["name"]); $target_file = $target_dir . $image_name; if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) { $product_image = $image_name; if (!empty($existing_image) && file_exists($target_dir . $existing_image)) { unlink($target_dir . $existing_image); } } } $sql = "UPDATE products SET product_name=?, product_description=?, product_price=?, category_id=?, brand_id=?, product_image=? WHERE product_id=?"; if ($stmt = mysqli_prepare($conn, $sql)) { mysqli_stmt_bind_param($stmt, "ssdissi", $product_name, $product_desc, $product_price, $category_id, $brand_id, $product_image, $product_id); if (mysqli_stmt_execute($stmt)) { header("Location: index.php?status=updated"); exit(); } else { $message = "Error: " . mysqli_error($conn); $message_type = 'error'; } mysqli_stmt_close($stmt); } else { $message = "Error: " . mysqli_error($conn); $message_type = 'error'; } }
$categories_result = mysqli_query($conn, "SELECT * FROM categories");
$brands_result = mysqli_query($conn, "SELECT * FROM brands");
$products_result = mysqli_query($conn, "SELECT p.*, c.category_name, b.brand_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id LEFT JOIN brands b ON p.brand_id = b.brand_id ORDER BY p.product_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Aura</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="admin-container">
        <header class="admin-header">
            <h1>Aura Admin Panel</h1>
            <a href="../index.php" class="admin-link">View Live Site</a>
        </header>

        <main class="admin-main">
            <?php if ($message): ?>
                <div class="admin-message <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="admin-section">
                <h2>Add New Product</h2>
                <form action="index.php" method="post" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group"><label for="product_name">Product Name</label><input type="text" id="product_name" name="product_name" required></div>
                    <div class="form-group"><label for="product_description">Description</label><textarea id="product_description" name="product_description" rows="4"></textarea></div>
                    <div class="form-group-row"><div class="form-group"><label for="product_price">Price (PHP)</label><input type="number" step="0.01" id="product_price" name="product_price" required></div><div class="form-group"><label for="product_image">Product Image</label><input type="file" id="product_image" name="product_image" required></div></div>
                    <div class="form-group-row"><div class="form-group"><label for="category_id">Category</label><select id="category_id" name="category_id"><option value="">Select Category</option><?php while($cat = mysqli_fetch_assoc($categories_result)) { echo "<option value='{$cat['category_id']}'>{$cat['category_name']}</option>"; } ?></select></div><div class="form-group"><label for="brand_id">Brand</label><select id="brand_id" name="brand_id"><option value="">Select Brand</option><?php while($brand = mysqli_fetch_assoc($brands_result)) { echo "<option value='{$brand['brand_id']}'>{$brand['brand_name']}</option>"; } ?></select></div></div>
                    <button type="submit" name="add_product" class="admin-button">Add Product</button>
                </form>
            </div>

            <div class="admin-section">
                <h2>Manage Existing Products</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php mysqli_data_seek($products_result, 0); ?>
                        <?php while($product = mysqli_fetch_assoc($products_result)): ?>
                        <tr>
                            <td><img src="../uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="50"></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
                            <td>PHP <?php echo number_format($product['product_price'], 2); ?></td>
                            <td class="action-cell">
                                <button class="action-btn edit-btn" 
                                        data-id="<?php echo $product['product_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                                        data-desc="<?php echo htmlspecialchars($product['product_description']); ?>"
                                        data-price="<?php echo $product['product_price']; ?>"
                                        data-catid="<?php echo $product['category_id']; ?>"
                                        data-brandid="<?php echo $product['brand_id']; ?>"
                                        data-image="<?php echo htmlspecialchars($product['product_image']); ?>">
                                    Edit
                                </button>
                                <!-- FIX: Changed back to <a> tag but styled like a button, removed onclick -->
                                <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" 
                                   class="action-btn delete-btn">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-content"><span class="modal-close">×</span><h2>Edit Product</h2><form action="index.php" method="post" enctype="multipart/form-data" class="admin-form"><input type="hidden" id="edit_product_id" name="product_id"><input type="hidden" id="edit_existing_image" name="existing_image"><div class="form-group"><label for="edit_product_name">Product Name</label><input type="text" id="edit_product_name" name="product_name" required></div><div class="form-group"><label for="edit_product_description">Description</label><textarea id="edit_product_description" name="product_description" rows="4"></textarea></div><div class="form-group-row"><div class="form-group"><label for="edit_product_price">Price (PHP)</label><input type="number" step="0.01" id="edit_product_price" name="product_price" required></div><div class="form-group"><label for="edit_product_image">New Product Image (Optional)</label><input type="file" id="edit_product_image" name="product_image"></div></div><div class="form-group-row"><div class="form-group"><label for="edit_brand_id">Category</label><select id="edit_category_id" name="category_id"></select></div><div class="form-group"><label for="edit_brand_id">Brand</label><select id="edit_brand_id" name="brand_id"></select></div></div><button type="submit" name="update_product" class="admin-button">Update Product</button></form></div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content delete-modal-content">
            <span class="modal-close delete-modal-close">×</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to permanently delete this product? This action cannot be undone.</p>
            <div class="delete-modal-actions">
                <button id="cancelDeleteBtn" class="admin-button secondary">Cancel</button>
                <a id="confirmDeleteBtn" href="#" class="action-btn delete-btn">Confirm Delete</a>
            </div>
        </div>
    </div>

    <!-- UPDATED JavaScript for Modals -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Edit Modal Logic ---
            const editModal = document.getElementById('editModal');
            if (editModal) {
                const closeEditBtn = editModal.querySelector('.modal-close');
                const editButtons = document.querySelectorAll('.edit-btn');
                
                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const desc = this.dataset.desc;
                        const price = this.dataset.price;
                        const catId = this.dataset.catid;
                        const brandId = this.dataset.brandid;
                        const image = this.dataset.image;

                        document.getElementById('edit_product_id').value = id;
                        document.getElementById('edit_product_name').value = name;
                        document.getElementById('edit_product_description').value = desc;
                        document.getElementById('edit_product_price').value = price;
                        document.getElementById('edit_existing_image').value = image;

                        const categorySelect = document.getElementById('edit_category_id');
                        const brandSelect = document.getElementById('edit_brand_id');
                        
                        // FIX: Reset the result sets before populating dropdowns
                        <?php mysqli_data_seek($categories_result, 0); ?>
                        <?php mysqli_data_seek($brands_result, 0); ?>
                        
                        categorySelect.innerHTML = document.getElementById('category_id').innerHTML;
                        brandSelect.innerHTML = document.getElementById('brand_id').innerHTML;
                        
                        categorySelect.value = catId;
                        brandSelect.value = brandId;
                        
                        editModal.style.display = 'block';
                    });
                });

                function closeEditModal() { editModal.style.display = 'none'; }
                closeEditBtn.addEventListener('click', closeEditModal);
            }

            // --- Delete Modal Logic ---
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                const closeDeleteBtn = deleteModal.querySelector('.modal-close');
                const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                const deleteButtons = document.querySelectorAll('.admin-table .delete-btn');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault(); // Stop the link from navigating
                        const deleteUrl = this.href; // Get URL from the <a> tag
                        confirmDeleteBtn.href = deleteUrl; // Set the URL on the confirm button
                        deleteModal.style.display = 'block';
                    });
                });

                function closeDeleteModal() { deleteModal.style.display = 'none'; }
                closeDeleteBtn.addEventListener('click', closeDeleteModal);
                cancelDeleteBtn.addEventListener('click', closeDeleteModal);
            }

            // --- General Modal Close Logic (Clicking Outside) ---
            window.addEventListener('click', function(event) {
                if (event.target == editModal) { editModal.style.display = 'none'; }
                if (event.target == deleteModal) { deleteModal.style.display = 'none'; }
            });
        });
    </script>

</body>
</html>
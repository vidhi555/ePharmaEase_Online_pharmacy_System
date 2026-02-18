<?php
require_once('db.php');
require('crud.php');
$pid = $_GET['p_id'];
// echo $pid;
require_once('edit.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product Detail</title>
  <!-- Stylesheets -->
  <link rel="shortcut icon" href="./assets/images/logo6.ico" type="image/x-icon">
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/fontawesome.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/brands.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/solid.min.css" rel="stylesheet">
  <link href="./assets/plugin/quill/quill.snow.css" rel="stylesheet">
  <link href="./assets/css/style4.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function confirmDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete these Product?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect or AJAX call
          window.location.href = "delete_product.php?p_id=" + id;
        }
      });
    }
  </script>
</head>
<style>

</style>

<body>
  <!-- Preloader -->
  <div id="preloader">
    <div class="spinner"></div>
  </div>
  <!-- Main Wrapper -->
  <div id="main-wrapper" class="d-flex">
    <?php
    //Sidebar content
    include('sidebar.php');
    ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">

      <!-- Header -->
      <div class="header d-flex align-items-center justify-content-between">
        <?php
        include('header.php');
        ?>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <div class="row">
          <div class="col-12">
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-color-2"><a href="products.php">Back</a> > Product Detail</h3>
              </div>

            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <?php
        try {
          $query = $conn->prepare("SELECT * FROM ep_products p JOIN ep_category c ON p.c_id = c.c_id WHERE p_id = :pid ");
          $query->execute([':pid' => $pid]);
          $fetch_product = $query->fetch(PDO::FETCH_ASSOC);
          if ($fetch_product) {
        ?>

            <div class="mt-4">

              <!-- Main content -->
              <div class="product-detail-card">
                <div class="product-left">
                  <img src="upload/<?= $fetch_product['image'] ?>" alt="Product Image">
                </div>

                <div class="product-right">
                  <div class="product-header">
                    <h3><?= $fetch_product['name'] ?></h3>

                    <div class="action-buttons">
                      <button type="button"
                        class="icon-btn edit"
                        data-bs-toggle="modal"
                        data-bs-target="#EditModal"

                        data-id="<?= $fetch_product['p_id'] ?>"
                        data-name="<?= htmlspecialchars($fetch_product['name']) ?>"
                        data-desc="<?= htmlspecialchars($fetch_product['description']) ?>"
                        data-price="<?= $fetch_product['price'] ?>"
                        data-stock="<?= $fetch_product['stock'] ?>"
                        data-expiry="<?= $fetch_product['expiry_date'] ?>"
                        data-status="<?= $fetch_product['status'] ?>"
                        data-category="<?= $fetch_product['c_id']  ?>"
                        data-img="<?= $fetch_product['image'] ?>">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="icon-btn delete" onclick="confirmDelete(<?= $fetch_product['p_id'] ?>)"><i class="fas fa-trash"></i></button>

                      <!-- <a href="edit_product.php?p_id=<?= $fetch_product['p_id'] ?>" class="icon-btn edit">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="delete_product.php?p_id=<?= $fetch_product['p_id'] ?>" 
                   class="icon-btn delete"
                   onclick="return confirm('Are you sure you want to delete this product?')">
                    <i class="fas fa-trash"></i>
                </a> -->
                    </div>
                  </div>

                  <p class="product-desc">
                    <?= $fetch_product['description'] ?? 'No description available.' ?>
                  </p>

                  <ul class="product-info">
                    <li><strong>Category:</strong> <?= $fetch_product['category_name'] ?></li>
                    <li><strong>Price:</strong> ₹<?= $fetch_product['price'] ?></li>
                    <li><strong>Stock:</strong> <?= $fetch_product['stock'] ?></li>
                    <li><strong>Expiry Date: </strong><?= date("d/m/Y", strtotime($fetch_product['expiry_date'])) ?></li>
                    <li><strong>Status: </strong><?= $fetch_product['status'] ?></li>
                  </ul>
                  <?php if ($fetch_product['stock'] <= 5) { ?>
                    <span class="badge bg-danger">Only <?= $fetch_product['stock'] ?> items left. Restock soon.</span>
                  <?php }
                  if ($fetch_product['stock'] == 0) { ?>
                    <span class="badge bg-danger">Out of Stock.</span>
                  <?php }
                  if ($fetch_product['status'] == 'In-Active') { ?>
                    <span class="badge bg-warning"><?= $fetch_product['name'] ?> is currently Disabled.</span>
                  <?php } ?>
                </div>
              </div>






            </div>
      </div>
      <!-- Footer -->
      <?php include('footer.php'); ?>
    </div>



    <!--Edit  Modal -->
    <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">
          <div class="modal-body p-4 position-relative">
            <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            <h2 class="h5 text-color-2 py-2">Edit Product</h2>
            <form class="row g-3" method="post" enctype="multipart/form-data">
              <input type="hidden" name="p_id" id="edit_p_id">

              <div class="col-12">
                <label for="Product_name" class="form-label text-color-2 text-normal">Product Name</label>
                <input type="text" name="pname" class="form-control" id="edit_pname" placeholder="Enter product name">
              </div>
              <!-- <div class="col-12">
                <label for="Product_desc" class="form-label text-color-2 text-normal">Description</label>
                <input type="text" name="desc" class="form-control" id="edit_desc" placeholder="Enter Description">
              </div> -->
              <div class="mb-3">
                <label for="Product_desc" class="form-label text-color-2 text-normal">Description</label>
                <textarea name="desc" class="form-control" id="edit_desc" rows="3" placeholder="Enter Description"></textarea>
              </div>
              <div class="col-6">
                <label for="price" class="form-label text-color-2 text-normal">Price</label>
                <input type="number" name="price" class="form-control" id="edit_price" placeholder="Enter Price">
              </div>
              <div class="col-6">
                <label for="stock" class="form-label text-color-2 text-normal">Stock</label>
                <input type="number" name="stock" class="form-control" id="edit_stock" placeholder="Enter Stock">
              </div>

              <div class="col-6">
                <label for="edate" class="form-label text-color-2 text-normal">Expiry Date</label>
                <input type="date" name="edate" class="form-control" min="<?= date('Y-m-d') ?>" id="edit_edate" placeholder="mm/dd/yyyy">
              </div>
              <div class="col-6">
                <label for="status" class="form-label text-color-2 text-normal">Status</label>
                <select name="status" id="edit_status" class="form-select text-normal">
                  <option value="">Choose Status</option>
                  <option value="Active">Active</option>
                  <option value="In-Active">In-Active</option>
                </select>
              </div>

              <div class="col-12">
                <label for="edit_category0" class="form-label text-color-2 text-normal">Category</label>
                <select id="edit_category" name="category" class="form-select text-normal">
                  <option value="" selected="selected" disabled>Choose Category</option>
                  <?php
                  $q = "SELECT * FROM ep_category";
                  $res = $conn->prepare($q);

                  if ($res->execute()) {
                    $fetch_row = $res->fetchAll(PDO::FETCH_ASSOC);
                    // echo $fetch_row['category_name'];
                    foreach ($fetch_row as $r) {
                      echo "<option value='{$r['c_id']}'>" . $r['category_name'] . "</option>";
                    }
                  } else {
                    echo "Fail";
                  }
                  ?>

                </select>
              </div>

              <input type="hidden" name="old_img" id="old_image" class="form-control">
              <div class="col-12">
                <label class="form-label text-color-2 text-normal">Product Image</label>
                <img src="" alt="product" id="edit_img_prev" style="height: 100px;width: 100px;border: 1px solid lightblue;">
                <input type="file" name="pimg" class="form-control">
               
              </div>

              <div class="col-12 mt-5">
                <button type="submit" name="edit" class="btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">Update Product</button>
              </div>
            </form>


          </div>
        </div>
      </div>
    </div>

<?php
          }
        } catch (PDOException $ex) {
          echo $ex;
        }
?>

<script>
 document.getElementById('EditModal').addEventListener('show.bs.modal', function(event) {

        let btn = event.relatedTarget;
        let old_img = btn.getAttribute('data-img');
        // alert(old_img);
        document.getElementById('edit_p_id').value = btn.getAttribute('data-id');
        document.getElementById('edit_pname').value = btn.getAttribute('data-name');
        document.getElementById('edit_desc').value = btn.getAttribute('data-desc');
        document.getElementById('edit_price').value = btn.getAttribute('data-price');
        document.getElementById('edit_stock').value = btn.getAttribute('data-stock');
        document.getElementById('edit_edate').value = btn.getAttribute('data-expiry');
        document.getElementById('edit_status').value = btn.getAttribute('data-status');
        document.getElementById('old_image').value = btn.getAttribute('data-img');
        document.getElementById('edit_category').value = btn.getAttribute('data-category');

        // set image
        document.getElementById('edit_img_prev').src = "upload/" + old_img;
  });
</script>
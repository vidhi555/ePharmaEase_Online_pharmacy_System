<?php
require_once('db.php');
require('crud.php');
$pid = $_GET['p_id'];
// echo $pid;
require_once('edit.php');

$page_title = "Product Detail";
require_once('header2.php');
?>

<style>
  /* LEFT IMAGE SECTION */
  .product-left {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-start;
    max-width: 420px;
  }

  /* SINGLE IMAGE BOX */
  .single-prd-item {
    width: 150px;
    height: 150px;
    border-radius: 12px;
    overflow: hidden;
    background: #f5f9ff;
    border: 1px solid #e3ecff;
    cursor: pointer;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* IMAGE */
  .single-prd-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.3s;
  }

  /* HOVER EFFECT */
  .single-prd-item:hover {
    transform: translateY(-4px);
    /* border-color: #2563eb; */
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
  }

  /* SCROLLBAR DESIGN */
  .product-left::-webkit-scrollbar {
    width: 6px;
  }

  .product-left::-webkit-scrollbar-thumb {
    background: #c7d8ff;
    border-radius: 10px;
  }

  .product-detail-card {
    display: flex;
    gap: 40px;
    background: #ffffff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    align-items: flex-start;
  }

  /* LEFT IMAGE AREA */

  .product-left {
    width: 22%;
    display: flex;
    flex-direction: row;
    gap: 15px;
    max-height: 450px;
    overflow-y: auto;
    padding-right: 10px;
    border-right: 1px solid #eee;
  }

  .single-prd-item {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
  }

  .single-prd-item img {
    max-width: 100%;
    border-radius: 6px;
    transition: 0.3s;
  }

  .single-prd-item img:hover {
    transform: scale(1.05);
  }

  /* RIGHT SIDE */

  .product-right {
    width: 65%;
  }

  /* HEADER */

  .product-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .product-header h3 {
    font-size: 26px;
    font-weight: 600;
    color: #3a57e8;
    margin: 0;
  }

  /* ACTION BUTTONS */

  .action-buttons {
    display: flex;
    gap: 10px;
  }

  .icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: none;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }

  .icon-btn.edit {
    background: #3a57e8;
  }

  .icon-btn.delete {
    background: #dc3545;
  }

  /* DESCRIPTION */

  .product-desc {
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
  }

  /* PRODUCT INFO */

  .product-info {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
  }

  @media (max-width: 576px) {
    .product-info {
      grid-template-columns: repeat(1, 1fr);
    }

    .product-left {
      width: 100%;
    }

    .product-right {
      width: 100%;
    }
  }

  .product-info li {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 6px;
    font-size: 15px;
  }

   .product-info li:hover{
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
    
   }
  /* PRICE STYLE */

  .product-price {
    color: #28a745;
    font-size: 18px;
    font-weight: 600;
  }

  /* ALERT */

  .expiry-alert {
    margin-top: 15px;
    background: #fff3f3;
    color: #dc3545;
    padding: 10px 15px;
    border-radius: 6px;
    font-size: 14px;
    border-left: 4px solid #dc3545;
  }
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
                <nav>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="products.php">Product</a></li>
                    <li class="breadcrumb-item active">Product Details</li>
                  </ol>
                </nav>
              </div>

            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <?php
        try {
          $query = $conn->prepare("SELECT p.* , c.category_name FROM ep_products p JOIN ep_category c ON p.c_id = c.c_id WHERE p_id = :pid ");
          $query->execute([':pid' => $pid]);
          $fetch_product = $query->fetch(PDO::FETCH_ASSOC);
          if ($fetch_product) {
        ?>

            <div class="mt-4">

              <!-- Main content -->
              <div class="product-detail-card">
                <div class="product-left">
                  <!-- <img src="All_images_uploads/<?= $fetch_product['image'] ?>" alt="Product Image"> -->
                  <?php
                  try {
                    $get_image = $conn->prepare("SELECT * FROM ep_image_gallery WHERE p_id = :pid");
                    $get_image->execute(['pid' => $pid]);
                    $fetch_iamge = $get_image->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($fetch_iamge as $a) {
                  ?>
                      <div class="single-prd-item">
                        <button
                          data-bs-toggle="modal"
                          data-bs-target="#imgModal"

                          data-id="<?= $a['p_id'] ?>"
                          data-img="<?= $a['image_name'] ?>">
                          <img class="img-fluid" src="../LearnAdmin/All_images_uploads/<?= $a['image_name'] ?>" alt="p_image" style="width: auto;">
                        </button>
                      </div>
                  <?php
                    }
                  } catch (PDOException $e) {
                    echo $e;
                  }
                  ?>
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
                      <button class="icon-btn delete" onclick="confirmDelete(<?= $fetch_product['p_id'] ?>,'delete_product.php?p_id=')"><i class="fas fa-trash"></i></button>

                    </div>
                  </div>

                  <p class="product-desc">
                    <?= $fetch_product['description'] ?? 'No description available.' ?>
                  </p>

                  <ul class="product-info">
                    <li><strong>Category:</strong> <?= $fetch_product['category_name'] ?></li>
                    <li><strong>Price:</strong> $<?= $fetch_product['price'] ?></li>
                    <li><strong>Stock:</strong> <?= $fetch_product['stock'] ?>
                      <?php if ($fetch_product['stock'] <= 0) { ?>
                        <span class="badge bg-danger">Out of Stock.</span>
                      <?php } elseif ($fetch_product['stock'] <= 5) { ?>
                        <span class="badge bg-danger">Only <?= $fetch_product['stock'] ?> items left. Restock soon.</span>
                      <?php } ?>

                      <?php if ($fetch_product['status'] == 'In-Active') { ?>
                        <span class="badge bg-warning"><?= $fetch_product['name'] ?> is currently Disabled.</span>
                      <?php } ?>
                    </li>
                    <li><strong>Status: </strong><?= $fetch_product['status'] ?></li>
                    <?php
                    try {
                      $e_query = $conn->prepare("SELECT * FROM `ep_products` WHERE expiry_date < NOW()");
                      $e_query->execute();
                      $count_rows = $e_query->rowCount();
                    } catch (PDOException $e) {
                      echo $e;
                    }
                    ?>
                    <li><strong>Expiry Date: </strong><?= date("d/m/Y", strtotime($fetch_product['expiry_date'])) ?>
                      <?php
                      if ($count_rows > 0) {
                        echo "<p class='text-danger'><i class='fa-regular fa-ban'></i> This product has expired. Please remove or update the stock.</p>";
                      }
                      ?>
                    </li>

                  </ul>


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
                <input type="number" name="price" min="0.00" step="0.01" class="form-control" id="edit_price" placeholder="Enter Price">
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
                <input type="file" name="pimg[]" multiple class="form-control">
              </div>
              <!-- <input type="text" name="" id="img_prev1"> -->

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
    document.getElementById('edit_img_prev').src = "All_images_uploads/" + old_img;
  });
</script>

<!--Edit  Modal -->
<div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content rounded-0">

      <div class="modal-body p-4 position-relative">
        <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
        <h2 class="h5 text-color-2 py-2">Image</h2>
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="text-center">
            <input type="hidden" name="p_id" id="p_id">
            <img src="" id="img_prev" alt="img" srcset="" width="500px" height="500px" style="border: 1px solid #333;border-radius: 26px;box-shadow: 0 6px 16px rgba(0, 0, 0, 0.8);">
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<script>
  document.getElementById('imgModal').addEventListener('show.bs.modal', function(event) {

    let btn = event.relatedTarget;
    let img = btn.getAttribute('data-img');

    document.getElementById('p_id').value = btn.getAttribute('data-id');
    // set image
    // document.getElementById('img_prev1').value = btn.getAttribute('data-img');
    document.getElementById('img_prev').src = "./All_images_uploads/" + img;
  });
</script>
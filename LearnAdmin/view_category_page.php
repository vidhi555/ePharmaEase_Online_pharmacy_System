<?php
require_once('db.php');
require('crud.php');

$table = "ep_category";
$cid = $_GET['c_id'];
if (isset($_POST['update'])) {
  $id = $_POST['c_id'];
  $cname = $_POST['categoryName'];
  $cdesc = $_POST['categoryDesc'];

   if(!empty($_FILES['cat_img']['name'])){
    //image upload
  $updateimg = $_FILES['cat_img']['name'];
  $tempimg = $_FILES['cat_img']['tmp_name'];
  $ext = pathinfo($updateimg, PATHINFO_EXTENSION);
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];
  $img_name = "category_" . time() . "." . $ext;
  $size = $_FILES['cat_img']['size'];
  $max_size = 2097152;

  if($size>$max_size){
    $errors[] = "Invalid Image Size!";
  }

  $target = "category/" . basename($img_name);
  //move_uploaded_file($tempimg,$target);
  if (!in_array(strtolower($ext), $allowed)) {
    $errors[] = "Invalid image format!!";
  }

  if (!move_uploaded_file($tempimg, $target)) {
    $errors[] = "Image upload failed!!!";
    // sweetAlert("Warning!", "Image upload failed!!", "warning");
    // exit;
  }
   }else{
    $img_name = $_POST['old_image'];
  }
     
 

  $data = [
    "category_name" => $cname,
    "description"=>$cdesc,
    "cat_image"=>$img_name
    ];
  $condition = "c_id = $id";
  //update function call
  $q = update_record($table, $data, $condition);
  if (!$q) {
    // $message = "Updation Fail";
    sweetAlert("Warning!", "Updation Fail!Try Again!!!", "warning");
  }else{
    sweetAlert("Updated Successfully.","","success");
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>category Detail</title>
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
        text: "Do you really want to delete these Category?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect or AJAX call
          window.location.href = "delete_category.php?p_id=" + id;
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
                <h3 class="mb-2 text-color-2"><a href="category.php">Back</a> > Category Detail</h3>
              </div>

            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <?php
        try {
          $query = $conn->prepare("SELECT * FROM  ep_category c JOIN ep_products p ON p.c_id = c.c_id  WHERE c.c_id = :cid ");
          $query->execute([':cid' => $cid]);
          $fetch_product = $query->fetch(PDO::FETCH_ASSOC);
          $count_products = $query->rowCount();
          if ($fetch_product) {
        ?>

            <div class="mt-4">

              <!-- Main content -->
              <div class="product-detail-card">
                <div class="product-left">
                  <img src="category/<?= $fetch_product['cat_image'] ?>" alt="Product Image">
                </div>

                <div class="product-right">
                  <div class="product-header">
                    <h3><?= $fetch_product['category_name'] ?></h3>
                    

                    <div class="action-buttons">
                      <button type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#categoryEditModal"
                        class="icon-btn edit"

                        data-cid="<?= $fetch_product['c_id'] ?>"
                        data-cname="<?= htmlspecialchars($fetch_product['category_name']) ?>"
                        data-cdesc="<?= htmlspecialchars($fetch_product['description']) ?>"
                        data-cimg="<?= $fetch_product['cat_image'] ?>"><i class="fa-regular fa-pen-to-square"></i></button>
                      <button class="icon-btn delete" onclick="confirmDelete(<?= $fetch_product['c_id'] ?>)"><i class="fas fa-trash"></i></button>


                    </div>
                  </div>
                  <p class="product-desc"><strong>Category ID:</strong> <?= $fetch_product['c_id'] ?><br>
                  <strong>Total Products:</strong> <?= $count_products ?></p>
                  <p class="product-desc">
                    "<?= $fetch_product['description'] ?? 'No description available.' ?>"
                  </p>
                </div>
              </div>






            </div>
      </div>
      <!-- Footer -->
      <?php include('footer.php'); ?>
    </div>



    <!--Edit  Modal -->
    <div class="modal fade" id="categoryEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">
          <div class="modal-body p-4 position-relative">
            <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            <h2 class="h5 text-color-2 py-2">Edit Category</h2>
            <form class="row g-3" method="post" enctype="multipart/form-data">
              <input type="hidden" name="c_id" id="edit_cid">
              <div class="col-12">
                <label for="edit_category" class="form-label text-color-2 text-normal">Category Name</label>
                <input type="text" name="categoryName" class="form-control" id="edit_category" placeholder="e.g. Oral care">
              </div>
              <div class="col-12">
                <label for="desc" class="form-label text-color-2 text-normal">Category Description</label>
                <input type="text" class="form-control" name="categoryDesc" id="categorydescedit" placeholder="Description">
              </div>
              <div class="col-12">
                <input type="hidden" id="old_image" name="old_image">
                <label for="catimg" class="form-label text-color-2 text-normal">Upload Image</label>
                <img src="" id="edit_img_prev" alt="img" width="100px" height="100px" srcset="">
                <input type="file" class="form-control" name="cat_img" id="categoryImgedit">
              </div>

              <div class="col-12 mt-5">
                <button type="submit" name="update" class="btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">Update Informations</button>
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
  //add event listner
  document.getElementById('categoryEditModal').addEventListener('show.bs.modal', function(event) {
        let btn = event.relatedTarget; //helps to load extra data attributes(data-*)
        let image = btn.getAttribute('data-cimg');
        document.getElementById('edit_cid').value = btn.getAttribute('data-cid');
        document.getElementById('edit_category').value = btn.getAttribute('data-cname');
        document.getElementById('categorydescedit').value = btn.getAttribute('data-cdesc');
        document.getElementById('old_image').value = image; 
        document.getElementById('edit_img_prev').src = "category/"+image; 
      });
</script>
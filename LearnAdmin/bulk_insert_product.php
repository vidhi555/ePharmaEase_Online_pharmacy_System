<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$table = "ep_products";


if(isset($_POST['upload']))
{
    $file = $_FILES['csv_file']['tmp_name'];

    $handle = fopen($file,"r");

    fgetcsv($handle); // skip header row

    while(($data = fgetcsv($handle,1000,",")) !== FALSE)
    {
        $name = $data[0];
        $description = $data[1];
        $stock = $data[2];
        $price = $data[3];
        $image = $data[4];
        $category = $data[5];
        $expirydate = $data[6];
        $status = $data[7];
        
        

        $sql = "INSERT INTO `ep_products`(`name`, `description`, `stock`, `price`, `image`, `c_id`, `expiry_date`, `status`)
                VALUES(:name , :descrip , :stock , :price , :image , :cat ,:edate , :sts)";

        $result = $conn->prepare($sql);
        $result->execute([
            'name'=>$name,
            'descrip'=>$description,
            'stock'=>$stock,
            'price'=>$price,
            'image'=>$image,
            'cat'=>$category,
            'edate'=>$expirydate,
            'sts'=>$status,
        ]);
    }

    fclose($handle);

    echo "Products Uploaded Successfully";
}

$page_title = "Category";
require_once("header2.php");

?>


<body>
  <!-- Preloader -->
  <div id="preloader">
    <div class="spinner"></div>
  </div>
  <!-- Main Wrapper -->
  <div id="main-wrapper" class="d-flex">
    <?php
    //Sidebar content
    include_once('sidebar.php');
    ?>
    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Header -->
      <div class="header d-flex align-items-center justify-content-between">
        <?php
        $page_title = "Category page";
        require_once('header.php');
        ?>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <div class="row">
          <div class="col-12">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Category</li>

              </ol>
            </nav>

            <div class="category-header d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-color-2">Bulk Insertion Products:</h3>
              </div>
            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <div class="mt-4">
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                <button type="submit" class="btn btn-primary mt-3" name="upload">Upload CSV</button>
            </div>
        </form>
        </div>
      </div>
      <!-- Footer -->
      <?php
      include('footer.php');
      ?>
    </div>


    <!--Create  Modal -->
    <div class="modal fade" id="courseCreateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">
          <div class="modal-body p-4 position-relative">
            <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            <h2 class="h5 text-color-2 py-2">Create Category</h2>
            <form class="row g-3" method="post" enctype="multipart/form-data">
              <!-- <input type="hidden" name="c_id"> -->
              <div class="col-12">
                <label for="categoryName" class="form-label text-color-2 text-normal">Category Name</label>
                <input type="text" class="form-control" name="categoryName" id="categoryName" placeholder="e.g. Eye Care">
              </div>
              <div class="col-12">
                <label for="desc" class="form-label text-color-2 text-normal">Category Description</label>
                <input type="text" class="form-control" name="categoryDesc" id="categorydesc" placeholder="Description">
              </div>
              <div class="col-12">
                <label for="catimg" class="form-label text-color-2 text-normal">Upload Image</label>
                <input type="file" class="form-control" name="categoryimg" id="categoryImg">
              </div>

              <div class="col-12 mt-5">
                <button type="submit" name="submit" class="btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">Save Informations</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

   
<?php
require_once('db.php');
require('crud.php');

//check Admin Session
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$table = "ep_category";
if (isset($_POST['submit'])) {
  $cname = $_POST['categoryName'];
  $cdesc = $_POST['categoryDesc'];

   //image upload
  $pimg = $_FILES['categoryimg']['name'];
  $tempimg = $_FILES['categoryimg']['tmp_name'];
  $ext = pathinfo($pimg, PATHINFO_EXTENSION);
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];
  $img_name = "category_" . time() . "." . $ext;

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

  try {

    //ADD Category
    $query = $conn->prepare("INSERT INTO `$table`(c_id, category_name,description ,cat_image) VALUES (null,'$cname','$cdesc','$img_name')");
    $result = $query->execute();
    if ($result) {
      // echo "Category Added Successfuly";
      sweetAlert("Added!!!", "Category Added Successfuly", "success");
    } else {
      // echo "Fail ";
      sweetAlert("Fail!", "Category not Added!!!", "warning");
    }
  } catch (PDOException $e) {
    echo "Error:$e";
  }
}
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
if (isset($_POST['search_cat'])) {
  $search_cat = $_POST['search_cat'];
  // echo $search_cat;
  // die();
  try {
    $sql = "SELECT * FROM ep_category WHERE category_name LIKE '{$search_cat}%' LIMIT 5";
    $res = $conn->prepare($sql);
    if ($res->execute()) {
      $products = $res->fetchAll(PDO::FETCH_ASSOC);
      foreach ($products as $p) {
?>

        <tr>
                          <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                           <td><?= $p['c_id'] ?></td>
                           <td><img src="category/<?= $p['cat_image'] ?>" alt="cat_img" width="90px" height="90px"></td>
                          
                          <td><?= $p['category_name'] ?></td>
                          <td><?= substr($p['description'],0,50); ?>...</td>
                          <td class="text-center">
                            <a class="btn btn-sm btn-warning mb-2 mb-lg-0 me-0 me-lg-2" href="view_category_page.php?c_id=<?= $p['c_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>

                            <button type="button"
                              data-bs-toggle="modal"
                              data-bs-target="#categoryEditModal"
                              class="btn btn-sm btn-primary me-2"

                              data-cid="<?= $p['c_id'] ?>"
                              data-cname="<?= htmlspecialchars($p['category_name']) ?>"
                              data-cdesc="<?= htmlspecialchars($p['description']) ?>"
                              data-cimg="<?= $p['cat_image'] ?>"

                              ><i class="fa-regular fa-pen-to-square"></i></button>
                              <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['c_id'] ?>)"><i class="fa-solid fa-trash-can"></i></button>
                          </td>
                        </tr>
<?php
      }
    }
    if(!$products){
          echo "<tr><td colspan='4' class='text-center'> Category Not found!!</td></tr>";
    }
  } catch (PDOException $e) {
    echo $e;
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Category</title>
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
            window.location.href = "delete_category.php?c_id=" + id;
        }
    });
}
</script>

</head>

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
            <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-color-2">Category</h3>
              </div>
              <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center">

                  <!-- Date Range Button -->
                  <div class="cursor-pointer bg-white d-flex align-items-center text-color-1 px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="input-group flex-nowrap">
                      <span style="border:none;" class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                      <input style="border:none;" type="text" id="livesearch" name="search" class="form-control search-input border-l-none ps-0" placeholder="Search Category" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                  </div>
                  <!-- Reports Button -->
                  <a href="#" data-bs-toggle="modal" data-bs-target="#courseCreateModal" class="cursor-pointer ms-4 bg-white bg-primary text-white d-flex align-items-center px-3 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">
                    <i class="fa-solid fa-plus me-3"></i>
                    Add Category
                  </a>
                </div>
              </div>
            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <div class="mt-4">
          <div class="card shadow-sm border-0">
            <div class="card-body p-0">
              <div class="table-responsive table-rounded-top">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <!-- <th><input type="checkbox" id="select-all" class="custom-checkbox"></th> -->
                       <th>ID</th>
                      
                      <th>Category Name</th>
                      <th>Description</th>

                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody id="result">
                    <?php


                    //Load Products Data

                    $limit = 5;
                    if (isset($_GET['page'])) {
                      $page = $_GET['page'];
                    } else {
                      $page = 1;
                    }
                    $offset = ($page - 1) * $limit;
                    $q = "SELECT * FROM ep_category LIMIT {$offset},{$limit}";
                    $res = $conn->prepare($q);
                    if ($res->execute()) {
                      $products = $res->fetchAll(PDO::FETCH_ASSOC);
                      // Id shows sequential numbers
                      $id = $offset;
                      foreach ($products as $index => $p) {
                      $id++;
                    ?>

                        <tr>
                          <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
                           <td><?php  echo $id; ?></td>
                           <td><img src="category/<?= $p['cat_image'] ?>" alt="cat_img" width="90px" height="90px">
                          
                          <?= $p['category_name'] ?></td>
                          <td><?= substr($p['description'],0,50); ?>...</td>
                          <td class="text-center">
                            <a class="btn btn-sm btn-warning mb-2 mb-lg-0 me-0 me-lg-2" href="view_category_page.php?c_id=<?= $p['c_id'] ?>"><i class="fa-regular fa-eye view-icon"></i></a>

                            <button type="button"
                              data-bs-toggle="modal"
                              data-bs-target="#categoryEditModal"
                              class="btn btn-sm btn-primary me-2"

                              data-cid="<?= $p['c_id'] ?>"
                              data-cname="<?= htmlspecialchars($p['category_name']) ?>"
                              data-cdesc="<?= htmlspecialchars($p['description']) ?>"
                              data-cimg="<?= $p['cat_image'] ?>"

                              ><i class="fa-regular fa-pen-to-square"></i></button>
                              <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['c_id'] ?>)"><i class="fa-solid fa-trash-can"></i></button>
                          </td>
                        </tr>
                    <?php
                      }
                    }

                    ?>
                  </tbody>
                </table>
              </div>

              <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <ul class="pagination">
                    <?php
                    $q = $conn->prepare("SELECT COUNT(*)
                                        FROM $table");
                    $q->execute();
                    $count = $q->fetchColumn();
                    // echo $count;
                    if ($count > 0) {
                      $pages = ceil($count / $limit);  //find total pages of all records per limit
                    ?>
                      <li class="page-item">
                        <?php if ($page > 1) { ?>
                          <a class="page-link" href="category.php?page=<?= $page - 1 ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                        <?php } ?>
                      </li>
                      <?php
                      for ($i = 1; $i <= $pages; $i++) {
                      ?>
                        <li class="page-item"><a class="page-link" href="category.php?page=<?= $i ?>"><?php echo $i; ?></a></li>
                      <?php
                      } ?>
                      <li class="page-item">
                        <?php if ($page < $pages) { ?>
                          <a class="page-link" href="category.php?page=<?= $page + 1 ?>" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                        <?php } ?>
                      </li>

                    <?php } ?>
                    <!-- <li class="page-item active"><a class="page-link" href="#">1</a></li>
                              <li class="page-item"><a class="page-link" href="#">2</a></li>
                              <li class="page-item"><a class="page-link" href="#"><i class="fas fa-ellipsis-h"></i></a></li>
                              <li class="page-item"><a class="page-link" href="#">6</a></li>
                              <li class="page-item"><a class="page-link" href="#">7</a></li>
                              <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                              </li> -->
                  </ul>
                </nav>
                <!-- <div class="d-flex justify-content-end">
                  <div class="page-selector">
                    <span>PAGE</span>
                    <select class="form-select" aria-label="Select page">
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    <span>OF 100</span>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
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

      //searching category
      $(document).ready(function() {
        function load_category() {

          var search_cat = $("#livesearch").val();

          // alert(search_cat);
          $.ajax({
            url: window.location.href,
            method: "POST",
            data: {
              search_cat: search_cat
            },

            success: function(data) {
              $("#result").html(data).show();
            }
          });

        }

        $(document).on("keyup", "#livesearch", function() {
          load_category();
        });

      });
    </script>
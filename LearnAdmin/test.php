
<?php
require_once("db.php");

if (isset($_POST['page'])) {


    $search = $_POST['search'] ?? '';

    $limit = 5;
    $page = $_POST['page'];
    $offset = ($page - 1) * $limit;

    $q = $conn->prepare("SELECT * FROM ep_category WHERE category_name LIKE :search LIMIT $offset , $limit");
    $q->execute([
      'search'=>$search.'%'
    ]);
    $data = $q->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $p) {
?>
        <tr>
            <td><?= $p['c_id'] ?></td>
            <td><?= $p['category_name'] ?></td>
            <td><?= substr($p['description'],0,50) ?>...</td>
            <td>
                <button class="btn btn-danger btn-sm">Delete</button>
            </td>
        </tr>
<?php
    }

    exit;
}
require_once('header2.php');

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
                    
                  </tbody>
                </table>
              </div>

              <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                  <!-- <ul class="pagination" id="pagination"></ul> -->
                  
                  <ul class="pagination">
                    <?php $search = $_POST['search'] ?? ''; ?>
                     <?= createPagination('ep_category', 'category_name', $search, 5); ?>
                  </ul>
                </nav>
            
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// function loadData(page = 1){
//     $.ajax({
//         url: window.location.href,
//         type: "POST",
//         data: {page: page},
//         success: function(data){
//             $("#result").html(data);
//         }
//     });
// }

// function loadPagination(){
//     $.ajax({
//         url: "pagination_category.php",
//         success: function(data){
//             $("#pagination").html(data);
//         }
//     });
// }

// // click pagination
// $(document).on("click",".page-btn",function(e){
//     e.preventDefault();
//     var page = $(this).data("page");
//     loadData(page);
// });

// // first load
// loadData();
// loadPagination();


function loadData(page = 1){
  let search = document.getElementById("livesearch").value;
  
    $.ajax({
        url: window.location.href,
        type: "POST",
        data: {page: page,
          search:search
        },
        success: function(data){
            $("#result").html(data);
        }
    });
}

function loadPagination(){
    $.ajax({
        url: "pagination_category.php",
        success: function(data){
            $("#pagination").html(data);
        }
    });
}

// click pagination
$(document).on("click",".page-btn",function(e){
    e.preventDefault();
    var page = $(this).data("page");
    loadData(page);
});
$(document).ready(function(){
  $(document).on("keyup","#livesearch",function(){
    var a = $(this).val();
    // alert(a);
    loadData(1);
    loadPagination();
  })
})

// first load
loadData();
loadPagination();

</script>
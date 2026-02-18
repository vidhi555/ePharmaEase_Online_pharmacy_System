<?php
require_once('db.php');
require('crud.php');


if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

//function for all boxes which display on dashboard
function counting_items($table)
{
  global $conn;
  $res = $conn->query("SELECT COUNT(*) FROM $table");
  $res->execute();
  $count = $res->fetchColumn();
  return $count;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <!-- Stylesheets -->
  <link rel="shortcut icon" href="./assets/images/logo6.ico" type="image/x-icon">
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/fontawesome.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/brands.min.css" rel="stylesheet">
  <link href="./assets/icons/fontawesome/css/solid.min.css" rel="stylesheet">
  <script src="./js/bootstrap.bundle.js"></script>
  <script src="./js/bootstrap.bundle.min.js"></script>
  <link href="./assets/css/style4.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



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
            <div class="d-flex align-items-lg-center flex-column flex-md-row  flex-lg-row  mt-3">
              <div class="flex-grow-1">
                <h3 class="mb-2 text-color-2">Admin Dashboard</h3>
              </div>
              <div class="mt-3 mt-lg-0">

              </div>
            </div><!-- end card header -->
          </div>
          <!--end col-->
        </div>
        <div class="mt-4">
          <div class="row">
            <div class="col-lg-4">
              <div class="row">
                <!-- Total Students Card -->
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                  <a href="products.php">
                    <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="stats-label">Total Products</div>
                        <div class="stats-value">
                          <?php
                          $table = "ep_products";
                          echo counting_items($table);
                          ?>
                        </div>
                      </div>
                      <div class="icon-wrapper icon-purple">
                        <i class="fas fa-pills"></i>
                      </div>
                    </div>
                  </div>
                  </a>
                </div>

                <!-- Total customers -->
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                  <a href="customers.php">
                    <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="stats-label">Total Customers</div>
                        <div class="stats-value">
                          <?php
                          $table = "ep_users";
                          $res = $conn->query("SELECT COUNT(*) FROM $table WHERE role = 'customer'");
                          $res->execute();
                          $count = $res->fetchColumn();
                          echo $count;

                          ?>
                        </div>

                      </div>
                      <div class="icon-wrapper icon-purple">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  </a>
                </div>

                <!-- Total Courses Card -->
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                  <a href="category.php">
                    <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="stats-label">Total Category</div>
                        <div class="stats-value">
                          <?php
                          $table = "ep_category";
                          echo counting_items($table);

                          ?>
                        </div>

                      </div>
                      <div class="icon-wrapper icon-red">
                        <i class="fas fa-th-large"></i>
                      </div>
                    </div>
                  </div>
                  </a>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="row">
                <!-- Overall Revenue Card -->
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                  <a href="orders.php">
                    <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="stats-label">Total Orders Revenue</div>
                        <?php
                        $table = "ep_orders_master";
                        $res = $conn->query("SELECT * FROM $table WHERE order_status = 'delivered' AND payment_status = 'paid'");
                        $res->execute();
                        $orders = $res->fetchALL(PDO::FETCH_ASSOC);
                        $total = 0;
                        foreach ($orders as $o) {
                          $total += $o['total_amount'];
                        }

                        ?>
                        <div class="stats-value">₹<?= $total ?></div>
                        <!-- <div class="trend-wrapper">
                          This month
                          <span class="trend-up">
                            <i class="fas fa-arrow-up"></i> 8.5%
                          </span>
                        </div> -->
                      </div>
                      <div class="icon-wrapper icon-green">
                        <i class="fas fa-rupee-sign"></i>
                      </div>
                    </div>
                  </div>
                  </a>
                </div>
                <!-- Total customers -->
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                  <a href="userreview.php">
                    <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="stats-label">Total User Review</div>
                        <div class="stats-value">
                          <?php
                          $table = "ep_review";
                          echo counting_items($table);

                          ?>
                        </div>

                      </div>
                      <div class="icon-wrapper icon-purple">
                        <i class="fas fa-list"></i>
                      </div>
                    </div>
                  </div>
                  </a>
                </div>

                <!-- Total Courses Card -->
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                  <a href="message_report.php">
                    <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="stats-label">Total User Comments</div>
                        <div class="stats-value">
                          <?php
                          $table = "ep_message";
                          echo counting_items($table);

                          ?>
                        </div>

                      </div>
                      <div class="icon-wrapper icon-red">
                        <i class="fas fa-comment-dots"></i><!-- with dots -->
                      </div>
                    </div>
                  </div>
                  </a>
                </div>

                <!-- Overall Revenue Card -->
                <!-- <div class="col-12 col-md-6 col-lg-12 mb-4">
                  <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="stats-label">Total Orders Revenue</div>
                        <?php
                        // $table = "ep_orders_master";
                        // $res = $conn->query("SELECT * FROM $table");
                        // $res->execute();
                        // $orders = $res->fetchALL(PDO::FETCH_ASSOC);
                        // $total = 0;
                        // foreach ($orders as $o) {
                        //   $total += $o['total_amount'];
                        // }

                        ?>
                        <div class="stats-value">₹<?= $total ?></div>
                        <div class="trend-wrapper">
                          This month
                          <span class="trend-up">
                            <i class="fas fa-arrow-up"></i> 8.5%
                          </span>
                        </div>
                      </div>
                      <div class="icon-wrapper icon-green">
                        <i class="fas fa-rupee-sign"></i>
                      </div>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
            <!-- <div class="col-lg-5 mb-4 mb-lg-0">

              <div class="instructors-section card pb-0">
                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center py-3">
                  <h5 class="mb-0 text-color-2">Traffic Sources</h5>
                  <div>
                    <select class="form-select form-select-sm w-auto border-0 text-color-3" aria-label="Select time period">
                      <option value="30 days" selected>30 days</option>
                      <option value="15 days">15 days</option>
                    </select>
                  </div>
                </div>
                <div class="card-body p-0 mt-40">
                  <div class="mb-2">
                    <div class="chart-container">
                      <canvas id="trafficChart"></canvas>
                    </div>
                    <div class="mx-5 mt-5 traffic-legend">
                      <table class="table table-borderless">
                        <tbody>
                          <tr>
                            <td><span class="organic text-color-1">Organic Search</span></td>
                            <td><span class="text-color-2">4,305</span></td>
                          </tr>
                          <tr>
                            <td><span class="referrals text-color-1">Referrals</span></td>
                            <td><span class="text-color-2">482</span></td>
                          </tr>
                          <tr>
                            <td><span class="social-media text-color-1">Social Media</span></td>
                            <td><span class="text-color-2">859</span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="instructors-section card pb-1">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                  <h5 class="mb-0 text-color-2">User Review</h5>
                  <a href="userreview.php" class="text-color-3">View All</a>
                </div>
                <div class="card-body p-0">
                  <ul class="list-group list-group-flush">
                    <?php
                    try {
                      $reviewes = $conn->prepare("SELECT * FROM ep_review r JOIN ep_users u ON r.u_id = u.u_id ORDER BY rate DESC LIMIT 5");
                      $reviewes->execute();
                      $fetch_review = $reviewes->fetchAll(PDO::FETCH_ASSOC);
                      foreach ($fetch_review as $a) {
                    ?>
                        <li class="list-group-item d-flex align-items-center py-3">
                          <div class="avatar rounded-circle bg-primary text-white me-3">
                            <?php
                            $user = ucfirst($a['name']);
                            echo substr($user, 0, 1);
                            ?></div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0 text-color-2"><?= $a['name'] ?></h6>
                            <small class="text-color-3"><?= $a['email'] ?></small>
                          </div>
                          <div class="text-end">
                            <div class="rating-stars text-size-13">
                              <?php
                              $count_start = $a['rate'];
                              for ($i = 1; $i <= $count_start; $i++) {
                                echo "<i class='fas fa-star'></i>";
                              }
                              ?>
                            </div>
                            <!-- <small class="d-block text-color-3">25 Reviews</small> -->
                          </div>
                        </li>
                    <?php
                      }
                    } catch (PDOException $e) {
                      echo $e;
                    }
                    ?>
                    <!-- <li class="list-group-item d-flex align-items-center py-3">
                      <div class="avatar rounded-circle bg-primary text-white me-3">AB</div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 text-color-2">Sofnio</h6>
                        <small class="text-color-3">info@softnio.com</small>
                      </div>
                      <div class="text-end">
                        <div class="rating-stars text-size-13">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                        </div>
                        <small class="d-block text-color-3">25 Reviews</small>
                      </div>
                    </li> -->
                    <!-- <li class="list-group-item d-flex align-items-center py-3">
                      <div class="avatar rounded-circle bg-info text-white me-3">AL</div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 text-color-2">Ashley Lawson</h6>
                        <small class="text-color-3">ashley@softnio.com</small>
                      </div>
                      <div class="text-end">
                        <div class="rating-stars text-size-13">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                        </div>
                        <small class="d-block text-color-3">22 Reviews</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center py-3">
                      <div class="avatar rounded-circle bg-success text-white me-3">JM</div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 text-color-2">Jane Montgomery</h6>
                        <small class="text-color-3">jane84@example.com</small>
                      </div>
                      <div class="text-end">
                        <div class="rating-stars text-size-13">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star-half-alt"></i>
                        </div>
                        <small class="d-block text-color-3">19 Reviews</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center py-3">
                      <div class="avatar rounded-circle bg-secondary text-white me-3">LH</div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 text-color-2">Larry Henry</h6>
                        <small class="text-color-3">larry108@example.com</small>
                      </div>
                      <div class="text-end">
                        <div class="rating-stars text-size-13">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star-half-alt"></i>
                        </div>
                        <small class="d-block text-color-3">24 Reviews</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center py-3">
                      <div class="avatar rounded-circle bg-secondary text-white me-3">LH</div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 text-color-2">Larry Henry</h6>
                        <small class="text-color-3">larry108@example.com</small>
                      </div>
                      <div class="text-end">
                        <div class="rating-stars text-size-13">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star-half-alt"></i>
                        </div>
                        <small class="d-block text-color-3">24 Reviews</small>
                      </div>
                    </li> -->
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-8 mb-4 mb-lg-0">

              <div class="instructors-section card">
                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center py-3">
                  <h5 class="mb-0 text-color-2">Conversions <script>
                      document.write(new Date().getFullYear());
                    </script>
                  </h5>
                  <!-- <div>
                    <select class="form-select form-select-sm w-auto border-0 text-color-3" aria-label="Select time period">
                      <option value="30 days" selected>30 days</option>
                      <option value="15 days">15 days</option>
                    </select>
                  </div> -->
                </div>
                <div class="card-body">
                  <canvas id="barChart" class="mt-5" height="96"></canvas>
                </div>
              </div>
              <?php
              try {
                //Two arrays
                $completed = [];
                $pending = [];

                //iterate for 12 months to check How much order is placed in a month
                for ($i = 1; $i <= 12; $i++) {
                  $query = $conn->prepare("SELECT COUNT(*) FROM `ep_orders_master` WHERE MONTH(oder_date) = $i  AND order_status = 'Placed' ");
                  $query->execute();
                  $pending[] = $query->fetchColumn();
                  // print_r($pending);
                }

                //iterate for 12 months to check How much order is Delivered in a month
                for ($i = 1; $i <= 12; $i++) {
                  $query = $conn->prepare("SELECT COUNT(*) FROM `ep_orders_master` WHERE MONTH(oder_date) = $i  AND order_status = 'delivered' ");
                  $query->execute();
                  $completed[] =  $query->fetchColumn();
                }
                // echo json_encode($completed);
                // echo json_encode($pending);


              } catch (PDOException $e) {
                echo $e;
              }
              ?>
            </div>
            <div class="col-lg-4 mt-3">
              <div class="instructors-section card pb-1">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                  <h5 class="mb-0 text-color-2">Top Categories</h5>
                  <a href="category.php" class="text-color-3">View All</a>
                </div>
                <div class="card-body p-0">
                  <ul class="list-group list-group-flush">

                    <?php
                    $q = $conn->prepare("SELECT DISTINCT c.category_name , c.c_id FROM ep_products p JOIN ep_category c ON c.c_id = p.c_id ORDER BY category_name LIMIT 5");
                    if ($q->execute()) {
                      $row = $q->fetchAll(PDO::FETCH_ASSOC);

                      foreach ($row as $r) {
                    ?>
                        <li class="list-group-item d-flex align-items-center py-3">
                          <div class="avatar rounded-circle bg-primary text-white me-3">
                            <?php
                            $fletter = ucfirst($r['category_name']);
                            echo substr($fletter, 0, 1);

                            // Count total products
                            $query = $conn->prepare("SELECT COUNT(*) FROM ep_products p JOIN ep_category c ON p.c_id= c.c_id WHERE category_name = :cname ");
                            $query->execute(['cname' => $fletter]);
                            $count = $query->fetchColumn();
                            ?>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0 text-color-2"><?= ucfirst($r['category_name']) ?></h6>
                            <small class="text-color-3"><?= $count ?>+ Products</small>
                          </div>
                          <div class="text-end">
                            <a href="category_wise_listing.php?c_id=<?= $r['c_id'] ?>"><i class="fa-solid fa-chevron-right arrow-icon"></i></a>
                          </div>
                        </li>
                    <?php
                      }
                    }
                    ?>


                  </ul>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- Footer -->
      <?php include('footer.php'); ?>
    </div>


    <!-- Scripts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script>
      const barChart = document.getElementById('barChart');
      if (barChart) {
        new Chart(barChart.getContext('2d'), {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Completed Orders',
                data: <?php echo json_encode($completed) ?>,
                backgroundColor: '#43A9D4',
                borderRadius: 8,
                barThickness: 15
              },
              {
                label: 'Pending Orders',
                data: <?php echo json_encode($pending) ?>,
                backgroundColor: '#68D137',
                borderRadius: 8,
                barThickness: 15
              }
            ]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: true,

              },
              tooltip: {
                enabled: true
              }
            },
            scales: {
              x: {
                stacked: true,
                grid: {
                  display: false
                }
              },
              y: {
                stacked: true,

                min: 0,

                grid: {
                  display: true
                },
                ticks: {
                  display: true,
                  stepSize: 1
                }
              }
            },
          }
        });
      }
    </script>

</body>

</html>
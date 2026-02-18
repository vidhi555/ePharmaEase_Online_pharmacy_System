<?php
require_once 'db.php';
$table = "ep_products";
$limit = 5;
$page  = isset($_POST['page']) ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;

$search  = $_POST['search'] ?? '';
$status = $_POST['status'] ?? '';

$where = "WHERE 1";

if (!empty($search)) {
    $where .= " AND name LIKE '$search%'";
}

if ($status !== '') {
    $where .= " AND status = '$status'";
}

// Fetch products
$query = "SELECT * FROM $table $where LIMIT $start, $limit";
$result = $conn->prepare($query);
$result->execute();
// Display products
while ($p = $result->fetch(PDO::FETCH_ASSOC)) {
    // echo "<div>
    //         <b>{$row['name']}</b> - ₹{$row['price']}
    //         <span>".($row['status'] ? 'Active' : 'Inactive')."</span>
    //       </div>";
?>
    <tr>
        <td><input type="checkbox" class="custom-checkbox row-checkbox"></td>
        <td>
            <div class="d-flex justify-content-start align-items-center">
                <img src="./upload/<?= $p['image'] ?>" class="tbl-img" alt="img">
                <span class="ms-2"><?= $p['name'] ?></span>
            </div>
        </td>
        <!-- <td><img class="tbl-img"  src="upload/<?= $p['image'] ?>" alt=""></td> -->
        <td style="width: 1000px;word-wrap: break-word;white-space: normal;"><?= $p['description'] ?></td>
        <td>₹<?= $p['price'] ?></td>
        <td><?= $p['stock'] ?></td>
        <td>
            <?= $p['c_id']  ?>
        </td>
        <td><?= $p['expiry_date'] ?></td>
        <td><?= $p['status'] == "Active" ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-warning'>In-active</span>" ?></td>
        <td class="text-center">

            <!-- <a href="edit.php?p_id=<?= $p['p_id'] ?>" name="update" data-bs-toggle="modal" data-bs-target="#EditModal" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"><i class="fa-regular fa-pen-to-square"></i></a> -->

            <button type="button"
                class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"
                data-bs-toggle="modal"
                data-bs-target="#EditModal">
                <i class="fa-regular fa-pen-to-square"></i>
            </button>

            <!-- <a href="edit.php?p_id=<?= $p['p_id'] ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"><i class="fa-regular fa-pen-to-square"></i></a> -->
            <a href="delete_product.php?p_id=<?= $p['p_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash-can"></i></a>

        </td>
    </tr>

    <?php


    // Pagination count
    $countQuery = "SELECT COUNT(*) FROM $table $where";
    $countResult = $conn->prepare($countQuery);

    $totalRow = $countResult->rowCount();
    $totalPages = ceil($totalRow / $limit);

    // Pagination buttons
    echo "<div class='pagination'>";
    ?>
    <?php
    //find total pages of all records per limit
    ?>
    <li class="page-item">
        <?php if ($totalPages > 1) { ?>
            <a class="page-link" href="search_page.php?page=<?= $totalPages - 1 ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
        <?php } ?>
    </li>
    <?php
    for ($i = 1; $i <= $totalPages; $i++) {
    ?>
        <li class="page-item"><a class="page-link" href="search_page.php?page=<?= $i ?>"><?php echo $i; ?></a></li>
    <?php
    } ?>
    <li class="page-item">
        <?php if ($totalPages < $totalPages) { ?>
            <a class="page-link" href="search_page.php?page=<?= $totalPages + 1 ?>" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
        <?php } ?>
    </li>

<?php }


?>
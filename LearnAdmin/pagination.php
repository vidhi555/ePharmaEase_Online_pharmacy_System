<?php
require_once("db.php");


//collect inputs
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$page = $_GET['page'] ?? '1';

$limit = 5;
$offset = ($page - 1) * $limit;



//pagination - display products accordingly
// $limit = 5;
// if (isset($_GET['page'])) {
//     //if you click on pagination button - product.php?page = 2
//     $page = $_GET['page'];
// } else {
//     //default it redirect first button(Diplay first 5 records)
//     $page = 1;
// }
// $offset = ($page - 1) * $limit;


$where = "WHERE 1";
$params = [];

//now check search is availabel
if(!empty($search)){
    $where = "name LIKE '{$input}%'";
}
if(!empty($status)){
    $where = "status = {$status}";
}
$q = "SELECT p.p_id, p.c_id,c.category_name, p.name, p.description,
                                p.stock, p.price, p.image, p.expiry_date, p.status
                                FROM ep_products p
                                JOIN ep_category c ON p.c_id = c.c_id
                                ORDER BY p.p_id DESC
                                LIMIT {$offset} , {$limit}";

$res = $conn->prepare($q);
if ($res->execute()) {
    $products = $res->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $p) {
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
                <?= $p['category_name']  ?>
            </td>
            <td><?= $p['expiry_date'] ?></td>
            <td><?= $p['status'] == "Active" ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-warning'>In-active</span>" ?></td>
            <td class="text-center">

                <!-- <a href="edit.php?p_id=<?= $p['p_id'] ?>" name="update" data-bs-toggle="modal" data-bs-target="#EditModal" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"><i class="fa-regular fa-pen-to-square"></i></a> -->

                <button type="button"
                    class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"
                    data-bs-toggle="modal"
                    data-bs-target="#EditModal"

                    data-id="<?= $p['p_id'] ?>"
                    data-name="<?= htmlspecialchars($p['name']) ?>"
                    data-desc="<?= htmlspecialchars($p['description']) ?>"
                    data-price="<?= $p['price'] ?>"
                    data-stock="<?= $p['stock'] ?>"
                    data-expiry="<?= $p['expiry_date'] ?>"
                    data-status="<?= $p['status'] ?>"
                    data-category="<?= $p['c_id']  ?>"
                    data-img="<?= $p['image'] ?>">
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>

                <!-- <a href="edit.php?p_id=<?= $p['p_id'] ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2"><i class="fa-regular fa-pen-to-square"></i></a> -->
                <a href="delete_product.php?p_id=<?= $p['p_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash-can"></i></a>

            </td>
        </tr>
<?php
    }
}

?>
<?php
if (!empty($search)) {
    //if i want to search product

} else if (isset($status)) {
    //if i want to sorting product as active & in-active

} else {
    //display products with pagination
}
//reusable function for pagination
function pagination($table, $offset, $limit)
{
    global $conn;
    $q = $conn->prepare("SELECT * FROM $table LIMIT {$offset} , {$limit}");
    $r = $q->execute();
    return $r;
}
?>
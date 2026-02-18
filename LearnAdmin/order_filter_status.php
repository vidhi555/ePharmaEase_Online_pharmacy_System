<?php
require_once("db.php");
if (isset($_POST['orderstatus'])) {
    $orderstatus = $_POST['orderstatus'];

    $show_orders = $conn->prepare("SELECT * FROM ep_orders_master WHERE order_status = :os ");
    $show_orders->execute(['os' => $orderstatus]);

    $fetch_orders = $show_orders->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fetch_orders as $order) {
?>
        <tr>
            <!-- <td><input type="checkbox" class="custom-checkbox row-checkbox"></td> -->
            <td><?= $order['o_id'] ?></td>
            <td><?= $order['u_id'] ?></td>
            <td><?= $order['oder_date'] ?></td>
            <td><?= $order['fname'];
                $order['lname'] ?></td>
            <td><?= $order['mobile'] ?></td>
            <td><?= $order['city'];
                $order['district'];
                $order['country'];
                $order['zip'] ?></td>
            <td><?= $order['total_amount'] ?></td>
            <td><?= $order['payment_method'] ?></td>
            <td><?= $order['payment_status'] ?></td>
            <td><?= $order['order_status'] ?></td>



            <td class="text-center">
                              <?php 
                              $disable = "";
                                if($order['payment_status'] == 'pending' || $order['order_status'] == 'delivered'){
                                  $disable = "disabled btn-disabled";
                                }
                              ?>
                              <a href="#" data-bs-toggle="modal"
                                data-bs-target="#EditModal"
                                data-status=<?= $order['order_status'] ?>
                                data-orderid=<?= $order['o_id'] ?>
                                data-o_id=<?= $order['o_id'] ?>
                                data-pay-status=<?= $order['payment_status'] ?>
                                class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2 "><i class="fa-regular fa-pen-to-square"></i></a>
                              <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $order['o_id'] ?>)"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
        </tr>
<?php
    }
}
?>
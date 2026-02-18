<?php
if (isset($_POST['cart'])) {
    try {
        $pid = $_POST['product_id'];

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
        $guest_id = $user_id ? NULL : session_id();

        // echo "User".$user_id."/";
        // echo "Guest User".$guest_id;
        // die();


        if ($user_id) {
            $check = $conn->prepare(
                "SELECT cart_id FROM ep_cart WHERE p_id = :pid AND u_id = :uid"
            );
            $check->execute([
                'pid' => $pid,
                'uid' => $user_id
            ]);
        } else {
            $check = $conn->prepare(
                "SELECT cart_id FROM ep_cart WHERE p_id = :pid AND guest_id = :gid"
            );
            $check->execute([
                'pid' => $pid,
                'gid' => $guest_id
            ]);
        }

        if ($check->rowCount() > 0) {
            sweetAlert("Already in your cart!!", "This product is Already exist in your cart!!!", "warning");
        } else {
            // fetch product
            $product = $conn->prepare(
                "SELECT name, price FROM ep_products WHERE p_id = :pid"
            );
            $product->execute(['pid' => $pid]);
            $p = $product->fetch(PDO::FETCH_ASSOC);

            if (!$p) {
                // $_SESSION['warning'] = "Product not found";
                sweetAlert("Error!", "Product Not Found!!", "warning");
            } else {
                // insert into cart
                $insert = $conn->prepare(
                    "INSERT INTO ep_cart (u_id, p_id, pname, qty, price,guest_id)
                     VALUES (:uid, :pid, :pname, 1, :price,:gid)"
                );
                // echo $guest_id;
                // die();
                $insert->execute([
                    'uid'   => $user_id,
                    'pid'   => $pid,
                    'pname' => $p['name'],
                    'price' => $p['price'],
                    'gid' => $guest_id
                ]);
                sweetAlert("Item Added!", "Successfully Added in Your cart!", "success");
            }
        }
    } catch (PDOException $e) {
        // echo "ERROR: " . $e->getMessage();
        sweetAlert("Error!", "$e", "error");
    }
}
?>

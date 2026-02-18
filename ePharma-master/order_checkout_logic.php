<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("PHPMailer/src/Exception.php");
require_once("PHPMailer/src/PHPMailer.php");
require_once("PHPMailer/src/SMTP.php");

ob_start();

$errors = [];
//calulate shipping

   
if ($user_id) {
    $total = $conn->prepare("SELECT SUM(qty * price) as gTotal FROM ep_cart WHERE u_id = $user_id");
    $total->execute();
} else {
    $total = $conn->prepare("SELECT SUM(qty * price) as gTotal FROM ep_cart WHERE guest_id = '$guest_id'");
    $total->execute();
}


$result = $total->fetch(PDO::FETCH_ASSOC);
$subtotal1 = $result['gTotal'];
if ($subtotal1 > 1000) {
    $shipping = 0;
} else {
    $shipping = 50;
}
$grand_total = $shipping + $subtotal1;
// echo $grand_total;
// die();


//checkout process
try {
    if (isset($_POST['place_order'])) {
        if (!empty($user_id)  || !empty($guest_id)) {
            // $conn->beginTransaction();      //if both query will be run , then only store in DB otherwise Not 
            //User is logged in
            $fname = htmlspecialchars($_POST['fname']);
            $lname = htmlspecialchars($_POST['lname']);
            $mobile = $_POST['mno'];
            $email = $_POST['email'];
            $country = $_POST['country'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $district = $_POST['district'];
            $zip = $_POST['zip'];
            $payment = $_POST['payment'] ?? '';
            $create_acc  = $_POST['create_account'] ?? '';
            $password = $_POST['password'] ?? '';


            //check Empty Fields
            if (
                empty($fname) || empty($lname) || empty($mobile) ||
                empty($email) || empty($country) || empty($address) ||
                empty($city) || empty($district) || empty($zip)
            ) {
                // sweetAlert("Required!", "Please fill the Required Fields!", "warning");
                $errors[] = "Please fill the Required Fields!";
            }
            if (empty($payment)) {
                // sweetAlert("Warning", "Please Select the Payment Option!", "warning");
                $errors[] = "Please Select the Payment Option!";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // sweetAlert("Not Valid", "Invalid Email Format!", "warning");
                $errors[] = "Invalid Email Format!";
            }
            if (strlen($mobile) != 10) {
                $errors[] = "Mobile Number must be 10 digits!!";
                // sweetAlert("Not Valid", "Mobile Number must be 10 digits!!", "warning");
            }
            if (strlen($zip) != 6) {
                // sweetAlert("Not Valid", "Wrong Pincode!!", "warning");
                $errors[] = "Wrong Pincode!!!";
            }
            if (strlen($fname) < 2 && strlen($lname) < 2) {
                // sweetAlert("Not Valid", "Please Enter your Name Properly!", "warning");
                $errors[] = "Please Enter your Name Properly!";
            }
            if (!empty($errors)) {
                sweetAlert("Error!", "Please Try Again!!!!", "error");
            } else {
                 if(!isset($create_acc)){
                        $sql = "INSERT INTO `ep_users`( `name`, `email`,`password`, `mobile`, `dob`, `address`, `gender`, `image`, `role`) VALUES (:username,:email,:password,:mobile,:dob,:address,:gender,:photo,:role)";
                        $add_acc = $conn->prepare($sql);
                        $data = $add_acc->execute([
                            'username' => $fname." ".$lname,
                            'email' => $email,
                            'password' => $password,
                            'mobile' => $mobile,
                            'dob' => $dob,
                            'address' => $address,
                            'gender' => '',
                            'photo' => "user.jpg",
                            'role' => 'customer'
                        ]);
                       
                    }
                    
                $query = "INSERT INTO ep_orders_master(u_id, fname, lname, mobile , email, city, district,country, zip,  total_amount, payment_method, address) VALUES (:uid , :fname , :lname , :mobile , :email , :city , :district ,:country, :zip ,:total_amt , :pay_method , :address )";
                $order = $conn->prepare($query);
                $result = $order->execute([
                    'uid' => $user_id,
                    'fname' => $fname,
                    'lname' => $lname,
                    'mobile' => $mobile,
                    'email' => $email,
                    'city' => $city,
                    'district' => $district,
                    'country' => $country,
                    'zip' => $zip,
                    'total_amt' => $grand_total,
                    'pay_method' => $payment,
                    'address' => $address
                ]);

                $order_id = $conn->lastInsertId();
                if (!$order_id) {
                    echo "Order ID is not generated!";
                }
                $_SESSION['last_order_id'] = $order_id;

                $inner_query = $conn->prepare("SELECT * FROM ep_cart WHERE u_id = :uid");
                $inner_query->execute(['uid' => $user_id]);
                $fetch_order_data = $inner_query->fetchAll(PDO::FETCH_ASSOC);

                $insert_item = $conn->prepare("INSERT INTO `ep_orders_items`(`o_id`, `p_id`, `u_id`, `qty`, `price`) VALUES (:oid, :pid , :uid ,:qty , :price)");
                foreach ($fetch_order_data as $f) {

                    $insert_item->execute([
                        'oid' => $order_id,
                        'pid' => $f['p_id'],
                        'uid' => $f['u_id'],
                        'qty' => $f['qty'],
                        'price' => $f['price'],

                    ]);
                }
                $clear_cart = $conn->prepare("DELETE FROM ep_cart WHERE u_id = :uid");
                $clear_cart->execute(['uid' => $user_id]);

                //Mail
                $order_data = $conn->prepare("SELECT * FROM ep_orders_master WHERE o_id = :oid");
                $order_data->execute(['oid' => $order_id]);
                $fetch_data = $order_data->fetch(PDO::FETCH_ASSOC);
                if ($fetch_data) {
                    $payment_method = $fetch_data['payment_method'] == 'COD' ? 'Cash on Delivery' : 'Online Payment';
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = "tls";
                        $mail->Port = 587;
                        $mail->Username = "pvidhi782@gmail.com";
                        $mail->Password = "ibcialcohyfmmvll";
                        $mail->setFrom("pvidhi782@gmail.com", "ePharmaEase");
                        $mail->addAddress($email);
                        $mail->Subject = "Your Order Has Been Confirmed - Thank You for Shopping with ePharmaEase";

                        $mail->Body = "Dear {$fname} {$lname},
                            
Thank you for placing your order with ePharmaEase! 👍
We are happy to let you know that your order has been successfully confirmed.

🧾 Order Details:

Order ID: {$order_id}
Order Date: {$fetch_data['oder_date']}
Total Amount: ₹ {$fetch_data['total_amount']}
Payment Method: {$payment_method}

Your order is currently being processed and will be shipped soon. 

📦 Shipping Address

{$fetch_data['address']}, {$fetch_data['city']}, {$fetch_data['country']}

If you have any questions or need assistance, feel free to contact us.

Thank you for choosing ePharmaEase for your healthcare needs. We wish you good health and a great day ahead!

Warm regards,
Team ePharmaEase
🌐 www.epharmaease.com
                            ";
                        $mail->send();
                    } catch (Exception $ex) {
                        echo $ex;
                    }
                   
                }

                sweetAlert("Order Confirmed", "Succesfull", "success");
                header("Location: confirmation.php");

                //Reduce stocks from products
                $query_Stock = $conn->prepare("SELECT * FROM ep_orders_items WHERE o_id = :oid ");
                $query_Stock->execute(['oid' => $order_id]);
                $fetch_stock = $query_Stock->fetchAll(PDO::FETCH_ASSOC);
                $update_stock = $conn->prepare("UPDATE ep_products SET stock = stock - :qty WHERE p_id = :pid AND stock > :qty");
                foreach ($fetch_stock as $s) {
                    $update_stock->execute([
                        "qty" => $s['qty'],
                        "pid" => $s['p_id']
                    ]);
                    if ($update_stock->rowCount() == 0) {
                        $errors[] = "Insufficient Stock for Product ID:" . $s['p_id'];
                    }
                }
            }
        } else {
            echo "Log In First!";
        }
    }
} catch (PDOException $e) {
    echo $e;
}

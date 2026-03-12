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
if ($subtotal1 > 10) {
    $shipping = 0.00;
} else {
    $shipping = 1.20;
}
$grand_total = $shipping + $subtotal1;
// echo $grand_total;
// die();


//checkout process
try {
    if (isset($_POST['place_order'])) {

        if (!empty($user_id)  || !empty($guest_id)) {

            $conn->beginTransaction();      //if both query will be run , then only store in DB otherwise Not 


            //User is logged in
            $fname = htmlspecialchars($_POST['fname']);
            $lname = htmlspecialchars($_POST['lname']);
            $mobile = $_POST['phone'];
            $email = $_POST['email'];
            $country = $_POST['country'];
            $address = $_POST['address'];
            $city = $_POST['city'];

            $zip = $_POST['zip'];
            $payment = $_POST['payment'] ?? '';
            $create_acc  = $_POST['create_account'] ?? '';
            $ship_message = $_POST['ship_message'] ?? NULL;
            $ship_checkbox = $_POST['check_ship_diff'] ?? '';


            $password = $_POST['password'] ?? '';
            $hash_password = password_hash($password, PASSWORD_DEFAULT);


            //check Empty Fields
            if (
                empty($fname) || empty($lname) || empty($mobile) ||
                empty($email) || empty($country) || empty($address) ||
                empty($city)  || empty($zip)
            ) {
                // sweetAlert("Required!", "Please fill the Required Fields!", "warning");
                $errors[] = "Please fill the All Required Fields!";
            }
            if (empty($payment)) {
                // sweetAlert("Warning", "Please Select the Payment Option!", "warning");
                $errors[] = "Please Select the Any 1 Payment Option!";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // sweetAlert("Not Valid", "Invalid Email Format!", "warning");
                $errors[] = "Invalid Email Format!";
            }
            // if (strlen($mobile) != 10) {
            //     $errors[] = "Mobile Number must be 10 digits!!";
            //     // sweetAlert("Not Valid", "Mobile Number must be 10 digits!!", "warning");
            // }
            if (!preg_match("/^\+?[0-9]{10,15}$/", $mobile)) {
                $errors[] = "Invalid Mobile number Length!";
            }
            if (!preg_match("/^[0-9]{5,6}$/", $zip)) {
                // sweetAlert("Not Valid", "Wrong Pincode!!", "warning");
                $errors[] = "Invalid Pincode!!!";
            }
            if (strlen($fname) < 2 && strlen($lname) < 2) {
                // sweetAlert("Not Valid", "Please Enter your Name Properly!", "warning");
                $errors[] = "Please Enter your Name Properly!";
            }
            if (!isset($_POST['t&c'])) {
                $errors[] = "Please Check Terms & Condition!!";
            }


            if (!empty($errors)) {
                sweetAlert("Error!", "Please Try Again!!!!", "error");
            } else {

                // Guest User create account
                if (!isset($_SESSION['user_id'])) {

                    $sql = "INSERT INTO `ep_users`( `name`, `email`,`password`, `mobile`,  `address`, `gender`, `image`, `role`) VALUES (:username,:email,:password,:mobile,:address,:gender,:photo,:role)";
                    $add_acc = $conn->prepare($sql);
                    $data = $add_acc->execute([
                        'username' => $fname . " " . $lname,
                        'email' => $email,
                        'password' => $hash_password,
                        'mobile' => $mobile,

                        'address' => $address,
                        'gender' => 'male',
                        'photo' => "user.jpg",
                        'role' => 'customer'
                    ]);
                    $uid = $conn->lastInsertId();

                    // Update cart from guest user to logged in user
                    if ($guest_id) {
                        $update_cart = $conn->prepare("UPDATE ep_cart SET u_id = :uid , guest_id = NULL WHERE guest_id = :gid");
                        $update_cart->execute([
                            'uid' => $uid,
                            'gid' => $guest_id
                        ]);
                    }
                    // echo "Inserted USer:".$uid;
                    // echo "Guest:".$guest_id;
                    // echo "User:".$user_id;
                    // die();
                } else {
                    // Already Logged in user
                    $uid = $_SESSION['user_id'];
                    // echo $uid;
                    // die();
                }


                $query = "INSERT INTO ep_orders_master(u_id, fname, lname, mobile , email, city,country, zip,  total_amount, payment_method, address,order_notes,expected_date) VALUES (:uid , :fname , :lname , :mobile , :email , :city  ,:country, :zip ,:total_amt , :pay_method , :address ,:order_notes, :edate)";
                $order = $conn->prepare($query);
                $result = $order->execute([
                    'uid' => $uid,
                    'fname' => $fname,
                    'lname' => $lname,
                    'mobile' => $mobile,
                    'email' => $email,
                    'city' => $city,

                    'country' => $country,
                    'zip' => $zip,
                    'total_amt' => $grand_total,
                    'pay_method' => $payment,
                    'address' => $address,
                    'order_notes' => $ship_message,
                    // 'edate' => date('Y-m-d', strtotime("+1 day"))
                    // 'edate'=> DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY)
                    'edate' => date('Y-m-d', strtotime('+1 day'))
                ]);

                $order_id = $conn->lastInsertId();
                if (!$order_id) {
                    echo "Order ID is not generated!";
                }

                $_SESSION['last_order_id'] = $order_id;
                //  echo "Inserted USer:".$uid;
                // echo "Guest:".$guest_id;
                // echo "User:".$user_id;
                // die();

                // echo $_SESSION['last_order_id'];
                // die();


                // get product data from cart table to insert in order_item table
                $inner_query = $conn->prepare("SELECT * FROM ep_cart WHERE u_id = :uid");
                $inner_query->execute(['uid' => $uid]);
                $fetch_order_data = $inner_query->fetchAll(PDO::FETCH_ASSOC);


                $insert_item = $conn->prepare("INSERT INTO `ep_orders_items`(`o_id`, `p_id`, `u_id`, `qty`, `price`) VALUES (:oid, :pid , :uid ,:qty , :price )");
                foreach ($fetch_order_data as $f) {

                    // echo $f['u_id'];
                    // die();
                    $insert_item->execute([
                        'oid' => $order_id,
                        'pid' => $f['p_id'],
                        'uid' => $f['u_id'],
                        'qty' => $f['qty'],
                        'price' => $f['price'],


                    ]);
                }
                // $_SESSION['user_id'] = $uid



                //Mail
                $order_data = $conn->prepare("SELECT * FROM ep_orders_master WHERE o_id = :oid");
                $order_data->execute(['oid' => $order_id]);
                $fetch_data = $order_data->fetch(PDO::FETCH_ASSOC);
                $total_amount = $fetch_data['total_amount'];
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
                        Total Amount: $ {$fetch_data['total_amount']}
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
                    if ($fetch_data['payment_method'] == 'COD') {
                        // remove products from cart
                        $clear_cart = $conn->prepare("DELETE FROM ep_cart WHERE u_id = :uid");
                        $clear_cart->execute(['uid' => $uid]);

                        //Reduce stocks from products
                        $query_Stock = $conn->prepare("SELECT * FROM ep_orders_items WHERE o_id = :oid ");
                        $query_Stock->execute(['oid' => $order_id]);
                        $fetch_stock = $query_Stock->fetchAll(PDO::FETCH_ASSOC);
                        $update_stock = $conn->prepare("UPDATE ep_products SET stock = stock - :qty WHERE p_id = :pid AND stock >= :qty");
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


                    //Insert Payment details
                    sweetAlert("Order Confirmed", "Succesfull", "success");
                    $insert_payment_data = $conn->prepare("INSERT INTO `ep_payment`(`o_id`, `u_id`, `payment_method`, `total_amount`, `payment_status`) VALUES (:oid , :uid,  :pay_method, :total_amt, :p_status )");
                    $insert_payment_data->execute([
                        'oid' => $order_id,
                        'uid' => $uid,

                        'pay_method' => $payment_method,
                        'total_amt' => $total_amount,

                        'p_status' => 'pending',

                    ]);
                } else {
                    $errors[] = "Please check the create account checkbox!";
                }
                // set Session 
                $_SESSION['user_id'] = $uid;
                // Store user in cookie
                setcookie("userid_session" , $_SESSION['user_id'] , time() + 3600 );

                $conn->commit();
                if ($_POST['payment'] == 'paypal') {
                    header("Location:PayPal_payment_gateway/paypal_payment.php?o_id=".$order_id);
                }

                if ($_POST['payment'] == 'COD') {
                    header("Location:confirmation.php?o_id=".$order_id);
                }
            }


            // header("Location: confirmation.php");
            // header("Location:paypal_payment.php");


        } else {
            echo "Log In First!";
        }
        //  $conn->commit();

    }
} catch (PDOException $e) {
    echo $e;
}

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("PHPMailer/src/Exception.php");
require_once("PHPMailer/src/PHPMailer.php");
require_once("PHPMailer/src/SMTP.php");

require_once('db.php');
$mid = $_GET['msg_id'];

$page_title = "Message Detail";
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
                        <div class="d-flex align-items-lg-center  flex-column flex-md-row flex-lg-row mt-3">
                            <div class="flex-grow-1">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="message_report.php">Message</a></li>
                                        <li class="breadcrumb-item active">Message Details</li>
                                    </ol>
                                </nav>
                            </div>

                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <?php
                try {
                    $query = $conn->prepare("SELECT * FROM ep_message m WHERE msg_id = :mid");
                    $query->execute([':mid' => $mid]);
                    $fetch_msg = $query->fetch(PDO::FETCH_ASSOC);
                    //   $count_review = $query->rowCount();
                    if ($fetch_msg) {
                ?>

                        <div class="mt-4">

                            <!-- Main content -->

                            


                                <div class="message-card">

                                    <div class="message-header">
                                        <div class="user-info">
                                            <div class="avatar">
                                                <?= substr($fetch_msg['name'],0,1) ?>
                                            </div>

                                            <div>
                                                <h4 class="name"><?= $fetch_msg['name'] ?></h4>
                                                <p class="email"><?= $fetch_msg['email'] ?></p>
                                            </div>
                                        </div>

                                        <div class="action-buttons">
                                            <button data-bs-toggle="modal"
                                                data-bs-target="#categoryEditModal"
                                                class="icon-btn edit"
                                                class="icon-btn edit"><i class="fas fa-reply"></i></button>
                                            <button class="icon-btn delete" onclick="confirmDelete(<?= $fetch_msg['msg_id'] ?>,'delete_message.php?msg_id=')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>

                                    <div class="message-body">

                                        <div class="info-row">
                                            <span class="label">Subject:</span>
                                            <span><?= $fetch_msg['subject'] ?></span>
                                        </div>

                                        <div class="info-row">
                                            <span class="label">Date:</span>
                                            <span><?= date('d/m/Y', strtotime($fetch_msg['message_at'])) ?> • <?= date('h:i A', strtotime($fetch_msg['message_at'])) ?></span>
                                        </div>

                                        <div class="info-row">
                                            <span class="label">Status:</span>
                                            <?php if ($fetch_msg['status'] == 'New') { ?>
                                                <span class="badge-new">New</span>
                                            <?php } elseif ($fetch_msg['status'] == 'replied') { ?>
                                                <span class="badge-replied"><i class="fas fa-success fa-check"></i> Replied</span>
                                                
                                            <?php }  ?>
                                        </div>

                                        <div class="message-text">
                                            <?= empty($fetch_msg['message']) ? 'No description available.':'<i class="fa-solid fa-quote-left"></i>'.$fetch_msg['message'].'<i class="fas fa-small fa-quote-right"></i>' ?>
                                        </div>

                                    </div>

                                </div>

                            
                            <!-- Footer -->
                            <?php include('footer.php'); ?>
                        </div>



                        <!--Edit  Modal -->
                        <div class="modal fade" id="categoryEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content rounded-0">
                                    <div class="modal-body p-4 position-relative">
                                        <button type="button" class="btn position-absolute end-1" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                                        <h2 class="h5 text-color-2 py-2">Send Response</h2>
                                        <form class="row g-3" method="post" enctype="multipart/form-data">


                                            <div class="col-12">
                                                <label for="reply" class="form-label text-color-2 text-normal">Response: </label>
                                                <textarea class="form-control" name="reply" id="reply"></textarea>
                                            </div>


                                            <div class="col-12 mt-5">
                                                <button type="submit" name="send" class="btn bg-white bg-primary text-white d-flex align-items-center px-4 py-2 rounded-2 text-normal fw-bolder letter-spacing-26">Send</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } catch (PDOException $ex) {
                    echo $ex;
                }
                if (isset($_POST['send'])) {
                    $reply = $_POST['reply'];
                    $send = $conn->prepare("UPDATE `ep_message` SET `status`='replied',`reply`= :reply  WHERE msg_id  = :msgid");
                    $result = $send->execute([
                        'msgid' => $mid,
                        'reply' => $reply
                    ]);
                    if ($result) {
                        sweetAlert("Message Sent Successfuly", "", "success");
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = "smtp.gmail.com";
                            $mail->SMTPAuth = true;
                            $mail->Username = "pvidhi782@gmail.com";
                            $mail->Password = "ibcialcohyfmmvll";
                            $mail->SMTPSecure = "tls";
                            $mail->Port = 587;

                            $message = "Hello Sir/Madam,
             Thank you for contacting EPharmaEase.
             
             $reply
             
             Thank you for your patience and understanding.
             
             Warm regards,
             Team ePharmaEase
             
             ";
                            $mail->setFrom("pvidhi782@gmail.com", "ePharmaEase");
                            $mail->addAddress($fetch_msg['email']);
                            $mail->Subject = "EPharmaEase Support";
                            $mail->Body = $message;
                            $mail->send();
                        } catch (Exception $e) {
                            echo $e;
                        }
                    }
                }

                require_once("sweetAlert.php");
                ?>
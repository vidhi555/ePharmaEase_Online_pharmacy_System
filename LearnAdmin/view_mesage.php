<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("PHPMailer/src/Exception.php");
require_once("PHPMailer/src/PHPMailer.php");
require_once("PHPMailer/src/SMTP.php");

require_once('db.php');
$mid = $_GET['msg_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message Detail</title>
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
                text: "Do you really want to delete these Message?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect or AJAX call
                    window.location.href = "delete_message.php?msg_id=" + id;
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
                                <h3 class="mb-2 text-color-2"><a href="message_report.php">Back</a> > Message Detail</h3>
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
                            <div class="product-detail-card">


                                <div class="product-right">
                                    <div class="product-header">
                                        <h3>From: <?= $fetch_msg['name'] ?></h3>

                                        <div class="action-buttons">
                                            <button data-bs-toggle="modal"
                                                data-bs-target="#categoryEditModal"
                                                class="icon-btn edit"
                                                class="icon-btn edit"><i class="fas fa-reply"></i></button>
                                            <button class="icon-btn delete" onclick="confirmDelete(<?= $fetch_msg['msg_id'] ?>)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>

                                    <hr>
                                    <p class="product-desc"><strong>Email:</strong> <?= $fetch_msg['email'] ?></p>
                                    <p class="product-desc"><strong>Subject:</strong> <?= $fetch_msg['subject'] ?></p>
                                    <p class="product-desc"><strong>Message:</strong>
                                        "<?= $fetch_msg['message'] ?? 'No description available.' ?>"
                                    </p>
                                    <p class="text-muted">
                                        <strong>Status:</strong>
                                        <?php if ($fetch_msg['status'] == 'New') { ?>
                                            <span class="badge bg-primary">New</span>
                                        <?php } elseif ($fetch_msg['status'] == 'replied') { ?>
                                            <span class="badge bg-success">Replied</span>
                                        <?php }  ?>
                                    </p>
                                    <!-- <table >
                                   <tr>
                                    <td>Email: </td>
                                    <td><?= $fetch_msg['email'] ?></td>
                                   </tr>
                                   <tr>
                                    <td>Subject: </td>
                                    <td><?= $fetch_msg['subject'] ?></td>
                                   </tr>
                                   <tr>
                                    <td>Message: </td>
                                    <td><?= $fetch_msg['message'] ?></td>
                                   </tr>
                                   <tr>
                                    <td>Status: </td>
                                    <td> <?php if ($fetch_msg['status'] == 'New') { ?>
                                        <span class="badge bg-primary">New</span>
                                    <?php } elseif ($fetch_msg['status'] == 'replied') { ?>
                                        <span class="badge bg-success">Replied</span>
                                    <?php }  ?></td>
                                   </tr>
                                   </table> -->

                                </div>
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
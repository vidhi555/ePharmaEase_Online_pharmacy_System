<?php
$table = "ep_products";
$error = [];

//update products
if (isset($_POST['edit'])) {
  $id = $_POST['p_id'];
  //collect user input
  $pname = $_POST['pname'];
  $desc = $_POST['desc'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $category = $_POST['category'];
  $edate = $_POST['edate'];
  $status = $_POST['status'];


  //check image
  if (!empty($_FILES['pimg']['name'])) {

    $pimg = $_FILES['pimg']['name'];
    $tempimg = $_FILES['pimg']['tmp_name'];
    $size = $_FILES['pimg']['size'];

    $ext = pathinfo($pimg, PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $img_name = "product_" . time() . "." . $ext;

    $target = "upload/" . basename($img_name);
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $max_size = 2097152;
    if($size>$max_size){
      $error[] = "File Size is too Large!Please Try again!";
    }
    if (!in_array(strtolower($ext), $allowed)) {
      $error[] = "Invalid image format";
      // sweetAlert("Warning!", "Invalid image format!", "warning");
    }
    if (!move_uploaded_file($tempimg, $target)) {
      // sweetAlert("warning","Image uploading Fail","warning");
      $error[] = "Image uploading Fail";
    }

  } else {
    // sweetAlert("Warning","Image is not select.","warning");
    // $error[] = "Image is not select.";
    // $img_name = "user.jpg";
    $img_name = $_POST['old_img'];
  }




  if (!empty($error)) {
    sweetAlert("Error!", "Please Try Again!", "error");
  } else {
    $data = [
      "name" => $pname,
      "description" => $desc,
      "stock" => $stock,
      "price" => $price,
      "c_id" => $category,
      "expiry_date" => $edate,
      "status" => $status,
      "image" => $img_name
    ];

    $condition = "p_id = $id";
    $query = update_record($table, $data, $condition);
    sweetAlert("Update Successfully! ", "", "success");
  }
}
?>
<!-- 
$table = "ep_products";
$error = [];
$gallery_images = [];

if (isset($_POST['edit'])) {

  $id = $_POST['p_id'];

  $pname = $_POST['pname'];
  $desc = $_POST['desc'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $category = $_POST['category'];
  $edate = $_POST['edate'];
  $status = $_POST['status'];

  $img_name = $_POST['old_img']; // default main image

  // ===== Upload Gallery Images =====
  if (!empty($_FILES['pimg']['name'][0])) {

    $num = 0;

    foreach ($_FILES['pimg']['name'] as $key => $val) {

      if (!empty($_FILES['pimg']['name'][$key])) {

        $num++;

        $pimg = $_FILES['pimg']['name'][$key];
        $tempimg = $_FILES['pimg']['tmp_name'][$key];
        $size = $_FILES['pimg']['size'][$key];

        $ext = strtolower(pathinfo($pimg, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        //extension validation
        if (!in_array($ext, $allowed)) {
          $error[] = "Invalid image format!";
          continue;
        }

        //size validation
        if ($size > 2 * 1024 * 1024) {
          $error[] = "Image size must be less than 2MB!";
          continue;
        }

        $img_name = "gallery_" . $id . "_" . $num . "." . $ext;

        $target = "All_images_uploads/" . $img_name;

        if (move_uploaded_file($tempimg, $target)) {

          $gallery_images[] = $img_name;
        } else {
          $error[] = "Image upload failed!";
        }
      }
    }
  }

  if (!empty($error)) {

    sweetAlert("Error!", implode("<br>", $error), "error");
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

    update_record($table, $data, $condition);

    //Replace Gallery Images
    if (!empty($gallery_images)) {

      $conn->prepare("DELETE FROM ep_image_gallery WHERE p_id=:pid")
        ->execute(['pid' => $id]);

      foreach ($gallery_images as $img) {

        $conn->prepare("INSERT INTO ep_image_gallery(p_id,image_name)
                        VALUES(:pid,:img)")
          ->execute([
            'pid' => $id,
            'img' => $img
          ]);
      }
    }

    sweetAlert("Update Successful!", "Product Updated Successfully", "success");
  }
} -->

<?php
if (isset($_POST['edit'])) {

$id = $_POST['p_id'];

$pname = $_POST['pname'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];
$edate = $_POST['edate'];
$status = $_POST['status'];

$data = [
"name"=>$pname,
"description"=>$desc,
"stock"=>$stock,
"price"=>$price,
"c_id"=>$category,
"expiry_date"=>$edate,
"status"=>$status
];

$condition = "p_id = $id";

update_record("ep_products",$data,$condition);


// CHECK IF NEW IMAGES UPLOADED
if(!empty($_FILES['pimg']['name'][0])){

// delete old gallery images
$conn->prepare("DELETE FROM ep_image_gallery WHERE p_id=:pid")
->execute(['pid'=>$id]);

$num = 0;

foreach($_FILES['pimg']['name'] as $key=>$val){

$num++;

$img = $_FILES['pimg']['name'][$key];
$tmp = $_FILES['pimg']['tmp_name'][$key];
$size = $_FILES['pimg']['size'][$key];

$ext = strtolower(pathinfo($img,PATHINFO_EXTENSION));

$allowed = ['jpg','jpeg','png','webp'];

if(!in_array($ext,$allowed)){
continue;
}

$img_name = "product_".$id."_".$num.".".$ext;

move_uploaded_file($tmp,"All_images_uploads/".$img_name);


// FIRST IMAGE = MAIN IMAGE
if($num==1){

$conn->prepare("
UPDATE ep_products
SET image=:img
WHERE p_id=:pid
")->execute([
'img'=>$img_name,
'pid'=>$id
]);

}


// INSERT INTO GALLERY
$conn->prepare("
INSERT INTO ep_image_gallery(p_id,image_name)
VALUES(:pid,:img)
")->execute([
'pid'=>$id,
'img'=>$img_name
]);

}

}

sweetAlert("Success","Product Updated Successfully","success");

}
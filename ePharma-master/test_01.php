<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">

</head>
<body>
<form method="POST" action="">
    <label>Phone Number:</label>
    <input id="phone" type="tel" name="phone" required>
    <br><br>
    <input type="submit" name="submit" value="Save">
</form>




<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>

<script>
var input = document.querySelector("#phone");

var iti = window.intlTelInput(input, {
    initialCountry: "in",   // default India
    separateDialCode: true, // shows +91 separately
    preferredCountries: ["in", "us", "gb"],
});
</script>
</body>
</html>
<?php
if(isset($_POST['submit'])){
    $phone = $_POST['phone'];
    echo "Saved Phone Number: " . $phone;
}
?>

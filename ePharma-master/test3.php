<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Phone Input</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
</head>
<body>

<form method="POST" action="" id="myForm">

    <label>Phone Number:</label><br>
    <input id="phone" type="tel" name="phone" required>
    <br><br>

    <input type="submit" name="submit" value="Save">

</form>

<!-- JS Library -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    var input = document.querySelector("#phone");

    var iti = window.intlTelInput(input, {

        initialCountry: "auto",   // it will Auto detect country
        geoIpLookup: function(callback) {
            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("in")); // fallback India
        },

        // onlyCountries: ["in", "us","uk"],  // Restrict to India + USA
        separateDialCode: false,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"
    });

    // Validate + Store Full Number
    document.querySelector("#myForm").addEventListener("submit", function(e) {

        if (!iti.isValidNumber()) {
            alert("Please enter a valid phone number");
            e.preventDefault();
            return false;
        }

        // Store full international number
        input.value = iti.getNumber();
    });

});
</script>

</body>
</html>

<?php
if(isset($_POST['submit'])){
    $phone = $_POST['phone'];
    echo "<h3>Saved Phone Number: " . $phone . "</h3>";
}
?>

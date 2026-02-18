<div class="footer text-center bg-white shadow-sm py-3 mt-5">
    <p class="m-0">
        Copyright &copy; <script>
            document.write(new Date().getFullYear());
        </script> <a href="http://localhost/ePharmaEase_Project/LearnAdmin/index.php" class="text-primary" target="_blank">ePharmaEase</a>. All Rights Reserved. Designed & Developed by ePharmaEase Team.
    </p>
</div>

<!-- Scripts -->
<script src="./assets/js/jquery-3.6.0.min.js"></script>
<script src="./assets/js/bootstrap.bundle.min.js"></script>
<script src="./assets/plugin/chart/chart.js"></script>
<script src="./assets/plugin/quill/quill.js"></script>
<!-- <script src="./assets/js/chart.js"></script> -->
<script src="./assets/js/main.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="./assets/js/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="./assets/plugin/chart/chart.js"></script>
<!-- <script src="./assets/js/chart.js"></script> -->
<script src="./assets/js/main.js"></script>
<?php require_once('sweetAlert.php'); ?>


<!-- Phone number JS Library -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>

<!-- this used for mobile numbers validations -->
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
   document.querySelector("#register_form").addEventListener("submit", function(e) {

    // Check empty
    if (input.value.trim() === "") {
        alert("Phone number is required");
        e.preventDefault();
        return;
    }

    // Validate number length according to country
    if (!iti.isValidNumber()) {
        alert("Please enter a valid phone number according to selected country");
        e.preventDefault();
        return;
    }

    // Store full international number
    input.value = iti.getNumber();
});


});
</script>

</body>

</html>
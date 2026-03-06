<?php
$page_title = "500 Error";
require_once("header2.php");

?>
<body>

    <div class="error-page">
        <div class="text-center">
            <div class="error-code">500</div>
            <h1 class="error-title">
                <i class="fa-solid fa-circle-xmark error-icon"></i>
                Internal Server Error
            </h1>
            <p class="error-message">You do not have permission to view this resource.</p>
            <a href="index.php" class="btn btn-primary back-home">Back to Home</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="./assets/js/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/plugin/chart/chart.js"></script>
    <script src="./assets/plugin/quill/quill.js"></script>
    <script src="./assets/js/chart.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>
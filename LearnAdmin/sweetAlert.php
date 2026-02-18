<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- SweetAlert CDN -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
if (isset($_SESSION['swal'])) {
    $swal = $_SESSION['swal'];
?>
    <script>
        swal({
            title: "<?= addslashes($swal['title']); ?>",
            text: "<?= addslashes($swal['text']); ?>",
            icon: "<?= $swal['icon']; ?>",
            button: "OK"
        });
    </script>
<?php
    unset($_SESSION['swal']); // unset session 
}
?>
<?php
require_once('connection/db.php');
require_once('header.php')
?>
<div id="reviewContainer">

    <div class="review-item">Review 1</div>
    <div class="review-item">Review 2</div>
    <div class="review-item">Review 3</div>
    <div class="review-item">Review 4</div>
    <div class="review-item">Review 5</div>
    <div class="review-item">Review 6</div>
    <div class="review-item">Review 7</div>

</div>

<!-- Arrow Button -->
<div class="load-more-btn">
    <button id="loadMore"><i class="fa fa-arrow-down"></i></button>
</div>

<style>
    .review-item{
    display:none;
}
</style>
<script>
    $(document).ready(function(){
    let expand = false;
    $(".review-item").slice(0,5).show(); // first 5 show

    $("#loadMore").click(function(){
        if(!expand){
            $(".review-item:hidden").slice(0,5).slideDown();
            expand = true;
        }else{
            $(".review-item").slice(5).slideUp();
            expand = false;
        }

        //Disable after click
        // if($(".review-item:hidden").length == 0){
        //     $("#loadMore").fadeOut();
        // }
    });

});
</script>
<?php
require_once('footer.php')
?>
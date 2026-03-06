// Pagination + Search

function loadData(page = 1){
  let search = document.getElementById("livesearch").value;
  
    $.ajax({
        url: window.location.href,
        method: "POST",
        data: {page: page,
          search:search
        },
        success: function(data){
            $("#result").html(data);
        }
    });
}

function loadPagination(){
    $.ajax({
        url: "pagination_category.php",
        success: function(data){
            $("#pagination").html(data);
        }
    });
}

// click pagination
$(document).on("click",".page-btn",function(e){
    e.preventDefault();
    var page = $(this).data("page");
    loadData(page);
});
$(document).ready(function(){
  $(document).on("keyup","#livesearch",function(){
    var a = $(this).val();
    // alert(a);
    loadData(1);
    loadPagination();
  })
})

// first load
loadData();
loadPagination();


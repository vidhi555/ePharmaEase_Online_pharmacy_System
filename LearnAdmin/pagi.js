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
            let parts = data.split("###pagination###");

            $("#result").html(parts[0]);
            $("#pagination").html(parts[1]);
        }
    });
}

// click pagination
$(document).on("click",".page-link",function(e){
    e.preventDefault();
    var page = $(this).data("page");
    loadData(page);
});
$(document).ready(function(){
  $(document).on("keyup","#livesearch",function(){
    var a = $(this).val();
    // alert(a);
    loadData(1);
  })
})

// first load
loadData(1);


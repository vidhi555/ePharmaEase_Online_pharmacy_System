function loadData(page = 1) {
    let search = document.getElementById("livesearch").value;
    var filter_status = document.getElementById("filterbystatus").value;

    $.ajax({
        url: window.location.href,
        type: "POST",
        data: {
            page: page,
            search: search,
            filter_status: filter_status
        },
        success: function (data) {
            let parts = data.split("###pagination###");
            $("#result").html(parts[0]);
            $("#pagination").html(parts[1]);
        }
    });
}

// click pagination
$(document).on("click", ".page-link", function (e) {
    e.preventDefault();
    var page = $(this).data("page");
    loadData(page);
});
$(document).ready(function () {
    $(document).on("keyup", "#livesearch", function () {
        var a = $(this).val();
        // alert(a);
        loadData(1);
        loadPagination();
    });
});
$(document).on("change", "#filterbystatus", function () {
    var b = $(this).val();
    // alert(b);
    loadData(1);

});


// first load
loadData(1);
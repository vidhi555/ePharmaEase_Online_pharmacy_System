$(document).ready(function() {

    function load_products(page = 1) {

      let category_id = $('input[name="category"]:checked').val();
      let search = $('#live_search').val();
      let sort = $('#filter_by_status').val();
      let max_price = $('#priceRange').val();

      $.ajax({
        url: "load_products_ajax.php",
        method: "POST",
        data: {
          page: page,
          category_id: category_id,
          search: search,
          sort: sort,
          max_price: max_price
        },
        success: function(data) {
          $("#product-list").html(data);
        }
      });
    }

    // Category change
    $(document).on("change", 'input[name="category"]', function() {
      load_products();
    });

    // Search
    $(document).on("keyup", "#live_search", function() {
      load_products();
    });

    // Sort
    $(document).on("change", "#filter_by_status", function() {
      load_products();
    });

    // Price filter
    $(".filter-btn").on("click", function() {
      load_products();
    });

    // Pagination click
    $(document).on("click", ".page-link", function(e) {
      e.preventDefault();
      let page = $(this).data("page");
      load_products(page);
    });

  });
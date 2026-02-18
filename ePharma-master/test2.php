<!-- search -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
    <input type="text" id="live_search" autocomplete="off" placeholder="Search...">

    <div id="result"></div>
    <script>
        $(document).ready(function() {
            $("#live_search").keyup(function() {
                var input = $(this).val();
                // alert(input);
                if (input != "") {
                    $.ajax({
                        url: "test3.php",
                        method: "POST",
                        data: {
                            input: input
                        },

                        success: function(data) {
                            $("#result").html(data).show();
                        }
                    });
                } else {
                    $("#result").hide().html("");
                }
            });
        });
    </script>
</body>

</html>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Search</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        #result{
            border: 1px solid #ccc;
            margin-top: 5px;
            padding: 5px;
        }
    </style>
</head>
<body>

<input type="text" id="live_search" autocomplete="off" placeholder="Search...">
<div id="result"></div>

<script>
$(document).ready(function(){

    $("#live_search").on("keyup", function(){
        let input = $(this).val().trim();

        if(input.length > 0){
            $.ajax({
                url: "test3.php",
                type: "POST",
                data: { input: input },
                success: function(data){
                    $("#result").html(data).show(); // 🔥 show every time
                }
            });
        }else{
            $("#result").hide().html("");
        }
    });

});
</script>

</body>
</html> -->
<?php include_once 'includes/top.php';?>
<body>
    <?php include_once 'includes/track_order.php'; ?>
    <?php include_once 'includes/footer_url.php'; ?>
</body>
</html>
<script>
    $(".track-btn").click(function(){
        $track_id = $(".tract-id-field").val();
        if($track_id == ""){
            alert('Please Enter Track ID');
            return false;
        }else{
            $(".track_result").text("Loading...");
            $.ajax({
                url: "process/data.php",
                type: "post",
                data: {"track_id":$track_id} ,
                success: function (response) {
                    $(".track_result").text(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                }
            });
        }
    });
</script>
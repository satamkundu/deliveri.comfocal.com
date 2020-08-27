<?php include_once('../include/config.php'); ?>
<?php
    if(isset($_POST)){
        if(isset($_POST['id']) && isset($_POST['status'])){
            $id=$_POST['id'];
            $status = $_POST['status'];
            $query=mysqli_query($con,"UPDATE `delivery_details` SET `order_status` = '$status' WHERE `delivery_details`.`delivery_id` = $id");
        }
    }
?>
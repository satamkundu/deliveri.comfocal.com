<?php include_once('../include/config.php'); ?>
<?php
    if(isset($_POST)){
        if(isset($_POST['id']) && isset($_POST['status'])){
            $id=$_POST['id'];
            $status = $_POST['status'];
            $query=mysqli_query($con,"UPDATE `delivery_details` SET `order_status` = '$status' WHERE `delivery_details`.`delivery_id` = $id");
        }
        if(isset($_POST['do_for']) && $_POST['do_for'] == "change_payment_status"){
            $ord_id=$_POST['ord_id'];
            $payment_status = $_POST['payment_status'];
            $query=mysqli_query($con,"UPDATE `order_main` SET `payment_status` = '$payment_status' WHERE `order_id` = $ord_id");
        }
        if(isset($_POST['do_for']) && $_POST['do_for'] == "change_order_for"){
            $ord_id=$_POST['ord_id'];
            $ord_for = $_POST['ord_for'];
            $query=mysqli_query($con,"UPDATE `order_main` SET `order_from` = '$ord_for' WHERE `order_id` = $ord_id");
            if(!$query) echo "0".mysqli_error($con);
            else echo "1";
        }
    }
?>
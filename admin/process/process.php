<?php include_once('../include/config.php'); ?>
<?php
    if(isset($_POST)){
        if(isset($_POST['id']) && isset($_POST['status'])){
            $id=$_POST['id'];
            $status = $_POST['status'];
            $query=mysqli_query($con,"UPDATE `delivery_details` SET `order_status` = '$status' WHERE `delivery_details`.`delivery_id` = $id");
        }
        if(isset($_POST['o_id']) && isset($_POST['cod_status'])){
            $id=$_POST['o_id'];
            $status = $_POST['cod_status'];
            $query=mysqli_query($con,"UPDATE `delivery_details` SET `cod_status` = '$status' WHERE `delivery_details`.`delivery_id` = $id");
            if($status == "cancel"){
                $qu = mysqli_query($con, "SELECT order_id, cod_self FROM delivery_details");
                $ro = mysqli_fetch_assoc($qu);
                $cod_self = $ro['cod_self'];
                $order_id = $ro['order_id'];
                $per_upon_cod = (int)$cod_self * 0.015;
                mysqli_query($con, "UPDATE order_main SET total_price = (total_price - $per_upon_cod) WHERE order_id = $order_id");
            }
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

        if(isset($_POST['pin'])){
            $pin = $_POST['pin'];
            if($_POST['for'] == 'close'){                
                mysqli_query($con, "UPDATE pincodes SET status = 'close' WHERE pincode = '$pin'");
            }else{
                mysqli_query($con, "UPDATE pincodes SET status = 'open' WHERE pincode = '$pin'");
            }
        }
    }
?>
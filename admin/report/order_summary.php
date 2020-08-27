<?php require_once('../include/top.php'); ?>
<script>    
    function do_print(){
        window.print();
        window.onmousemove = function() {window.close(); }
    }
</script>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
<body onload="do_print()">
<?php
$cnt = 1;


if(isset($_GET['from']) && isset($_GET['to'])){
    if(!empty($_GET['from']) && !empty($_GET['to'])){
        $from_date = $_GET['from'];
        $to_date = $_GET['to'];
        //Data between two days
        $sql = "SELECT * FROM `order_main` WHERE DATE(`datetime`) BETWEEN '$from_date' AND '$to_date';";
    }else{
        echo "<center><h1>Choose Proper Date</h1></center>"; 
        exit();
    }
}
if(isset($_GET['for'])){
    if($_GET['for']=='today'){
        //Today data
        $sql = "SELECT * FROM `order_main` WHERE DATE(`datetime`) = CURDATE()";
    }else if($_GET['for']=='yesterday'){
        //Yesterday data
        $sql = "SELECT * FROM `order_main` WHERE DATE(`datetime`) = CURDATE() - 1";
    }
}

if(isset($_GET['orderid'])){
    if(!empty($_GET['orderid'])){
        $orderid = $_GET['orderid'];
        //for individual
        $sql = "SELECT * FROM `order_main` WHERE `order_id` = '$orderid'";
    }else{
        echo "<center><h1>Choose Proper Date</h1></center>"; 
        exit();
    }
}

if(isset($_GET['from'])||isset($_GET['for'])||isset($_GET['orderid'])){

$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    ?>
    <table style="width:55.7rem;border-collapse: collapse;" border=1>
        <tbody>
            <tr>
                <th></th>
                <th style="width:7rem">Order Number</th>
                <th style="width:5rem">Order Date</th>
                <th style="width:5rem">Total</th>
                <th style="width:13rem">PickUp Details</th>
                <th>Delivery Details</th>
            </tr>
    <?php
    while($row = mysqli_fetch_assoc($result)) {
        $order_id = $row['order_id'];
        $sql0 = "SELECT * FROM `pick_up_details` WHERE `order_id`='$order_id'";
        $row0 = mysqli_fetch_assoc(mysqli_query($con, $sql0));
        ?>    
            <tr style="font-size: 0.7rem;line-height: initial;">
                <td><?=$cnt;$cnt++?></td>
                <td><?=$order_id?></td>
                <td><?=date('d-m-Y',strtotime($row['datetime']))?></td>
                <td><?=$row['total_price']?></td>
                <td><?=$row0['name'].", ".$row0['address'].", ".$row0['pin'].", ".$row0['landmark'].", ".$row0['phone']?></td>
        <?php        
        $sql1 = "SELECT * FROM `delivery_details` WHERE `order_id`='$order_id'";
        $result1 = mysqli_query($con, $sql1);
        if (mysqli_num_rows($result1) > 0) {
            echo "<td><table style='border-collapse: collapse;font-size:0.7rem;width:100%' border=1>";
            echo "<tr><th>Address</th><th>Weight</th><th>COD</th><th>Product Value</th><th>Amount</th><th>Status</th></tr>";
            while($row1 = mysqli_fetch_assoc($result1)) {            
                ?>
                    <tr>
                        <td><?=$row1['name'].", ".$row1['address'].", ".$row1['pin'].", ".$row1['landmark'].", ".$row1['phone']?></td>
                        <td><?=$row1['weight'].".".$row1['weight_gm']?></td>
                        <td><?=$row1['cod_amt']?></td>
                        <td><?=$row1['aprx_amt']?></td>
                        <td><?=$row1['amount_self']?></td>
                        <td><?=$row1['order_status']?></td>
                    </tr>                
                <?php    
            }            
            echo "</table></td>";            
        }else{
            echo "<td>No Delivery Details Found</td>";
        }        
    }
    echo "</tr></tbody></table>";
    } else {
    echo "0 results";
}   
mysqli_close($con);
}else{
 echo "<center><h1>Unauthorized Access</h1></center>";   
}?>
</body>
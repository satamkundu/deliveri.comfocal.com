<?php
require_once('include/top.php');
if(strlen($_SESSION['alogin'])==0){ 
  header('location:index.php');
}else{  
  $oid=intval($_GET['oid']);
?>
<script language="javascript" type="text/javascript">
  function f2(){
    window.close();
  }
  function f3(){
    window.print(); 
  }
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Update Compliant</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="anuj.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/loading.css">
</head>
<body>
<div class="loading">Loading&#8230;</div>
<div style="margin-left:50px;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr height="50">
        <td colspan="2" class="fontkink2" style="padding-left:0px;"><div class="fontpink2"> <b>Update Order !</b></div></td>
      </tr>
      <tr height="30">
        <td class="fontkink1"><b>order Id:</b></td>
        <td class="fontkink"><?php echo $oid;?></td>
      </tr>
    <?php 
      $ret = mysqli_query($con,"SELECT order_main.status AS status, order_main.datetime AS datetime, pick_up_details.name AS p_name, pick_up_details.address AS p_add, pick_up_details.pin AS p_pin, pick_up_details.phone AS p_phone, pick_up_details.landmark AS p_landmark FROM order_main JOIN pick_up_details ON order_main.order_id = pick_up_details.order_id AND pick_up_details.order_id='$oid'");
      while($row=mysqli_fetch_array($ret)){
    ?>
    <tr height="20">
      <td class="fontkink1" ><b>At Date:</b></td>
      <td  class="fontkink"><?php echo $row['datetime'];?></td>
    </tr>
     <!-- <tr height="20">
      <td  class="fontkink1"><b>Status:</b></td>
      <td  class="fontkink"><?php echo $row['status'];?></td>
    </tr> -->
    <tr>
      <td colspan="2"><hr /></td>
    </tr>
    <tr>
      <td class="fontkink1"><b>PickUp Details</b></th>
      <td class="fontkink">
        Name : <?=$row['p_name']?><br>
        Address : <?=$row['p_add']?>
        Pin : <?=$row['p_pin']?><br>
        Landmark : <?=$row['p_landmark']?><br>
        Phone : <?=$row['p_phone']?><br>        
      </td>
    </tr>
    <tr>
      <td colspan="2"><hr /></td>
    </tr>
   <?php } ?>

    <?php
      $rt1 = mysqli_query($con,"SELECT * FROM delivery_details WHERE order_id='$oid'");
      $counter = 1;
    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td class="fontkink1"><b>Drop Location (<?= mysqli_num_rows($rt1)?>)</b></td>
       <td class="fontkink">
       <table width="100%" border="1" cellspacing="0" cellpadding="0" class="deli-table">
       <tr>
            <th>#</th>
            <th class="fontkink2">Name</th>
            <th class="fontkink2">Landmark</th>
            <th class="fontkink2">Address</th>            
            <th class="fontkink2">Phone</th>
            <th class="fontkink2">Weight</th>
            <th class="fontkink2">Product Value</th>
            <th class="fontkink2">Order Status</th>
            <th class="fontkink2">COD</th>
            <th class="fontkink2">COD Status</th>
          </tr>
    <?php
      while($num1=mysqli_fetch_array($rt1)){?>
          <tr>
            <td><?=$counter?></td>
            <td class="fontkink2"><?=$num1['name']?></td>
            <td class="fontkink2"><?=$num1['landmark']?></td>
            <td class="fontkink2"><?=$num1['address'].', '.$num1['pin']?></td>
            <td class="fontkink2"><?=$num1['phone']?></td>
            <td class="fontkink2"><?=$num1['weight'].".".$num1['weight_gm']?></td>
            <td class="fontkink2"><?=$num1['aprx_amt']?></td>
            <td class="fontkink2">
              <input type="hidden" id="id<?=$counter?>" value="<?=$num1['delivery_id']?>">
              <select name="deli-status" class="fontkink" id="<?=$counter?>" onchange="change_deli_status(this.id)">
                <option value="">Select Status</option>
                <option value="in transit" <?=($num1['order_status']=="in transit")?'selected':''?>>In transit</option>
                <option value="on the way to pick up" <?=($num1['order_status']=="on the way to pick up")?'selected':''?>>On the way to pick up</option>
                <option value="delivered" <?=($num1['order_status']=="delivered")?'selected':''?>>Delivered</option>
                <option value="cancel" <?=($num1['order_status']=="cancel")?'selected':''?>>Cancel</option>
              </select>
            </td>
            <td class="fontkink2"><?=$num1['cod_self']?></td>
            <td>
            <?php
            if($num1['cod_self'] > 0){
                ?>
                <input type="hidden" id="o_id<?=$counter?>" value="<?=$num1['delivery_id']?>">
                <select name="cod-status" class="fontkink" id="o_<?=$counter?>" onchange="change_cod_status(this.id)">
                  <option value="">Select Status</option>
                  <option value="pending" <?=($num1['cod_status']=="pending")?'selected':''?>>Pending</option>
                  <option value="success" <?=($num1['cod_status']=="success")?'selected':''?>>Success</option>                  
                  <option value="cancel" <?=($num1['cod_status']=="cancel")?'selected':''?>>Cancel</option>
                </select>
           <?php }  ?>
            </td>
          </tr>
        <?php $counter++; }  ?>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2"><hr /></td>
  </tr>
  </table>
</div>
</body>
</html>
<?php } ?>
<style>
.deli-table tr td{
  padding:1.5rem;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $('.loading').hide();
});
function change_deli_status(id){
  $('.loading').show(); 
  $.ajax({
    type: "POST",
    url: "process/process.php",
    data: {id:$('#id'+id).val(),status:$('#'+id).val()},    
    success: function(result){
      location.reload();
    }
  });
}

function change_cod_status(id){
  $('.loading').show(); 
  var id = id.slice(2);
  $.ajax({
    type: "POST",
    url: "process/process.php",
    data: {o_id:$('#o_id'+id).val(),cod_status:$('#o_'+id).val()},    
    success: function(result){
      location.reload();
    }
  });
}
</script>
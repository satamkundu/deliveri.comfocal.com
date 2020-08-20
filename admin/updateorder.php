<?php
require_once('include/top.php');
if(strlen($_SESSION['alogin'])==0){ 
  header('location:index.php');
}else{
  $oid=intval($_GET['oid']);
  if(isset($_POST['submit2'])){
    $status=$_POST['status'];
    $query=mysqli_query($con,"UPDATE `order_main` SET `status` = '$status' WHERE `order_main`.`order_id` = $oid");
    echo ($query)?"<script>alert('Order updated sucessfully...');</script>":"<script>alert('Something Went Wrong...');</script>";
  }
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
</head>
<body>

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
     <tr height="20">
      <td  class="fontkink1"><b>Status:</b></td>
      <td  class="fontkink"><?php echo $row['status'];?></td>
    </tr>
    <tr>
      <td colspan="2"><hr /></td>
    </tr>
    <tr>
      <td class="fontkink1"><b>PickUp Details</b></th>
      <td class="fontkink">
        Name : <?=$row['p_name']?><br>
        Phone : <?=$row['p_phone']?><br>
        Address : <?=$row['p_add']?><br>
        Pin : <?=$row['p_pin']?><br>
        Landmark : <?=$row['p_landmark']?>
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
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <?php
      while($num1=mysqli_fetch_array($rt1)){?>
          <tr>
            <td><?=$counter?></td>
            <td class="fontkink2"><?="Name : ".$num1['name']?></td>
            <td class="fontkink2"><?="Phone : ".$num1['phone']?></td>
            <td class="fontkink2"><?="Address : ".$num1['address']?></td>
            <td class="fontkink2"><?="Pin : ".$num1['pin']?></td>
            <td class="fontkink2"><?="Weight : ".$num1['weight']." ".$num1['weight_unit']?></td>
            <td class="fontkink2"><?="Landmark : ".$num1['landmark']?></td>
            <td class="fontkink2"><?="Aprx Amount : ".$num1['aprx_amt']?></td>
          </tr>
        <?php $counter++; }  ?>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2"><hr /></td>
  </tr>
  </table>

  <form name="updateticket" id="updateticket" method="POST"> 
   <?php 
    $st='delivered';
    $rt = mysqli_query($con,"SELECT * FROM order_main WHERE order_id='$oid'");
    while($num=mysqli_fetch_array($rt)){
     $currrentSt=$num['status'];
    }
    if($st==$currrentSt){ ?>
    <tr>
      <td colspan="2"><b>Product Delivered</b></td>
    <?php }else  {?>   
    <tr height="50">
      <td class="fontkink1">Status: </td>
        <td  class="fontkink"><span class="fontkink1" >
          <select name="status" class="fontkink" required="required" >
            <option value="">Select Status</option>
            <option value="in process">In Process</option>
            <option value="delivered">Delivered</option>
          </select>
        </span>
      </td>
    </tr>
    <tr>
      <td class="fontkink1">&nbsp;</td>
      <td  >&nbsp;</td>
    </tr>
    <tr>
      <td class="fontkink"></td>
      <td  class="fontkink"> <input type="submit" name="submit2"  value="update"   size="40" style="cursor: pointer;" /> &nbsp;&nbsp;   
      <input name="Submit2" type="submit" class="txtbox4" value="Close this Window " onClick="return f2();" style="cursor: pointer;"  /></td>
    </tr>
<?php } ?>
</table>
 </form>
</div>
</body>
</html>
<?php } ?>
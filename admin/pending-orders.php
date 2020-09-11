<?php
require_once('include/top.php');
if(strlen($_SESSION['alogin'])==0){	
	header('location:index.php');
}else{
	date_default_timezone_set('Asia/Kolkata');// change according timezone
	$currentTime = date( 'd-m-Y h:i:s A', time () );

	if(isset($_GET['del'])){
		if(isset($_GET['user']) && $_GET['user'] == "bed59c54e6ed09cbe588173dba3935a2"){
			if(mysqli_query($con,"delete from order_main where order_id = '".$_GET['order_id']."'"))
				if(mysqli_query($con,"delete from delivery_details where order_id = '".$_GET['order_id']."'"))
					if(mysqli_query($con,"delete from pick_up_details where order_id = '".$_GET['order_id']."'")){
						$_SESSION['delmsg']="Order deleted !!";
						header('location: pending-orders.php');
						exit;
					}
		}else{
			$_SESSION['delmsg']="Invalid Request";
			header('location: pending-orders.php');
			exit;
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| Pending Orders</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="css/loading.css">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>

	


	<script language="javascript" type="text/javascript">
		var popUpWin=0;
		function popUpWindow(URLStr, left, top, width, height){
			if(popUpWin){
				if(!popUpWin.closed) popUpWin.close();
			}
			popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+600+',height='+600+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
		}
	</script>
</head>
<body>
<?php include('include/header.php');?>
<div class="loading">Loading&#8230;</div>
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<?php include('include/sidebar.php');?>				
				<div class="span9">
					<div class="content">
						<div class="module">
							<div class="module-head">
								<h3>Pending Orders</h3>
							</div>
							<div class="module-body table">
							<?php //if(isset($_GET['del'])){?>
							<div class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<?= (isset($_SESSION['delmsg']))?$_SESSION['delmsg']:'' ?>
								<?php
								 if(isset($_SESSION['delmsg']))
									unset($_SESSION['delmsg']);
								?>
							</div>
							<?php //} ?>
							<br />
							<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display table-responsive" >
								<thead>
									<tr>
									<th>#</th>
									<th>OrderID</th>						
									<th>Amount </th>
									<th>Order Date</th>
									<th>Payment Status</th>
									<?=($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2)?"<th>Order Goes To</th>":"" ?>									
									<th>Action</th>
									</tr>
								</thead>								
								<tbody>
									<?php 
									$st='delivered';
									$user_id = $_SESSION['id'];
									
									if($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2)
										$query=mysqli_query($con,"SELECT *  FROM `order_main` WHERE status != '$st' ORDER BY `order_main`.`datetime` DESC");
									else
										$query=mysqli_query($con,"SELECT *  FROM `order_main` WHERE status != '$st' AND order_from = '$user_id' ORDER BY `order_main`.`datetime` DESC");
									$cnt=1;
									while($row=mysqli_fetch_array($query)){
									?>										
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><?php echo htmlentities($row['order_id']);?></td>
											<td><?php echo htmlentities($row['total_price']);?></td>					
											<td><?php echo htmlentities($row['datetime'])?></td>
											<td>
												<input type="hidden" id="ord_id<?=$cnt?>" value="<?=$row['order_id']?>">
												<select style="width:9rem !important" class="fontkink" id="<?=$cnt?>" onchange="change_payment_status(this.id)">
													<option value="pending" <?=($row['payment_status']=="pending")?'selected':''?>>Pending</option>
													<option value="success" <?=($row['payment_status']=="success")?'selected':''?>>Success</option>												
												</select>
											</td>
											<?php 
												if($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2){
											?>
											<td>
												<select style="width:9rem !important" class="fontkink" id="o<?=$cnt?>" onchange="change_order_for(this.id)">
												<?php
													$query_admin=mysqli_query($con,"SELECT *  FROM `admin`");
													while($row_admin=mysqli_fetch_array($query_admin)){
												?>
													<option value="<?=$row_admin['id']?>" <?=($row_admin['id']==$row['order_from'])?'selected':''?>><?=$row_admin['name']?></option>
												<?php } ?>
												</select>
											</td>
											<?php } ?>

											<td>
												<a href="updateorder.php?oid=<?php echo htmlentities($row['order_id']);?>" title="Update order" target="_blank"><i class="icon-edit"></i></a>
												<?php 
													if($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2){
												?>
												<a href="pending-orders.php?order_id=<?php echo $row['order_id']?>&del=delete&user=<?=md5('com%$12\ad@mi^')?>" onClick="return confirm('Are you sure you want to delete?')"><i class="icon-remove-sign"></i></a></td>
													<?php } ?>
											</td>
										</tr>
										<?php $cnt=$cnt+1; } ?>
										</tbody>
								</table>
							</div>
						</div>	
					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->

<?php include('include/footer.php');?>

	<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="scripts/datatables/jquery.dataTables.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatable-1').dataTable();
			$('.dataTables_paginate').addClass("btn-group datatable-pagination");
			$('.dataTables_paginate > a').wrapInner('<span />');
			$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
			$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
			
			$('.loading').hide();
		});

		function change_payment_status(id){
			$('.loading').show(); 			
			$.ajax({
				type: "POST",
				url: "process/process.php",
				data: {ord_id:$('#ord_id'+id).val(),payment_status:$('#'+id).val(),do_for:"change_payment_status"},    
				success: function(result){
					// console.log(result);
					location.reload();
				}
			});
		}

		function change_order_for(id){
			// $('.loading').show(); 
			var id = id.substring(1);
			$.ajax({
				type: "POST",
				url: "process/process.php",
				data: {ord_id:$('#ord_id'+id).val(),ord_for:$('#o'+id).val(),do_for:"change_order_for"},    
				success: function(result){
					// console.log(result);
					location.reload();
				}
			});
		}
	</script>
</body>
<?php } ?>
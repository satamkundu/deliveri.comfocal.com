<?php
require_once('include/top.php');
if(strlen($_SESSION['alogin'])==0  || $_SESSION['admin_type'] > 2){	
	header('location:index.php');
}else{
	date_default_timezone_set('Asia/Kolkata');// change according timezone
	$currentTime = date( 'd-m-Y h:i:s A', time () );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| Pin Restriction</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<link rel="stylesheet" href="css/loading.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<style>
	 .ui-autocomplete-loading {
        background: white url("../assets/images/ui-anim_basic_16x16.gif") right center no-repeat;
    }
	.ui-autocomplete {
        max-height: 300px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
    * we use height instead, but this forces the menu to always be this tall
    */
    * html .ui-autocomplete {
        height: 100px;
    }
</style>

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
							<h3>Pin Restriction</h3>
						</div>
						<div class="module-body">							
							<br />
							<form class="form-horizontal row-fluid" method="post">													
								<div class="control-group">				
									<div class="controls">
										<div class="ui-widget">
											<input type="text" id="pin" onkeyup="check_pin_avail(this.id)" maxlength="6" placeholder="Enter Pin Number" class="span8 tip">
										</div>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<button id="res_pin" class="btn">Restrict Status</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div class="module">
						<div class="module-head">
							<h3>Manage Brands</h3>
						</div>
						<div class="module-body table">
							<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Pin</th>
										<th>Change Restriction</th>
									</tr>
								</thead>
								<tbody>

								<?php $query=mysqli_query($con,"SELECT distinct pincode, status from pincodes WHERE status = 'close'");
								$cnt=1;
								while($row=mysqli_fetch_array($query)){?>									
									<tr>
										<td><?php echo htmlentities($cnt);?></td>
										<td><?php echo htmlentities($row['pincode']);?></td>
										<td>
											<select class="pin_status" onchange="pin_open(<?=$row['pincode']?>)">
												<option value="open" <?=($row['status']=='open')?'selected':'' ?>>Open</option>
												<option value="close" <?=($row['status']=='close')?'selected':'' ?>>Close</option>
											</select>
										</td>
									</tr>
									<?php $cnt=$cnt+1; } ?>										
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

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<script>
		$(document).ready(function() {

			$('.loading').hide();

			$('.datatable-1').dataTable();
			$('.dataTables_paginate').addClass("btn-group datatable-pagination");
			$('.dataTables_paginate > a').wrapInner('<span />');
			$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
			$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');			
		});

		$("#res_pin").click(function(){
			$.ajax({
				url: "process/process.php",
				type:'POST',
				data: {pin: $("#pin").val(),for:'close'},
				success: function( data ) {
					location.reload();
				}
			});
		});

		function check_pin_avail(id){
			$("#"+id).autocomplete({
				source: function( request, response ) {
					$.ajax({
						url: "process/request.php",
						dataType: "json",
						data: {
							q: request.term
						},
						success: function( data ) {
							response( data );
						}
					});
				},
				minLength: 3,
				select: function( event, ui ) {},
				open: function() {},
				close: function() {}
			});
		}

		function pin_open(pin){
			$('.loading').show();
			$.ajax({
				url: "process/process.php",
				type:'POST',
				data: {pin: pin,for:'open'},
				success: function( data ) {					
					location.reload();					
				}
			});
		}


	</script>
</body>
<?php } ?>
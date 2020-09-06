<?php $user_id = $_SESSION['id']; ?>
<div class="span3">
	<div class="sidebar">
		<ul class="widget widget-menu unstyled">
			<li>
				<a class="collapsed" data-toggle="collapse" href="#togglePages">
					<i class="menu-icon icon-cog"></i>
					<i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right"></i>Order Management
				</a>
				<ul id="togglePages" class="collapse unstyled">
					<li>
						<a href="todays-orders.php"><i class="icon-tasks"></i>Today's Orders
							<?php
								$f1="00:00:00";
								$from=date('Y-m-d')." ".$f1;
								$t1="23:59:59";
								$to=date('Y-m-d')." ".$t1;
								if($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2)
									$result = mysqli_query($con,"SELECT * FROM order_main where datetime Between '$from' and '$to'");
								else
									$result = mysqli_query($con,"SELECT * FROM order_main where order_from = $user_id AND datetime Between '$from' and '$to'");
								$num_rows1 = mysqli_num_rows($result);	
							?>
							<b class="label orange pull-right"><?php echo htmlentities($num_rows1); ?></b>
						</a>
					</li>
					<li>
						<a href="pending-orders.php"><i class="icon-tasks"></i>All Orders
							<?php	
								// $status='delivered';	
								if($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2)								 
									$ret = mysqli_query($con,"SELECT * FROM order_main");
								else
									$ret = mysqli_query($con,"SELECT * FROM order_main where order_from = $user_id ");
								$num = mysqli_num_rows($ret);	
							?>
							<b class="label orange pull-right"><?php echo htmlentities($num); ?></b>
						</a>
					</li>
				</ul>
			</li>
		</ul>
		<ul class="widget widget-menu unstyled">						
			<li><a href="main.php"><i class="menu-icon icon-file"></i>Report</a></li>
		</ul>
		<?php if($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2){?>
		<ul class="widget widget-menu unstyled">						
			<li><a href="promo-code.php"><i class="menu-icon icon-file"></i>Promo Code</a></li>
		</ul>
		<?php } ?>
		<ul class="widget widget-menu unstyled">						
			<li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout</a></li>
		</ul>
	</div>
</div>

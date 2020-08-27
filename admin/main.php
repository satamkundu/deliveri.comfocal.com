<?php
require_once('include/top.php');
if(strlen($_SESSION['alogin'])==0){	
	header('location:index.php');
}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin | Home</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>

</head>
<body>
<?php include('include/header.php');?>
<?php
//today
if($res=mysqli_fetch_array(mysqli_query($con,"SELECT SUM(cod_self) FROM delivery_details WHERE DATE(`datetime`) = CURDATE()")))
    $today_cod = $res['SUM(cod_self)'];

//yesterday
if($res=mysqli_fetch_array(mysqli_query($con,"SELECT SUM(cod_self) FROM delivery_details WHERE DATE(`datetime`) = CURDATE()-1")))
    $yesterday_cod = $res['SUM(cod_self)'];

//This month
if($res=mysqli_fetch_array(mysqli_query($con,"SELECT SUM(cod_self) FROM delivery_details WHERE MONTH(datetime) = MONTH(NOW()) AND YEAR(datetime) = YEAR(NOW())")))
    $this_month_cod = $res['SUM(cod_self)'];

//Total COD
if($res=mysqli_fetch_array(mysqli_query($con,"SELECT SUM(cod_self) FROM delivery_details")))
    $total_cod = $res['SUM(cod_self)'];


?>

<div class="wrapper">
		<div class="container">
			<div class="row">
			<?php include('include/sidebar.php');?>	
            
            <div class="span9">
            <!--COD REPORT start-->
                <!-- <div class="content">
                    <div class="module">
                        <div class="module-head">
                            <h3>COD Value</h3>
                        </div>
                        <div class="module-body">
                            <div style="width:33%;display:inline-block">
                                <p>Today's COD value : <?=$today_cod?></p>                                
                            </div>
                            <div style="width:33%;display:inline-block">
                                <p>Yesterday's COD value : <?=($yesterday_cod=="NULL" || $yesterday_cod==0)?0:$yesterday_cod?></p>
                            </div>
                            <div style="width:33%;display:inline-block">
                                <p>This Month's COD value : <?=$this_month_cod?></p>
                            </div>
                            <div style="width:33%;display:inline-block">
                                <p>Total COD value : <?=$total_cod?></p>
                            </div>
                            <div style="width:33%;display:inline-block">
                                <a href='report/today_cod_report.php' target="_blank"><button>Print Today's COD Report</button></a>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="content">
                    <div class="module">
                        <div class="module-head">
                            <h3>COD Value</h3>
                        </div>                        
                        <div class="module-body">
                            <div style="width:24%;display:inline-block">
                                <p>Today's : <?=$today_cod?></p>                                
                            </div>
                            <div style="width:24%;display:inline-block">
                                <p>Yesterday's : <?=($yesterday_cod=="NULL" || $yesterday_cod==0)?0:$yesterday_cod?></p>
                            </div>
                            <div style="width:24%;display:inline-block">
                                <p>This Month's : <?=$this_month_cod?></p>
                            </div>
                            <div style="width:24%;display:inline-block">
                                <p>Total : <?=$total_cod?></p>
                            </div>
                            <div style="margin-bottom:3rem">
                                <div>
                                    <a style="float:left" href='report/today_cod_report.php?for=today' target="_blank"><button>Print Today's COD Report</button></a>
                                    <a style="float:right" href='report/today_cod_report.php?for=yesterday' target="_blank"><button>Print Yesterday's COD Report</button></a>
                                </div>
                            </div>

                            <div>
                                <div style="width:27%;display:inline-block">
                                    <p><input id="cod_individual" type="number" placeholder="Enter OrderID"></p>
                                </div>
                                <div style="width:47%;display:inline-block">
                                    <button style="margin-top:-1rem" onclick="go_cod_individual()">Generate COD Report</button>
                                </div>
                            </div>
                            
                            <div style="width:33%;display:inline-block">
                                <p><b>From </b><input id="from_date_cod" type="date" name="from_date"></p>                                
                            </div>
                            <div style="width:33%;display:inline-block">
                                <p><b>To </b><input id="to_date_cod" type="date" name="to_date"></p>
                            </div>
                            <div style="width:33%;display:inline-block">
                                <button style="margin-top:-1rem" onclick="go_cod_summary()">Generate COD Report</button>
                            </div>                            
                        </div>
                    </div>
                </div>
                <!--COD REPORT end-->

                <!--Order summary REPORT start-->            
                <div class="content">
                    <div class="module">
                        <div class="module-head">
                            <h3>Order summary</h3>
                        </div>                        
                        <div class="module-body">
                            <div style="margin-bottom:3rem">
                                <div>
                                    <a style="float:left" href='report/order_summary.php?for=today' target="_blank"><button>Print Today's Order summary</button></a>
                                <!-- </div>
                                <div style="width:47%;display:inline-block;"> -->
                                    <a style="float:right" href='report/order_summary.php?for=yesterday' target="_blank"><button>Print Yesterday's Order summary</button></a>
                                </div>
                            </div>

                            <div>
                                <div style="width:27%;display:inline-block">
                                    <p><input id="os_individual" type="number" placeholder="Enter OrderID"></p>
                                </div>
                                <div style="width:47%;display:inline-block">
                                    <button style="margin-top:-1rem" onclick="go_summary_individual()">Generate Order summary</button>
                                </div>
                            </div>
                            
                            <div style="width:33%;display:inline-block">
                                <p><b>From </b><input id="from_date" type="date" name="from_date"></p>                                
                            </div>
                            <div style="width:33%;display:inline-block">
                                <p><b>To </b><input id="to_date" type="date" name="to_date"></p>
                            </div>
                            <div style="width:33%;display:inline-block">
                                <button onclick="go_summary()">Generate Order summary</button>
                            </div>                            
                        </div>
                    </div>
                </div>            
                <!--Order summary REPORT end-->
            </div>
            
        </div>
    </div>
</div>
<?php include('include/footer.php');?>
	<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
</body>
<?php } ?>

<script>
    function go_summary(){
        let from_date = document.getElementById('from_date').value;
        let to_date = document.getElementById('to_date').value;
        if(from_date != "" && to_date != ""){
            window.open('report/order_summary.php?from='+from_date+'&to='+to_date);
        }else
            alert('Please fill currect date');
    }

    function go_summary_individual(){
        let os_individual = document.getElementById('os_individual').value;
        if(os_individual != ""){
            window.open('report/order_summary.php?orderid='+os_individual);
        }else
            alert('Please fill currect date');
    }

    function go_cod_summary(){
        let from_date = document.getElementById('from_date_cod').value;
        let to_date = document.getElementById('to_date_cod').value;
        if(from_date != "" && to_date != ""){
            window.open('report/today_cod_report.php?from='+from_date+'&to='+to_date);
        }else
            alert('Please fill currect date');
    }

    function go_cod_individual(){
        let os_individual = document.getElementById('cod_individual').value;
        if(os_individual != ""){
            window.open('report/today_cod_report.php?orderid='+os_individual);
        }else
            alert('Please fill currect date');
    }
</script>
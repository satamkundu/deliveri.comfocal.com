<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
include_once('../include/config.php');
$q = $_GET['q'];
if(isset($q) || !empty($q)) {	
    $query = "SELECT * FROM pincodes WHERE pincode LIKE '$q%' AND status = 'open'";
    $result = mysqli_query($con, $query);
    $res = array();
    while($resultSet = mysqli_fetch_assoc($result)) {   
        $res[$resultSet['id']]['id'] =  $resultSet['rates_per_kg'];  
        $res[$resultSet['id']]['value'] = $resultSet['pincode'];
        $res[$resultSet['id']]['label'] = $resultSet['pincode'].", ".$resultSet['taluk'].", ".$resultSet['divisionname'].", ".$resultSet['statename'];
    }
    if(!$res) {
    	$res[0] = 'Not found!';
    }
    echo json_encode($res);
}

?>

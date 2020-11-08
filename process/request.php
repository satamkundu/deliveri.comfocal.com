<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require_once '../includes/config.php';
$q = $_GET['q'];
$for_option = $_GET['for_option'];
if(isset($q) || !empty($q)) {	
    if(isset($for_option) || !empty($for_option)){
        if($for_option=='same'){
            $query = "SELECT * FROM pincodes WHERE pincode LIKE '$q%' AND status = 'open' AND same_day = 'yes'";
            $result = mysqli_query($con, $query);
            $res = array();
            while($resultSet = mysqli_fetch_assoc($result)) {   
                $res[$resultSet['id']]['id'] =  $resultSet['rates_per_kg'];  
                $res[$resultSet['id']]['value'] = $resultSet['pincode'];
                $res[$resultSet['id']]['label'] = $resultSet['pincode'].", ".$resultSet['taluk'].", ".$resultSet['districtname'].", ".$resultSet['statename'];
                $res[$resultSet['id']]['districtname'] = $resultSet['districtname'];
            }
            if(!$res) {
                $res[0] = 'Not found!';
            }
        }else{
            $query = "SELECT * FROM pincodes WHERE pincode LIKE '$q%' AND status = 'open'";
            $result = mysqli_query($con, $query);
            $res = array();
            while($resultSet = mysqli_fetch_assoc($result)) {   
                $res[$resultSet['id']]['id'] =  $resultSet['rates_per_kg'];  
                $res[$resultSet['id']]['value'] = $resultSet['pincode'];
                $res[$resultSet['id']]['label'] = $resultSet['pincode'].", ".$resultSet['taluk'].", ".$resultSet['districtname'].", ".$resultSet['statename'];
                $res[$resultSet['id']]['districtname'] = $resultSet['districtname'];
            }
            if(!$res) {
                $res[0] = 'Not found!';
            }
        }
    }    
    echo json_encode($res);
}

?>

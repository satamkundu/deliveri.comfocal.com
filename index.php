<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<?php include_once 'includes/top.php'; ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<style>
    * {
        margin: 0;
        padding:0;
        box-sizing:border-box;
    }
    .deli-frm{
        padding:0;
    }
    .upi-code{
        font-size:2rem;
        padding-top:10vh;
    }
    .btn-ord{
        padding:3rem;
        background-color:#00001B;
        color:white;
    }
    .delivery-btn{
        padding-top:20vh
    }
    .pick_up_details{
        padding-top:10vh;
    }
    .delivery_details{
        padding-top:5vh;
    }
    .deli-con{
        padding-bottom:10vh;
    }
    #res-mobile{
        display:none;
    }
    .final_message{
        margin-top: -6rem;
    }
    .bank-details{
        margin-left:17vh;
        text-align: left;
        margin-bottom:1rem;
    }

    .ui-autocomplete-loading {
        background: white url("assets/images/ui-anim_basic_16x16.gif") right center no-repeat;
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

    @media only screen and (max-width: 600px) {
        .legend {
            font-size: inherit;
        }
        .pick-head{
            font-size: inherit;
        }
        .pick-box{
            width:100%;
        }
        .final-table{
            font-size: xx-small;
        }
        .order-text{
            font-size: inherit;
        }
        .upi-code{
            font-size:1rem;
            padding-top:0;
        }
        .delivery-btn{
            padding-top:5vh
        }
        .pick_up_details{
            padding-top:5vh;
        }
        .btn-ord{
            padding:2rem;
            width: 100%;
            margin-bottom:1rem;
        }
        #res{
            display:none;
        }
        #res-mobile{
            display:inline;
            font-size: 0.7rem;
        }
        .btn-last{
            display:block;
            margin:1rem;
        }
        .bank-details{
            margin-left:0vh;
        }
    }
</style>
<body>
    <div class="container-fluid">
        <div class="row"> 
            <div class="col-md-6">
                <img class="logo" src="assets/images/logo.png" alt="Logo">
            </div>
            <?php
                $user_id = 0;
                $user_admin_type = 0;
                if(isset($_SESSION["id_user"]) && isset($_SESSION["username"]) ){
                    include_once 'includes/config.php';
                    $user_id = $_SESSION["id_user"];
                    $res1 = mysqli_query($con, "SELECT admin_id FROM users WHERE id = '$user_id'");
                    $row1 = mysqli_fetch_assoc($res1);
                    $user_admin_type = $row1['admin_id'];                    
            ?>
            <div class="col-md-6">
                <p style="float: right;padding: 2rem 2rem;">
                    <i class="fa fa-user pr-2" aria-hidden="true"></i><b class="pr-5"><?= (isset($_SESSION["username"]))?htmlspecialchars($_SESSION["username"]):''; ?></b>
                    <a href="users/logout.php" class="btn btn-danger">Sign Out</a>
                </p>
            </div>
                <?php } ?>
        </div>
    </div>
    <div class="container-fluid" style="margin-bottom:6rem;">
        <div class="row">
            <div class="col-md-12">
                <div class="fieldset">                    
                    <div class="delivery-btn">
                        <h1 class="legend text-center text-primary">Choose Delivery Option</h1>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 text-center">
                                <a href="https://wa.me/+917450070216"> <button type="button" class="btn-ord swift-btn btn">Swift Delivery</button></a>
                                <button type="button" class="btn-ord regular-btn btn">Regular Delivery</button>
                                <button type="button" class="btn-ord same-btn btn">Same Day Delivery</button>
                            </div>
                        </div> 
                    </div>
                </div>

                <?php
                if(isset($_SESSION["id_user"])){
                    $res2 = mysqli_query($con, "SELECT * FROM user_address WHERE user_id = '$user_id'");                    
                    $row2 = mysqli_fetch_assoc($res2);
                    extract($row2);                    
                    mysqli_close($con);
                }
                ?>
                <input type="hidden" id="pick_default_pin" value="<?=(isset($_SESSION["id_user"]))?$pincode:'' ?>">

                <div class="fieldset">
                    <div class="pick_up_details" style="display:none">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 pick-box">
                                <form class="text-center border border-light" action="#!">
                                    <p class="h4 mb-4 pick-head">Pick Up Details</p>                            
                                    <input type="text" id="p_name" class="form-control mb-2" placeholder="Name" value="<?=(isset($_SESSION["id_user"]))?$name:'' ?>">
                                    <input type="number" id="p_phone" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" class="form-control mb-2" placeholder="Phone" value="<?=(isset($_SESSION["id_user"]))?$phone:'' ?>" >
                                    <textarea name="add" id="p_add" class="form-control mb-2" placeholder="Address..."><?=(isset($_SESSION["id_user"]))?$address:'' ?></textarea>
                                    <input type="text" id="p_landmark" class="form-control mb-2" placeholder="Landmark"  value="<?=(isset($_SESSION["id_user"]))?$landmark:'' ?>">
                                    <input type="number" id= "p_pin" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" class="form-control mb-2" placeholder="Pincode"  value="<?=(isset($_SESSION["id_user"]))?$pincode:'' ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-9" style="padding-right:0"><input type="text" id="promo_code" class="form-control mb-2" placeholder="Have You Any Valid Promo Code ? "></div>
                                        <div class="col-sm-3" style="padding-left:0"><input type="button" id="validate_promocode" class="btn btn-primary mb-2" value="Validate Promocode"></div>
                                        <p id="promo_msg" style="margin-left: 2rem;font-size: 11px;"></p>
                                    </div>                                    
                                </form>
                                <button id="back-1" type="button" class="back-btn btn btn-danger">Back</button>
                                <button id="next-1" style="float:right" type="button" class="next-btn btn btn-success">Next</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fieldset">
                    <div class="delivery_details" style="display:none">
                        <form autocomplete="off" class="text-center border border-light" action="#!" style="padding-bottom:15vh;">
                            <p class="h4 mb-4 pick-head text-info">Delivery Details</p>
                            <table class="table" style="width: 100%;" id="productTable">
                            <tbody>
                            <?php
						  		$arrayNumber = 0;
						  		for($x = 1; $x < 2; $x++) { 
                            ?>                            
                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                <td>
                                    <div class="container deli-con">
                                        <div class="row">
                                        <div class="col-md-12 deli-frm text-center">Delivery Details <?php echo $x; ?></div>
                                            <div class="col-md-3 deli-frm">
                                                <input type="text" id= "name<?php echo $x; ?>" class="form-control mb-4" placeholder="Name" >
                                            </div>
                                            <div class="col-md-3 deli-frm">
                                                <input type="number" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" id= "phone<?php echo $x; ?>" class="form-control mb-4" placeholder="Phone" >
                                            </div>
                                            <div class="col-md-3 deli-frm">
                                                <input name="add" id= "add<?php echo $x; ?>" class="form-control mb-4" placeholder="Address..." >
                                            </div>
                                            <div class="col-md-3 deli-frm">
                                                <input type="text" id= "landmark<?php echo $x; ?>" class="form-control mb-4" placeholder="landmark" >
                                            </div>
                                            <div class="col-md-3 deli-frm">
                                                <div class="ui-widget">
                                                    <input type="number" autocomplete="new-pin" onkeyup="check_pin_avail(this.id)" id= "pin<?php echo $x; ?>" class="form-control mb-4" placeholder="Pincode">
                                                    <input type="hidden" id="pin_rate<?= $x ?>" value="0">
                                                    <input type="hidden" id="pin_rate_taluk<?= $x ?>" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3 deli-frm">
                                                <input type="number" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" id= "aprx_amt<?php echo $x; ?>" class="form-control mb-4" placeholder="Declare Product Value" >
                                            </div>
                                            <div class="col-md-3 deli-frm">
                                                <div class="row m-0">
                                                    <div class="col-md-6 col-xs-6 col deli-frm">
                                                        <input type="number" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" id= "weight<?php echo $x; ?>" class="form-control mb-4" placeholder="Item Weight (KG)" >
                                                    </div>
                                                    <div class="col-md-6 col-xs-6 col deli-frm">
                                                        <select class="form-control" id="weight-gm<?php echo $x; ?>">
                                                            <option value="0">Select GM</option>
                                                            <option value="0">0</option>
                                                            <option value="100">100</option>
                                                            <option value="200">200</option>
                                                            <option value="300">300</option>
                                                            <option value="400">400</option>
                                                            <option value="500">500</option>
                                                            <option value="600">600</option>
                                                            <option value="700">700</option>
                                                            <option value="800">800</option>
                                                            <option value="900">900</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="col-md-3 deli-frm">
                                                <div class="row m-0">
                                                    <div class="col-md-6 col-xs-6 col deli-frm">                                                    
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input mb-4" onclick="cod_change(<?php echo $x; ?>)" id="cod<?php echo $x; ?>">
                                                            <label class="form-check-label" for="cod<?php echo $x; ?>">With COD</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6 col deli-frm">
                                                        <input type="number" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" id= "cod-amt<?php echo $x; ?>" class="form-control mb-4" placeholder="COD Amount" value="0" disabled>
                                                    <div>
                                                </div>
                                            </div>                                          
                                        </div>                                        
                                    </div>
                                </td>
                            </tr> 
                            <?php
					  			$arrayNumber++;
						  	} // /for
                              ?> 
                              </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-circle" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="fa fa-plus" aria-hidden="true"></i>Add Address</button>
                        </form>
                        <button id="back-2" type="button" class="back-btn btn btn-danger">Back</button>
                        <button id="next-2" style="float:right" type="button" class="next-btn btn btn-success">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="final_message" style="margin-bottom:6rem;display:none;">
        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header text-center" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Order Details
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div id="res"></div>
                        <div id="res-mobile"></div>
                    </div>
                </div>                
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="https://comfocall.co.in/payment-page/" target="_blank"><button class="btn btn-info btn-last">Proceed to Payment</button></a>
                        <button class="btn btn-info btn-last" onclick="printDiv()">Print Bill</button>
                        <a href="https://wa.me/+917450070216"><button class="btn btn-info btn-last">Share Screenshot of Payment with your Order ID</button></a>
                        <button class="btn btn-success btn-last" id="printed-name-tag">Do you want printed name tag?</button>
                        <button class="btn btn-success btn-last" id="hand-written-name-tag" data-toggle="modal" data-target=".bd-example-modal-lg">Do you want Hand written name tag?</button>
                    </div>
                </div>
            </div>           
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"></div>
        </div>
    </div>

    <footer class="footer fixed-bottom text-white">
        <div class="footer-copyright text-center py-3" style="background-color:#00001B">© 2020 Copyright : 
            <a href="https://comfocal.co.in/" style="color:white;"> comfocal.co.in</a>
        </div>
    </footer>
</body>
<?php include_once 'includes/footer_url.php'; ?>
<script>
    let details = 
    {
        delivery_option : "",
        pick_up_details : {
            name : "",
            pincode : "",
            phone : "",
            address : "",
            landmark : ""
        },
        delivery_details :[],
        order_id:Math.floor(Math.random()*900000000) + 100000000,
        amount:"",
        user_id:<?=$user_id?>,
        user_admin_type:<?=$user_admin_type?>,
        promo_id:'0',
        promo_rate:"0"
    }

    // console.log(details.user_id+" "+details.user_admin_type);

    let option;
    let p_name, p_pincode, p_phone, p_address, p_landmark;
    function show_pickup(){
        $(".delivery-btn").hide();
        $(".pick_up_details").show();
    }

    $(document).ready(function(){
        $(".pick_up_details").hide();
        $(".delivery_details").hide();
        $(".final_message").hide();

        $(".swift-btn").click(function(){
            // show_pickup();
            // option = "swift";
            // details.delivery_option = option;
            // alert('Currently Unavailable this Option')
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Oops...',
            //     text: 'Currently Unavailable this Option',
            // });
        });

        $(".back-btn").click(function(){
            $(".delivery-btn").show();
            $(".pick_up_details").hide();
        });

        $(".next-btn").click(function(){
            p_name = $('#p_name').val();
            p_pincode = $('#p_pin').val();
            p_phone = $('#p_phone').val();
            p_address = $('#p_add').val();
            p_landmark = $('#p_landmark').val();

            var error = 0;
            if(p_name=="" || p_pincode == "" || p_phone == "" || p_address == "" || p_landmark == ""){
                // alert("Please Enter All PickUp Details");
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter all pickup details',
                });
                error = 1;
                return false;
            }else{
                if( !p_name.match('^[a-zA-Z ]{3,150}$') ){
                    // alert('Please Give a Proper Name');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please input proper name',
                    });
                    error = 1;
                    return false;
                }
                if( !p_pincode.match('^[0-9]{6,6}$') ){
                    // alert('Please Enter a Valid Pincode');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please enter a valid pincode',
                    });
                    error = 1;
                    return false;
                }
                if( !p_phone.match('^[0-9]{10,10}$') ){
                    // alert('Please Enter a Valid Phone Number');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please enter a valid phone number',
                    });
                    error = 1;
                    return false;
                }
                if(error == 0){
                    details.pick_up_details.name = p_name;
                    details.pick_up_details.pincode = p_pincode;
                    details.pick_up_details.phone = p_phone;
                    details.pick_up_details.address = p_address;
                    details.pick_up_details.landmark = p_landmark;

                    $(".pick_up_details").hide();
                    $(".delivery_details").show();
                    $(".back-btn").attr("id", "back-2");
                    $(this).attr("id", "next-2");
                }            
            }
        });

        $("#back-2").click(function(){
            $(".delivery-btn").hide();
            $(".delivery_details").hide();
            $(".pick_up_details").show();
            $(this).attr("id", "back-1");
            $("#next-2").attr("id", "next-1");
        });

        $("#next-2").click(function(){

            details.delivery_details = []; 

            var tableLength = $("#productTable tbody tr").length;

            var tableRow;
            var arrayNumber;
            var count;

            if(tableLength > 0) {		
                tableRow = $("#productTable tbody tr:last").attr('id');
                count = tableRow.substring(3);

                // console.log(count);
                var error = 0;
                if($("#name"+(count)).val() !="" &&  $("#phone"+(count)).val() != "" && $("#add"+(count)).val() != ""&& $("#pin"+(count)).val() != "" && $("#weight"+(count)).val() != "" && $("#landmark"+(count)).val() != "" && $("#aprx_amt"+(count)).val() != ""){  
                    if( !$("#name"+(count)).val().match('^[a-zA-Z ]{3,16}$') ){
                        // alert('Please Give a Proper Name');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please input proper name',
                        });
                        error = 1;
                        return false;
                    }
                    if( !$("#pin"+(count)).val().match('^[0-9]{6,6}$') ){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please enter a valid pincode',
                        });
                        error = 1;
                        return false;
                    }

                    if( $("#pin_rate"+(count)).val() == "0"){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please Choose Pincode From Dropdown List',
                        });
                        error = 1;
                        return false;
                    }

                    if( !$("#phone"+(count)).val().match('^[0-9]{10,10}$') ){
                        // alert('Please Enter a Valid Phone Number');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please enter a valid phone number',
                        });
                        error = 1;
                        return false;
                    }

                    

                    var amount_details = [];

                    if(error == 0){
                        var amount_temp=0;
                        if(details.delivery_option=="regular"){
                            var amount = 0;                        
                            if(count == 1){
                                const base_amount_for_more_than_one_kg = $("#pin_rate"+(count)).val();
                                const  base_amount_for_half_gm = (base_amount_for_more_than_one_kg / 2);
                                // const base_amount_for_one_kg = 50;
                                const base_amount_for_one_kg = $("#pin_rate"+(count)).val();
                                const taluk = $("#pin_rate_taluk"+(count)).val();
                                
                                if($("#weight-gm"+(count)).val() != 0){
                                    amount +=  Math.floor(base_amount_for_one_kg) + base_amount_for_more_than_one_kg * Math.floor($("#weight"+(count)).val());
                                    amount_temp =  Math.floor(base_amount_for_one_kg) + base_amount_for_more_than_one_kg * Math.floor($("#weight"+(count)).val());
                                }else if($("#weight-gm"+(count)).val() == 0){
                                    if($("#weight"+(count)).val() == 1){
                                        amount +=  Math.floor(base_amount_for_one_kg);
                                        amount_temp =  Math.floor(base_amount_for_one_kg);
                                    }else{
                                        amount +=  Math.floor(base_amount_for_one_kg) + base_amount_for_more_than_one_kg * ( Math.floor($("#weight"+(count)).val()) - 1 );
                                        amount_temp =  Math.floor(base_amount_for_one_kg) + base_amount_for_more_than_one_kg * ( Math.floor($("#weight"+(count)).val()) - 1 );
                                    }
                                }
                                if($("#cod-amt"+(count)).val() != 0){
                                    let interest = ($("#cod-amt"+(count)).val() * 0.015);
                                    amount +=  parseFloat(interest.toFixed(2));
                                    amount = parseFloat(amount.toFixed(2));                      
                                }
                                amount_details.push({"amount_self":amount_temp,"cod_self":$("#cod-amt"+(count)).val()});
                            }else{
                                const base_amount_for_more_than_one_kg = $("#pin_rate"+(count)).val();
                                // const base_amount_for_one_kg = 50;
                                const base_amount_for_one_kg = $("#pin_rate"+(count)).val();
                                for(var i = 1; i <= count; i++){
                                    if($("#weight-gm"+(i)).val() != 0){
                                        amount += Math.floor( base_amount_for_one_kg) + base_amount_for_more_than_one_kg * Math.floor($("#weight"+(i)).val());
                                        amount_temp =  Math.floor(base_amount_for_one_kg) + base_amount_for_more_than_one_kg * Math.floor($("#weight"+(i)).val());
                                    }else if($("#weight-gm"+(i)).val() == 0){
                                        if($("#weight"+(i)).val() == 1){
                                            amount +=  Math.floor(base_amount_for_one_kg);
                                            amount_temp =  Math.floor(base_amount_for_one_kg);
                                        }else{
                                            amount +=  Math.floor(base_amount_for_one_kg) + base_amount_for_more_than_one_kg * ( Math.floor($("#weight"+(i)).val()) - 1 );
                                            amount_temp =  Math.floor(base_amount_for_one_kg) + base_amount_for_more_than_one_kg * ( Math.floor($("#weight"+(i)).val()) - 1 );
                                        }
                                    }                                
                                    if($("#cod-amt"+(i)).val() != 0){
                                        let interest = ($("#cod-amt"+(i)).val() * 0.015);
                                        amount +=  parseFloat(interest.toFixed(2));
                                        amount = parseFloat(amount.toFixed(2));                         
                                    }
                                    amount_details.push({"amount_self":amount_temp,"cod_self":$("#cod-amt"+(i)).val()});
                                }                                
                            }
                            if( $("#pick_default_pin").val() != $("#p_pin").val() ){      
                                amount += 25; //convence charge for regular delivery
                            }
                            if(details.promo_rate > 0){
                                // console.log(details.promo_rate);
                                amount = amount - amount * (details.promo_rate / 100);
                                details.amount = parseFloat(amount.toFixed(2));
                            }else{
                                details.amount = amount;
                            }
                        }
                        else if(details.delivery_option=="same"){
                            var amount = 0;
                            var amount_temp = 0;

                            const base_amount_for_more_than_one_kg = $("#pin_rate"+(count)).val();
                            const base_amount_for_one_kg = 80;            
                            for(var i = 1; i <= count; i++){
                                if($("#weight-gm"+(i)).val() != 0){
                                    amount += base_amount_for_one_kg + base_amount_for_more_than_one_kg * Math.floor($("#weight"+(i)).val());
                                    amount_temp = base_amount_for_one_kg + base_amount_for_more_than_one_kg * Math.floor($("#weight"+(i)).val());
                                }else if($("#weight-gm"+(i)).val() == 0){
                                    if($("#weight"+(i)).val() == 1){
                                        amount += base_amount_for_one_kg;
                                        amount_temp = base_amount_for_one_kg;
                                    }else{
                                        amount += base_amount_for_one_kg + base_amount_for_more_than_one_kg * ( Math.floor($("#weight"+(i)).val()) - 1 );
                                        amount_temp = base_amount_for_one_kg + base_amount_for_more_than_one_kg * ( Math.floor($("#weight"+(i)).val()) - 1 );
                                    }
                                }
                                if($("#cod-amt"+(i)).val() != 0){
                                    let interest = ($("#cod-amt"+(i)).val() * 0.015);
                                    amount +=  parseFloat(interest.toFixed(2));
                                    amount = parseFloat(amount.toFixed(2));                             
                                }
                                amount_details.push({"amount_self":amount_temp,"cod_self":$("#cod-amt"+(i)).val()});
                            }
                            if(details.promo_rate > 0){
                                amount = amount - amount * (details.promo_rate / 100);
                                details.amount = parseFloat(amount.toFixed(2));
                            }else{
                                details.amount = amount;
                            }
                            // details.amount = amount;
                            // console.log(amount);
                        }
                        
                        for(var i = 1; i <= count; i++){
                            details.delivery_details.push({"name":$("#name"+i).val(), "pin":$("#pin"+i).val(), "phone":$("#phone"+i).val(), "add":$("#add"+i).val(), "weight":Math.floor($("#weight"+i).val()), "landmark":$("#landmark"+i).val(), "weight_gm":$("#weight-gm"+i).val(),"aprx_amt":$("#aprx_amt"+i).val(),"cod_amt":$("#cod-amt"+i).val(),"amount_self":amount_details[i-1].amount_self,"cod_self":amount_details[i-1].cod_self});
                        }

                        Swal.fire({
                            title: 'Checkout Confirmation',
                            text: "Do you want to proceed for checkout?",
                            footer: 'Total amount : '+details.amount,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                            }).then((result) => {
                            if (result.value) {
                                // console.log(details);
                                $("#res").html("<h6 class='text-primary text-center'>Please Wait...</h6>");
                                var dataString = JSON.stringify(details);
                                
                                $.ajax({
                                    type: 'POST',    
                                    url:'process/data.php',
                                    data:{myData: dataString},
                                    success: function(msg){
                                        var newHTML = [];
                                        
                                        newHTML.push('<div id="content2" style="background: #fff;border-bottom: 1px solid #ffffff;">'+
                                            '<div class="tokenDet" style="padding: 15px;border: 1px solid #000;width:50rem;margin: 0 auto;position: relative;overflow: hidden;">'+
                                                '<div style="border-bottom: 1px solid #000;margin-bottom: 15px;height: 5rem;">'+
                                                '<img src="assets/images/logo.png" style="height: 2rem;">'+
                                                '<p style="font-size: 0.7rem;float: right;">Address : 51, College Road, Howrah, West Bengal 711103<br>Email ID : info@comfocall.co.in</p>'+
                                                '</div>'+                                                
                                                '<div class="title" style="text-align: center; color: green; border-bottom: 1px solid #000;margin-bottom: 15px;">'+
                                                    '<h2>Order Confirmation Details</h2>'+
                                                '</div>'+
                                                '<div class="parentdiv" style="display: inline-block;width: 100%;position: relative;color: black;">'+
                                                    '<div class="innerdiv" style="width: 100%;float: left;">'+
                                                        '<div class="restDet">'+
                                                            '<div class="div">'+
                                                                '<div class="label" style="width: 30%;float: left;">'+
                                                                    '<strong>Tracking ID</strong>'+
                                                                '</div>'+
                                                                '<div class="data" style="width: 70%;display: inline-block;">'+
                                                                    '<span>'+details.order_id+'</span>'+
                                                                '</div>'+
                                                                '<div class="label" style="width: 30%;float: left;">'+
                                                                    '<strong>Order Type</strong>'+
                                                                '</div>'+
                                                                '<div class="data" style="width: 70%;display: inline-block;">'+
                                                                    '<span>'+details.delivery_option.toUpperCase()+'</span>'+
                                                                '</div>'+
                                                                '<div class="label" style="width: 30%;float: left;">'+
                                                                    '<strong>Total Price</strong>'+
                                                                '</div>'+
                                                                '<div class="data" style="width: 70%;display: inline-block;">'+
                                                                    '<span>'+amount+'</span>'+
                                                                '</div>'+
                                                                '<div class="label" style="width: 30%;float: left;">'+
                                                                    '<strong>PickUp Details</strong>'+
                                                                '</div>'+
                                                                '<div class="data" style="width: 70%;display: inline-block;">'+
                                                                    '<span>'+
                                                                        '<b>Name : </b>'+details.pick_up_details.name+', '+
                                                                        '<b>Phone No : </b>'+ details.pick_up_details.phone+'<br>'+
                                                                        '<b>Address : </b>'+ details.pick_up_details.address+', '+details.pick_up_details.pincode+'<br>'+
                                                                        '<b>Landmark : </b>'+ details.pick_up_details.landmark+
                                                                    '</span>'+
                                                                '</div>'+
                                                                '<div class="label" style="width: 30%;float: left;">'+
                                                                    '<strong>Drop Details</strong>'+
                                                                '</div>'+
                                                                '<div class="data" style="width: 70%;display: inline-block;">'+
                                                                    '<span>');
                                                                    var cnt = 1;
                                                                    newHTML.push('<table class="table">'+
                                                                    '<tr>'+
                                                                        '<th></th>'+
                                                                        '<th>Price</th>'+
                                                                        '<th>COD</th>'+
                                                                        '<th>COD Gateway charge (1.5%)</th>'+
                                                                    '</tr>');
                                                                    for (var i = 0; i < details.delivery_details.length; i++) {
                                                                        newHTML.push('<tr>'+
                                                                            '<td>'+cnt+'</td>'+
                                                                            '<td>'+ details.delivery_details[i].amount_self +'</td>'+
                                                                            '<td>'+ details.delivery_details[i].cod_self +'</td>'+
                                                                            '<td>'+ ((details.delivery_details[i].cod_self) * 0.015).toFixed(2) +'</td>'+
                                                                        '</tr>'
                                                                    );
                                                                        cnt++;
                                                                    }
                                                                    if( $("#pick_default_pin").val() != $("#p_pin").val() ){
                                                                        // if(details.delivery_option=="regular" && count > 1){
                                                                            newHTML.push('<tr><th colspan="4">Convenience Fee</th><th>25</th></tr>');
                                                                        // }
                                                                    }
                                                                    newHTML.push('<tr><th colspan="4">Total</th><th colspan="2">'+details.amount+'</th></tr></table></span>'+
                                                                '</div>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>'+                                        
                                                '</div>'+
                                            '</div>'+
                                        '</div>');

                                        $("#res").html(newHTML.join(""));

                                        var newHTML_Mobile = [];
                                        newHTML_Mobile.push('<table class="table table-sm">'+                                         
                                            '<tr>'+ 
                                                '<th scope="col">Tracking ID</th>'+ 
                                                '<th>'+details.order_id+'</th>'+ 
                                            '</tr>'+                                         
                                            '<tbody>'+ 
                                                '<tr>'+ 
                                                    '<th scope="col">Order Type</th>'+ 
                                                    '<th>'+details.delivery_option.toUpperCase()+'</th>'+ 
                                                '</tr>'+ 
                                                '<tr>'+ 
                                                    '<th scope="col">Total Price</th>'+ 
                                                    '<th>'+amount+'</th>'+ 
                                                '</tr>'+ 
                                                '<tr>'+ 
                                                    '<th scope="col">PickUp Details</th>'+ 
                                                    '<th>'+
                                                        '<b>Name : </b>'+details.pick_up_details.name+', '+
                                                        '<b>Phone No : </b>'+ details.pick_up_details.phone+'<br>'+
                                                        '<b>Address : </b>'+ details.pick_up_details.address+', '+details.pick_up_details.pincode+'<br>'+
                                                        '<b>Landmark : </b>'+ details.pick_up_details.landmark+
                                                    '</th>'+ 
                                                '</tr>'+  
                                                '<tr>'+ 
                                                    '<th colspan="2">Drop Details</th>'+
                                                '</tr>'+                                                
                                            '</tbody>'+ 
                                        '</table>');
                                        var cnt = 1;
                                        newHTML_Mobile.push('<table class="table table-sm">'+
                                        '<tr>'+
                                            '<th></th>'+
                                            '<th>Price</th>'+
                                            '<th>COD</th>'+
                                            '<th>COD Gateway charge(1.5%)</th>'+
                                        '</tr>');
                                        for (var i = 0; i < details.delivery_details.length; i++) {
                                            newHTML_Mobile.push('<tr>'+
                                                '<td>'+cnt+'</td>'+
                                                '<td>'+ details.delivery_details[i].amount_self +'</td>'+
                                                '<td>'+ details.delivery_details[i].cod_self +'</td>'+
                                                '<td>'+ ((details.delivery_details[i].cod_self) * 0.015).toFixed(2) +'</td>'+
                                            '</tr>'
                                        );
                                            cnt++;
                                        }
                                        if( $("#pick_default_pin").val() != $("#p_pin").val() ){
                                            // if(details.delivery_option=="regular" && count > 1){
                                                newHTML_Mobile.push('<tr><th colspan="4">Convenience Fee</th><th>25</th></tr>');
                                            // }
                                        }
                                        newHTML_Mobile.push('<tr><th colspan="4">Total</th><th colspan="2">'+details.amount+'</th></tr></table>');
                                        $("#res-mobile").html(newHTML_Mobile.join(""));
                                    }
                                });

                                $(".delivery-btn").hide();
                                $(".delivery_details").hide();
                                $(".pick_up_details").hide();
                                $("#back-2").hide();
                                $("#next-2").hide();
                                $(".final_message").show();
                            }
                        });                         
                    }
                }else{
                    // alert("Please Fill all delivery addressess");
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please fill all delivery addressess',
                    });
                }
            } else {
                count = 1;
                arrayNumber = 0;
            }
        });

        $(".regular-btn").click(function(){
            show_pickup();
            option = "regular";
            details.delivery_option = option;
            // console.log(details);
        });

        $(".same-btn").click(function(){
            show_pickup();
            option = "same";
            details.delivery_option = option;
            // console.log(details);
        });

        $("#printed-name-tag").click(function(){
            var hand_written = [];
            hand_written.push('<table style="width:65rem;font-size:1.1rem;border-collapse: collapse;">'+            
            '<tbody>'+               
                '<tr>'+
                    '<th colspan="3">Delivery Details</th>'+                           
                '</tr>');
                var cnt = 1;
                for(var i = 0; i < details.delivery_details.length; i++){
                    hand_written.push('<tr>'+
                            '<th>'+cnt+'</th>'+
                            '<td style="width:30rem;padding:1.5rem;border-style: dotted;"><b>From : </b><br>'+ details.pick_up_details.name +'<br>'+ details.pick_up_details.address+', '+details.pick_up_details.pincode+'<br>'+ details.pick_up_details.landmark+'<br>'+ details.pick_up_details.phone+'</td>'+
                            '<td style="width:30rem;padding:1.5rem;border-style: dotted;"><b>To : </b><br>'+ details.delivery_details[i].name +'<br>'+ details.delivery_details[i].add+', '+details.delivery_details[i].pin+'<br>'+ details.delivery_details[i].landmark+'<br>'+ details.delivery_details[i].phone+'</td>'+
                        '</tr>');
                    cnt++;
                }
                hand_written.push('</tbody>'+
            '</table>');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><body onload="window.print()">'+hand_written.join(" ")+'</body></html>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},10);
        });

        $("#hand-written-name-tag").click(function(){
            var hand_written = [];
            hand_written.push('<table class="table table-sm">'+
                        '<thead>'+
                            '<tr>'+
                                '<th scope="col" colspan="3">Pick Up Details</th>'+                            
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+                            
                            '<tr>'+
                                '<th scope="col" colspan="3">Delivery Details</th>'+                           
                           '</tr>');

                            var cnt = 1;
                            for(var i = 0; i < details.delivery_details.length; i++){
                                hand_written.push('<tr>'+
                                        '<th>'+cnt+'</th>'+
                                        '<td>From<br>'+ details.pick_up_details.name +'<br>'+ details.pick_up_details.address+', '+details.pick_up_details.pincode+'<br>'+ details.pick_up_details.landmark+'<br>'+ details.pick_up_details.phone+'</td>'+
                                        '<td>To<br>'+ details.delivery_details[i].name +'<br>'+ details.delivery_details[i].add+', '+details.delivery_details[i].pin+'<br>'+ details.delivery_details[i].landmark+'<br>'+ details.delivery_details[i].phone+'</td>'+
                                    '</tr>');
                                cnt++;
                            }
                            hand_written.push('</tbody>'+
                        '</table>');
                $('.modal-content').html(hand_written.join(" "));
        });
    });


    function addRow() {
        // $("#addRowBtn").button("loading");

        var tableLength = $("#productTable tbody tr").length;

        var tableRow;
        var arrayNumber;
        var count;

        if(tableLength > 0) {		
            tableRow = $("#productTable tbody tr:last").attr('id');
            arrayNumber = $("#productTable tbody tr:last").attr('class');
            count = tableRow.substring(3);	
            count = Number(count) + 1;
            arrayNumber = Number(arrayNumber) + 1;					
        } else {
            // no table row
            count = 1;
            arrayNumber = 0;
        }

        
        // $("#addRowBtn").button("reset");


        var tr = '<tr id="row'+count+'" class="'+arrayNumber+'">'+
            '<td>'+
                '<div class="container deli-con">'+
                    '<div class="row">'+
                        '<div class="col-md-12 deli-frm text-center">Delivery Details '+count+'</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<input type="text" id= "name'+count+'" class="form-control mb-4" placeholder="Name" >'+
                        '</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<input type="number" id= "phone'+count+'" class="form-control mb-4" placeholder="Phone" >'+
                        '</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<input name="add" id= "add'+count+'" class="form-control mb-4" placeholder="Address..." >'+
                        '</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<input type="text" id= "landmark'+count+'" class="form-control mb-4" placeholder="landmark" >'+
                        '</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<div class="ui-widget">'+
                                '<input type="number" autocomplete="new-pin" onkeyup="check_pin_avail(this.id)" id="pin'+count+'" class="form-control mb-4" placeholder="Pincode">'+
                                '<input type="hidden" id="pin_rate'+count+'" value="0">'+
                                '<input type="hidden" id="pin_rate_taluk'+count+'" value="">'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<input type="number" id="aprx_amt'+count+'" class="form-control mb-4" placeholder="Declare Product Value" >'+
                        '</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<div class="row m-0">'+
                                '<div class="col-md-6 col-xs-6 col deli-frm">'+
                                    '<input type="number" id= "weight'+count+'" class="form-control mb-4" placeholder="Item Weight" >'+
                                '</div>'+
                                '<div class="col-md-6 col-xs-6 col deli-frm">'+
                                    '<select class="form-control" id="weight-gm'+count+'">'+
                                        '<option value="0">Select GM</option>'+
                                        '<option value="0">0</option>'+
                                        '<option value="100">100</option>'+
                                        '<option value="200">200</option>'+
                                        '<option value="300">300</option>'+
                                        '<option value="400">400</option>'+
                                        '<option value="500">500</option>'+
                                        '<option value="600">600</option>'+
                                        '<option value="700">700</option>'+
                                        '<option value="800">800</option>'+
                                        '<option value="900">900</option>'+
                                    '</select>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3 deli-frm">'+
                            '<div class="row m-0">'+
                                '<div class="col-md-6 col-xs-6 col deli-frm">'+
                                    '<div class="form-check">'+
                                        '<input type="checkbox" class="form-check-input mb-4" onclick="cod_change('+count+')" id="cod'+count+'">'+
                                        '<label class="form-check-label" for="cod'+count+'">With COD</label>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-6 col-xs-6 col deli-frm">'+
                                    '<input type="number" id= "cod-amt'+count+'" class="form-control mb-4" placeholder="COD Amount" value="0" disabled>'+
                                '<div>'+
                            '</div>'+
                        '</div> '+
                    '</div>'+
                '</div>'+
            '</td>'+
        '</tr> ';
        
        if(tableLength > 0) {
            var d_name,d_phone,d_add,d_pin,d_weight,d_weight_unit,d_landmark;
            let error = 0;
            d_name = $("#name"+(count-1)).val();
            d_phone = $("#phone"+(count-1)).val();
            d_add =$("#add"+(count-1)).val();
            d_pin=$("#pin"+(count-1)).val();
            d_weight=$("#weight"+(count-1)).val();
            // d_weight_unit=$("#weight-unit"+(count-1)).val();
            d_landmark=$("#landmark"+(count-1)).val();
            d_aprx_amt = $("#aprx_amt"+(count-1)).val();

                

            if( d_name != "" && d_pin != "" && d_phone != "" && d_add != "" && d_weight != "" && d_landmark != "" && d_aprx_amt != ""){
                if( !d_name.match('^[a-zA-Z ]{3,150}$') ){
                    // alert('Please Give a Proper Name');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please input proper name',
                    });
                    error = 1;
                    return false;
                }
                if( !d_pin.match('^[0-9]{6,6}$') ){
                    // alert('Please Enter a Valid Pincode');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please enter a valid pincode',
                    });
                    error = 1;
                    return false;
                }
                if( !d_phone.match('^[0-9]{10,10}$') ){
                    // alert('Please Enter a Valid Phone Number');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please enter a valid phone number',
                    });
                    error = 1;
                    return false;
                }            
                if(error == 0)
                    $("#productTable tbody tr:last").after(tr);

            }else
                // alert('Please fill previous address fields');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill previous fields',
                });

        } else {				
            $("#productTable tbody").append(tr);
        }	

    } // /add row

    function removeProductRow(row = null) {
        if(row) {
            $("#row"+row).remove();
        } else {
            // alert('error! Refresh the page again');
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error! Refresh the page again',
            });
        }
    }

    function printDiv(){
        // var divToPrint=document.getElementById('DivIdToPrint');
        var divToPrint=document.getElementById('content2');
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},10);
    }

    function cod_change(id){
        if (document.getElementById('cod'+id).checked){
            document.getElementById('cod-amt'+id).removeAttribute("disabled");
        } else {
            $("#cod-amt"+id).val(0);
            document.getElementById('cod-amt'+id).setAttribute("disabled", "true");
        }   
    }

    $("#validate_promocode").click(function(){
        let promocode = $("#promo_code").val()
        if(promocode != ""){            
            $.ajax({
                type:'POST',
                url:'process/data.php',
                data:{promocode:promocode},
                success : function(res){
                    res = $.parseJSON(res)
                    if(res.error == 'false' ){
                        details.promo_id = res.promo_id;
                        details.promo_rate = res.promo_value;
                        $("#promo_msg").text(res.message);
                        $("#promo_msg").css({ 'color': 'green'});
                    }else{
                        $("#promo_msg").text(res.message);
                        $("#promo_msg").css({ 'color': 'red'});
                    }
                }
            });
        }
    });
    
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    function check_pin_avail(id){        
        $("#"+id).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "process/request.php",
                    dataType: "json",
                    data: {
                        q: request.term,
                        for_option:details.delivery_option
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 3,
            select: function( event, ui ) {
                var vl = ui.item.id; 
                var taluk = ui.item.taluk; 
                var num = $("#"+id).attr('id');
                $("#pin_rate"+num.substring(3)).val(vl);
                $("#pin_rate_taluk"+num.substring(3)).val(vl);
            },
            open: function() {},
            close: function() {}
        });
    }

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js" integrity="sha256-c9vxcXyAG4paArQG3xk6DjyW/9aHxai2ef9RpMWO44A=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
<script>
    $('#downloadPDF').click(function () {
        domtoimage.toPng(document.getElementById('content2')).then(function (blob) {
            var pdf = new jsPDF('l', 'pt', [$('#content2').width(), $('#content2').height()]);

            pdf.addImage(blob, 'PNG', 0, 0, $('#content2').width(), $('#content2').height());
            pdf.save("order"+details.order_id+".pdf");
        });
    });
</script>
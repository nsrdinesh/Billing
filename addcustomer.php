<html>
<head>
<?php 
include('config.php');
include('index.php');
?>
	<script src="js/jquery-1.11.3.js" type="text/javascript"></script>
	<script src="js/script.js" type="text/javascript"></script>
	<link href="css/mainstyle.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="css/style.css" media="screen" rel="stylesheet" type="text/css" />
<?php
	if(isset($_POST['submit'])){
		extract($_POST);
		$insert = "INSERT INTO address(	address_line_1,address_line_2,landmark,city,state,pin) values('$ad1','$ad2','$landmark','$city','$state','$pin')";
		$res = mysqli_query($link,$insert);
		$addId = mysqli_insert_id($link);
		$today = date('y-m-d');
		if($meterId != ''){
			$rand  = $meterId;

		}else{ $rand = mt_rand(10000000,99999999); }
		$insertIntoCusTable = "INSERT INTO customer(address_id,biller_id,meter_id,phone_number,first_name,last_name,customer_type,status,activation_date,email_id,cycle_id)";
		$insertIntoCusTable.=" VALUES($addId,$biller,'$rand',$phone,'$fname','$lname','local','A','$today','$email',$cycle)";
		mysqli_query($link,$insertIntoCusTable);
		echo "Record Added Successfully!";
	}

?>	 

<body>
	
	<div class="container">
      <div class="one">
        <div class="register">
          <h3>Add New Customer</h3>
          <form id="reg-form" method="post" action="">
            <div class="lableDiv" >
              <label for="name" class="nextlableDiv">First name</label>
              <input type="text" name="fname" spellcheck="false" />
            </div>
            <div class="lableDiv">
              <label class="nextlableDiv">Last name</label>
              <input type="text" name="lname" spellcheck="false" placeholder=""/>
            </div>     
            <div class="lableDiv">
              <label for="cycle" class="nextlableDiv">Cycle</label>
              <select name="cycle">
              	<option value="">---SELECT---</option>
              <?php
              	$select = "SELECT distinct cycle_id FROM cycle";
              	$res =mysqli_query($link, $select);
              	while($rows = mysqli_fetch_array($res)){ ?>
              		<option value="<?php echo $rows['cycle_id']?>"><?php echo $rows['cycle_id'];?></option>

              	<?php }
              ?>
              </select>
            </div>
            <div class="lableDiv">
              <label for="biller" class="nextlableDiv">Biller ID</label>
              <select name="biller">
              	<option value="">---SELECT---</option>
              <?php 
              	$select = "SELECT Biller_Id,name FROM biller_info";
              	$res =mysqli_query($link, $select);
              	while($rows = mysqli_fetch_array($res)){ ?>
              		<option value="<?php echo $rows['Biller_Id']?>"><?php echo $rows['name'];?></option>

              	<?php }
              ?>
              </select>             
            </div>
            <div class="lableDiv">
              <label for="phone" class="nextlableDiv">Meter Id</label>
              <input type="text" name="meterId" placeholder=""/>
            </div>             
            <div class="lableDiv">
              <label for="phone" class="nextlableDiv">Phone Number</label>
              <input type="text" name="phone" placeholder=""/>
            </div>        
            <div class="lableDiv">
              <label for="password" class="nextlableDiv">Email Id</label>
              <input type="text" name="email" />
            </div>
            <div class="lableDiv">
              <label for="Address" class="nextlableDiv">Address Line 1</label>
              <input type="text" name="ad1" />
            </div>   
            <div class="lableDiv">
              <label for="Address" class="nextlableDiv">Address Line 2</label>
              <input type="text" name="ad2" />
            </div>  
            <div class="lableDiv">
              <label for="Address" class="nextlableDiv">landmark</label>
              <input type="text" name="landmark" />
            </div>
            <div class="lableDiv">
              <label for="Address" class="nextlableDiv">City</label>
              <input type="text" name="city" />
            </div> 
            <div class="lableDiv">
              <label for="Address" class="nextlableDiv">State</label>
              <input type="text" name="state" />
            </div> 
            <div class="lableDiv">
              <label for="Address" class="nextlableDiv">pin</label>
              <input type="text" name="pin" />
            </div>                                       
            <div class="lableDiv">
              <label></label>
              <input type="submit" value="Add Customer" id="Add Customer" name="submit" class="button"/>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
</body>>
</html>

<?php

//ini_set("display_errors",1);
//require_once 'phpExcel/excel_reader2.php';
		include('index.php');
require_once 'config.php';
$uploadedStatus = 0;
if ( isset($_POST["submit"]) ) {
	if ( isset($_FILES["file"])) {
	//if there was an error uploading the file
	if ($_FILES["file"]["error"] > 0) {
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else {
		if (file_exists($_FILES["file"]["name"])) {
		unlink($_FILES["file"]["name"]);
		}
		$storagename = "file.xlsx";
		move_uploaded_file($_FILES["file"]["tmp_name"],  $storagename);
		$uploadedStatus = 1;



////////////////////////////////////////////////////////////
//set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel/');
include 'PHPExcel/IOFactory.php';

// This is the file path to be uploaded.
$inputFileName = 'file.xlsx'; 

try {
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
} catch(Exception $e) {
	die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

for($i=2;$i<=$arrayCount;$i++){

$mid = trim($allDataInSheet[$i]["0"]);
$start_time = trim($allDataInSheet[$i]["1"]);
$start_unit = trim($allDataInSheet[$i]["2"]);
$old_date_timestamp1 = strtotime($start_time);
$start_time = date('Y-m-d H:i:s', $old_date_timestamp1);
$end_time = trim($allDataInSheet[$i]["3"]);
$old_date_timestamp2 = strtotime($end_time);
$end_time = date('Y-m-d H:i:s', $old_date_timestamp2);
$end_unit = trim($allDataInSheet[$i]["4"]);


//$query = "SELECT name FROM YOUR_TABLE WHERE name = '".$userName."' and email = '".$userMobile."'";
//$sql = mysql_query($query);
//$recResult = mysql_fetch_array($sql);
//$existName = $recResult["name"];
//if($existName=="") {
$query = "insert into meter_usage(meter_id,start_time,end_time,start_unit,end_unit) values('".$mid."','".$start_time."','".$end_time."','".$start_unit."','".$end_unit."')";
mysqli_query($link,$query);
//$msg = 'Record has been added. ';
//} else {
//$msg = 'Record already exist. ';
//}
}
echo "Record Added!";
	/////////////////////////////////////////////
		}
	} else {
	echo "No file selected <br />";
	}
}
?>

<table width="600" style="margin:115px auto; background:#f8f8f8; border:1px solid #eee; padding:20px 0 25px 0;">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<tr><td colspan="2" style="font:bold 15px arial; text-align:center; padding:0 0 5px 0;">Browse and Upload Your File </td></tr>
<tr>
<td width="50%" style="font:bold 12px tahoma, arial, sans-serif; text-align:right; border-bottom:1px solid #eee; padding:5px 10px 5px 0px; border-right:1px solid #eee;">Select file</td>
<td width="50%" style="border-bottom:1px solid #eee; padding:5px;"><input type="file" name="file" id="file" /></td>
</tr>
<tr>
<td style="font:bold 12px tahoma, arial, sans-serif; text-align:right; padding:5px 10px 5px 0px; border-right:1px solid #eee;">Submit</td>
<td width="50%" style=" padding:5px;"><input type="submit" name="submit" /></td>
</tr>
</table>
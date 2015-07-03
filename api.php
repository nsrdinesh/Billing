<?php

$data = json_decode($_REQUEST['request'], TRUE);
include("config.php");
$old_date_timestamp2 = strtotime($data['start_time']);
$start_time = date('Y-m-d H:i:s', $old_date_timestamp2);

$old_date_timestamp2 = strtotime($data['end_time']);
$end_time = date('Y-m-d H:i:s', $old_date_timestamp2);

$insert = "INSERT INTO meter_usage(meter_id,start_time,end_time,start_unit,end_unit)";
$insert.=" VALUES(".$data['meter_id'].",'".$start_time."','".$end_time."','".$data['start_unit']."','".$data['end_unit']."')";
mysqli_query($link,$insert);

?>
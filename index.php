<?php
require 'vendor/autoload.php';
include_once 'include.php';
header("Content-Type:text/html;charset=UTF-8");
//echo '</br> 执行 Admin 状态：';
//$db = new Admin($old_db, $new_db);
//$db->transfer();
//echo '</br> 执行 Store 状态：';
//$db = new Store($old_db, $new_db);
//$db->transfer();
//echo '</br> 执行 Member 状态：';
//$db = new Member($old_db, $new_db);
//$db->transfer();
//echo '</br> 执行 VipCard 状态：';
//$db = new VipCard($old_db, $new_db);
//$db->transfer();
//echo '</br> 执行 ThirdParty 状态：';
//$db = new ThirdParty($old_db, $new_db);
//$db->transfer();
//echo '</br> 执行 Activity 状态：';
//$db = new Activity($old_db, $new_db);
//$db->transfer();
//echo '</br> 执行 RechargeActivity 状态：';
//$db = new RechargeActivity($old_db, $new_db);
//$db->transfer();
//echo '</br> 执行 CustomeIntegralActivity 状态：';
//$db = new CustomeIntegralActivity($old_db, $new_db);
//$db->transfer();

//echo '</br> 执行 PayOrders 状态：';
//$db = new PayOrders($old_db, $new_db);
//$db->transfer();

echo '</br> 执行 PayRefund 状态：';
$db = new PayRefund($old_db, $new_db);
$db->transfer();



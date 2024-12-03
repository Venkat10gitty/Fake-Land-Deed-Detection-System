<?php

$conn = mysqli_connect('localhost','root','','iwp_proj') or die('connection failed');

if(isset($_POST['send'])){

   $im_no = mysqli_real_escape_string($conn, $_POST['imagenumber']);
   $length = mysqli_real_escape_string($conn, $_POST['length']);
   $width = mysqli_real_escape_string($conn, $_POST['width']);
   $fourlions = mysqli_real_escape_string($conn, $_POST['fourlions']);

   $select_message = mysqli_query($conn, "SELECT * FROM `contact_form` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');
   
   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `validate`('imagenumber', 'length', 'width', 'fourlions') VALUES('$im_no', '$length', '$width', '$fourlions')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}
?>

<?php

$order_start_number = 45777;
//connection
$conn = mysqli_connect('localhost','root','','iwp_proj') or die('connection failed');

$query = "SELECT * from object";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$row = mysqli_fetch_row($result);

$height[4];
$width[4];
$i=0;
while ($row = mysqli_fetch_row($result)) {
    $height[$i] = $row[1];
    $width[$i] = $row[2];
	$i++;
}
//database
$sql_user = 'root';
$sql_pass='';
$sql_db = 'iwp_proj';
$sql_host = 'localhost';

if($_POST) {
	$name = addslashes($_POST["fio"]);
	$count = addslashes($_POST["countbox"]);
	$sum = addslashes($_POST["totalsum"]);
	$fake=''; 
	$ppo=299;
	$sum = $count*$ppo;
	try {
		$link = mysqli_connect($sql_host,$sql_user,$sql_pass);
		mysqli_select_db($link, $sql_db);
		mysqli_set_charset($link, "utf8");
	} catch (Exception $e) {
		echo mysqli_connect_error();
	}
	
	$query = 'select count(*) as cnt from object';
	try {
		$result = mysqli_fetch_row(mysqli_query($link, $query));		
	} catch (Exception $e) {
		echo mysqli_error($link);
	}
	
	$order_id = (($result[0]!= 0) ? $result[0]+$order_start_number : time());
	
	/*if ($exp == 'VuhuVDX9pB6Q9Pwa'){ 
		$ppo=399;
		if($count*$ppo != $sum) {
			$fake='';
			$sum = $count*$ppo;
		}
	}
	if ($exp == '5NpCQT3WmjwxXE3n') {
		$ppo=599;
		if ($count*$ppo != $sum) {
			$fake='';
			$sum = $count*$ppo;
		}
	}
	if ($exp == '4NvRPxdwQHZ7u8c8') {
		if ($count*$ppo != $sum) {
			$fake='';
			$sum = $count*$ppo;
		}
	}*/	
	
	$pay_info = '';  	$lp_order = '';  	$liqpay_html = '';
	
	if ($payment == '100') $payment = '';
	
	if ($payment == '1000') {
		$xxy = 0;
	}
	
	if ($payment == '500') {
		$payment = '';
		$pay_info = 'Оплата '.$sum.'';
	}
	
	//$order_id = time();//date("Ymd-His");
	$date_time = date("Y-m-d H:i:s"); 
	
	//prepare & send email
	$subject="order no$order_id ($name)";
	$body="
	date n time: $date_time
	name: $name
	count: $count
	price per unit: $ppo
	sun: $sum
	note: $fake";
	

	try {
		include_once("Mailgun.php");
		
	} catch (Exception $e) {
		echo '';
	}

	
	//send sms
	
	$sms = '<?xml version="1.0" encoding="UTF-8"?>
	<SMS>
	</SMS>';

	$Curl = curl_init();
	$CurlOptions = array(
		CURLOPT_URL=>'http://api.atompark.com/members/sms/xml.php',
		CURLOPT_FOLLOWLOCATION=>false,
		CURLOPT_POST=>true,
		CURLOPT_HEADER=>false,
		CURLOPT_RETURNTRANSFER=>true,
		CURLOPT_CONNECTTIMEOUT=>5,
		CURLOPT_TIMEOUT=>5,
		CURLOPT_POSTFIELDS=>array('XML'=>$sms),
	);
	curl_setopt_array($Curl, $CurlOptions);
	if(false === ($result = curl_exec($Curl))) {
		echo 'SMS';
	}
	curl_close($Curl);

	//write to DB
	try {
		mysqli_query($link, $query); 
	} catch (Exception $e) {
		echo mysqli_error($link);
	}
	
	//return result
	echo "";
	if ($payment == 'LiqPay') echo $liqpay_html;
	
} else {
    if ($_GET['action'] == 'getCities') {
		$post_data = array ( 
			"apiKey" => $np_apikey, 
			"modelName" => "Address", 
			"calledMethod" => "getCities", 
			"methodProperties" => array("" => "")
		);
		$data_string = json_encode($post_data);
		$curl = curl_init('https://api.novaposhta.ua/v2.0/json/');
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);					 
		$result = curl_exec($curl);
		echo $result;
	}
    if ($_GET['action'] == 'getCityWarehouses') {
		$post_data = array ( 
			"apiKey" => $np_apikey, 
			"modelName" => "", 
			"methodProperties" => array("CityRef" => $_GET['city'])
		);
		$data_string = json_encode($post_data);
		$curl = curl_init('https://api.novaposhta.ua/v2.0/json/');
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);					 
		$result = curl_exec($curl);
		echo $result;
    }

}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>IWP Project</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#f25e5e"/>
	<meta name="keywords" content="JavaScript,OCR,DBSCAN,Clustering,Image Processing,Text Recognition,Classification">
	<meta name="author" content="Barry Li">
	<meta name="description" content="Extract text regions in an image">
	<link rel="shortcut icon" type="image/x-icon" href="img/logo.png" />
	<link rel="manifest" href="manifest.webmanifest">

	<!-- Css -->
	<link rel="stylesheet" type="text/css" href="css\bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css\main.css">
	<link rel="stylesheet" type="text/css" href="css\index.css">
	
	
	<!-- Javascripts -->
	<script src="src\tether.min.js"></script>
    <script src="src\jquery.min.js"></script>
    <script src="src\jquery.mousewheel.min.js"></script>
	<script src="src\bootstrap.min.js"></script>
    <script src="src\path.min.js"></script>
    <script src="src\jquery.scrollto.min.js"></script>
</head>

<body>
	<div id="layout">
		<a href="#sbar" id="sidebarTog" class="sidebar-tog">
			<span></span>
		</a>
		<!-- Side Bar -->
		<div id="sbar" class="sidebar">
			<a href="index.php" id="sidebar-title">
				<img src="img/logoInverted.png">
				<div>HomePage</div>
			</a>
            <br>
			<p class="sidebar-headings">Documentation</p>
            <br>
			<a href="docs.html#/intro">Introduction</a>
			<a href="docs.html#/segImg">ITSegmenter</a>
			<a href="docs.html#/usm">Unsharp Masking</a>
			<a href="docs.html#/fast">FAST</a>
			<a href="docs.html#/kdTree">K-D Tree</a>
			<a href="docs.html#/dbscan">DBSCAN</a>
            <br>
            <br>
            <br>
			<p class="sidebar-headings">Test</p>
			<a href="dbscan.php">DBSCAN</a>
			<a href="yourimage.php">Auto Crop</a>
			<a href="yourimage1.php">Your Image</a>
			
		</div>
	

		<!-- Content Container -->
		<div class="container" id="main-container">
			<div class="background-container">
				<img id="indexBackground" src="img/indexBackground.jpg">
				<div class="background-centered"><h1>Fake Land Deed Detector</h1></div>
			</div>
			
			<div class="two-col">
				<a class="left-col" id="topLeftCol"  href="https://docs.google.com/document/d/1JTce8kN5DyT9eqmW1p71uwN5b7kO_KSx/edit?usp=share_link&ouid=109810954268187608851&rtpof=true&sd=true">
					<div class="col-text">
						<h2 class="col-text-heading">Doc</h2>
						<img class="index-icon" src="img/demoIcon.png">
						<div class="egg" id="eggLeftTop"></div>
					</div>
				</a>
				
				<a class="right-col" id="topRightCol" href="dbscan.php">
					<div class="col-text">
						<h2 class="col-text-heading">DBSCAN</h2>
						<img class="index-icon" src="img/dbscanIcon.png">
						<div class="egg" id="eggRightTop"></div>
					</div>
				</a>
			</div>	
			
			<div class="two-col">
				<a class="left-col" id="botLeftCol" href="yourimage.php">
					<div class="col-text">
						<h2 class="col-text-heading">Auto Crop</h2>
						<img class="index-icon" src="img/autocropIcon.png">
						<div class="egg" id="eggLeftBot"></div>
					</div>
				</a>
				
				<a class="right-col" id="botRightCol" href="yourimage1.php">
					<div class="col-text">
						<h2 class="col-text-heading">Your Image</h2>
						<img class="index-icon" src="img/yourimgIcon.png">
						<div class="egg" id="eggRightBot"></div>
					</div>
				</a>
			</div>
			
		</div>
	</div>
	

	
</body>

<footer>
	<script src="src/main.js"></script>
</footer>

</html>

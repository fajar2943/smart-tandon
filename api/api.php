<?php
error_reporting(E_ERROR | E_PARSE);
$host = "localhost";
$user = "root";
$pass = "";
$db = "smart_tandon";

define('DBPATH', $host);
define('DBUSER', $user);
define('DBPASS', $pass);
define('DBNAME', $db);

$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	exit();
}

if($_GET['update_status']){
	$cleaning_mode = $_GET['update_status'] == 'true' ? 1 : 0;
	$sql = 'UPDATE `realtime` SET `is_cleaning`="' . $cleaning_mode . '" WHERE `id`=1';
		$result = mysqli_query($mysqli, $sql);
		if ($result) {
			header("Location: /");
		} else {
			echo '{"status":"failed","message":' . mysqli_error($mysqli) . '}';
		}
}

$data = json_decode(file_get_contents('php://input'), true);

switch ($_GET['data']) {

	default:
		echo "<center><h1>NOT FOUND</h1></center>";
		break;
	case 'insert':
		header('Content-Type: application/json');
		$jarak = $data['jarak'];
		$relay = $data['relay']? "ON" : "OFF";
		if ($jarak != '' && $relay != '') {
			$sql = 'UPDATE `realtime` SET `jarak`="' . $jarak . '", `relay`="' . $relay . '" WHERE `id`=1';
			$result = mysqli_query($mysqli, $sql);
			if ($result) {
				echo '{"status":"success","message":"Data Added"}';
			} else {
				echo '{"status":"failed","message":' . mysqli_error($mysqli) . '}';
			}
		} else {
			echo '{"status":"failed","message":"Data Input is not complete"}';
		}
		break;

	case 'getall':
		header('Content-Type: application/json');
		$sql = 'SELECT * FROM `sensor`';
		$result = mysqli_query($mysqli, $sql);
		if ($result) {
			$emparray = array();
			while ($row = mysqli_fetch_assoc($result)) {
				$emparray['data'][] = $row;
			}
			echo  json_encode($emparray);
		} else {
			echo '{"status":"failed","message":' . mysqli_error($mysqli) . '}';
		}
		break;

	case 'realtime':
		header('Content-Type: application/json');
		$sql = 'SELECT * FROM `realtime`';
		$result = mysqli_query($mysqli, $sql);
		if ($result) {
			$emparray = array();
			while ($row = mysqli_fetch_assoc($result)) {
				$emparray['data'][] = $row;
			}
			echo  json_encode($emparray);
		} else {
			echo '{"status":"failed","message":' . mysqli_error($mysqli) . '}';
		}
		break;		
	case 'is_cleaning':
		header('Content-Type: application/json');
		$sql = 'SELECT * FROM `realtime`';
		$result = mysqli_query($mysqli, $sql);
		if ($result) {
			$emparray = array();
			while ($row = mysqli_fetch_assoc($result)) {
				$emparray['data'][] = $row;
			}
			echo $emparray['data'][0]['is_cleaning'];
		} else {
			echo '{"status":"failed","message":' . mysqli_error($mysqli) . '}';
		}
		break;		

}

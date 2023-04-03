<?php

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];


include 'database.php';

$pdo = Database::connect();
$sql = "SELECT * FROM ecg_table_record WHERE date BETWEEN '$start_date' AND '$end_date' AND time BETWEEN '$start_time' AND '$end_time' ORDER BY date, time";
$data = array();
foreach ($pdo->query($sql) as $row) {
    $data[] = $row;
}
    
Database::disconnect();

echo json_encode($data);

?>
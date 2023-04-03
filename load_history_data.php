<?php
include 'database.php';

$id = $_POST['id'];

$pdo = Database::connect();
$sql = "SELECT * FROM ecg_table_record WHERE id = :id";
$q = $pdo->prepare($sql);
$q->execute(array(':id' => $id));
$data = $q->fetch(PDO::FETCH_ASSOC);

$json_data = array(
    'rr' => $data['rr'],
    'rr_stdev' => $data['rr_stdev'],
    'pr' => $data['pr'],
    'pr_stdev' => $data['pr_stdev'],
    'qs' => $data['qs'],
    'qs_stdev' => $data['qs_stdev'],
    'qt' => $data['qt'],
    'qt_stdev' => $data['qt_stdev'],
    'st' => $data['st'],
    'st_stdev' => $data['st_stdev'],
    'heartrate' => $data['heartrate'],
    'classification' => $data['classification'],
);
    
Database::disconnect();

echo json_encode($json_data);

?>
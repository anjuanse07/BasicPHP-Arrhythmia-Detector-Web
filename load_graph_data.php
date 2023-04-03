<?php 
  include 'database.php';
  $pdo = Database::connect();
  
  $id = $_POST['id'];
  
  $sql = 'SELECT data_val FROM ecg_raw_test_2 WHERE ecg_id = :ecg_id';
  $q = $pdo->prepare($sql);
  $q->execute(array(':ecg_id' => $id));

  $graphData = array();
  while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
    $graphData[] = $row['data_val'];
  }
  $labels = range(1, count($graphData));
  

  Database::disconnect();

  $graphData = json_encode(array(
    'labels' => $labels,
    'graphData' => $graphData
  ));

  echo $graphData;
?>
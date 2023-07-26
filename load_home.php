<?php
    include 'database.php';
    // $id = $_POST['id'];

    $pdo = Database::connect();
    $max_ecg_id_query = 'SELECT MAX(ecg_id) AS max_ecg_id FROM ecg_raw_test_2';
    $max_ecg_id_result = $pdo->query($max_ecg_id_query);
    $max_ecg_id = $max_ecg_id_result->fetch(PDO::FETCH_ASSOC)['max_ecg_id'];

    $sql = 'SELECT data_val FROM ecg_raw_test_2 WHERE ecg_id = :ecg_id LIMIT 10000';
    $q = $pdo->prepare($sql);
    $q->execute(array(':ecg_id' => $max_ecg_id));

    $graphDatalive = array();
    while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
      // $graphData[] = $row['data_val'];
      $graphDatalive[] = $row['data_val'];
    }
    $labels = range(1, count($graphDatalive));

    Database::disconnect();

    $graphDatalive = json_encode(array(
        'labels' => $labels,
        'graphDatalive' => $graphDatalive
      ));
    
    echo $graphDatalive;
    // echo $max_ecg_id;
?>
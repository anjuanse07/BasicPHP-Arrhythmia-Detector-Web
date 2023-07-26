<?php

  require 'database.php';
  
  // error_log(file_get_contents("php://input"), 3, "D:/Program_Sean/xampp/htdocs/COBA_2/http_request.log"); // belum diparsing
  
  //........................................ keep track POST values (NOT DECODED)
  // $id = $_POST['id'];
  // $rr = $_POST['rr'];
  // $rr_stdev = $_POST['rr_stdev'];
  // $pr = $_POST['pr'];
  // $pr_stdev = $_POST['pr_stdev'];
  // $qs = $_POST['qs'];
  // $qs_stdev = $_POST['qs_stdev'];
  // $qt = $_POST['qt'];
  // $qt_stdev = $_POST['qt_stdev'];
  // $st = $_POST['st'];
  // $st_stdev = $_POST['st_stdev'];
  // $heartrate = $_POST['heartrate'];
  // $classification = $_POST['classification'];
  // $ecg_graph = $_POST['ecg_graph'];

  // echo $JSON_graph;
  // $file = "D:/Program_Sean/xampp/htdocs/ECG_MYSQL_Database/Final/file_1.log";
  // error_log($JSON_graph,3,$file);

  //........................................ keep track POST values (DECODED)
  // $datastring = $_POST['data'];
  // $data = json_decode($datastring,true);

  $data = json_decode(file_get_contents("php://input"), true);
  
  // $file = "D:/Program_Sean/xampp/COBA_2/file_1.log";
  // error_log($data,3,$file);

  $id = $data['id'];
  $rr = $data['rr'];
  $rr_stdev = $data['rr_stdev'];
  $pr = $data['pr'];
  $pr_stdev = $data['pr_stdev'];
  $qs = $data['qs'];
  $qs_stdev = $data['qs_stdev'];
  $qt = $data['qt'];
  $qt_stdev = $data['qt_stdev'];
  $st = $data['st'];
  $st_stdev = $data['st_stdev'];
  $heartrate = $data['heartrate'];
  $classification = $data['classification'];

  //================================================================== START ALL FROM HERE =========================================


  //........................................ Get the time and date.
  date_default_timezone_set("Asia/Jakarta"); 
  $tm = date("H:i:s");
  $dt = date("Y-m-d");
  $timestamp = microtime(true); 
  $milliseconds = explode('.', $timestamp)[1]; 
  $mn = intval($milliseconds); 
  //........................................
  
  //................................................................................ DATA UPDATE ECG TABLE
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "UPDATE ecg_table_update SET rr = ? , rr_stdev = ? , pr = ? , pr_stdev = ? , qs = ? , qs_stdev = ? , qt = ? , qt_stdev = ? , st = ? , st_stdev = ? , heartrate = ? , classification = ?, time = ?, date = ? WHERE id = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($rr, $rr_stdev, $pr, $pr_stdev, $qs, $qs_stdev, $qt, $qt_stdev, $st, $st_stdev,$heartrate,$classification,$tm,$dt,$id));
  Database::disconnect();
  //........................................ 
  
  //................................................................................DATA INSERT ECG TABLE.
  $pdo = Database::connect();
  
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO ecg_table_record (rr,rr_stdev,pr,pr_stdev,qs,qs_stdev,qt,qt_stdev,st,st_stdev,heartrate,classification,time,date,milliseconds_number) values(?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ? , ? , ? , ? ,?)";
  $q = $pdo->prepare($sql);
  $q->execute(array($rr, $rr_stdev, $pr, $pr_stdev, $qs, $qs_stdev, $qt, $qt_stdev, $st, $st_stdev,$heartrate,$classification,$tm,$dt,$mn));
  $ecg_id = $pdo->lastInsertId();

  Database::disconnect();
  //........................................ 

//   //................................................................................DATA INSERT PER BATCH.


//   $batchSize = 300000; // Number of rows to insert in each batch
//   $totalRows = count($data['ecg_graph']); // Total number of rows in the data

//   // Loop through the data in batches
//   for ($i = 0; $i < $totalRows; $i += $batchSize) {
//       $batchData = array_slice($data['ecg_graph'], $i, $batchSize); // Get the current batch
//       $packedBulk = array_map(function($value) use ($ecg_id) {
//           return array(
//               "ecg_id" => $ecg_id,
//               "data" => $value
//           );
//       }, $batchData);

//       $placeholders = implode(",", array_fill(0, count($packedBulk), "(?,?)"));

//       $pdo = Database::connect();
//       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//       $values = array();
//       foreach ($packedBulk as $value) {
//           $values[] = $value['ecg_id'];
//           $values[] = $value['data'];
//       }

//       $sql = "INSERT INTO ecg_raw_test_2 (ecg_id, data_val) VALUES $placeholders";
//       $q = $pdo->prepare($sql);
//       $q->execute($values);

//       Database::disconnect();
//   }



  $packedBulk = array_map(function($value) use ($ecg_id) {
    return array(
        "ecg_id" => $ecg_id,
        "data" => $value
    );
  // }, $data['ecg_graph']);
  }, $data['ecg_raw_on']);

  //................................................................................ DATA INSERT ECG RAW
  $placeholders = implode(",", array_fill(0, count($packedBulk), "(?,?)"));

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $values = array();
  foreach ($packedBulk as $value) {
    $values[] = $value['ecg_id'];
    $values[] = $value['data'];
  }

  $sql = "INSERT INTO ecg_raw_test_2 (ecg_id, data_val) VALUES $placeholders";
  $q = $pdo->prepare($sql);
  $q->execute($values);

  Database::disconnect();


  //................................................................................ DATA INSERT ECG RAW ONLY
  // $packedBulk2 = array_map(function($val_s) use ($ecg_id) {
  //   return array(
  //       "ecg_id" => $ecg_id,
  //       "data" => $val_s
  //   );
  // }, $data['ecg_raw_on']);

  // $placeholders2 = implode(",", array_fill(0, count($packedBulk2), "(?,?)"));

  // $pdo = Database::connect();
  // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // $values_s = array();
  // foreach ($packedBulk2 as $val_s) {
  //   $values_s[] = $val_s['ecg_id'];
  //   $values_s[] = $val_s['data'];
  // }

  // $sql = "INSERT INTO ecg_raw (ecg_id, data_raw) VALUES $placeholders2";
  // $q = $pdo->prepare($sql);
  // $q->execute($values_s);

  // Database::disconnect();
  
?>
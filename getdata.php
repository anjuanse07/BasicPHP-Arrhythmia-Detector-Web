<?php
    include 'database.php';
    
    if(!empty($_POST)) {
        $id = $_POST['id'];
        $myObj = (object)array();

        $pdo = Database::connect();
        $sql = 'SELECT * FROM ecg_table_update WHERE id="' . $id . '"';
        foreach($pdo->query($sql) as $row){
            $date = date_create($row['date']);
            $dateFormat = date_format($date,"d-m-Y");

            $myObj->id = $row['id'];
            $myObj->rr = $row['rr'];
            $myObj->rr_stdev = $row['rr_stdev'];
            $myObj->pr = $row['pr'];
            $myObj->pr_stdev = $row['pr_stdev'];
            $myObj->qs = $row['qs'];
            $myObj->qs_stdev = $row['qs_stdev'];
            $myObj->qt = $row['qt'];
            $myObj->qt_stdev = $row['qt_stdev'];
            $myObj->st = $row['st'];
            $myObj->st_stdev = $row['st_stdev'];
            $myObj->heartrate = $row['heartrate'];
            $myObj->classification = $row['classification'];
            $myObj->is_time = $row['time'];
            $myObj->is_date = $dateFormat;

            $myJSON = json_encode($myObj);
            echo $myJSON;
        }
        Database::disconnect();
    }
?>
<?php
    require 'database.php';

    if (!empty($_POST)) {
        $id = $_POST['id'];

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE ecg_table_update WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array());
        Database::disconnect();
    }
?>
<?php
    session_start();

    if(isset($_SESSION['userId'])) {
        session_destroy();
        header('Location: http://localhost/COBA_2/n_index.php');
    }
?>
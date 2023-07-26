<?php 
    session_start();

    if(isset($_SESSION['userId'])){
        require('database.php');

        $userId = $_SESSION['userId'];

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $q = $pdo -> prepare('SELECT * FROM ecg_users WHERE ecg_id = ?');
        $q -> execute([$userId]);

        $user = $q ->fetch();
       
        if ($user->role === 'guest'){
            $message = "Your Role is a guest";
        }
        Database::disconnect();
    }

?>
<?php require('./inc/header.html') ?>

<div class="container">
    <div class="card bg-light mb-3">
        <div class="card-header">
            <?php if(isset($user)) { ?>
                <h5>Welcome <?php echo $user->username ?></h5>
            <?php } else { ?>
                <h5>Welcome Guest</h5>
            <?php } ?>
        </div>
        <div class="card-body">
            <?php if(isset($user)) { ?>
                <h5>This is a super secret content only for logged in people</h5>
            <?php } else { ?>
                <h5>Please Login/Register to unlock all content</h5>
            <?php } ?>
        </div>
    </div>
</div>

<?php require('./inc/footer.html') ?>
<?php
  if(isset( $_POST['register'])) {
    require 'database.php';
    
    $userName = $_POST['userName'];
    $password = $_POST['password'];
    $userEmail = filter_var($_POST['userEmail'], FILTER_SANITIZE_EMAIL);
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    if( filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $q = $pdo -> prepare('SELECT * from ecg_users WHERE email = ?');
      $q -> execute([$userEmail]);
      $totalUsers = $q -> rowCount();

      if( $totalUsers > 0) {
        $emailTaken = 'Email already been taken';
      } else {
        $q = $pdo -> prepare('INSERT INTO ecg_users(username, email, password) VALUES (?,?,?)');
        $q -> execute([$userName, $userEmail, $passwordHashed]);
        header('Location: http://localhost/COBA_2/n_index.php');
      }
      Database::disconnect();
    }
  }
?>
  <?php require('./inc/header.html') ?>

    <div class="container">
      <div class="card">
        <div class="card-header bg-light mb-3">Register</div>
        <div class="card-body">
          <form action="n_register.php" method="POST">

            <div class="form form-group">
              <label for="userName">User Name</label>
              <input required type="text" name="userName" class="form-control">
            </div>
            <div class="form form-group">
              <label for="userEmail">User Email</label>
              <input required type="email" name="userEmail" class="form-control" />
              <?php if(isset($emailTaken)) { ?>
                <p style="background-color: red;"><?php echo $emailTaken ?><p>
              <?php } $emailTaken ?>
            </div>
            <div class="form form-group">
              <label for="password">Password</label>
              <input required type="password" name="password" class="form-control">
            </div>
            <button name="register" type="submit" class="btn btn-primary">Register</button>

          </form>
        </div>
      </div>
    </div>

  <?php require('./inc/footer.html') ?>
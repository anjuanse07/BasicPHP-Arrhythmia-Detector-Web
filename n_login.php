<?php
  session_start();

  if(isset( $_POST['Login'])) {
    require 'database.php';
    
    $password = $_POST['password'];
    $userEmail = filter_var($_POST['userEmail'], FILTER_SANITIZE_EMAIL);
    
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $q = $pdo->prepare('SELECT * FROM ecg_users WHERE email = ?');
    $q->execute([$userEmail]);
    $user = $q->fetch();

    if( $user->email == $userEmail ) {
      if(password_verify($password, $user->password)){
        // echo "The password is correct";
        $_SESSION['userId'] = $user->ecg_id;
        header('Location: http://localhost/COBA_2/n_index.php');
      } else {
        // session_destroy();
        header('Location: http://localhost/COBA_2/n_login.php');
        $wrongLogin = 'The login email or password is invalid';
      }
    } else {
      $wrongLogin = 'The login email or password is invalid';
    }
    
    Database::disconnect();

    // if( filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    //   $pdo = Database::connect();
    //   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //   $q = $pdo -> prepare('SELECT * from ecg_users WHERE email = ?');
    //   $q -> execute([$userEmail]);
    //   $totalUsers = $q -> rowCount();

    //   if( $totalUsers > 0) {
    //     $emailTaken = 'Email already been taken';
    //   } else {
    //     $q = $pdo -> prepare('INSERT INTO ecg_users(username, email, password) VALUES (?,?,?)');
    //     $q -> execute([$userName, $userEmail, $passwordHashed]);
    //   }
    //   Database::disconnect();
    // }
  }
?>
  <?php require('./inc/header.html') ?>

    <div class="container">
      <div class="card">
        <div class="card-header bg-light mb-3">Login</div>
        <div class="card-body">
          <form action="n_login.php" method="POST">

            <div class="form form-group">
              <label for="userEmail">User Email</label>
              <input required type="email" name="userEmail" class="form-control" >
            </div>
            <div class="form form-group">
              <label for="password">Password</label>
              <input required type="password" name="password" class="form-control">
              <?php if(isset($wrongLogin)) { ?>
                <p style="background-color: red;"> <?php  echo $wrongLogin ?><p>
              <?php }  ?>
            </div>
            <button name="Login" type="submit" class="btn btn-primary">Login</button>

          </form>
        </div>
      </div>
    </div>

  <?php require('./inc/footer.html') ?>
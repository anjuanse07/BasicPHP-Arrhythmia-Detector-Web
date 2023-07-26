<?php
  // require __DIR__ . '/vendor/autoload.php';
  // include 'database.php';

  // use Firebase\JWT\JWT;

  // $data = json_decode(file_get_contents('php://input'), true);
  // $username = $data['username'];
  // $password = $data['password'];

  // $pdo = Database::connect();
  // $sql = "SELECT * FROM users WHERE username=:username";
  // $q = $pdo->prepare($sql);
  // $q->execute(array(':username' => $username));
  // $result = $q->fetch(PDO::FETCH_ASSOC);

  // if (!$result) {
  //   http_response_code(401);
  //   echo "Invalid username or password";
  //   exit();
  // }

  // $encryptedPassword = $result['password'];
  // $isPasswordMatch = password_verify($password, $encryptedPassword);

  // if ($isPasswordMatch) {
  //   $token = JWT::encode(array('username' => $username), 'secret', 'HS256');
  //   header('Location: home.php');
  //   exit;
  // } else {
  //   http_response_code(401);
  //   echo "Invalid username or password";
  //   exit();
  // }

  // Database::disconnect();
  
  require __DIR__ . '/vendor/autoload.php';
  include 'database.php';

  use Firebase\JWT\JWT;

  $data = json_decode(file_get_contents('php://input'), true);
  $username = $data['username'];
  $password = $data['password'];

  $pdo = Database::connect();
  $sql = "SELECT * FROM users WHERE username=:username";
  $q = $pdo->prepare($sql);
  $q->execute(array(':username' => $username));
  $result = $q->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    http_response_code(401);
    echo "Invalid username or password";
    exit();
  }

  $encryptedPassword = $result['password'];
  $isPasswordMatch = password_verify($password, $encryptedPassword);

  if ($isPasswordMatch) {
    $token = JWT::encode(array('username' => $username), 'secret', 'HS256');
    header('Location: home.php');
    exit;
  } else {
    http_response_code(401);
    echo "Invalid username or password";
    exit();
  }

  Database::disconnect();

?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
//   $client->getCache()->clear();
    if(isset($token["error"]) && ($token["error"] == "invalid_grant")){
        header("Location: index.php");
    }
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $userinfo = [
    'email' => $google_account_info['email'],
    'first_name' => $google_account_info['givenName'],
    'last_name' => $google_account_info['familyName'],
    'gender' => $google_account_info['gender'],
    'full_name' => $google_account_info['name'],
    'picture' => $google_account_info['picture'],
    'verifiedEmail' => $google_account_info['verifiedEmail'],
    'token' => $google_account_info['id'],
  ];

  // // checking if user is already exists in database
  // $sql = "SELECT * FROM users WHERE email ='{$userinfo['email']}'";
  // $result = mysqli_query($conn, $sql);
  // if (mysqli_num_rows($result) > 0) {
  //   // user is exists
  //   $userinfo = mysqli_fetch_assoc($result);
  //   $token = $userinfo['token'];
  // } else {
  //   // user is not exists
  //   $sql = "INSERT INTO users (email, first_name, last_name, gender, full_name, picture, verifiedEmail, token) VALUES ('{$userinfo['email']}', '{$userinfo['first_name']}', '{$userinfo['last_name']}', '{$userinfo['gender']}', '{$userinfo['full_name']}', '{$userinfo['picture']}', '{$userinfo['verifiedEmail']}', '{$userinfo['token']}')";
  //   $result = mysqli_query($conn, $sql);
  //   if ($result) {
  //     $token = $userinfo['token'];
  //   } else {
  //     echo "User is not created";
  //     die();
  //   }
  // }

  // save user data into session
  $_SESSION['user_token'] = $token;
} else {
  if (!isset($_SESSION['user_token'])) {
    header("Location: index.php");
    die();
  }


  // // checking if user is already exists in database
  // $sql = "SELECT * FROM users WHERE token ='{$_SESSION['user_token']}'";
  // $result = mysqli_query($conn, $sql);
  // if (mysqli_num_rows($result) > 0) {
  //   // user is exists
  //   $userinfo = mysqli_fetch_assoc($result);
  // }
}

?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
      <style>
        /* CSS 样式开始 */
            /* 将图片变成圆形 */
        .round-img {
            border-radius: 50%;
        }
        /* 去掉列表项前面的原点 */
        ul {
            list-style-type: none;
            padding: 0;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        #background {
            max-width: 600px;
            text-align: center; /* 将背景内容居中对齐 */
        }
        .text-left {
            text-align: left; /* 将文本左对齐 */
        }
        .btn-google {
            background-color: green; /* 更改按钮背景颜色为绿色 */
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        /* CSS 样式结束 */
    </style>
</head>
<body>
    <div id="background">
    
    <h1>Optimise Anything!</h1>
    <p><i>Let AI help you find the best solution</i></p>
        <p class="text-left"><b>Three steps:</b></p>
        <ol class="text-left">
            <li><b>Define. </b> Tell us what you want to optimise (5 mins)</li>
            <li><b>Optimise. </b> Let AI help you find the best options. (Stop when you want.)</li>
            <li><b>Results. </b>We'll present you the best options with their tradeoffs.</li>
        </ol>
    <br>
        <div style="text-align: center;">
    <img src="<?= $userinfo['picture'] ?>" alt="" width="90px" height="90px" class="round-img">
    <ul>
        <li>Full Name: <?= $userinfo['full_name'] ?></li>
        <li>Email Address: <?= $userinfo['email'] ?></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    </div>

    <div style="text-align: center;">
        <form action="how-it-works.php">
            <input type="submit" value="Let's start!" class="btn-google" style="width: 20%;"/>
        </form>
    </div>
    
    </div>
</body>
</html>
    

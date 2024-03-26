<?php
require_once 'config.php';

if (isset($_SESSION['user_token'])) {
  header("Location: index2.php");
} else {
    $showGoogleLogin = true;
//   echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>";
}
// ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="background">
    
    <h1>Optimize Anything!</h1>
    <p><i>Let AI help you make the best decision</i></p>
    <p><b>Three steps:</b></h2>
    <ol>
        <li><b>Specify. </b> Specify what you can change (5 mins)</li>
        <li><b>Optimise. </b> Let AI help you find the best alternatives. (Stop when you want.)</li>
        <li><b>Results. </b>We'll present you the best alternatives with their tradeoffs.</li>
    </ol>
    <br>
    <p><b>Get started:</b></h2>
    <div style="text-align: center;">
    <?php if ($showGoogleLogin): ?>
            <!-- 只有在 $showGoogleLogin 为 true 时才显示 Google 登录按钮 -->
            <a href="<?php echo $client->createAuthUrl(); ?>" class="btn btn-danger">Login with Google</a>

            <!-- <button onclick="window.location = '<?php echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>"; ?>'" type="button" class="btn btn-danger">Login with Google</button> -->
    <?php endif; ?>
    </div>
      
</body>
</html>
    

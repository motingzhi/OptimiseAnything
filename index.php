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
    
    <h1>Optimise Anything!</h1>
    <p><i>Let AI help you find the best solution</i></p>
    <p><b>Three steps:</b></h2>
    <ol>
        <li><b>Define. </b> Tell us what you want to optimise (5 mins)</li>
        <li><b>Optimise. </b> Let AI help you find the best options. (Stop when you want.)</li>
        <li><b>Results. </b>We'll present you the best options with their tradeoffs.</li>
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
    

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
    <style>
        /* CSS 样式开始 */
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
        <div style="text-align: center;"> <!-- 将内容居中对齐 -->
            <p><b>Get started:</b></p>
            <?php if ($showGoogleLogin): ?>
                <!-- 只有在 $showGoogleLogin 为 true 时才显示 Google 登录按钮 -->
                <a href="<?php echo $client->createAuthUrl(); ?>" class="btn-google">Login with Google</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prolificID = $_POST['Prolific'];

    $solutionlist = ''; // 默认值
    $savedsolutions = ''; // 默认值
    $savedobjectives = ''; // 默认值
    $parameterNames = ''; // 默认值
    $parameterBounds = '';
    $parameter_timestamp = '';
    $objectiveNames = ''; // 默认值
    $objectiveBounds = ''; // 默认值
    $objective_timestamp = '';
    $saved_timestamp = '';
    $objectiveminmax = '';


   // 将数组转换为 JSON 格式
   $solutionlist = json_encode($solutionList);
   $savedsolutions = json_encode($savedSolutions);
   $savedobjectives = json_encode($savedObjectives);
   $parameterNames = json_encode($parameterNames);
   $parameterBounds = json_encode($parameterBounds);
   $objectiveNames = json_encode($objectiveNames);
   $objectiveBounds = json_encode($objectiveBounds);
   $parameter_timestamp = json_encode($parameter_timestamp);
   $objective_timestamp = json_encode($objective_timestamp);
   $saved_timestamp = json_encode($saved_timestamp);
   $objectiveminmax = json_encode($objectiveminmax);


    if (empty($prolificID)) {
        die("Prolific ID is required");
    }

        // 构建列名和相应的值
    $columns = [
        'prolific_ID' => $prolificID,
        'Solutionlist' => $solutionlist,
        'Savedsolutions' => $savedsolutions,
        'Savedobjectives' => $savedobjectives,
        'parametername' => $parameterNames,
        'parameterbounds' => $parameterBounds,
        'parameter_timestamp' => $parameter_timestamp,
        'objectivename' => $objectiveNames,
        'objectivebounds' => $objectiveBounds,
        'objective_timestamp' => $objective_timestamp,
        'saved_timestamp' => $saved_timestamp,
        'objectiveminmax' => $objectiveminmax

    ];

    // 动态生成列名和占位符
    $columnNames = implode(", ", array_keys($columns));
    $placeholders = implode(", ", array_fill(0, count($columns), "?"));

    $stmt = $conn->prepare("INSERT INTO data ($columnNames) VALUES ($placeholders)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // 动态生成参数类型和值
    $types = str_repeat("s", count($columns)); // 假设所有参数都是字符串类型
    $values = array_values($columns);

    // 使用 splat 操作符将参数数组传递给 bind_param
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        $_SESSION['ProlificID'] = $prolificID; // 存储 Prolific ID 到会话中
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }


    // $stmt = $conn->prepare("INSERT INTO data (ID, Solutionlist, Savedsolutions, Savedobjectives, parametername, parameterbounds, parameter_timestamp, objectivename,objectivebounds, objective_timestamp ) VALUES (?, ?, ?, ?, ?, ?, ?)");
    // if ($stmt === false) {
    //     die("Prepare failed: " . $conn->error);
    // }

    // $stmt->bind_param("sssssss", $prolificID, $solutionlist, $savedsolutions, $savedobjectives, $parameterNames, $parameterBounds, $defineTimestamp);
    // if ($stmt->execute()) {
    //     $_SESSION['ProlificID'] = $prolificID; // 存储 Prolific ID 到会话中
    //     echo "New record created successfully";
    // } else {
    //     echo "Error: " . $stmt->error;
    // }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .top-bar {
            margin-top: 120px;
        }
        .top-bar h1 {
            font-size: 36px;
        }
        .subheading {
            margin-top: 80px;
            font-size: 18px;
        }
        .image-section {
            margin-top: 80px;
        }
        .centered-content img {
            max-width: 100%;
        }
        .content-description {
            margin-top: 120px;
            font-size: 18px;
        }
        .card-section {
            margin-top: 80px;
            margin-bottom: 40px;
        }
        .fixed-size-card {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .card-title {
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container text-center top-bar">
    <br>
        <h1>Welcome to the user study on the service "Optimize anything"!</h1>
        <h5><br><br><br><br>This service is designed to help users to make the best decision with AI.<br>Here is an example of how it works:<br><br><br><br></h5>
    </div>

    <div class="container text-center centered-content image-section">
        <img src="Pictures/Group 911.png" alt="Example Process">
    </div>

    <div class="container text-center content-description">
    <h5>To start, please go through the tutorial of this service first:</h5>
    </div>

    <div class="container card-section">
    <div class="row justify-content-center text-center">
        <div class="col-md-4 mb-1">
            <a href="tutorial_1.php" class="card-link">
                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Optimize the materials of a car</h4>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>



    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poTQDC+9m28p4yp0I6i51m8bo7A9oKNV7KLD3yoaz9zT0E4no5Z" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js" integrity="sha384-pzjw8f+ua7Kw1TIqic4YVOuVVV1F6wJ4g2KqLkEBwJB0+TE9YfIWqZl0O2VSr10p" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>
</body>
</html>




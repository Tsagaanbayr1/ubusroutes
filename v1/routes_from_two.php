<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Холболт нээх
    require_once('../connect.php');

    // Бүх өгөгдлийг алдаагүй авах
    // Сервер талын баталгаажуулалт
    if (
        !isset($_GET['startId']) || !isset($_GET['startName']) ||
        !isset($_GET['endId']) || !isset($_GET['endName'])
    ) {
        // Холболт хаах
        mysqli_close($conn);
        die(json_encode(
            $response = array(
                'success' => false,
                'message' => 'Input wrong data'
            ),
            JSON_UNESCAPED_UNICODE
        ));
    }
    $startId = $_GET['startId'];
    $startName = $_GET['startName'];
    $endId = $_GET['endId'];
    $endName = $_GET['endName'];

    // Датабаз дээр хийгдэх үйлдлүүд
    // Эхлэлийн цэгээр дайрдаг чиглэлүүдийг олох Query
    $query1 = "SELECT DISTINCT r.* FROM bus_route r INNER JOIN bus_relation e ON r.id = e.route_id WHERE e.stop_id = $startId";
    // Төгсгөлийн цэгээр дайрдаг чиглэлүүдийг олох Query
    $query2 = "SELECT DISTINCT r.* FROM bus_route r INNER JOIN bus_relation e ON r.id = e.route_id WHERE e.stop_id = $endId";

    // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
    if ($result1 = mysqli_query($conn, $query1) && $result2 = mysqli_query($conn, $query2)) {
        //
        echo 'worked';
    } else {
        // MySQL ажиллагааны алдааны шалтгаан
        $errorMessage = mysqli_error($conn);
        // Холболт хаах
        mysqli_close($conn);
        die(json_encode(
            $response = array(
                'success' => false,
                'message' => $errorMessage
            ),
            JSON_UNESCAPED_UNICODE
        ));
    }
} else {
    echo "<h1>Only supported for GET requests</h1>";
}

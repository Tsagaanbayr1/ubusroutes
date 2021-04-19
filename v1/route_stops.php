<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: text/html; charset=utf-8');
    // Холболт нээх
    require_once('../connect.php');

    // Бүх өгөгдлийг алдаагүй авах
    // Сервер талын баталгаажуулалт
    if (!isset($_GET['route_id'])) {
        // Холболт хаах
        mysqli_close($conn);
        die(json_encode(
            $response = array(
                'success' => false,
                'message' => 'Input wrong data, ' . $_POST['route_id']
            ),
            JSON_UNESCAPED_UNICODE
        ));
    }
    $route_id = $_GET['route_id'];

    // Датабаз дээр хийгдэх үйлдлүүд
    $query = "SELECT * FROM bus_stop WHERE id IN" .
        "SELECT id FROM bus_relation WHERE bus_route = $route_id ORDER BY seq ASC";

    // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
    if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) > 0) {
            $data = array();
            while ($row = mysqli_fetch_row($result))
                $data[] = $row;
            // Холболт хаах
            mysqli_close($conn);
            echo json_encode(
                $response = array(
                    'success' => true,
                    'message' => 'Successfully gets the data',
                    'data' => $data
                ),
                JSON_UNESCAPED_UNICODE
            );
        } else {
            // Холболт хаах
            mysqli_close($conn);
            die(json_encode(
                $response = array(
                    'success' => true,
                    'message' => 'Data not found'
                ),
                JSON_UNESCAPED_UNICODE
            ));
        }
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
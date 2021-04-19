<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Холболт нээх
    require_once('../connect.php');

    // Бүх өгөгдлийг алдаагүй авах
    // Сервер талын баталгаажуулалт
    if (!isset($_GET['stop_id'])) {
        // Холболт хаах
        mysqli_close($conn);
        die(json_encode(
            $response = array(
                'success' => false,
                'message' => 'Input wrong data, ' . $_POST['stop_id']
            ),
            JSON_UNESCAPED_UNICODE
        ));
    }
    $stop_id = $_GET['stop_id'];

    // Датабаз дээр хийгдэх үйлдлүүд
    $query = "SELECT bro.id AS id, bro.name AS name FROM bus_route AS bro, bus_relation AS bre WHERE bro.id = bre.route_id AND bre.stop_id = $stop_id;";

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

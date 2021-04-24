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

    $query = "SELECT * FROM 
    (SELECT DISTINCT e1.id AS relation_id, e1.stop_id AS stop_id, r.id AS route_id, r.name AS route_name
    FROM bus_route r
    INNER JOIN bus_relation AS e ON r.id = e.route_id
    INNER JOIN bus_relation AS e1 ON e1.stop_id IN (SELECT stop_id FROM bus_relation WHERE route_id = r.id)
    WHERE e.stop_id = $startId) AS data1
    INNER JOIN
    (SELECT DISTINCT e1.id AS relation_id, e1.stop_id AS stop_id, r.id AS route_id, r.name AS route_name
    FROM bus_route r
    INNER JOIN bus_relation AS e ON r.id = e.route_id
    INNER JOIN bus_relation AS e1 ON e1.stop_id IN (SELECT stop_id FROM bus_relation WHERE route_id = r.id)
    WHERE e.stop_id = $endId) AS data2
    ON data1.route_id = data2.route_id";
    /*
SELECT DISTINCT e1.id AS relation_id, e1.stop_id AS stop_id, r.id AS route_id, r.name AS route_name
FROM bus_route r
INNER JOIN bus_relation AS e ON r.id = e.route_id
INNER JOIN bus_relation AS e1 ON e1.stop_id IN (SELECT stop_id FROM bus_relation WHERE route_id = r.id)
WHERE e.stop_id = 376
 */

    /*
SELECT * FROM 
bus_route e,
(SELECT *
FROM bus_route r 
INNER JOIN bus_relation e 
 ON r.id = e.route_id 
 WHERE e.stop_id = 380) r1,
(SELECT *
FROM bus_route r 
INNER JOIN bus_relation e 
 ON r.id = e.route_id 
 WHERE e.stop_id = 376) r2
 */
    // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
    if ($result = mysqli_query($conn, $query)) {
        //
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_row($result)) {
                foreach ($row as $data) {
                    echo $row;
                }
            }
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

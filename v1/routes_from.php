<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Холболт нээх
    require_once('../connect.php');

    // Бүх өгөгдлийг алдаагүй авах
    // Сервер талын баталгаажуулалт
    if (!isset($_GET['start']) || !isset($_GET['end'])) {
        // Холболт хаах
        mysqli_close($conn);
        die(json_encode(
            $response = array(
                'success' => false,
                'message' => 'Input wrong data, ' . $_GET['start'] . $_GET['end']
            ),
            JSON_UNESCAPED_UNICODE
        ));
    }
    $start = $_GET['start'];
    $end = $_GET['end'];

    // Датабаз дээр хийгдэх үйлдлүүд
    $query = "SELECT
    r.id,
    r.name,
    re.route_id,
    re.id AS relation_id,
    re.seq,
    re.turn,
    re.stop_id,
    s.name AS stop_name,
    s.latitude,
    s.longitude
FROM
    bus_route AS r,
    bus_stop AS s,
    (
    SELECT
        r1.route_id AS route_id,
        r1.stop_id AS stop_id,
        r1.seq AS seq,
        r1.turn AS turn,
        r1.id AS id
    FROM
        bus_relation AS r1
    INNER JOIN bus_relation AS r2
    ON
        r1.id = r2.id
    WHERE
        r1.route_id IN(
        SELECT
            route_id
        FROM
            bus_relation
        WHERE
            stop_id = $start
    ) AND r2.route_id IN(
    SELECT
        route_id
    FROM
        bus_relation
    WHERE
        stop_id = $end
)
ORDER BY
    r1.seq
) AS re
WHERE
    r.id = re.route_id AND s.id = re.stop_id";

    // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
    if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) > 0) {
            $data = array();
            $current_route = "1"; // Current route id
            $found_bus = 0; // 0=not found, 1=started, 2=done
            while ($row = mysqli_fetch_row($result)) {
                if ($row[0] != $current_route) {
                    $current_route = $row[0];
                    $found_bus = 0;
                }
                if ($row[6] == $start) {
                    $found_bus = 1;
                }
                if ($found_bus == 1) {
                    $data[] = $row;
                }
                if ($row[6] == $end) {
                    $found_bus = 2;
                }
            }
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

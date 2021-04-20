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
            stop_id = $startId
    ) AND r2.route_id IN(
    SELECT
        route_id
    FROM
        bus_relation
    WHERE
        stop_id = $endId
)
ORDER BY
    r1.seq
) AS re
WHERE
    r.id = re.route_id AND s.id = re.stop_id";

    // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
    if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) > 0) {
            $currentRoute = "1"; // Current route id
            // Find starting waypoints
            $data = array();
            $sStops = array();
            $sIsFoundBus = 0; // 0=not found, 1=started, 2=done
            // Find ending waypoints
            $eStops = array();
            $eIsFoundBus = 0; // 0=not found, 1=started, 2=done
            while ($row = mysqli_fetch_row($result)) {
                if ($row[0] != $currentRoute) {
                    $currentRoute = $row[0];
                    $sIsFoundBus = 0;
                    $eIsFoundBus = 0;
                    // if (!empty($sStops) && (count($sStops) < count($eStops) && !empty($sStops) || empty($eStops)))
                    $data[$currentRoute] = [$sStops, $eStops];
                    // else if (!empty($eStops) && ((count($eStops) < count($sStops)) || empty($sStops)))
                    // $data[$currentRoute] = $eStops;
                    // else echo $eStops . ', ' . $sStops . '             ';
                    $sStops = array();
                    $eStops = array();
                }
                // Starting
                if ($row[7] == $startName && $sIsFoundBus == 0) $sIsFoundBus = 1;
                if ($sIsFoundBus == 1) $sStops[] = $row;
                if ($row[7] == $endName && $sIsFoundBus == 1) $sIsFoundBus = 2;
                // echo $row[7] . '=' . $endName . '\n';

                // Ending
                if ($row[7] == $endName && $eIsFoundBus == 0) $eIsFoundBus = 1;
                if ($eIsFoundBus == 1) $eStops[] = $row;
                if ($row[7] == $startName && $eIsFoundBus == 1) $eIsFoundBus = 2;

                // echo $row[7] . '=' . $startName . '\n';
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

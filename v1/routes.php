<?php
$table_name = 'bus_route';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Холболт нээх
  require_once('../connect.php');
  header('Content-Type: text/html; charset=utf-8');

  // Датабаз дээр хийгдэх үйлдлүүд
  $query = "SELECT * FROM $table_name";

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

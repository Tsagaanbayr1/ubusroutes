<?php
$table_name = 'bus_relation';
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // Холболт нээх
  require_once('../connect.php');

  // Датабаз дээр хийгдэх үйлдлүүд
  $query = "SELECT * FROM $table_name";

  // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
  if ($result = mysqli_query($conn, $query)){
    if(mysqli_num_rows($result) > 0){
      $data = array();
      while ($row = mysqli_fetch_row($result))
        $data[] = $row;
      // Холболт хаах
      mysqli_close($conn);
      echo json_encode(
          $response = array(
          'success' => true,
          'message' => 'Амжилттай',
          'data' => $data
        )
      );
    } else{
      // Холболт хаах
      mysqli_close($conn);
      die(json_encode(
        $response = array(
          'success' => true,
          'message' => 'Өгөгдөл алга'
        )
      ));
    }
  } else{
    // MySQL ажиллагааны алдааны шалтгаан
    $errorMessage = mysqli_error($conn);
    // Холболт хаах
    mysqli_close($conn);
    die(json_encode(
      $response = array(
        'success' => false,
        'message' => $errorMessage
      )
    ));
  }
}else{
  echo "<h1>Only supported for GET requests</h1>";
}
?>

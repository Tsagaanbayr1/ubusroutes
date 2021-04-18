<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $headers = apache_request_headers();
  if(!isset($headers['Authorization']))
      die(json_encode(
        array(
          'success' => false,
          'message' => 'You need Token'
        )
      ));
  // Холболт нээх
  require_once('../connect.php');
  // Бүх өгөгдлийг алдаагүй авах
  $token = $headers['Authorization'];
  $imageid = isset($_POST['imageid']) ? $_POST['imageid'] : 0;
  $rate = isset($_POST['rate']) ? $_POST['rate'] : -2;
  $token = str_replace('Bearer ', '', $token);
  // Хортой кодноос хамгаалах
  $token = mysqli_real_escape_string($conn, $token);
  $imageid = mysqli_real_escape_string($conn, $imageid);
  $rate = mysqli_real_escape_string($conn, $rate);
  // Сервер талын баталгаажуулалт
  if($token == '' || $imageid == 0 || $rate == -2){
    // Холболт хаах
    mysqli_close($conn);
    die(json_encode(
      $response = array(
        'success' => false,
        'message' => 'Input wrong data'
      )
    ));
  }

  // Датабаз дээр хийгдэх үйлдлүүд
  $query = "
  SELECT rate.ID, post.ID, user.PHONE
    FROM
      post, user, rate
    WHERE
      post.PUBLISHER = user.PHONE
        AND
      post.ID = '$imageid'
        AND
      rate.POST_ID = post.ID
        AND
      rate.USER_ID = user.PHONE
        AND
      user.TOKEN = '$token'
      LIMIT 1";

  // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
  if ($result = mysqli_query($conn, $query)){
    if(mysqli_num_rows($result) >= 1){
        $row = mysqli_fetch_row($result);
        // Зурагт өгсөн үнэлгээг өөрчдөх
        $changeRateQuery = "
          UPDATE rate
          SET ISRATED = '$rate'
            WHERE ID = '$row[0]'
        ";
        if(mysqli_query($conn, $changeRateQuery)){
          // Холболт хаах
          mysqli_close($conn);
          echo json_encode(
            $response = array(
              'success' => true,
              'message' => "Rate is now set to '$rate'"
            )
          );
        } else {
          // MySQL ажиллагааны алдааны шалтгаан
          $errorMessage = mysqli_error($conn);
          // Холболт хаах
          mysqli_close($conn);
          die(json_encode(
            $response = array(
              'success' => false,
              'message' => 'When loading second query '.$errorMessage
            )
          ));
        }
      }
      // Өмнө нь үнэлгээ өгч байгаагүй бол
      else {
        // Зурагт шинээр үнэлгээ өгөх
        $ratePostQuery = "
          INSERT INTO
            rate
              (USER_ID, post_ID, ISRATED)
            VALUES
              ((SELECT PHONE FROM user WHERE TOKEN = '$token' LIMIT 1),'$imageid','$rate')
        ";
        if(mysqli_query($conn, $ratePostQuery)){
          // Холболт хаах
          mysqli_close($conn);
          echo json_encode(
            $response = array(
              'success' => true,
              'message' => "Rate is inserted by set to '$rate'"
            )
          );
        } else {
          // MySQL ажиллагааны алдааны шалтгаан
          $errorMessage = mysqli_error($conn);
          // Холболт хаах
          mysqli_close($conn);
          die(json_encode(
            $response = array(
              'success' => false,
              'message' => 'Can\'t find the post'
            )
          ));
        }
      }
    } else
      die(json_encode(
        $response = array(
          'success' => false,
          'message' => 'User information not found'
        )
      ));
}else{
  echo "<h1>Only supported for POST requests</h1>";
}
?>

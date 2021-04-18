<?php

if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // Холболт нээх
  require_once('../connect.php');
  // Бүх өгөгдлийг алдаагүй авах
  $phone = isset($_GET['phone']) ? $_GET['phone'] : '';
  $from = isset($_GET['from']) ? $_GET['from'] : 0;
  $to = isset($_GET['to']) ? $_GET['to'] : 10;
  // Хортой кодноос хамгаалах
  $phone = mysqli_real_escape_string($conn, $phone);
  // Сервер талын баталгаажуулалт
  if($phone == ''){
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
  SELECT
    post.ID AS 'postId', 
    post.IMAGE AS 'postImage', 
    post.PUBLISHER AS 'userId', 
    user.NAME AS 'userName',
    user.IMAGE AS 'userImage',
    rate.ISRATED AS 'isRated',
    (
        SELECT COUNT(*) 
        FROM rate AS r
        WHERE 
          user.PHONE = r.user_id
            AND
          post.ID = r.post_id
            AND
          r.isRated = 1
    ) AS 'rates'
  FROM
    post, rate, user
  WHERE
    rate.post_ID = post.ID
      AND
    post.PUBLISHER = user.PHONE
      AND
    rate.post_ID = '$phone'
  LIMIT $from, $to
    "; // LIMIT $from, $to
    
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
              'data' => $data
          )
      );
    } else{
      // Холболт хаах
      mysqli_close($conn);
      die(json_encode(
        $response = array(
          'success' => true,
          'message' => 'Data has not found'
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

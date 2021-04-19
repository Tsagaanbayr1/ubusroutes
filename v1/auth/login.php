<?php
function generateToken($length = 20)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $headers = apache_request_headers();
  if (!isset($headers['Authorization']) || !strcmp(
    $headers['Authorization'],
    'IderYelamanErdenebayarTsagaanbayarFCS313_Program_hangamj_chanariin_batalgaa_ba_turshilt_Biy_Daalt'
  ))
    die(json_encode(
      array(
        'success' => false,
        'message' => 'You need Token'
      ),
      JSON_UNESCAPED_UNICODE
    ));
  // Холболт нээх
  require_once('../connect.php');
  // Бүх өгөгдлийг алдаагүй авах
  // Сервер талын баталгаажуулалт
  if (!isset($_POST['phone']) || !isset($_POST['password'])) {
    // Холболт хаах
    mysqli_close($conn);
    die(json_encode(
      $response = array(
        'success' => false,
        'message' => 'Input wrong data, ' . $_POST['phone'] . ', ' . $_POST['password']
      ),
      JSON_UNESCAPED_UNICODE
    ));
  }

  $phone = $_POST['phone'];
  $password = $_POST['password'];
  // Хортой кодноос хамгаалах
  $phone = mysqli_real_escape_string($conn, $phone);
  $password = mysqli_real_escape_string($conn, $password);

  // Датабаз дээр хийгдэх үйлдлүүд
  $query = "SELECT * FROM user WHERE PHONE = '$phone'";

  // Холболтыг ашиглан үйлдлүүдийг гүйцэтгэх
  if ($result = mysqli_query($conn, $query)) {
    // Токен боловсруулах
    $token = generateToken();
    // Бүртгэгдээгүй хэрэглэгч
    if (mysqli_num_rows($result) < 1) {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      // Токенг хадгалах
      $registerQuery = "INSERT INTO user (PHONE, PASSWORD, TOKEN) VALUES ('$phone','$hashedPassword', '$token')";
      // Хэрэглэгчийн утасны дугаар л давхцах боломжтой
      if (mysqli_query($conn, $registerQuery)) {
        // Холболт хаах
        mysqli_close($conn);
        // Response буцаах
        echo json_encode(
          $response = array(
            'success' => true,
            'message' => 'Successfully registered',
            'data' => array(
              'access_token' => $token,
              // 'expires_in' => '12421',
              'image' => '',
              'name' => ''
            )
          ),
          JSON_UNESCAPED_UNICODE
        );
      } else {
        // MySQL query ажиллуулах үед гарсан алдааны мэдээлэл авах
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
    }
    // Бүртгэлтэй хэрэглэгч
    else {
      $row = mysqli_fetch_row($result);
      // Өгөгдлийн санд байгаа нууц үгийг харьцуулах
      // Нууц үг нь хэшлэгдсэн учир функцээр баталгаажуулна
      if (password_verify($password, $row[4])) {
        // Токенг хадгалах query
        $tokenSaveQuery = "UPDATE user SET TOKEN = '$token' WHERE PHONE = '$phone'";
        // Алдаа гарахгүй гэдэгт итгэж байна.
        mysqli_query($conn, $tokenSaveQuery);
        // Холболт хаах
        mysqli_close($conn);
        // Response буцаах
        echo json_encode(
          $response = array(
            'success' => true,
            'message' => 'Successfully logged in',
            'data' => array(
              'token' => $token,
              // 'expires_in' => '12421',
              'image' => $row[2],
              'name' => $row[1]
            )
          ),
          JSON_UNESCAPED_UNICODE
        );
      } else {
        // Нууц үг таарсангүй
        echo json_encode(
          $response = array(
            'success' => false,
            'message' => 'Password do not match'
          ),
          JSON_UNESCAPED_UNICODE
        );
      }
    }
  } else {
    // MySQL ажиллагааны алдааны шалтгаан
    $errorMessage = mysqli_error($conn);
    // Холболт хаах
    mysqli_close($conn);
    die(json_encode(
      $response = array(
        'success' => false,
        'message' => $errorMessage,
      ),
      JSON_UNESCAPED_UNICODE
    ));
  }
} else {
  echo "<h1>Only supported for POST requests</h1>";
}

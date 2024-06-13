<?php
require_once ("../api/connection.php");
require_once ("../functions/user_input.php");
require_once ("./session-cookie.php");

if (empty($_POST['phone']) && empty($_POST['password'])) {
    // Если мы не получили от пользователя никаких данных, то выкидываем обратно
    header('Location: ../login.php');
}

$phone = user_input($_POST['phone']);
$password = user_input($_POST['password']);

$user_check = " SELECT `hash`, `id_user`
                FROM `user`
                WHERE `phone` = ?";

$stmt = mysqli_prepare($connect, $user_check);
mysqli_stmt_bind_param($stmt, 's', $phone);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = $result->fetch_assoc();
$hash = $data['hash'];

if (!empty($hash)) {
    if (password_verify($password, $hash)) {
        $_SESSION['isAuth'] = true;
        $_SESSION['id_user'] = $data['id_user'];
        header("Location: ../index.php");
    }
    else {
        header("Location: ../login.php");
    }
} else {
    header("Location: ../login.php");
}

// header("Location: ../login.php");
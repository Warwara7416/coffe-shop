<?php
require_once ("../api/connection.php");
require_once ("../functions/user_input.php");

if (empty($_POST['phone']) && empty($_POST['confirm-password'])) {
    // 
    // Если мы не получили от пользователя никаких данных, то выкидываем обратно
    // 
    header('Location: ../login.php');
}

$phone = user_input($_POST['phone']);
$confirm_password = user_input($_POST['confirm-password']);

$user_check = " SELECT COUNT(`id_user`) 
                FROM `user` 
                WHERE `phone` = ?";

$stmt = mysqli_prepare($connect, $user_check);
mysqli_stmt_bind_param($stmt, 's', $phone);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$result = $result->fetch_assoc();
$result = array_shift($result);

if ($result == 0) {
    $hash = password_hash($confirm_password, PASSWORD_BCRYPT);

    $add_user = "   INSERT INTO `user`(`phone`, `hash`) 
                    VALUES (?, ?)";
    $stmt = mysqli_prepare($connect, $add_user);
    mysqli_stmt_bind_param($stmt, 'sss', $phone, $hash, $confirm_password);
    mysqli_stmt_execute($stmt);

    header('Location: ../login.php');
} else {
    // 
    // Если введены некорректные данные или пользователь незареган, то выкидываем обратно на страницу с авторизацией
    // 
    header('Location: ../login.php');
}
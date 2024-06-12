<?php
session_start();
require_once ("../api/connection.php");
require_once ("../functions/user_input.php");

$id_user = $_SESSION['id_user'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalAmount = 0;

foreach ($cart as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}


$payment_method = intval(user_input($_POST['payment_method']));
$city           = intval(user_input($_POST['city']));
$pickup_point   = intval(user_input($_POST['pickup_point']));
$order_date     = user_input($_POST['order_date']);

// Проверка данных и вставка в таблицу orders
$order_insert = "INSERT INTO `orders`(`id_user`, `id_status`, `id_pickup_point`, `id_payment_method`, `date`) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($connect, $order_insert);
$order_status = 1;
mysqli_stmt_bind_param($stmt, 'iiiis', $id_user, $order_status, $pickup_point, $payment_method, $order_date);
mysqli_stmt_execute($stmt);

$order_id = mysqli_insert_id($connect);

// Вставка в таблицу order_position
foreach ($cart as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $item_total = $item['price'] * $quantity;

    $order_position_insert = "INSERT INTO `order_position`(`id_order`, `id_product`, `number`, `total_amount`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $order_position_insert);
    mysqli_stmt_bind_param($stmt, 'iiid', $order_id, $product_id, $quantity, $item_total);
    mysqli_stmt_execute($stmt);
}

// Очистка корзины
unset($_SESSION['cart']);

header("Location: ../order_success.php");
?>

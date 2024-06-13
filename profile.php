<?php
session_start();
require_once("api/connection.php");

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Получение истории заказов с сортировкой по дате в обратном порядке
$orders_query = "SELECT `id_order`, `id_status`, `id_pickup_point`, `id_payment_method`, `date` 
                 FROM `orders` 
                 WHERE `id_user` = ?
                 ORDER BY `date` DESC"; // Сортировка по дате в обратном порядке
$stmt = mysqli_prepare($connect, $orders_query);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$orders_result = mysqli_stmt_get_result($stmt);

$orders = [];
while ($order = mysqli_fetch_assoc($orders_result)) {
    // Получение информации о способе оплаты
    $payment_query = "SELECT `name` FROM `payment_method` WHERE `id_payment_method` = ?";
    $payment_stmt = mysqli_prepare($connect, $payment_query);
    mysqli_stmt_bind_param($payment_stmt, "i", $order['id_payment_method']);
    mysqli_stmt_execute($payment_stmt);
    $payment_result = mysqli_stmt_get_result($payment_stmt);
    $payment_method = mysqli_fetch_assoc($payment_result)['name'];

    // Получение информации о точке самовывоза
    $pickup_query = "SELECT `address` FROM `pickup_points` WHERE `id_pickup_point` = ?";
    $pickup_stmt = mysqli_prepare($connect, $pickup_query);
    mysqli_stmt_bind_param($pickup_stmt, "i", $order['id_pickup_point']);
    mysqli_stmt_execute($pickup_stmt);
    $pickup_result = mysqli_stmt_get_result($pickup_stmt);
    $pickup_point = mysqli_fetch_assoc($pickup_result)['address'];

    // Получение информации о статусе заказа
    $status_query = "SELECT `name` FROM `order_status` WHERE `id_status` = ?";
    $status_stmt = mysqli_prepare($connect, $status_query);
    mysqli_stmt_bind_param($status_stmt, "i", $order['id_status']);
    mysqli_stmt_execute($status_stmt);
    $status_result = mysqli_stmt_get_result($status_stmt);
    $status = mysqli_fetch_assoc($status_result)['name'];

    // Получение информации о товарах в заказе
    $order_items_query = "SELECT `products`.`product_name`, `order_position`.`number`, `products`.`price` 
                          FROM `order_position` 
                          JOIN `products` ON `order_position`.`id_product` = `products`.`id_product` 
                          WHERE `id_order` = ?";
    $order_items_stmt = mysqli_prepare($connect, $order_items_query);
    mysqli_stmt_bind_param($order_items_stmt, "i", $order['id_order']);
    mysqli_stmt_execute($order_items_stmt);
    $order_items_result = mysqli_stmt_get_result($order_items_stmt);

    $order_items = mysqli_fetch_all($order_items_result, MYSQLI_ASSOC);

    // Рассчитываем общую сумму заказа
    $total_amount = 0;
    foreach ($order_items as $item) {
        $total_amount += $item['price'] * $item['number'];
    }

    // Сохранение данных о заказе
    $orders[] = [
        'id_order' => $order['id_order'],
        'date' => $order['date'],
        'total_amount' => $total_amount,
        'status' => $status,
        'payment_method' => $payment_method,
        'pickup_point' => $pickup_point,
        'items' => $order_items
    ];
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>

    <!-- Custom CSS File Link  -->
    <link rel="stylesheet" href="css/profile.css">

    <!-- Font Awesome CDN Link -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <a class="logo">coffee <i class="fas fa-mug-hot"></i></a>

        <div class="btn-container">
            <a class="btn" href="index.php">Главная</a>
            <a class="btn" id="logout-btn" href="logout.php">Выйти</a>
        </div>
    </header>

    <!-- PROFILE -->
    <section class="profile" id="profile">
        <h1 class="heading">История <span>заказов</span></h1>

        <div class="order-history">
            <?php foreach ($orders as $order): ?>
                <div class="order-summary" data-order='<?php echo json_encode($order); ?>'>
                    <span class="order-number">Заказ #<?php echo htmlspecialchars($order['id_order']); ?></span>
                    <span class="order-date"><?php echo htmlspecialchars($order['date']); ?></span>
                    <span class="order-total"><?php echo htmlspecialchars($order['total_amount']); ?>&#8381;</span>
                    <span class="order-pickup"><?php echo htmlspecialchars($order['pickup_point']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- MODAL -->
    <div id="order-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Заказ #<span id="modal-order-number"></span></h3>
            <p>Дата: <span id="modal-order-date"></span></p>
            <p>Сумма: <span id="modal-order-total"></span>&#8381;</p>
            <p>Статус: <span id="modal-order-status"></span></p>
            <p>Способ оплаты: <span id="modal-order-payment"></span></p>
            <p>Точка самовывоза: <span id="modal-order-pickup"></span></p>
            <h4>Товары:</h4>
            <ul id="modal-order-items"></ul>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>

</html>

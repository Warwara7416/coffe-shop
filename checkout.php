<?php
session_start();
require_once ("./api/connection.php");
require_once ("./functions/user_input.php");

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalAmount = 0;

foreach ($cart as $item) {
    // Проверка наличия всех ключей
    if (isset($item['price']) && isset($item['quantity'])) {
        $totalAmount += $item['price'] * $item['quantity'];
    } else {
        // Обработка ошибки, если отсутствуют нужные ключи
        echo "Ошибка: отсутствуют необходимые данные о товаре.";
        exit;
    }
}

// Получаем способы оплаты
$payment_query = "SELECT `id_payment_method`, `name` FROM `payment_method`";
$payment_result = mysqli_query($connect, $payment_query);
$payment_methods = mysqli_fetch_all($payment_result, MYSQLI_ASSOC);

// Получаем города
$city_query = "SELECT `id_city`, `city_name` FROM `citys`";
$city_result = mysqli_query($connect, $city_query);
$cities = mysqli_fetch_all($city_result, MYSQLI_ASSOC);

// Получаем точки самовывоза
$pickup_query = "SELECT `id_pickup_point`, `id_city`, `address` FROM `pickup_points`";
$pickup_result = mysqli_query($connect, $pickup_query);
$pickup_points = mysqli_fetch_all($pickup_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <a href="index.php" class="btn-back">Назад</a>
    <div class="container">
        <div class="column">
            <h2>Корзина</h2>
            <ul class="cart-items">
                <?php foreach ($cart as $item): ?>
                    <li><?php echo htmlspecialchars($item['name']); ?> - <?php echo htmlspecialchars($item['price']); ?>&#8381; x <?php echo htmlspecialchars($item['quantity']); ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Итоговая сумма: <?php echo $totalAmount; ?>&#8381;</p>
        </div>
        <div class="column">
            <form action="./functions/process_order.php" method="post">
                <h2>Способы оплаты</h2>
                <select name="payment_method">
                    <?php foreach ($payment_methods as $method): ?>
                        <option value="<?php echo htmlspecialchars($method['id_payment_method']); ?>"><?php echo htmlspecialchars($method['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <h2>Город</h2>
                <select name="city" id="city">
                    <?php foreach ($cities as $city): ?>
                        <option value="<?php echo htmlspecialchars($city['id_city']); ?>"><?php echo htmlspecialchars($city['city_name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <h2>Точка самовывоза</h2>
                <select name="pickup_point" id="pickup_point">
                    <!-- Динамически заполняется через JavaScript -->
                </select>

                <input type="hidden" name="order_date" id="order_date" value="">

                <button type="submit">Оформить заказ</button>
            </form>
        </div>
    </div>

    <script>
    const cities = <?php echo json_encode($cities); ?>;
    const pickupPoints = <?php echo json_encode($pickup_points); ?>;

    document.querySelector('#city').addEventListener('change', function() {
        const cityId = this.value;
        const pickupSelect = document.querySelector('#pickup_point');
        pickupSelect.innerHTML = '';

        pickupPoints.forEach(point => {
            if (point.id_city == cityId) {
                const option = document.createElement('option');
                option.value = point.id_pickup_point;
                option.textContent = point.address;
                pickupSelect.appendChild(option);
            }
        });
    });

    // Триггерим событие изменения города при загрузке страницы, чтобы заполнились точки самовывоза для первого города
    document.querySelector('#city').dispatchEvent(new Event('change'));

    // Устанавливаем текущую дату в поле заказа
    document.getElementById('order_date').value = new Date().toISOString().slice(0, 10);
    </script>
</body>
</html>

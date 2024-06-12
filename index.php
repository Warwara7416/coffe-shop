<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$isAuth = isset($_SESSION['isAuth']) ? $_SESSION['isAuth'] : false;
require_once ("./api/connection.php");

$products_query = "SELECT `id_product`, `product_name`, `description`, `price` FROM `products`";
$stmt = mysqli_prepare($connect, $products_query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome CDN Link -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div id="menu-btn" class="fas fa-bars"></div>

        <a class="logo">coffee <i class="fas fa-mug-hot"></i></a>

        <div class="btn-container">
            <a class="btn" id="cart-btn">Корзина</a>
            <?php if ($isAuth): ?>
                <a class="btn" id="profile-btn" href="profile.php">Личный кабинет</a>
            <?php else: ?>
                <a class="btn" id="login-btn" href="login.php">Войти</a>
            <?php endif; ?>
        </div>

        <div class="cart-popup" id="cart-popup">
            <div id="cart-container" class="cart">
            <!-- Элементы корзины будут динамически добавляться здесь -->
            </div>
            <button class="btn" id="order-btn">Заказать</button> <!-- Изменили на кнопку -->
        </div>
    </header>

    <!-- COMMON BACKGROUND CONTAINER -->
    <div class="common-background">

        <!-- HOME -->
        <section class="home" id="home">
            <div class="row">
                <div class="content">
                    <h3>Свежий кофе с собой</h3>
                </div>

                <div class="image">
                    <img src="image/coffee-cup.svg" class="main-home-image" alt="">
                </div>
            </div>
        </section>

        <!-- MENU -->
        <section class="menu" id="menu">
            <h1 class="heading">Меню <span>Популярные позиции</span></h1>

            <div class="box-container">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <a class="box" data-id="<?php echo htmlspecialchars($row['id_product']); ?>" data-name="<?php echo htmlspecialchars($row['product_name']); ?>" data-price="<?php echo htmlspecialchars($row['price']); ?>">
                        <img src="image/menu-<?php echo htmlspecialchars($row['id_product']); ?>.png" alt="">
                        <div class="content">
                            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <span><?php echo htmlspecialchars($row['price']); ?>&#8381;</span>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </section>

    </div>

    <!-- FOOTER -->
    <section class="footer">
       <div class="box-container">
           <div class="box">
               <h3>Контактная информация</h3>
               <a href="#"><i class="fas fa-phone"></i> +7(987)654-32-11</a>
               <a href="#"><i class="fas fa-envelope"></i> coffee@gmail.com</a>
               <a href="#"><i class="fas fa-envelope"></i> Челябинск, ул. Липовая 43</a>
           </div>
       </div>
    </section>

    <script src="js/script.js"></script>
</body>

</html>

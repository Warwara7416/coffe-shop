<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$isAuth = isset($_SESSION['isAuth']) ? $_SESSION['isAuth'] : false;
require_once ("./api/connection.php");

$categories_query = "SELECT `id_category`, `category_name` FROM `categorys`";
$categories_stmt = mysqli_prepare($connect, $categories_query);
mysqli_stmt_execute($categories_stmt);
$categories_result = mysqli_stmt_get_result($categories_stmt);

$products_query = "SELECT `id_product`, `product_name`, `description`, `price`, `id_category`, `img_path` FROM `products`";
$products_stmt = mysqli_prepare($connect, $products_query);
mysqli_stmt_execute($products_stmt);
$products_result = mysqli_stmt_get_result($products_stmt);

$categories = [];
while ($category = mysqli_fetch_assoc($categories_result)) {
    $categories[$category['id_category']] = $category['category_name'];
}

$products = [];
while ($product = mysqli_fetch_assoc($products_result)) {
    $products[$product['id_category']][] = $product;
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Coffee shop">
    <meta name="keywords" content="Coffee, Coffee shop, Tasty coffee">
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
            <?php foreach ($products as $category_id => $products_in_category): ?>
                <h1 class="heading">Меню <span><?php echo htmlspecialchars($categories[$category_id]); ?></span></h1>
                <div class="box-container">
                    <?php foreach ($products_in_category as $product): ?>
                        <a class="box" data-id="<?php echo htmlspecialchars($product['id_product']); ?>" data-name="<?php echo htmlspecialchars($product['product_name']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>">
                            <img src="image/<?php echo htmlspecialchars($product['img_path']); ?>.png" alt="">
                            <div class="content">
                                <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <span><?php echo htmlspecialchars($product['price']); ?>&#8381;</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
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

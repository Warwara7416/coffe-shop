<?php
session_start();
$isAuth = isset($_SESSION['isAuth']) ? $_SESSION['isAuth'] : false;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>

<body>
    <div class="container">
        <?php if ($isAuth): ?>
            <form action="logout.php">
                <h2>Уже покидаете нас?</h2>
                <button id="submit">Выйти</button>
            </form>
            <form action="index.php">
                <button type="submit">Вернуться</button>
            </form>
        <?php else: ?>
            <form action="./security/validation.php" id="authForm" class="auth-form" method="post">
                <h2>Авторизация</h2>
                <div class="input-group">
                    <label for="phone">Номер телефона</label>
                    <input type="tel" id="phone" name="phone" placeholder="+7 (___) ___-__-__" required>
                </div>
                <div class="input-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Войти</button>
                <button type="button" id="toggleForm">Зарегистрироваться</button>
            </form>
            <form action="./security/register.php" id="regForm" class="auth-form" method="post" style="display: none;">
                <h2>Регистрация</h2>
                <div class="input-group">
                    <label for="reg-phone">Номер телефона</label>
                    <input type="tel" id="reg-phone" name="phone" placeholder="+7 (___) ___-__-__" required>
                </div>
                <div class="input-group">
                    <label for="reg-password">Пароль</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm-password">Подтвердите пароль</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit">Зарегистрироваться</button>
                <button type="button" id="toggleFormBack">Авторизация</button>
            </form>
        <?php endif; ?>
        <div id="error-message" class="error-message"></div>
    </div>

    <script src="./js/login.js"></script>
</body>

</html>

<?php
require_once ("../api/connection.php");
require_once ("../functions/user_input.php");

$products = "   SELECT `id_product`, `product_name`, `description`, `price` 
                FROM `products`";

$stmt = mysqli_prepare($connect, $products);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$result = $result->fetch_assoc();

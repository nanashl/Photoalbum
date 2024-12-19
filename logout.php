<?php
session_start();
session_unset(); // Удаляем все данные сессии
session_destroy(); // Уничтожаем сессию
header("Location: index.php"); // Перенаправляем на главную страницу
exit();
?>

<?php
        session_start();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Модуль 14</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>SPA-салон</h1>
    </header>
    <main>
    

    <?php

        require_once 'func.php';

        if (isset($_POST['password']) && isset($_POST['login'])) {
            if (existsUser($_POST['login'])) {
                if (checkPassword($_POST['login'], $_POST['password'])) {
                    // echo 'Авторизованы';
                    setUserLoginTime();
                    $_SESSION['auth'] = $_POST['login'];
                } else {
                    echo 'Неверный пароль';
                }
            } else {
                echo 'Неверный логин';
            }
        }

        $auth = $_SESSION['auth'] ?? null;

        if ($auth) {
            header("Location:index.php");
        }
        ?>

    <div class="content">
        <form action="login.php" method="post" class="auth_form">
            <p>АВТОРИЗАЦИЯ</p>
            <input name="login" type="text" placeholder="Логин"><br>
            <input name="password" type="password" placeholder="Пароль"><br>
            <button name="loin" type="submit">Войти</button>
        </form>

    </div>


    </main>


    <footer>
        <div class="SPA">SPA <span>&copy;&nbsp;SPA</span></div>
    </footer>
</body>


</html>
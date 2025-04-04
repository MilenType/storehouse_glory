<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Модуль 14</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php
session_start();
require_once 'func.php';

function getDiffDays($birthday_string)
{
    $today = new DateTime();
    $today->setDate($today->format('Y'), $today->format('n'), $today->format('j'));
    $birthday = DateTime::createFromFormat('Y-m-d', $birthday_string);
    $birthday->setDate($today->format('Y'), $birthday->format('n'), $birthday->format('j'));

    // Определяем разницу между датами
    $interval = $today->diff($birthday);

    if ($interval->invert == 1) {
        // Если разница отрицательная, значит день рождения уже прошел в этом году
        $next_birthday = clone $birthday;
        $next_birthday->modify('+1 year');

        $days_until_next_bd = $today->diff($next_birthday)->days;
        return "До твоего следующего дня рождения осталось $days_until_next_bd дней.";
    } else {
        // Если разница положительная, то день рождения еще впереди
        $days_until_bd = $interval->days;
        return "До твоего дня рождения осталось $days_until_bd дней.";
    }
}
?>



<body>
    <header>
        <h1>SPA-салон Магнолия</h1>
    </header>
    <main>

        <div class="text">
            <p>
                <?php echo getCurrentUser($_SESSION['auth']) . '!'; ?>
                Добро пожаловать на сайт нашего SPA-салона!</p><br>
            <div class="fig"> <img src="images/spa2.jpg" alt="SPA1" width="600" height="400">
            </div>
        </div>

        <?php
        $auth = $_SESSION['auth'] ?? null;

        if (!$auth) {
            header("Location:login.php");
        }
        ?>


        <?php
        $user_birthday = getUserData($_SESSION['auth']);

        if($user_birthday) {
            $user_birthday_date = DateTime::createFromFormat('Y-m-d', $user_birthday);
            $target_day_month = $user_birthday_date->format('m-d');
        }

        else {
            $user_birthday_date = "";
            $target_day_month = "";
        }

        $current_date = date('m-d');
        ?>

        <?php if($user_birthday) { ?>
        <div>
            <div class="birthday"><?php echo "Ваш День Рождения: " . $user_birthday; ?></div>
            <?php if ($current_date === $target_day_month) { ?>

                <div class="greed"><?php echo "<br> Поздравляем с Днём рождения! <br> Сегодня для вас скидка 5% на все услуги" . "<br>"; ?></div>

            <?php } else { ?>
                <div class="daysleft"><?php echo getDiffDays($user_birthday) . "<br>"; ?></div>

            <?php } ?>
        </div>

        <?php } elseif (isset($_POST['birthday'])) { ?>
        <div>
            <div class="secondin"></div>
            <?php setUserData($_SESSION['auth'], $_POST['birthday']);
            $user_birthday = getUserData($_SESSION['auth']);

            if ($current_date === $user_birthday) {?>
            <div class="greed"><?php echo "<br> Поздравляем с Днём рождения! <br> Сегодня для вас скидка 5% на все услуги" . "<br>"; ?></div>
            <?php } else { ?>
                <div class="daysleft"><?php echo getDiffDays($user_birthday) . "<br>"; ?></div>

            <?php }} ?> 


            <?php
        

        $action_time = getTimeSale();
        $hour = intdiv(($action_time), 3600);
        $modhour = $action_time - $hour * 3600;
        $min = intdiv($modhour, 60);
        $sec  = $modhour -  $min * 60;

        if (($hour > 0) && ($min > 0) && ($sec)) { ?>
        <div>
            <div class="saleinfo"><?php echo "<br> Уважаемый, " . getCurrentUser($_SESSION['auth']) . "!<br> Для вас сегодня действует персональная акция на любой вид массажа 500 рублей!"; ?></div>
            <div class="sale"> <?php echo "<br> До конца персональной акции осталось: " . $hour . " ч. " . $min . " мин. " . $sec . " сек. <br>"; ?></div>
            <?php } else { ?>
            <div class="nonsale"> <?php echo "Акция завершена";}?></div>
        </div>            

        <form  class="calender" action="index.php" method="post">
            <p><br>Для получения персональной акции введите дату рождения</p><br>
            <input name="birthday" type="date" placeholder="День">
            <button name="submit" type="submit">Ввести дату рождения</button>
        </form>


        <?php
        $services = [
            ["name" => "Классический массаж", "price" => 1500],
            ["name" => "Антицеллюлитный массаж", "price" => 1800],
            ["name" => "Тайский массаж", "price" => 2500],
            ["name" => "Спортивный массаж", "price" => 2200],
            ["name" => "Расслабляющий массаж", "price" => 1700]
        ];

        ?>

        <table>
        <div class="fig"> <img src="images/massaj.jpg" alt="SPA2" width="600" height="400">
            <caption>Вид массажа</caption>
            <tr>
                <th></th>
                <th>Цена</th>
                <th>Скидка</th>
                <th>Персональная акция</th>
                <th>Итого</th>
            </tr>
            <?php
            foreach ($services as $service) {


            ?>
                <tr>
                    <th><?php echo $service['name']; ?></th>
                    <td><?php echo $service['price']; ?> руб</td>
                    <td><?php
                        if ($current_date == $target_day_month) {
                            echo '5%';
                        } else echo '0%';
                    ?></td>
                    <td><?php
                        if (($hour > 0) && ($min > 0) && ($sec)) {
                            echo '-500 рублей';
                        } else echo '0 рублей';
                    ?></td>
                    <td><?php
                        if (($current_date == $target_day_month) && (($hour > 0) && ($min > 0) && ($sec))) {
                            $res = $service['price'] * 0.95 - 500;
                            echo $res . ' рублей';
                        } elseif ($current_date == $target_day_month) {
                            $res = $service['price'] * 0.95 - 500;
                            echo $res . ' рублей';
                        } else                     
                            echo $service['price'] - 500 . ' рублей';
                    ?></td>
                </tr>
            <?php
            }
            ?>

        </table>

        <?php
        $wrapping_types = [
            ["name" => "Шоколадное обертывание", "price" => 1500],
            ["name" => "Морское обертывание", "price" => 1200],
            ["name" => "Грязевое обертывание", "price" => 1000],
            ["name" => "Медовое обертывание", "price" => 1800],
            ["name" => "Альгинатное обертывание", "price" => 1400]
        ];

        ?>    
        <table>
        <div class="fig"> <img src="images/obert.jpg" alt="SPA1" width="600" height="400">
            <caption>Обертывание</caption>
            <tr>
                <th></th>
                <th>Цена</th>
                <th>Скидка</th>
                <th>Итого</th>
            </tr>
            <?php
            foreach ($wrapping_types as $wrapping_type) {


            ?>
                <tr>
                    <th><?php echo $wrapping_type['name']; ?></th>
                    <td><?php echo $wrapping_type['price']; ?> руб</td>
                    <td><?php
                        if ($current_date == $target_day_month) {
                            echo '5%';
                        } else echo '0%';
                        ?></td>
                    <td><?php
                        if ($current_date == $target_day_month) {
                            $res = $wrapping_type['price'] * 0.95;
                            echo $res . ' рублей';
                        } else echo $wrapping_type['price'] . ' рублей';
                        ?></td>
                </tr>
            <?php
            }
            ?>
        </table>


        <?php
        $spa_baths = [
            ["name" => "Финская сауна", "price" => 2000],
            ["name" => "Русская баня", "price" => 2500],
            ["name" => "Турецкий хаммам", "price" => 3000],
            ["name" => "Инфракрасная сауна", "price" => 1500],
            ["name" => "Японская баня (офуро)", "price" => 3500]
        ];

        ?> 

        <table>
        <div class="fig"> <img src="images/khamam.jpg" alt="SPA1" width="600" height="400">
            <caption>Баня</caption>
            <tr>
                <th></th>
                <th>Цена</th>
                <th>Скидка</th>
                <th>Итого</th>
            </tr>
            <?php
            foreach ($spa_baths as $spa_bath) {

            ?>
                <tr>
                    <th><?php echo $spa_bath['name']; ?></th>
                    <td><?php echo $spa_bath['price']; ?> руб</td>
                    <td><?php
                        if ($current_date == $target_day_month) {
                            echo '5%';
                        } else echo '0%';
                        ?></td>
                    <td><?php
                        if ($current_date == $target_day_month) {
                            $res = $spa_bath['price'] * 0.95;
                            echo $res . ' рублей';
                        } else echo $spa_bath['price'] . ' рублей';
                        ?></td>
                </tr>
            <?php
            }
            ?>
        </table>

    </main>
    <footer>
        <a href="logout.php" class="">Выйти</a><br><br>
        <div class="SPA">SPA<span>&copy;&nbsp;SPA</span></div>
    </footer>
</body>

</html>
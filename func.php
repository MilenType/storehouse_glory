<?php



function getUsersList()  //Получение списка пользователей
{
    $file = file_get_contents('db/users_hash.json');
    if (!$file) {
        echo "Не найден файл для чтения";
    }
    $users = json_decode($file, true);
    return $users;
}

// Запись хэшировнного пароля в файл с пользователями
// $users = getUsersList();

// for ($i = 0; $i < count($users); $i++){
//    $users[$i]["password"] = password_hash($users[$i]["password"], PASSWORD_DEFAULT);   
// }
// $file = fopen('db/users_hash.json', 'w+');

// fwrite($file, json_encode($users));


function existsUser($login) // Проверка существования логина пользователя
{
    $users = getUsersList();

    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['login'] == $login) {
            return true;
        }
    }

    return false;
}

function checkPassword($login, $password) // Проверка существования пользователя с введеным логином и паролем
{
    $users = getUsersList();

    if (existsUser($login)) {
        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]['login'] === $login) {
                if (password_verify($password, $users[$i]['password'])) {
                    return true;
                }
            }
        }

        return false;
    }

    return false;
}

function getCurrentUser($login) // Функция, возвращающая либо имя вошедшего на сайт пользователя, либо null.
{
    $users = getUsersList();
    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['login'] === $login) {
            return $users[$i]['login'];
        }
    }
    return null;
}


function setUserLoginTime() 
{
    $filename = 'db/time.txt';
    $file = fopen($filename, 'w+');
    fwrite($file, time());
}

function getTimeDiffrents() // Функция отсчета времени нахождения на сайте
{
    $filename = 'db/time.txt';
    $enter_time = file_get_contents($filename);
    return time() - $enter_time;
}

// echo time() - file_get_contents($filename);

function getTimeSale()
{

    $day = date('d');
    $month = date('m');
    $year = date('Y');
    $timestamp = mktime(20, 59, 59, $month, $day, $year);
    $time = time();
    $action_time = $timestamp - $time;
    return $action_time;
}


function setUserBirthday($birthday) // Функция записи даты рождения
{
    $filename = 'db/birthday.txt';
    $file = fopen($filename, 'w+');
    fwrite($file, $birthday);
}


function getToday() { //текущая дата
    $today = date("Y-m-d");
    return $today;
}

function setUserData($login, $birthday) {
    $filename = 'db/birthdays.json';
    $file = file_get_contents($filename);
    $birthdays = json_decode($file, true);
    $birthdays[$login] = $birthday;

    $fileopen = fopen($filename, 'w+');
    fwrite($fileopen, json_encode($birthdays));
}

function getUserData($login) {
    $filename = 'db/birthdays.json';
    $file = file_get_contents($filename);
    $birthdays = json_decode($file, true);

    if(isset($birthdays[$login])) {
        return $birthdays[$login];
    }
    else {
        return false;
    }
}


?>
<?php
/**
 * Created by PhpStorm.
 * User: Odmen
 * Date: 2/1/2019
 * Time: 6:07 PM
 */
//Устанавливаем кодировку и вывод всех ошибок
header('Content-Type: text/html; charset=UTF8');
error_reporting(E_ALL);

//Адрес базы данных
define('DB_SERVER','localhost');

//Логин БД
define('DB_USER','root');

//Пароль БД
define('DB_PASSWORD','');

//Имя Базы Данных
define('DATABASE','two_up');

//Подключение к базе данных mySQL с помощью PDO
try {
    $db = new PDO('mysql:host=localhost;dbname='.DATABASE, DB_USER, DB_PASSWORD, array(
        PDO::ATTR_PERSISTENT => true
    ));

} catch (PDOException $e) {
    print "Ошибка соединеия!: " . $e->getMessage() . "<br/>";
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>2up</title>
    </head>
    <body>
    <h3>Найти пользователя:</h3>
    <form name="search" method="get">
        <table>
            <tr>
                <td>Логин:</td>
                <td><input type="text" size="30" name="login"></td>
            </tr>
            <tr>
                <td>Имя:</td>
                <td><input type="text" size="30" name="name"></td>
            </tr>
            <tr>
                <td>Фамилия:</td>
                <td><input type="text" size="30" name="surname"></td>
            </tr>
            <tr>
                <td>E-mail:</td>
                <td><input type="text" size="30" name="email"></td>
            </tr>
            <tr>
                <td>Тип пользователя:</td>
                <td><input type="radio" name="is_admin" value="2">Admin<input type="radio" name="is_admin" value="1">User</td>
            </tr>
            <tr>
                <td> </td>
                <td colspan="2"><input type="submit" value="find" name="submit"></td>
            </tr>
        </table>
    </form>
<hr>
    <h3>Добавить пользователя:</h3>
    <form action="" method="POST">
        <table>
            <tr>
                <td>Логин:</td>
                <td><input type="text" size="30" name="login"></td>
            </tr>
            <tr>
                <td>Имя:</td>
                <td><input type="text" size="30" name="name"></td>
            </tr>
            <tr>
                <td>Фамилия:</td>
                <td><input type="text" size="30" name="surname"></td>
            </tr>
            <tr>
                <td>E-mail:</td>
                <td><input type="text" size="30" name="email"></td>
            </tr>
            <tr>
                <td>Тип пользователя:</td>
                <td><input type="radio" name="is_admin" value="2">Admin<input type="radio" name="is_admin" value="1" checked>User</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2"><input type="submit" value="Зарегистроваться" name="submit"></td>
            </tr>
        </table>
    </form>
    </body>
</html>
<?php
//Подключаем добавление пользователей в БД
include 'add_user.php';
//Поиск
if(empty($_GET)) {
    return false;
} else {
$sql = "SELECT * FROM `users` WHERE  
`login` LIKE :login AND `first_name` LIKE :name AND `last_name` LIKE :surname AND `email` LIKE :email AND `is_admin` LIKE :is_admin";

//Подготавливаем PDO выражение для SQL запроса
$stmt = $db->prepare($sql);
$stmt->bindValue(':login', "%". $_GET['login'] . "%", PDO::PARAM_STR);
$stmt->bindValue(':name', "%". $_GET['name'] . "%", PDO::PARAM_STR);
$stmt->bindValue(':surname', "%". $_GET['surname'] . "%", PDO::PARAM_STR);
$stmt->bindValue(':email', "%". $_GET['email'] . "%", PDO::PARAM_STR);
$stmt->bindValue(':is_admin', "%". $_GET['is_admin'] . "%", PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Таблица
echo '<table cellpadding="5" cellspacing="0" border="1">';
echo '<tr><td>Логин</td><td>Имя</td><td>Фамилия</td><td>E-mail</td><td>Тип пользователя</td></tr>';
foreach ($rows as $key => $value) {
    echo "<tr>";
    foreach ($value as $data)
        echo "<td>".$data."</td>";
    echo "</tr>";
}
echo "</table>";
}
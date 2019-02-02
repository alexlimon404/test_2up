<?php
/**
 * Created by PhpStorm.
 * User: Odmen
 * Date: 2/1/2019
 * Time: 6:07 PM
 */

function emailValid($email)
{
    if(function_exists('filter_var')){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
    }else{
        if(!preg_match("/^[a-z0-9_.-]+@([a-z0-9]+\.)+[a-z]{2,6}$/i", $email)){
            return false;
        }else{
            return true;
        }
    }
}
//Функция вывода ошибок
function showErrorMessage($data)
{
    $err = '<ul>'."\n";

    if(is_array($data)) {
        foreach($data as $val)
            $err .= '<li style="color:red;">'. $val .'</li>'."\n";
    }
    else
        $err .= '<li style="color:red;">'. $data .'</li>'."\n";
        $err .= '</ul>'."\n";
        return $err;
}

$err = array();
if(isset($_POST['submit'])) {
    //Проверка
    if(empty($_POST['login']))
        $err[] = 'Поле Логин не может быть пустым!';
    if(empty($_POST['name']))
        $err[] = 'Поле Имя  не может быть пустым';
    if(empty($_POST['surname']))
        $err[] = 'Поле Фамилия пароля не может быть пустым';
    if(empty($_POST['is_admin']))
        $err[] = 'Поле Тип пользователя не может быть пустым';
    if(empty($_POST['email']))
        $err[] = 'Поле E-mail не может быть пустым';
    else {
        if(emailValid($_POST['email']) === false)
            $err[] = 'Не правильно введен E-mail'."\n";
    }
    if(count($err) > 0){
        echo showErrorMessage($err);
    } else {
        /*Проверяем существует ли у нас такой пользователь в БД*/
        $sql = 'SELECT `login` FROM `users`	WHERE `login` = :login';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':login', $_POST['login'], PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($rows) > 0) {
            $err[] = 'К сожалению Логин: <b>' . $_POST['login'] . '</b> занят!';
            echo showErrorMessage($err);
        } else {
                /*Если все хорошо, пишем данные в базу*/
                $sql = 'INSERT INTO `users`	VALUES(:login, :first_name,	:last_name,	:email,	:is_admin)';
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':login', $_POST['login'], PDO::PARAM_STR);
                $stmt->bindValue(':first_name', $_POST['name'], PDO::PARAM_STR);
                $stmt->bindValue(':last_name', $_POST['surname'], PDO::PARAM_STR);
                $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $stmt->bindValue(':is_admin', $_POST['is_admin'], PDO::PARAM_STR);
                $stmt->execute();
                echo '<span style="color:red;">Добавил пользователя успешно</span>';
                exit;
        }
    }
}
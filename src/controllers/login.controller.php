<?php

session_start();

$trace = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $errors = [];

    if (!Validator::string($password, 5, 255)) {
        $errors['body'] = "password has to be at least 5 characters";
    }

    if (!Validator::string($username)) {
        $errors['body'] = "username has to be at least 5 characters";
    }

    if (empty($errors)) {
        $config = require "config.php";
        $db = new Database($config['database']);

        $users = $db->query("select * from users where username = :username", [':username' => $username])->fetch();

        if ($users) {
            if (password_verify($password, $users['password'])) {
                $_SESSION['user'] = $username;
                header("Location: /home");
                exit();
            } else {
                $errors['body'] = "wrong password";
            }
        } else {
            $errors['body'] = "user does not exist";
        }
    }

}

require 'src/views/login.view.php';
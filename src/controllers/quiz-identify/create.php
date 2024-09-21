<?php

use Core\App;
use Core\Database;
use Core\Validator;

session_start();

$db = App::resolve(Database::class);

Validator::verify_user($_SESSION['user']);

$score = $_POST['score'] ?? 0;
$time_spent = $_POST['elapsedTime'] ?? '00:00:00';
$username = $_SESSION['user'];

$db->query("INSERT INTO results_identify (score, username, time) VALUES (:score, :username, :time)", [
    ':score' => $score,
    ':username' => $username,
    ':time' => $time_spent,
]);

header("location: /quiz-identify");
exit();

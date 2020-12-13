<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "13.125.222.132";
        $DB_NAME = "infinite_movie";
        $DB_USER = "jiyoung";
        $DB_PW = "1213";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
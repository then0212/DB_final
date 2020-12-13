<?php

function createCustomer($customerID, $l_name, $f_name, $address, $city, $state_, $zipCode, $telephone, $email, $creditCard, $accountType)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO CUSTOMERS(customerID, l_name, f_name, address, city, state_, zipCode, telephone, email,
    creditCard, accountType) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$customerID, $l_name, $f_name, $address, $city, $state_, $zipCode, $telephone, $email, $creditCard, $accountType]);

    $st = null;
    $pdo = null;

}

function alreadyExistCustomerID($customerID)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from CUSTOMERS where customerID = ?) as is_already_exist;";
    $st = $pdo->prepare($query);
    $st->execute([$customerID]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return intval($res[0]['is_already_exist']);
}

function getRented($customerID)
{
    $pdo = pdoSqlConnect();
    $query = "select distinct movieTitle
from MOVIES
         inner join ORDERS on MOVIES.movieID = ORDERS.movieID
where returnDate is null
  and customerID = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$customerID]);
    $st->setFetchMode(PDO::FETCH_ASSOC);

    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return $res;
}

function getMovieQueue($customerID)
{
    $pdo = pdoSqlConnect();
    $query = "select distinct movieTitle
from MOVIES
         inner join MOVIE_QUEUE on MOVIES.movieID = MOVIE_QUEUE.movieID
where MOVIE_QUEUE.customerID = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$customerID]);
    $st->setFetchMode(PDO::FETCH_ASSOC);

    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return $res;
}

function getAccountType($customerID)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT accountType
from CUSTOMERS
where customerID = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$customerID]);
    $st->setFetchMode(PDO::FETCH_ASSOC);

    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return $res[0];
}

function getMovieByType()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT movieTitle, movieType
from MOVIES
where numCopies > 0
order by movieType;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);

    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return $res;
}

function searchMovie($piece_of_movie_title)
{
    $pdo = pdoSqlConnect();
    $query = "select movieTitle
from MOVIES
where movieTitle like concat('%', ?, '%');";

    $st = $pdo->prepare($query);
    $st->execute([$piece_of_movie_title]);
    $st->setFetchMode(PDO::FETCH_ASSOC);

    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return $res;
}

function searchMovieWithActorName($piece_of_actor_name)
{
    $pdo = pdoSqlConnect();
    $query = "select distinct ACTORS.actorName, movieTitle
from MOVIES
         inner join PLAYS_ON
         inner join ACTORS on MOVIES.movieID = PLAYS_ON.movieID and PLAYS_ON.actorID = ACTORS.actorID
where ACTORS.actorID in (
    select distinct actorID from ACTORS where actorName like concat('%', ?, '%'))
order by actorName;";

    $st = $pdo->prepare($query);
    $st->execute([$piece_of_actor_name]);
    $st->setFetchMode(PDO::FETCH_ASSOC);

    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return $res;
}

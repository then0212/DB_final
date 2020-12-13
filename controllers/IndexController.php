<?php
require 'function.php';

$res = (object)array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;


        case "createCustomer":
            http_response_code(200);

            $customerID = $req->customerID;
            $l_name = $req->l_name;
            $f_name = $req->f_name;
            $address = $req->address;
            $city = $req->city;
            $state = $req->state;
            $zipCode = $req->zipCode;
            $telephone = $req->telephone;
            $email = $req->email;
            $creditCard = $req->creditCard;
            $accountType = $req->accountType;

            if (alreadyExistCustomerID($customerID)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "이미 존재하는 아이디입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            createCustomer($customerID, $l_name, $f_name, $address, $city, $state, $zipCode, $telephone, $email, $creditCard, $accountType);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "login":
            http_response_code(200);

            $customerID = $req->customerID;

            if (!alreadyExistCustomerID($customerID)) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "존재하지 않는 아이디입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "rented":
            http_response_code(200);

            $customerID = $vars["customerID"];

            $res->result = getRented($vars["customerID"]);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "현재 내가 대여중인 영화 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "movie_queue":
            http_response_code(200);

            $customerID = $vars["customerID"];

            $res->result = getMovieQueue($vars["customerID"]);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "나의 movie queue 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "account_type":
            http_response_code(200);

            $customerID = $vars["customerID"];

            $res->result = getAccountType($vars["customerID"]);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "나의 결제플랜 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "movie_by_type":
            http_response_code(200);

            $res->result = getMovieByType();

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "타입별 영화 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "search_movie":
            http_response_code(200);

            $piece_of_movie_title = $_GET['piece_of_movie_title'];

            $res->result = searchMovie($piece_of_movie_title);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "영화 검색 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "search_movie_with_actor_name":
            http_response_code(200);

            $piece_of_actor_name = $_GET['piece_of_actor_name'];

            $res->result = searchMovieWithActorName($piece_of_actor_name);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "배우 및 출연영화 검색 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}

<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Headers: ');
header('Access-Control-Allow-Headers: x-rapidapi-key, x-rapidapi-host');
header('Access-Control-Allow-Headers: content-type');
header('Content-Type: application/json');
require_once '../inc/class_autoloader.inc.php';
require_once '../inc/function.inc.php';
$_POST = json_decode(file_get_contents('php://input'), true);
$api_return = ['error' => false, 'message' => '', 'header' => 200 , 'datasent' => $_POST, 'emptyinput' => ['username' => !isset($_POST['username']) , 'password' => !isset($_POST['password'])  ]];

    if(!isset($_POST['username']) || !isset($_POST['password'])){
        $api_return['error'] = true;
        $api_return['message'] = 'Unautorished';
        $api_return['header'] = 200;
        apiExit($api_return);
    }
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $api_return['validinput'] = ['username' => $username, 'password' => $password && true];
    
    if(empty($username) || empty($password)){
        $api_return['error'] = true;
        $api_return['message'] = 'empty Input';
        $api_return['header'] = 200;
        apiExit($api_return);
    }
    if($username == false){
        $api_return['error'] = true;
        $api_return['message'] = 'Invalid Email';
        $api_return['header'] = 200;
        apiExit($api_return);
    }
    

   $login = new Dbh;
   $logging = $login->checkUser($username, $username);

   if($logging === true){
       $api_return['error'] = true;
        $api_return['message'] = 'User Not found';
        $api_return['header'] = 200;
        apiExit($api_return);
   }
   else {
       
    $loginData = $logging[0];
    

    $hashed_pwd = $loginData['user_password'];
    if(password_verify($password, $hashed_pwd) === false){
        $api_return['error'] = true;
        $api_return['message'] = 'Wrong Password';
        $api_return['header'] = 200;
        apiExit($api_return);
    }
    else{
        $api_return['error'] = false;
        $api_return['message'] = 'Logged in';
        $api_return['header'] = 200;
        $api_return['token'] = "200";
        apiExit($api_return);
    }
   }



<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Headers: ');
header('Access-Control-Allow-Headers: x-rapidapi-key, x-rapidapi-host');
header('Access-Control-Allow-Headers: content-type');
header('Content-Type: application/json');

    $_POST = json_decode(file_get_contents('php://input'), true);
    $api_return = ['error' => false, 'message' => '', 'header' => 200 , 'datasent' => $_POST, 'emptyinput' => ['username' => !isset($_POST['username']) , 'password' => !isset($_POST['password']) , 'email' => !isset($_POST['email']) ]];
    if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email'])){
        $api_return['error'] = true;
        $api_return['message'] = 'Unautorished';
        $api_return['header'] = 200;
       echo json_encode($api_return);
       exit;

    }
    

        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $api_return['validinput'] = ['username' => $username, 'password' => $password && true, 'email' => $email];
        if(empty($username) || empty($password) || empty($email)){
            
        $api_return['error'] = true;
        $api_return['message'] = 'empty Input';
        $api_return['header'] = 200;
    }
    else{

        if($email == false){
            $api_return['error'] = true;
        $api_return['message'] = 'Invalid Email';
        $api_return['header'] = 200;
    }
    else{

        require_once '../inc/class_autoloader.inc.php';
   require_once '../inc/function.inc.php';
   $otp = get_otp();
   $newUser = new NewUser($username, $email, $password, $otp);
   $signUp = new NewObject($newUser);
   $createUser = $signUp->Create($newUser);
   
   if($createUser === true){
       $api_return['error'] = false;
       $api_return['message'] = 'Successfully SignUp';
        $api_return['header'] = 200;

   }else{
       $api_return['error'] = true;
       $api_return['message'] = $createUser['message'];
       $api_return['header'] = 200;
    }
}
}
header('Content-Type: application/json', true, $api_return['header'] );

echo json_encode($api_return);

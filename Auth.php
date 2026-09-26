<?php
require 'Config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;



$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Auth extends Config{
    public function create_user(){
    $info = file_get_contents('php://input');
    $details = json_decode($info);
    $first_name = $details->firstName;
    $last_name = $details->lastName;
    $email = $details->Email;
    $pass = $details->Password;
    $nationality = $details->Nationality;
    $occupation = $details->Occupation;

    $password = password_hash($pass, PASSWORD_DEFAULT);

    $query = "SELECT * FROM user WHERE email = '$email' ";
    $result = mysqli_query($this->connection, $query);

    if(mysqli_num_rows($result)>0){
        echo json_encode(['status' => 400, 'message' => 'Instructor already has an account']);
    }else{
        $insertQuery = "INSERT INTO user (First_name, Last_name, Email, `Password`, Country, Occupation) VALUES ('$first_name', '$last_name', '$email', '$password', '$nationality', '$occupation')";


        $savedUser = mysqli_query($this->connection, $insertQuery);
        if($savedUser){
            echo json_encode(['status'=>200, 'message'=> 'Signup succesful']);
    }else{
             echo json_encode(['status'=>500, 'message'=> 'Error signing up, try again later']);
    }



    }
    }


    public function login_user(){
        $info = file_get_contents('php://input');
        $details = json_decode($info);
        $email = $details->email;
        $pass = $details->password;

        $query = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($this->connection, $query);

        if(mysqli_num_rows($result)>0){
            $foundUser = mysqli_fetch_assoc($result);
            $verify = password_verify($pass, $foundUser['Password']);

            if($verify){
                $payload=[
                    'First_name' => $foundUser['First_Name'],
                    'Last_name' => $foundUser['Last_Name'],

                    'iat'=> time(),
                    'exp'=> time() + 3600
                ];

                $token = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');
                echo json_encode(['status' => 200, 'message' => 'login successful', 'token' => $token
                    ]);

                // $updateQuery = "UPDATE user SET token = '$token' WHERE email = '$email'";
                // $addToken = mysqli_query($this->connection, $updateQuery);
                
                // if($addToken){
                //      echo json_encode(['status' => 200, 'message'=> 'login successful', 'token'=> $token]);
                // }

            }else{
              echo  json_encode(['status'=>400, 'message'=>'incorrect password']);
            }
        } else{
           echo  json_encode(['status'=>504, 'message'=>'user does not exist!']);
        }

    }
}
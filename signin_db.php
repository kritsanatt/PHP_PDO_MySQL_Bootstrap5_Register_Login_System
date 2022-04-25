<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['signin']))
{
    $email=$_POST['email'];
    $password=$_POST['password'];


    if (empty($email)) {
        $_SESSION['error']='Email is required';
        header("location: signin.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error']='Email format is not correct';
        header("location: signin.php");
    } else if (empty($password)){
        $_SESSION['error']='Password is required';
        header("location: signin.php");
    } else if (strlen($password)>20 || strlen(($password) <5)){
        $_SESSION['error']='Password length must be between 6 to 20 charactors';
        header("location: signin.php");
    } else {
        //All Set
        //$_SESSION="";
        try {
            $check_data = $conn->prepare("SELECT * FROM users WHERE email=:email");
            $check_data->bindParam(":email",$email);
            $check_data->execute();
            $row=$check_data->fetch((PDO::FETCH_ASSOC));

            if ($check_data->rowCount()>0) {

                if ($row['email']==$email){
                    if (password_verify($password,$row['password'])){
                        if ($row['urole']=='admin') {
                            $_SESSION['admin_login'] = $row['id'];
                            header("location: admin.php");
                        }
                        else{
                            $_SESSION['user_login'] = $row['id'];
                            header("location: user.php");
                        }
                    }
                    else
                    {
                        $_SESSION['error'] ="Password is not correcct";
                        $conn=null;
                        header("location: signin.php");
                    }
                }
                else {
                    $_SESSION['error'] ="Username is not correct";
                    $conn=null;
                    header("location: signin.php");
                }
            } else {
                $_SESSION['warning'] = "This user is not found. <a href='index.php'>Click here</a> to register";
                $conn=null;
                header("location: signup.php");
            }


        } catch (PDOException $e) {
            $_SESSION['error'] = "There is some error: ".$e->getMessage();
            header("location: signin.php");
        }

    }

}
else
{
    $_SESSION['error'] ="There is no data";
    header("location: signin.php");
}

?>
<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['signup']))
{
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $cpassword=$_POST['c_password'];
    $urole='user';
    

    if (empty($firstname)){
        $_SESSION['error']='FirstName is required';
        header("location: index.php");
    } else if (empty($lastname)) {
        $_SESSION['error']='LastName is required';
        header("location: index.php");
    } else if (empty($email)) {
        $_SESSION['error']='Email is required';
        header("location: index.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error']='Email format is not correct';
        header("location: index.php");
    } else if (empty($password)){
        $_SESSION['error']='Password is required';
        header("location: index.php");
    } else if (strlen($password)>20 || strlen(($password) <5)){
        $_SESSION['error']='Password length must be between 6 to 20 charactors';
        header("location: index.php");
    } else if ($password !=$cpassword){
        $_SESSION['error']='Password not matched';
        header("location: index.php");
    } else {
        //All Set
        //$_SESSION="";
        try {
            $check_email = $conn->prepare("SELECT email FROM users WHERE email=:email");
            $check_email->bindParam(":email",$email);
            $check_email->execute();
            $row=$check_email->fetch((PDO::FETCH_ASSOC));

            if ($row['email']==$email) {
                $_SESSION['warning'] = "This email is already in System <a href='signup.php'>Click here</a> to signin";
                //close connection
                $conn=null;
                header("location: index.php");
            }
            else if (!isset($_Session['error'])) {
                $passwordHash=password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (firstname,lastname,email,password,urole) VALUES (:firstname,:lastname,:email,:pwd,:urole)");
                $stmt->bindParam(":firstname",$firstname);
                $stmt->bindParam(":lastname",$lastname);
                $stmt->bindParam(":email",$email);
                $stmt->bindParam(":pwd",$passwordHash);
                $stmt->bindParam(":urole",$urole);
                $stmt->execute();
                $_SESSION['success'] ="Registration successfully done! <a href='signin.php' class='alert-link'>Click here</a> to signin";
                $conn=null;
                header("location: index.php");
            } else {
                $_SESSION['error'] ="There is some error";
                $conn=null;
                header("location: index.php");                
            }


        } catch (PDOException $e) {
            $_SESSION['error'] = "There is some error: ".$e->getMessage();
            header("location: index.php");
        }

    }

}
else
{
    $_SESSION['error'] ="There is no data";
    header("location: index.php");
}

?>
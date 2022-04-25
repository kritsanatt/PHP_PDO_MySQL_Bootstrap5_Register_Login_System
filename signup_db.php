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
    //Test
    // echo $firstname.",".$lastname.",".$email.",".$password.",".strlen($password);
    // if (strlen($password)>20 || strlen(($password) <5)){
    //    echo "<br>true";
    // }
    // exit();

    if (empty($firstname)){
        $_SESSION['error']='กรุณากรอกชื่อ';
        header("location: index.php");
    } else if (empty($lastname)) {
        $_SESSION['error']='กรุณากรอกนามสกุล';
        header("location: index.php");
    } else if (empty($email)) {
        $_SESSION['error']='กรุณากรอก Email';
        header("location: index.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error']='รูปแบบ Email ไม่ถูกต้อง';
        header("location: index.php");
    } else if (empty($password)){
        $_SESSION['error']='กรุณากรอก password';
        header("location: index.php");
    } else if (strlen($password)>20 || strlen(($password) <5)){
        $_SESSION['error']='Password ต้องมีความยาว 6 ถึง 20 ตัวอักษร';
        header("location: index.php");
    } else if ($password !=$cpassword){
        $_SESSION['error']='Password ไม่ตรงกัน';
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
                $_SESSION['warning'] = "มีอีเมลนี้อยู่ในระบบแล้ว <a href='signup.php'>คลิ๊กที่นี่</a> เพื่อเข้าสู่ระบบ";
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
                $_SESSION['success'] ="สมัครสมาชิกเรียบร้อยแล้ว! <a href='signin.php' class='alert-link'>คลิ๊กที่นี่</a> เพื่อเข้าสู่ระบบ";
                $conn=null;
                header("location: index.php");
            } else {
                $_SESSION['error'] ="มีบางอย่างผิดพลาด";
                $conn=null;
                header("location: index.php");                
            }


        } catch (PDOException $e) {
            $_SESSION['error'] = "พบข้อผิดพลาด ".$e->getMessage();
            header("location: index.php");
        }

    }

}
else
{
    $_SESSION['error'] ="มีบางอย่างผิดพลาด หา signup ไม่เจอ";
    header("location: index.php");
}

?>
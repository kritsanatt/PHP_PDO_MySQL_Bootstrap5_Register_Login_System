<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['signin']))
{
    $email=$_POST['email'];
    $password=$_POST['password'];

    //Test
    // echo $firstname.",".$lastname.",".$email.",".$password.",".strlen($password);
    // if (strlen($password)>20 || strlen(($password) <5)){
    //    echo "<br>true";
    // }
    // exit();

    if (empty($email)) {
        $_SESSION['error']='กรุณากรอก Email';
        header("location: signin.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error']='รูปแบบ Email ไม่ถูกต้อง';
        header("location: signin.php");
    } else if (empty($password)){
        $_SESSION['error']='กรุณากรอก password';
        header("location: signin.php");
    } else if (strlen($password)>20 || strlen(($password) <5)){
        $_SESSION['error']='Password ต้องมีความยาว 6 ถึง 20 ตัวอักษร';
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
                        $_SESSION['error'] ="รหัสผ่านไม่ถูกต้อง";
                        $conn=null;
                        header("location: signin.php");
                    }
                }
                else {
                    $_SESSION['error'] ="Username ไม่ถูกต้อง";
                    $conn=null;
                    header("location: signin.php");
                }
            } else {
                $_SESSION['warning'] = "ไม่มีข้อมูลในระบบ <a href='index.php'>คลิ๊กที่นี่</a> เพื่อลงทะเบียนใหม่";
                $conn=null;
                header("location: signup.php");
            }


        } catch (PDOException $e) {
            $_SESSION['error'] = "พบข้อผิดพลาด ".$e->getMessage();
            header("location: signin.php");
        }

    }

}
else
{
    $_SESSION['error'] ="มีบางอย่างผิดพลาด หา signup ไม่เจอ";
    header("location: signin.php");
}

?>
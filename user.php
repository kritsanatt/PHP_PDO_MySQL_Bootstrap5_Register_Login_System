<?php
    session_start();
    require_once 'config/db.php';

    if (!isset($_SESSION['user_login']))
    {
        $_SESSION['error']="Please signin to the system";
        header("location:signin.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <?php
            if (isset($_SESSION['user_login']))
            {
                $user_id=$_SESSION['user_login'];
                $stmt=$conn->query("SELECT * FROM users WHERE id=$user_id");
                $stmt->execute();
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
            }
        ?>
        <h3 class="mt-4">Welcome <?php echo $row['firstname'].' '.$row['lastname']; $conn=null;?>. You are User.</h3>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
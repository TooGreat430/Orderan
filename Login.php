<?php
    include 'connect.php';
    include 'session.php';

    if($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['submitbutton'])){
    
        $id=$_POST['id'];
        $password=$_POST['password'];
        
        //sudah ada parameter required di html jadi sudah wajib diisi
        $query=mysqli_query($conn, "SELECT * FROM user WHERE UserEmail LIKE '".$id."' OR UserUsername LIKE '".$id."'");

        if(mysqli_num_rows($query)!=0){
            $user=$query->fetch_assoc();

            if(password_verify($password, $user['UserPassword']) OR $password==$user['UserPassword']){
                $_SESSION['userid']=$user['UserID'];
                $_SESSION['user']=$user;
                $_SESSION['RoleID']=$user['RoleID'];

                header("Location: session.php");
            }else{
                echo "Wrong Password! <br>";
            }
        }else{
            echo "User with the ID or Username does not exist! <br>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniEat</title>
</head>
<body>
    <header>
        <div id="logo">UniEat</div>
        <div id="akun">
            <a href="Register.php" id="guestbtn">Register</a>
            <a href="Login.php" id="guestbtn">Login</a>
        </div>
    </header>
    <div id="content">
        <a href="Guest.php" id="backbtn">Back</a>
        <!-- Halamannya kalau di back manual di browser akan berkali kali dan nge send ulang data jadi saya tambahkan back button -->
        <form action="" method="post" id="registform">
            <label for="id">Email / Username:</label>
            <input type="text" name="id" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <input type="submit" name="submitbutton" value="Submit">
        </form>
    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
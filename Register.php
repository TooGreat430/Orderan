<?php
    include 'connect.php';
    include 'session.php';

    if($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['submitbutton'])){
        $email=$_POST["email"];
        $username=$_POST["username"];
        $phone=$_POST["phone"];
        $password=$_POST["password"];
        $confpassword=$_POST["confpassword"];

        $query=mysqli_query($conn, "SELECT * FROM user WHERE UserEmail LIKE '".$email."' OR UserUsername LIKE '".$username."'");

        //Maaf kalau berantakan :)

        $flag=true;
        if(mysqli_num_rows($query)>0){
            echo "There is already a user with the email or username".'<br>';
        }else{
            //email tidak perlu di validasi krn sudah pakai input email dan semuanya sudah pakai required jadi wajib diisi
            //username validation
            if(strlen($username)<5){
                echo "username is too short".'<br>';
                $flag=false;
            }else if(preg_match('/[^a-zA-Z ]/', $username)){ //spasi dibolehkan atau tidak ya ?
                echo "username must only be alphabetical!".'<br>';
                $flag=false;
            }

            //phone num validation
            if(strlen($phone)<10){
                echo "phone number is too short".'<br>';
                $flag=false;
            }else if(preg_match('/[^+0-9]/', $phone)){
                echo "phone number must only consist of numbers!".'<br>';
                $flag=false;
            }

            //password validation
            if(strlen($password)<6){
                echo "password is too short".'<br>';
                $flag=false;
            }

            //confirm Password
            if(strcmp($password, $confpassword)!=0){
                echo "confirmed password does not match!".'<br>';
                $flag=false;
            }

            if($flag){
                $query=mysqli_query($conn, "INSERT INTO user(UserEmail, UserUsername, UserPassword, UserPhone, RoleID) VALUES ('".$email."', '".$username."', '".password_hash($password, PASSWORD_BCRYPT)."', '".$phone."', 2)");
                $query=mysqli_query($conn, "SELECT * FROM user WHERE UserEmail LIKE '".$email."'");
                $user=$query->fetch_assoc();

                $_SESSION['userid']=$user['UserID'];
                $_SESSION['user']=$user;
                $_SESSION['RoleID']=2;

                header("Location: session.php");
            }
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
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="phone">Phone:</label>
            <input type="text" name="phone" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <label for="confpassword">Confirm Password:</label>
            <input type="password" name="confpassword" required>
            <input type="submit" name="submitbutton" value="Submit">
        </form>
    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
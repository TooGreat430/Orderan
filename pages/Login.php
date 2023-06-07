<?php
    include 'connect.php';
    include 'session.php';

    // $_SESSION['formstep']=$_COOKIE['formstep'];

    if($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['loginsubmit'])){
        $id=$_POST['username'];
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
                $wrongpassflag=true;
            }
        }else{
            echo "User with the ID or Username does not exist! <br>";
            $notexistflag=true;
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['regissubmit'])){
        $email=$_POST["email"];
        $username=$_POST["username"];
        $phone=$_POST["phone"];
        $password=$_POST["password"];
        $confpassword=$_POST["confpassword"];

        $query=mysqli_query($conn, "SELECT * FROM user WHERE UserEmail LIKE '".$email."' OR UserUsername LIKE '".$username."'");

        $flag=true;
        if(mysqli_num_rows($query)>0){
            echo "There is already a user with the email or username".'<br>';
        }else{
            //email tidak perlu di validasi krn sudah pakai input email dan semuanya sudah pakai required jadi wajib diisi
            //username validation
            if(strlen($username)<5){
                echo "username is too short".'<br>';
                $flag=false;
                $shortflag=true;
            }else if(preg_match('/[^a-zA-Z ]/', $username)){ //spasi dibolehkan atau tidak ya ?
                echo "username must only be alphabetical!".'<br>';
                $flag=false;
                $alphaflag=true;
            }

            //phone num validation
            if(strlen($phone)<10){
                echo "phone number is too short".'<br>';
                $flag=false;
                $phoneflag=true;
            }else if(preg_match('/[^+0-9]/', $phone)){
                echo "phone number must only consist of numbers!".'<br>';
                $flag=false;
                $phonenumflag=true;
            }

            //password validation
            if(strlen($password)<6){
                echo "password is too short".'<br>';
                $flag=false;
                $passflag=true;
            }

            //confirm Password
            if(strcmp($password, $confpassword)!=0){
                echo "confirmed password does not match!".'<br>';
                $flag=false;
                $confpassflag=true;
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
    <script src="https://kit.fontawesome.com/23428a21db.js" crossorigin="anonymous"></script>
    <title>Orderan</title>
</head>
<body>
    <div id="content1">
        <div class="container">
            <div class="form-box">
                <a href="Guest.php" id="backbtnlogin" class="loginback">
                    <img src="../pictures/logo-revisi1.png" alt="">
                </a>
                <h1 id="title">Sign Up</h1>
                <form method="POST" id='loginregisform'>
                    <div class="input-group">
                        <div class="input-field" id="name">
                            <i class="fa-solid fa-user"></i>
                            <input name='username' type="text" placeholder="Name" required>
                        </div>
                        <div class="input-field" id="email">
                            <i class="fa-solid fa-envelope"></i>
                            <input name='email' type="email" placeholder="Email">
                        </div>
                        <div class="input-field" id="phone">
                            <i class="fa-solid fa-phone"></i>
                            <input name='phone' type="text" placeholder="Phone">
                        </div>
                        <div class="input-field" id="password">
                            <i class="fa-solid fa-lock"></i>
                            <input name='password' type="password" placeholder="Password" required>
                        </div>
                        <div class="input-field" id="confpass">
                            <i class="fa-solid fa-key"></i>
                            <input name='confpassword' type="password" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="btn-field">
                        <button type="button" name="regissubmit" value="regis" id="signup">Sign Up</button>
                        <button type="button" name="loginsubmit" value="login" id="login" class="disable">Log In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="loginstyle.css">
<script>
            let signup = document.getElementById("signup")
            let login = document.getElementById("login")
            let name = document.getElementById("name")
            let title = document.getElementById("title")
            let form = document.getElementById("loginregisform");
            let step = <?php echo json_encode($_SESSION['formstep'], JSON_HEX_TAG); ?>;

            if(step==1){
                email.style.display = "flex";
                phone.style.display = "flex";
                confpass.style.display = "flex";
                title.innerHTML = "Sign Up";
                login.classList.add("disable");
                signup.classList.remove("disable");
            }else if(step==2){
                email.style.display = "none";
                phone.style.display = "none";
                confpass.style.display = "none";
                title.innerHTML = "Log In";
                signup.classList.add("disable");
                login.classList.remove("disable");
            }
            
            signup.onclick = function(){
                step = 1;
                <?php $_SESSION['formstep']='1'?>
                document.cookie = "formstep= "+step;
                login.type='button';
                signup.type='button';
                if(login.classList.contains("disable")){
                    signup.type='submit';
                }else{
                    email.style.display = "flex";
                    phone.style.display = "flex";
                    confpass.style.display = "flex";
                    title.innerHTML = "Sign Up";
                    login.classList.add("disable");
                    signup.classList.remove("disable");
                }
            }

            login.onclick = function(){
                step = 2;
                <?php $_SESSION['formstep']='2'?>
                login.type='button';
                signup.type='button';
                if(login.classList.contains("disable")){
                    email.style.display = "none";
                    phone.style.display = "none";
                    confpass.style.display = "none";
                    title.innerHTML = "Log In";
                    signup.classList.add("disable");
                    login.classList.remove("disable");
                }else{
                    login.type='submit';
                }
            }

        </script>
</html>
<?php 
    require_once 'connect.php';
    session_start();

    //check role -> if incorrect redirect
    if(!(isset($_SESSION["userid"]) OR $_SESSION["userid"]==true OR $_SESSION["RoleID"]==2)){
        header("Location: Login.php");
        exit;
    }else{
        $query=mysqli_query($conn, "SELECT * FROM shop s JOIN user u WHERE s.OwnerID=u.UserID AND u.UserID LIKE '".$_SESSION['userid']."'");

        $_SESSION['yourshop']=$query->fetch_assoc();
    }

    if(isset($_POST['logout'])){
        session_destroy();
        header("Location: Login.php");
        exit;
    }

    if(isset($_POST['changebutton'])){

        $name=$_SESSION['user']['UserUsername'];
        if($_POST['name']!=''){
            if(strlen($_POST['name'])<5){
                echo "username is too short".'<br>';
            }else if(preg_match('/[^a-zA-Z ]/', $_POST['name'])){ //spasi dibolehkan atau tidak ya ?
                echo "username must only be alphabetical!".'<br>';
            }else{
                $name=$_POST['name'];
            }
        }

        $phone=$_SESSION['user']['UserPhone'];
        if($_POST['phone']!=''){
            if(strlen($phone)<10){
                echo "phone number is too short".'<br>';
            }else if(preg_match('/[^+0-9]/', $phone)){
                echo "phone number must only consist of numbers!".'<br>';
            }else{
                $phone=$_POST['phone'];
            }
        }

        $shopname=$_SESSION['yourshop']['ShopName'];
        if($_POST['shopname']!=''){
            if(strlen($_POST['shopname'])<5){
                echo "shop name is too short".'<br>';
            }else if(preg_match('/[^a-zA-Z ]/', $_POST['name'])){
                echo "shop name must only be alphabetical!".'<br>';
            }else{
                $shopname=$_POST['shopname'];
            }
        }

        $query=mysqli_query($conn, "UPDATE user SET UserUsername='".$name."', UserPhone='".$phone."' WHERE UserID LIKE '".$_SESSION['user']['UserID']."'");

        $_SESSION['user']=mysqli_query($conn, "SELECT * FROM user WHERE UserID LIKE '".$_SESSION['user']['UserID']."'")->fetch_assoc();

        $query=mysqli_query($conn, "UPDATE shop SET ShopName='".$shopname."' WHERE OwnerID LIKE '".$_SESSION['user']['UserID']."'");

        $_SESSION['yourshop']=mysqli_query($conn, "SELECT * FROM shop WHERE OwnerID LIKE '".$_SESSION['user']['UserID']."'")->fetch_assoc();
    }

    if(isset($_POST['confpass'])){
        if(password_verify($_POST['nowpass'], $_SESSION['user']['UserPassword'])){
            $newpass=$_POST['changepass'];
            $confnewpass=$_POST['confpass'];

            if(strlen($newpass)<6){
                echo "password is too short".'<br>';
                $_POST['changepass']='yes';
            }else if(strcmp($newpass, $confnewpass)!=0){
                echo "confirmed password does not match!".'<br>';
                $_POST['changepass']='yes';
            }else{
                mysqli_query($conn, "UPDATE user SET UserPassword='".password_hash($newpass, PASSWORD_BCRYPT)."' WHERE UserID LIKE '".$_SESSION['user']['UserID']."'");

                $_SESSION['user']=mysqli_query($conn, "SELECT * FROM user WHERE UserID LIKE '".$_SESSION['user']['UserID']."'")->fetch_assoc();

                unset($_POST['changepass']);

                echo 'Password changed<br>';
            }
        }else{
            $_POST['changepass']='yes';
            echo 'Wrong Password!<br>';
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
             
        <div id="profile">
            <?php
                echo $_SESSION['yourshop']['ShopName'] ;
            ?>
            (<?php 
                echo $_SESSION['user']['UserUsername']
            ?>)
            <div id="dropdownmenu">
                <form action="" method="post">
                    <a href="Tenant.php">Home</a>
                    <a href="Completedorder.php">Completed Orders</a>
                    <input type="submit" value="Manage Items" name="manageitem">
                    <a href="Tenantsetting.php">Settings</a>
                    <input type="submit" value="Logout" name="logout">
                </form>
            </div>
        </div>
    </header>
    <div id="content">

        <a href="Tenant.php" id="backbtn">Back</a>
        <!-- Halamannya kalau di back manual di browser akan berkali kali dan nge send ulang data jadi saya tambahkan back button -->

        <form action="" method="post" id="registform">
            <label for="name">Name:</label>
            <input type="text" name="name" 
            <?php
                echo 'placeholder="'.$_SESSION['user']['UserUsername'].'"';
            ?>
            >
            <label for="phone">Phone:</label>
            <input type="text" name="phone" 
            <?php
                echo 'placeholder='.$_SESSION['user']['UserPhone'];
            ?>
            >
            <label for="shopname">Shop Name:</label>
            <input type="text" name="shopname" placeholder="<?php echo $_SESSION['yourshop']['ShopName']; ?>">
            <label>Email:</label>
            <?php
                echo '<div id="emaildisplay">'.$_SESSION['user']['UserEmail'].'</div>';
            ?>
            <div id="changeandpass">
                <button type="submit" name="changepass" value="Change Password">Change Password</button>
                <input type="submit" name="changebutton" value="Change">
            </div>
        </form>

        <?php
            if(isset($_POST['changepass'])){
                echo '<form id="registform" class="overlay" method="post">';
                echo '<label for="nowpass">Current Password:</label>
                <input type="password" name="nowpass" required>';
                echo '<label for="changepasspass">New Password:</label>
                <input type="password" name="changepass" required>';
                echo '<label for="confpass">Confirm New Password:</label>
                <input type="password" name="confpass" required>';
                echo '<button type="submit" name="changepassconf" value="Change Password" id="changepassconf">Change Password</button>';
                echo '</form>';
                echo '<form>';
                echo '<input type="submit" name="cancelbutton" value="Cancel" id="cancelbutton">';
                echo '</form>';
            }
        ?>
    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
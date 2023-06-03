<?php 
    require_once 'connect.php';
    session_start();

    //check role -> if incorrect redirect
    if(!(isset($_SESSION["userid"]) OR $_SESSION["userid"]==true OR $_SESSION["RoleID"]==2)){
        header("Location: Login.php");
        exit;
    }

    if(isset($_POST['logout'])){
        session_destroy();
        header("Location: Login.php");
        exit;
    }

    if(isset($_POST['confirm'])){
        if($_POST['score']>5 || $_POST['score']<1){
            echo 'Please input score from 1 to 5 only! (can be decimals)';
            $_POST['rate']=$_SESSION['shoptorate'];
        }else{
            $query=mysqli_query($conn, "UPDATE orderheader SET RatingScore='".$_POST['score']."', RatingComment='".$_POST['comment']."' WHERE OrderID='".$_SESSION['shoptorate']."'");
        }
    }

    if(isset($_POST['details'])){
        $_SESSION['ordertosee']=$_POST['details'];
        header("Location: cusdetailhistory.php");
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
        <form id="searchbar" method="post">
            <input type="text" id="searchbar" name='searchbar'>
            <input type="submit" name="search" value="search"></input>
        </form>
        
        <div id="keranjang">
            <a href="Keranjang.php" id="keranjanglink">Keranjang</a>
            <?php
                $adaisi=false;
                foreach($_SESSION['cart'] as $x=>$val){
                    if($val['itemid']!=0){
                        $adaisi=true;
                        break;
                    }
                }

                if($adaisi){
                    echo'<div id="buletan"></div>';
                }
            ?>
        </div>
        
        <div id="profile">
            <?php
                echo $_SESSION['user']['UserUsername'];
            ?>
            <div id="dropdownmenu">
                <form method="post">
                    <a href="customer.php">Home</a>
                    <a href="cushistory.php">History</a>
                    <a href="customersetting.php">Settings</a>
                    <input type="submit" value="Logout" name="logout">
                </form>
            </div>
        </div>
    </header>
    <div id="content">

        <a href="customer.php" id="backbtn">Back</a>
        <!-- Halamannya kalau di back manual di browser akan berkali kali dan nge send ulang data jadi saya tambahkan back button -->
        
        <h3>Previous Orders</h3>
        <div id="history">
            <?php
                $query=mysqli_query($conn, "SELECT * FROM orderheader oh JOIN shop s ON oh.ShopID=s.ShopID WHERE CustomerID LIKE '".$_SESSION['userid']."' ORDER BY OrderID DESC");

                
                while($d=$query->fetch_assoc()){
                    $datetime=mysqli_query($conn, "SELECT DATE_FORMAT(OrderDate, '%d %M %Y %H:%i') AS Dates FROM orderheader WHERE OrderID LIKE '".$d['OrderID']."'")->fetch_assoc()['Dates'];

                    $total=mysqli_query($conn, "SELECT SUM(Quantity*ItemPrice) as total FROM orderdetail od JOIN item i ON od.ItemID=i.ItemID WHERE od.OrderID LIKE '".$d['OrderID']."'")->fetch_assoc()['total'];

                    echo '<div id="shopshistory">';

                    if(isset($_POST['rate'])){
                        $_SESSION['shoptorate']=$_POST['rate'];
                        if($_POST['rate']==$d['OrderID']){
                            echo '<div id="rateoverlay">';
                            echo '<form method="post">';
                            echo '<div>';
                            echo '<label for="score">Input Your Ratings: </label>';
                            echo '<input type="text" name="score" placeholder="(1-5)">';
                            echo '</div>';
                            echo '<div>';
                            echo '<label for="comment">Comment: </label>';
                            echo '<textarea name="comment" id="comment">';
                            echo '</textarea>';
                            echo '</div>';
                            echo '<div>';
                            echo '<input type="submit" name="confirm" value="Rate">';
                            echo '<input type="submit" name="cancel" value="Cancel">';
                            echo '</div>';
                            echo '</form>';
                            echo '</div>';
                        }
                    }

                    echo '<div id="shoppiccontainerhis"><img id="shoppichis" src="shoppictures/'.$d['ShopID'].'.png" alt="LOGO">';
                    echo '</div>';
                    echo '<div id="historydesc">';
                    echo '<h2>'.$d['ShopName'].'</h2>';
                    echo '<p>'.$datetime.'</p>';

                    if($d['OrderStatus']==1){
                        echo '<p>The shop is preparing your order</p>';
                    }else if($d['OrderStatus']==0){
                        echo '<p>The order is cancelled</p>';
                    }else if($d['OrderStatus']==2){
                        echo '<p>Done</p>';
                    }
                    
                    echo '<div id="pricenrate">'.$total;
                    if($d['OrderStatus']==2 AND $d['RatingScore']==0){
                        echo '<form method="post">';
                        echo '<button type="submit" name="rate" value="'.$d['OrderID'].'">Rate</button>';
                        echo '</form>';
                    }else if($d['OrderStatus']==2 AND $d['RatingScore']!=0){
                        echo '<span>Rated: '.$d['RatingScore'];
                        echo '</span>';
                    }
                    echo '<form method="post">';
                    echo '<button type="submit" name="details" value="'.$d['OrderID'].'">Details</button>';
                    echo '</form>';
                    echo '</div>';

                    echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>

    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
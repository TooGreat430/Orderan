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

        <a href="cushistory.php" id="backbtn">Back</a>
        <!-- Halamannya kalau di back manual di browser akan berkali kali dan nge send ulang data jadi saya tambahkan back button -->
        
        <h3>Previous Orders Detail</h3>
        <div id="history">
            <?php
                $d=mysqli_query($conn, "SELECT * FROM orderheader oh JOIN shop s ON oh.ShopID=s.ShopID WHERE OrderID LIKE '".$_SESSION['ordertosee']."'")->fetch_assoc();

                $datetime=mysqli_query($conn, "SELECT DATE_FORMAT(OrderDate, '%d %M %Y %H:%i') AS Dates FROM orderheader WHERE OrderID LIKE '".$d['OrderID']."'")->fetch_assoc()['Dates'];

                $total=mysqli_query($conn, "SELECT SUM(Quantity*ItemPrice) as total FROM orderdetail od JOIN item i ON od.ItemID=i.ItemID WHERE od.OrderID LIKE '".$d['OrderID']."'")->fetch_assoc()['total'];

                echo '<div id="shopshistory">';
                
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
                
                echo '</div>';
                echo '</div>';
                
                echo '<div id="seperator"></div>';

                $query=mysqli_query($conn, "SELECT * FROM orderdetail od JOIN item i ON od.ItemID=i.ItemID WHERE OrderID LIKE '".$_SESSION['ordertosee']."'");
                
                while($d=$query->fetch_assoc()){
                    $totalitemprice=mysqli_query($conn, "SELECT (ItemPrice*Quantity) as totalprice FROM orderdetail od JOIN item i ON od.ItemID=i.ItemID WHERE od.OrderID LIKE '".$_SESSION['ordertosee']."' AND i.ItemID LIKE '".$d['ItemID']."'")->fetch_assoc()['totalprice'];
                    
                    echo '<div id="orderdetailcontainer">';
                    echo '<div id="odcontainertitle">';
                    echo '<span>'.$d['ItemName'].' x '.$d['Quantity'].'</span>';
                    echo '<span>'.$totalitemprice.'</span>';
                    echo '</div>';
                    echo '<div id="notes">'.$d['Note'].'</div>';
                    echo '</div>';
                }

                $place=mysqli_query($conn, "SELECT * FROM orderheader WHERE OrderID LIKE '".$_SESSION['ordertosee']."'")->fetch_assoc();

                echo '<div id="seperator"></div>';
                
                echo '<div id="viewalamat">Place : ';
                echo '<div id="alamatbox">'.$place['Alamat'].'</div>';
                echo '</div>';

                echo '<div id="seperator"></div>';
                
                echo '<div id="oddetailfoot">';
                echo '<span>Total:</span>';
                echo '<span>'.$total.'</span>';
                echo '</div>';
            ?>
        </div>

    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
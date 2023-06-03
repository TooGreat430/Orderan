<?php 
    require_once 'connect.php';

    session_start();

    date_default_timezone_set('Asia/Bangkok');

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

    if(!isset($_SESSION['cart'])){
        $_SESSION['cart']=array(array("itemid"=>'0', "quantity"=>'0'));
    }

    // echo $_SESSION['ShopIDKeranjang'].'<br>';

    if(isset($_GET['food'])){
        if(!isset($_SESSION['ShopIDKeranjang'])){
            $_SESSION['ShopIDKeranjang']=$_SESSION['ShopID'];
        }
        if($_SESSION['ShopID']==$_SESSION['ShopIDKeranjang']){
            $str='qtyinp'.$_GET['food'];
            if($_GET[$str]!='' AND $_GET[$str]!=0){

                //check if its already in the cart
                foreach($_SESSION['cart'] as $x => $val){
                    if($val['itemid']==$_GET['food']){
                        $index=$x;
                    }
                }

                if(isset($index)){
                    unset($_SESSION['cart'][$index]);
                }

                $item=array("itemid"=>$_GET['food'], "quantity"=>$_GET[$str]);
                array_push($_SESSION['cart'], $item);
            }else{
                foreach($_SESSION['cart'] as $x => $val){
                    if($val['itemid']==$_GET['food']){
                        $index=$x;
                    }
                }

                unset($_SESSION['cart'][$index]);
            }
        }else{
            echo 'There are already items in the cart from another shop, would you like to empty the cart?';
            echo '<form method="get" id="alrcart">';
            echo '<button type="submit" name="confirmation" value="1">Yes</button>';
            echo '<button type="submit" name="confirmation" value="0">No</button>';
            echo '</form>';
            //need to make this an overlay, maybe later
        }
    }

    // if(isset($_SESSION['cart'])){
    //     foreach($_SESSION['cart'] as $x){
    //         echo $x['itemid'].' '.$x['quantity'].'<br>';
    //     }
    // }
    
    if(isset($_GET['confirmation'])){
        switch($_GET['confirmation']){
            case 1:
                unset($_SESSION['ShopIDKeranjang']);
                foreach($_SESSION['cart'] as $x=>$val){
                    unset($_SESSION['cart'], $val);
                }
                header("Location: Viewshopcustomer.php");
                break;
            case 0:
                //kenapa saya bikin switch case ya?
                break;
        }
    }
    
    if(isset($_POST['orderbtn'])){
        $query=mysqli_query($conn, "INSERT INTO OrderHeader(ShopID, CustomerID, Alamat, OrderDate, OrderStatus) VALUES ('".$_SESSION['ShopIDKeranjang']."', '".$_SESSION['user']['UserID']."', '".$_POST['alamatinp']."', '".date('Y-m-d H:i:s')."', 1)");

        $orderid=mysqli_query($conn, "SELECT * FROM orderheader ORDER BY OrderID DESC LIMIT 1")->fetch_assoc();

        foreach($_SESSION['cart'] as $x){
            $query=mysqli_query($conn, "INSERT INTO orderdetail(OrderID, ItemID, Quantity, Note) VALUES('".$orderid['OrderID']."', '".$x['itemid']."', '".$x['quantity']."', '')");
            unset($_SESSION['cart'], $val);
        }

        header("Location: keranjang.php");
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

        <?php

            echo '<div id="cartcontainer">';
            
            if(isset($_SESSION['ShopIDKeranjang'])){
                $query=mysqli_query($conn, "SELECT * FROM shop s WHERE ShopID LIKE '".$_SESSION['ShopIDKeranjang']."'")->fetch_assoc();

                echo '<div id="cartshop">';
                echo 'Shop : '.$query['ShopName'];
                echo '</div>';
            }

            echo '<div id="usercontainer">';
            echo 'Delivered to : '.$_SESSION['user']['UserUsername'].' ('.$_SESSION['user']['UserPhone'].')';
            echo '</div>';

            if($adaisi){
                $_SESSION['totalkeseluruhan']=0;
                foreach($_SESSION["cart"] as $x){
                    if($x['itemid']!=0){
                        $query=mysqli_query($conn, "SELECT * FROM shop s JOIN item i ON s.ShopID=i.ShopID WHERE s.ShopID LIKE '".$_SESSION['ShopIDKeranjang']."' AND ItemID LIKE '".$x['itemid']."'")->fetch_assoc();

                        echo '<div id="cartitem">';

                        echo '<div id="foodpiccontainer">';
                        echo '<img id="foodpic" src=foodpictures/'.$query['ItemID'].'.png alt="FoodPic">';
                        echo '</div>';

                        echo '<div id="foodstatcontainer">';

                        echo '<div id="itemname">';
                        echo $query['ItemName'];
                        echo '</div>';

                        echo '<div id="itemdesc">';
                        echo $query['ItemPrice'].' x '.$x['quantity'];
                        echo '</div>';

                        echo '<form method="get" id="hargadanbtn">';

                        echo '<div id="itemprice">';
                        echo 'Total: '.$query['ItemPrice']*$x['quantity'];
                        echo '</div>';

                        echo '<input type="text" placeholder="Quantity (0 to remove)" name="qtyinp'.$query['ItemID'].'">';

                        echo '<button type="submit" name="food" value="'.$query['ItemID'].'">Update</button>';

                        $_SESSION['totalkeseluruhan']+=$query['ItemPrice']*$x['quantity'];

                        echo '</form>';

                        echo '</div>'; //untuk foodstatcontainer
                        
                        echo '</div>'; //untuk menuitem
                    }
                }
                echo '<div id="cartfoot">';
                echo '<div id="hargatotal">Keseluruhan: ';
                echo '</div>';
                echo $_SESSION['totalkeseluruhan'];
                echo '</div>';
                echo '<form method="post" id="pushorderbtn">';
                echo '<div id="alamat" method="post">';
                echo 'Place : ';
                echo '<input type="text" id="alamattext" name="alamatinp" required>';
                echo '</div>';
                echo '<input type="submit" name="orderbtn" value="Order">';
                echo '</form>';
            }else{
                if(isset($_SESSION['ShopIDKeranjang'])){
                    unset($_SESSION['ShopIDKeranjang']);
                    header("Location: Keranjang.php");
                }
                echo '<p>Keranjang belum ada isinya...</p>';
            }
            echo '</div>';
        ?>

    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
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

            $shop=mysqli_query($conn, "SELECT * FROM shop WHERE ShopID LIKE '".$_SESSION['ShopID']."'")->fetch_assoc();
            
            $category=mysqli_query($conn, "SELECT * FROM shop s
                JOIN shop_categories sc ON s.ShopID=sc.ShopID
                JOIN category c ON sc.CategoryID=c.CategoryID
                WHERE s.ShopID=".$shop['ShopID']);
            
            echo '<div id="menu">';

            //warning: code dibawah ini bikin mual
            echo '<div id="shops">
                <div id="shoppiccontainer">
                <img id="shoppic" src="shoppictures/'.$shop['ShopID'].'.png" alt="LOGO">
                </div>
                <div id="shopdesc">
                <div id="shopname">'.$shop['ShopName'].'</div>
                <div id="categorycontainer">';
            while($cat = $category->fetch_assoc()){
                echo '<div id="categories">';
                echo $cat['CategoryName'];
                echo '</div>';
            }
            echo'  </div>
                </div>
            </div>';

            //menunya
            $menu=mysqli_query($conn, "SELECT * FROM shop s JOIN item i ON s.ShopID=i.ShopID WHERE s.ShopID LIKE '".$shop['ShopID']."'");

            if(mysqli_num_rows($menu)==0){
                echo '<div id="notavailable">';
                echo 'Toko belum menyiapkan menu apa-apa :/';
                echo '</div>';
            }

            while($d=mysqli_fetch_array($menu)){
                echo '<div id="menuitem">';

                echo '<div id="foodpiccontainer">';
                echo '<img id="foodpic" src=foodpictures/'.$d['ItemID'].'.png alt="FoodPic">';
                echo '</div>';

                echo '<div id="foodstatcontainer">';

                echo '<div id="itemname">';
                echo $d['ItemName'];
                echo '</div>';

                echo '<div id="itemdesc">';
                echo $d['ItemDescription'];
                echo '</div>';

                echo '<form method="get" id="hargadanbtn">';

                echo '<div id="itemprice">';
                echo $d['ItemPrice'];
                echo '</div>';

                echo '<input type="text" placeholder="Quantity..." name="qtyinp'.$d['ItemID'].'">';

                echo '<button type="submit" name="food" value="'.$d['ItemID'].'">Add To Cart</button>';

                echo '</form>';

                echo '</div>'; //untuk foodstatcontainer

                echo '</div>'; //untuk menuitem
            }

            echo '</div>' //untuk div menu
        ?>
    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
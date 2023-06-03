<?php 
    require_once 'connect.php';

    session_start();

    if(isset($_GET['food'])){
        header('Location: Login.php');
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

        <div id="akun">
            <a href="Register.php" id="guestbtn">Register</a>
            <a href="Login.php" id="guestbtn">Login</a>
        </div>
    </header>
    <div id="content">

        <a href="Guest.php" id="backbtn">Back</a>
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
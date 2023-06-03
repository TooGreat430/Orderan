<?php 
    require_once 'connect.php';
    session_start();

    //check role -> if incorrect redirect
    if(!(isset($_SESSION["userid"]) OR $_SESSION["userid"]==true OR $_SESSION["RoleID"]==4)){
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

        <div id="profile">
            <?php
            echo $_SESSION['user']['UserUsername'];
            ?>
            <div id="dropdownmenu">
                <form action="" method="post">
                    <a href="Admin.php">Home</a>
                    <a href="">Manage Admin</a>
                    <a href="Managecategories.php">Manage Categories</a>
                    <input type="submit" value="Logout" name="logout">
                </form>
            </div>
        </div>
    </header>
    <div id="content">
        <ul id="category">
            <form action="" id="catform" method="post">
                <li><input type="submit" name="categorybutton" value="all"></input></li>
            <?php
                $category=mysqli_query($conn, "SELECT * FROM category");
                while($c=mysqli_fetch_array($category)){
                    echo '<li><input type="submit" name="categorybutton" value="'.$c['CategoryName'].'"></input></li>';
                }
            ?>
            </form>
        </ul>
        <?php
        $query="SELECT * FROM shop";

        if(isset($_POST['categorybutton'])){
            if($_POST['categorybutton']=='all'){
                $query="SELECT * FROM shop";
            }else{
                $query="SELECT * FROM shop s
                JOIN shop_categories sc ON s.ShopID=sc.ShopID
                JOIN category c ON sc.CategoryID=c.CategoryID
                WHERE c.CategoryName LIKE '".$_POST['categorybutton']."'";
            }
        }

        if(isset($_POST['search']) AND $_POST['searchbar']!=''){
            $query="SELECT * FROM shop s WHERE LOWER(s.ShopName) LIKE '".strtolower($_POST['searchbar'])."%'";
        }

        $data=mysqli_query($conn, $query);
        while($d = mysqli_fetch_array($data)){
                $category=mysqli_query($conn, "SELECT * FROM shop s
                JOIN shop_categories sc ON s.ShopID=sc.ShopID
                JOIN category c ON sc.CategoryID=c.CategoryID
                WHERE s.ShopID=".$d['ShopID']);

                echo '<div id="shops">'.
                '<div id="shoppiccontainer"><img id="shoppic" src="shoppictures/'.$d['ShopID'].'.png" alt="LOGO"></div>';
                echo '<div id="shopdesc">';
                echo '<div id="shopname">'.$d['ShopName'].'</div>';
                echo '<div id="categorycontainer">';
                while($cat = $category->fetch_assoc()){
                    echo '<div id="categories">';
                    echo $cat['CategoryName'];
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>' ;
        }
        ?>

    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
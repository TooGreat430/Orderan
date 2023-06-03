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

    if(isset($_POST['addnewcatbtn'])){
        $query=mysqli_query($conn, "INSERT INTO category (CategoryName) VALUES ('".$_POST['addnewcat']."')");
    }

    if(isset($_POST['changecatname'])){
        $catid=$_SESSION['catid'];
        $query=mysqli_query($conn, "UPDATE category SET CategoryName='".$_POST['changecatname']."' WHERE CategoryID = '".$catid."'");
    }

    if(isset($_POST['delete'])){
        $_SESSION['catid']=$_POST['delete'];
        echo 'Are you sure you want to delete this category?';
        echo '<form method="post">';
        echo '<input type="submit" name="yes" value="Yes">';
        echo '<input type="submit" name="no" value="No">';
        echo '</form>';
    }

    if(isset($_POST['yes'])){
        $catid=$_SESSION['catid'];
        $query=mysqli_query($conn, "DELETE FROM category WHERE CategoryID LIKE '".$catid."'");
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
                    <a href="">Manage Categories</a>
                    <input type="submit" value="Logout" name="logout">
                </form>
            </div>
        </div>
    </header>

    <div id="content">
        <a href="Admin.php" id="backbtn">Back</a>
        <!-- Halamannya kalau di back manual di browser akan berkali kali dan nge send ulang data jadi saya tambahkan back button -->

        <div id="categoriescontent">
            <form method="post">
                <button type="submit" name="newcat">New Category</button>
            </form>
            <?php
                $query=mysqli_query($conn, "SELECT * FROM category");
                while($d=$query->fetch_assoc()){
                    echo '<div id="catlistcontainer">';
                    echo '<p>'.$d['CategoryName'].'</p>';
                    echo '<form method="post">';
                    echo '<button type="submit" name="edit" value='.$d['CategoryID'].'>Edit</button>';
                    echo '<button type="submit" name="delete" value='.$d['CategoryID'].'>Delete</button>';
                    echo '</form>';
                    echo '</div>';
                }

                if(isset($_POST['newcat'])){
                    echo '<form id="newcat" method="post">';
                    echo '<div>';
                    echo '<label for="newcat">Category : </label>';
                    echo '<input type="text" name="addnewcat" required>';
                    echo '</div>';
                    echo '<div id="newcatbtncont">';
                    echo '<input type="submit" name="addnewcatbtn" value="Add">';
                    echo '</form>';
                    echo '<form>';
                    echo '<input type="submit" name="cancel" value="Cancel">';
                    echo '</form>';
                    echo '</div>';
                }

                if(isset($_POST['edit'])){
                    $_SESSION['catid']=$_POST['edit'];
                    $query=mysqli_query($conn, "SELECT * FROM category WHERE CategoryID LIKE '".$_SESSION['catid']."'")->fetch_assoc();
                    echo '<form id="changecat" method="post">';
                    echo '<div>';
                    echo '<label for="newcat">New Category Name : </label>';
                    echo '<input type="text" name="changecatname" placeholder="'.$query['CategoryName'].'" required>';
                    echo '</div>';
                    echo '<div id="newcatbtncont">';
                    echo '<input type="submit" name="changecat" value="Change">';
                    echo '</form>';
                    echo '<form>';
                    echo '<input type="submit" name="cancel" value="Cancel">';
                    echo '</form>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
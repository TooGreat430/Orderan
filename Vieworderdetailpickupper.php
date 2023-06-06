<?php 
    require_once 'connect.php';
    session_start();

    //check role -> if incorrect redirect
    if(!(isset($_SESSION["userid"]) OR $_SESSION["userid"]==true OR $_SESSION["RoleID"]!=5)){
        header("Location: Login.php");
        exit;
    }

    if(isset($_POST['logout'])){
        session_destroy();
        session_start();
        $_SESSION['formstep']='2';
        header("Location: Login.php");
        exit;
    }

    if(isset($_POST['finishorder'])){
        $query=mysqli_query($conn, "UPDATE orderheader SET OrderStatus=3 WHERE OrderID LIKE '".$_SESSION['vieworderid']."'");

        unset($_SESSION['vieworderid']);
        header("Location: Pickupper.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orderan</title>
</head>
<body>
    <header>
        <div id="logo">Orderan</div>

        <div id="profile">
            <?php
            echo $_SESSION['user']['UserUsername'];
            ?>
            <div id="dropdownmenu">
                <form action="" method="post">
                    <input type="submit" value="Home" name="home">
                    <input type="submit" value="Completed Orders" name="completedorder">
                    <input type="submit" value="Manage Items" name="manageitem">
                    <input type="submit" value="Settings" name="settings">
                    <input type="submit" value="Logout" name="logout">
                </form>
            </div>
        </div>
    </header>
    <div id="content">

        <a href="pickupper.php" id="backbtn">Back</a>
        <!-- Halamannya kalau di back manual di browser akan berkali kali dan nge send ulang data jadi saya tambahkan back button -->

        <div id="orderdetail">

        <?php
        $query=mysqli_query($conn, "SELECT *, ItemPrice*Quantity as totalitem FROM orderdetail od JOIN item i ON od.ItemID=i.ItemID WHERE od.OrderID LIKE '".$_SESSION['vieworderid']."'");

        $totalprice=mysqli_query($conn, "SELECT SUM(ItemPrice*Quantity) as totalprice FROM orderdetail od JOIN item i ON od.ItemID=i.ItemID WHERE od.OrderID LIKE '".$_SESSION['vieworderid']."'")->fetch_assoc()['totalprice'];

        $personandplace=mysqli_query($conn, "SELECT * FROM orderheader oh JOIN user u ON oh.CustomerID=u.UserID WHERE oh.OrderID LIKE '".$_SESSION['vieworderid']."'")->fetch_assoc();

        $datetime=mysqli_query($conn, "SELECT DATE_FORMAT(OrderDate, '%d %M %Y %H:%i') AS Dates FROM orderheader WHERE OrderID LIKE '".$_SESSION['vieworderid']."'")->fetch_assoc()['Dates'];
        
        echo '<p>Delivered to : '.$personandplace['UserUsername'].' ('.$personandplace['UserPhone'].')</p>';

        echo '<div id="viewalamat">Place : ';
        echo '<div id="alamatbox">'.$personandplace['Alamat'].'</div>';
        echo '</div>';

        echo '<div id="ordersum"><h2>Order Summary</h2><p>'.$datetime.'</p></div>';

        while($d=$query->fetch_assoc()){
            echo '<div id="orderdetailcontainer">';
            echo '<div id="odcontainertitle">';
            echo '<span>'.$d['ItemName'].' x '.$d['Quantity'].'</span>';
            echo '<span>'.$d['totalitem'].'</span>';
            echo '</div>';
            echo '<div id="notes">'.$d['Note'].'</div>';
            echo '</div>';
        }

        echo '<div id="oddetailfoot">';
        echo '<span>Total:</span>';
        echo '<span>'.$totalprice.'</span>';
        echo '</div>';
        echo '<form id="finishbtn" method="post">';
        echo '<input type="submit" name="finishorder" value="Finish Order">';
        echo '</form>';
        ?>

        </div>

    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
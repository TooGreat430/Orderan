<?php 
    require_once 'connect.php';
    session_start();

    //check role -> if incorrect redirect
    if(!(isset($_SESSION["userid"]) OR $_SESSION["userid"]==true OR $_SESSION["RoleID"]!=3)){
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

    if(isset($_GET['editbtn'])){
        $_SESSION['itemid']=$_GET['editbtn'];
        $query=mysqli_query($conn, "SELECT * FROM item i WHERE i.ItemID LIKE '".$_SESSION['itemid']."'");
        $selecteditem=$query->fetch_assoc();
    }

    $tooshort=False;
    $desctooshort=False;
    $pricenotnumber=False;
    if(isset($_POST['confirmbtn'])){

        $newitemname=$selecteditem['ItemName'];
        if($_POST['itemname']!=''){
            if(strlen($_POST['itemname'])<5){
                $tooshort=True;
            }else{
                $newitemname=$_POST['itemname'];
            }
        }
        
        $newitemdesc=$selecteditem['ItemDescription'];
        if($_POST['itemdesc']!=''){
            if(strlen($_POST['itemdesc'])<20){
                $desctooshort=True;
            }else{
                $newitemdesc=$_POST['itemdesc'];
            }
        }

        $newitemprice=$selecteditem['ItemPrice'];
        if($_POST['itemprice']!=''){
            if(preg_match('/[^+0-9]/', $_POST['itemprice'])){
                $pricenotnumber=True;
            }else{
                $newitemprice=$_POST['itemprice'];
            }
        }

        $query=mysqli_query($conn, "UPDATE item SET ItemName='".$newitemname."', ItemDescription='".$newitemdesc."', ItemPrice='".$newitemprice."' WHERE ItemID LIKE '".$selecteditem['ItemID']."'");
        $query=mysqli_query($conn, "SELECT * FROM item i WHERE i.ItemID LIKE '".$_SESSION['itemid']."'");
        $selecteditem=$query->fetch_assoc();
    }

    if(isset($_GET['editbtn'])){
        echo '<div id="changemenucont">';
        echo '<form id="registform" class="overlayitem" method="post">';
        echo '<div id="shoppiccontainer"><img id="shoppic" src="foodpictures/'.$selecteditem['ItemID'].'.png" alt="LOGO"></div>';
        echo '<label for="itemname">Item Name:</label>
        <input type="text" name="itemname" placeholder="'.$selecteditem['ItemName'].'">';
        if($tooshort){
            echo 'Item Name too short! (min 5 chars)';    
        }
        echo '<label for="itemdesc">Description:</label>
        <textarea id="itemdesctextarea" name="itemdesc" placeholder="'.$selecteditem['ItemDescription'].'"></textarea>';
        if($desctooshort){
            echo 'Item Description too short (min 20 chars)';
        }
        echo '<label for="itemprice">Item Price:</label>
        <input type="text" name="itemprice" placeholder="'.$selecteditem['ItemPrice'].'">';
        if($pricenotnumber){
            echo "Please put item price in numbers only!";     
        }
        echo '<button type="submit" name="confirmbtn" value="confirm" id="edititemconfirm">Confirm</button>';
        echo '</form>';
        echo '<form id="formcancelbtn" class="manageitemcancel" method="post">';
        echo '<input type="submit" name="cancelbutton" value="Cancel">';
        echo '<input type="submit" name="removebutton" value="Remove">';
        echo '</form>';
        echo '</div>';
    }

    if(isset($_POST['cancelbutton'])){
        unset($_GET['editbtn']);
        header("Location: Manageitems.php");
    }

    if(isset($_POST['removebutton'])){
        echo '<div id="changemenucont">';
        echo '<div class="confirmationoverlay">';
        echo '<p> Are you sure you want to delete ';
        echo $selecteditem['ItemName'];
        echo ' ?</p>';
        echo '<form id="confirmationform" class="manageitemcancel" method="post">';
        echo '<input type="submit" name="confirmbtn" value="Cancel">';
        echo '<input type="submit" name="confirmbtn" value="Remove">';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }

    if(@$_POST['confirmbtn'] == 'Cancel'){
        unset($_POST['cancelbutton']);
        if(isset($selecteditem['ItemID'])){
            header("Location: Manageitems.php?editbtn=".$selecteditem['ItemID']);
        }
    }else if(@$_POST['confirmbtn'] == 'Remove'){
        $query=mysqli_query($conn, "UPDATE item SET ItemAvailability='0' WHERE ItemID LIKE '".$selecteditem['ItemID']."'");
        unset($_GET['editbtn']);
        unset($_POST['cancelbutton']);
        header("Location: Manageitems.php");
    }

    if(isset($_POST['additem'])){
        
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
        <div class="additemcont">
            <form action="POST">
                <input type="submit" name="additem" value="Add Item">
            </form>
        </div>
        <?php
        $query="SELECT * FROM item i WHERE i.ShopID LIKE '".$_SESSION['yourshop']['ShopID']."' AND i.ItemAvailability='1'";

        if(isset($_POST['search']) AND $_POST['searchbar']!=''){
            $query=$query."AND LOWER(i.ItemName) LIKE '".strtolower($_POST['searchbar'])."%'";
        }

        $query=mysqli_query($conn, $query);

        echo '<div id="gridview">';
        if(mysqli_num_rows($query)!=0){
            while ($d=$query->fetch_assoc()){
                echo '<div id="itemlistgrid">';
                echo '<div id="shoppiccontainer"><img id="shoppic" src="foodpictures/'.$d['ItemID'].'.png" alt="LOGO"></div>';
                echo '<h1>'.$d['ItemName'].'</h1>';
                if($d['ItemDescription']!=null){
                    echo '<p>'.$d['ItemDescription'].'</p>';
                }else{
                    echo '<p>Tanpa deskripsi</p>';
                }
                echo '<p>Price: '.$d['ItemPrice'].'</p>';
                echo '<form method="GET">';
                echo '<button name="editbtn" value="'.$d['ItemID'].'">';
                echo 'Edit Item</button>';
                echo '</form>';
                echo '</div>';

                unset($ordernum);
                unset($total);
                unset($datetime);
            }
        }else{
            if(isset($_POST['search'])){
                echo '<h2>There is no item(s) found with that name</h2>';
            }else{
                echo '<h2>There is no items</h2>';
            }
        }
        echo '</div>';
        ?>

    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
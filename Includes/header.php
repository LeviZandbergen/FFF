<?php
include('DBconfig.php');
?>
<html>

<div id="upperHead">
    <img onclick="location.href='index.php'" style="height: 100%; cursor: pointer" src="../FFF/Images/Logo.png">
    <?php
    if (isset($_SESSION["ID"]) && $_SESSION["STATUS"] === 1) {
        ?>
        <a style="float: right" href="uitloggen.php">Logout</a>
        <?php
    } else {
        ?>
        <a style="float: right" href="login.php">Login</a>
        <?php
    }
    ?>

</div>
<div id="menu">
    <div id="emptyContainer">

    </div>
    <button class="menuButton" onclick="location.href='index.php'">Home</button>
    <button class="menuButton" onclick="location.href='articles.php'">Artikelen</button>
    <button class="menuButton" onclick="location.href='shoppingCart.php'">Winkelmandje</button>
    <button class="menuButton" onclick="location.href='contact.php'">Contact</button>
</div>
</html>
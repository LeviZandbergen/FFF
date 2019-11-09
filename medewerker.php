<link href="../FFF/Style/style.css" rel="stylesheet">
<?php
include "Includes/header.php";
include "Includes/medewerkerContent.php";
include "Includes/footer.php";

if (isset($_POST["submit"])) {
//    Hiermee word er korting in de database gezet bij een ingevuld emailadres
    $email = htmlspecialchars($_POST["email"]);
    $korting = htmlspecialchars((int)$_POST["korting"]);
//Kijkt of het emailadres bestaat in de database
    $sql = "SELECT email FROM klant WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute(array('email' => $email));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
//    Kijkt of het een geldig emailadres is
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    Wanneer deze bestaat wordt er korting gegeven aan de klant
        if ($result > 0) {
            $query = "UPDATE klant SET korting = '" . $korting . "' WHERE email='" . $email . "'";
            $stmt = $db->prepare($query);
            $stmt->execute(array());
//        Wanneer deze niet bestaat wordt er een alert getoont
        } else {
            echo '<script language="javascript">';
            echo 'alert("Dit emailadres behoort nog niet tot een klant")';
            echo '</script>';
        }
    }
}
?>


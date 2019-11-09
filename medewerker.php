<link href="../FFF/Style/style.css" rel="stylesheet">
<?php
include "Includes/header.php";
include "Includes/menu.php";
include "Includes/medewerkerContent.php";
include "Includes/footer.php";

if (isset($_POST["submit"])) {
    $email = htmlspecialchars($_POST["email"]);
    $korting = htmlspecialchars((int)$_POST["korting"]);
    $query = "UPDATE klant SET korting = '" . $korting . "' WHERE email='" . $email . "'";
    $stmt = $db->prepare($query);
    $stmt->execute(array());

}
?>


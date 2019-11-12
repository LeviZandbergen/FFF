<link href="../FFF/Style/style.css" rel="stylesheet">
<?php
include "Includes/header.php";
include "Includes/lijstenContent.php";
include "Includes/footer.php";

//Pakt de input van de klant en geeft stuurt naar de juiste link
if (isset($_POST['submit'])) {
    if ($_POST["download"] === 'medewerker') {
        echo "<script>window.location.href = 'medewerkerLijst.php';</script>";
    }
    if ($_POST["download"] === 'C1') {
        echo "<script>window.location.href = 'chauffeur1lijst.php?id=1';</script>";
    }
    if ($_POST["download"] === 'C2') {
        echo "<script>window.location.href = 'chauffeur1lijst.php?id=2';</script>";
    }
    if ($_POST["download"] === 'C3') {
        echo "<script>window.location = 'chauffeur1lijst.php?id=3';</script>";
    }
    if ($_POST["download"] === 'Facturen') {
        echo "<script>window.location = 'factuur.php';</script>";
    }
}
?>

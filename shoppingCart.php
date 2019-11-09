<link href="../FFF/Style/style.css" rel="stylesheet">
<?php
include "Includes/header.php";
include "Includes/menu.php";
include "Includes/shoppingCartContent.php";
include "Includes/footer.php";

//Wanneer er op verwijderen word gedrukt
if (isset($_POST["verwijderen"])) {
    $id = $_POST["id"];
//  haalt geselecteerde product uit de artikelen array
    unset($_SESSION["artikelen"][$id]);
//    Herladen pagina
    echo "<script>window.location = 'shoppingCart.php';</script>";
}
//Wanneer het aantal wordt veranderd
if (isset($_POST["submitAantal"])) {
    $id = $_POST["submitAantal"];
    $nieuwAantal = $_POST["nieuwAantal"];
//    Zet nieuwe aantal in array
    $_SESSION["artikelen"][$id]["aantal"] = $nieuwAantal;
//    Herladen pagina
    echo "<script>window.location = 'shoppingCart.php';</script>";
}
//Wanneer Producten worden gereserveerd
if (isset($_POST["submit"]) && !empty($_SESSION['artikelen'])) {
//    Alle gegevens uit de form opslaan in variablen
    $naam = htmlspecialchars(strtolower($_POST["naam"]));
    $tussenvoegsel = htmlspecialchars(strtolower($_POST["tussenvoegsel"]));
    $achternaam = htmlspecialchars(strtolower($_POST["achternaam"]));
    $email = htmlspecialchars($_POST["email"]);

    $straatnaam = htmlspecialchars(strtolower($_POST["straatnaam"]));
    $huisnummer = htmlspecialchars($_POST["huisnummer"]);
    $postcode = htmlspecialchars($_POST["postcode"]);
    $woonplaats = htmlspecialchars(strtolower($_POST["woonplaats"]));

//    Geeft juiste value aan of er bezord word of niet
    if (htmlspecialchars($_POST["bezorgen"])) {
        $bezorgen = 1;
    } else {
        $bezorgen = 0;
    }
    var_dump("bezorgen = " . $bezorgen);
//    Controleert of postcode juist is
    $controlPostcode = substr(str_replace(' ', '', strtoupper($postcode)), 0, 6);
    if (!preg_match('/\d\d\d\d[A-Z]{2}/', $controlPostcode)) {
        echo '<script language="javascript">';
        echo 'alert("Voer een juiste postcode in")';
        echo '</script>';
    } else {
//        Controlleert of email juist is
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $checkedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            $sql = "SELECT email, idklant, korting FROM klant WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute(array('email' => $email));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $response = null;
            $idKlant = $result["idklant"];
            $korting = $result["korting"];
            $kortingEmail = $result["email"];
            if ($result > 0) {
//                Haalt gegevens op van de klant en checkt of het adres al bestaat
                $sql = "SELECT * FROM address WHERE address_idklant = :idklant";
                $stmt = $db->prepare($sql);
                $stmt->execute(array('idklant' => $idKlant));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result['straat'] == $straatnaam && $result['huisnummer'] == $huisnummer && $result['woonplaats'] == $woonplaats && $result['postcode'] == $postcode) {
                    $idAdres = $result['idaddress'];
                    order($idKlant, $idAdres, $totaalprijs, $db, $bezorgen, $korting, $kortingEmail);
//                    Wanneer het adres niet bestaat word er een nieuw adres aangemaakt
                } else {
                    $query = "INSERT INTO address(address_idKlant, straat, huisnummer, postcode, woonplaats)  VALUES('$idKlant', '$straatnaam', '$huisnummer', '$postcode', '$woonplaats')";
                    $db->exec($query);
                    if ($query) {
                        $sql = "SELECT idaddress FROM address WHERE address_idKlant = '$idKlant' AND huisnummer = '$huisnummer' AND straat = '$straatnaam'";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array());
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $idAdres = $result['idaddress'];
                        order($idKlant, $idAdres, $totaalprijs, $db, $bezorgen, $korting, $kortingEmail);
                    }
                }
            } else {
//                Maakt een nieuwe klant aan
                $query = "INSERT INTO klant(naam, tussenvoegsel, achternaam, email)  VALUES('$naam', '$tussenvoegsel', '$achternaam', '$email')";
                $db->exec($query);
                if ($query) {
                    $sql = "SELECT idklant FROM klant WHERE email = :email";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array('email' => $email));
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $idKlant = $result["idklant"];
                    if ($sql) {
//                        Maakt nieuw adres aan voor klant
                        $query = "INSERT INTO address(address_idKlant, straat, huisnummer, postcode, woonplaats)  VALUES('$idKlant', '$straatnaam', '$huisnummer', '$postcode', '$woonplaats')";
                        $db->exec($query);
                        if ($query) {
                            $sql = "SELECT idaddress FROM address WHERE address_idKlant = '$idKlant' AND huisnummer = '$huisnummer' AND straat = '$straatnaam'";
                            $stmt = $db->prepare($sql);
                            $stmt->execute(array());
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            $idAdres = $result['idaddress'];

                            order($idKlant, $idAdres, $totaalprijs, $db, $bezorgen, $korting, $kortingEmail);
                        }
                    }
                }
            }
        }
    }
}
//Functie waar de order wordt geplaatst
function order($idKlant, $idAddress, $totaalprijs, $db, $bezorgen, $korting, $email)
{
//    Berekend korting bij de totaalbrijs
    $afTeTrekkenPrijs = (($totaalprijs / 100) * $korting);
    ($totaalprijs -= $afTeTrekkenPrijs);
// Rekent 50 extra bij de totaalprijs op wanneer er voor bezorgen is gekozen
    if ($bezorgen == 1) {
        ($totaalprijs += 50);
    }

// Zet de korting van de klant naar 0
    $query = "UPDATE klant SET korting = '0' WHERE email='" . $email . "'";
    $stmt = $db->prepare($query);
    $stmt->execute(array());

//    Plaatst de order
    $query = "INSERT INTO orders (orders_idKlant, orders_idAddress, totaalprijs, betaald, bezorgen) VALUES ('$idKlant', '$idAddress', '$totaalprijs', false, $bezorgen)";
    $db->exec($query);
    if ($query) {
//Pakt het order id van de net gemaakte order
        $sql = "SELECT idOrders FROM orders WHERE orders_idKlant = '$idKlant' AND orders_idAddress = '$idAddress' AND totaalprijs = '$totaalprijs'";
        $stmt = $db->prepare($sql);
        $stmt->execute(array());
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($sql) {
//            Maakt voor elk artikel in de winkelwagen een order regel aan met de juiste gegevens
            foreach ($_SESSION['artikelen'] as $pId => $items) {
                $idOrders = $result["idOrders"];
                $idArtikel = $items["id"];
                $categorie = $items["categorie"];
                $aantal = $items["aantal"];
                $empty = '';
//                Kijkt of een product een koopproduct is
                if ($categorie = 2) {
                    $query = "INSERT INTO orderregel (orderRegel_idArtikel, orderRegel_idOrders, bestelDatum, retourDatum, aantal) VALUES ('$idArtikel', '$idOrders', '$empty', '$empty', '$aantal')";
                    $db->exec($query);
//                    Kijkt of een product een Huurproduct is
                } else {
                    $bestelDatum = $items["startDatum"];
                    $retourDatum = $items["eindDatum"];
                    $query = "INSERT INTO orderregel (orderRegel_idArtikel, orderRegel_idOrders, bestelDatum, retourDatum, aantal) VALUES ('$idArtikel', '$idOrders', '$bestelDatum', '$retourDatum', '$aantal')";
                    $db->exec($query);
                }
            }
//            Verwijdert alle artikelen uit de winkelwagen en herlaad de pagina
            $_SESSION['artikelen'] = array();
            echo '<script language="javascript">';
            echo 'alert("Uw reservering is verzonden. \nBij het ophalen of bezorgen moet u €' . $totaalprijs . ' betalen.")';
            echo '</script>';

            echo "<script>window.location = 'shoppingCart.php';</script>";
        }
    }
}

?>
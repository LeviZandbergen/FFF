<link href="../FFF/Style/style.css" rel="stylesheet">
<?php
include "Includes/header.php";
include "Includes/product.php";
include "Includes/footer.php";

//Kijkt of er al een instance van de artikelen winkelmand bestaat en anders maakt hij er een aan
if (!$_SESSION["artikelen"]) {
    $_SESSION["artikelen"] = array();
}

//Wanneer er op submit is geklikt en het product niet in onderhoud is
if (isset($_POST["submitKoop"]) && $products["onderhoud"] == 0) {
//    Kijkt of er een aantal is ingevuld
    if ($_POST["aantal"] > 0 && $_POST["aantal"] !== "") {
//        Maakt een indicator aan om te kijken of het product al in de winkelmand zit
        $indicator = 0;
        foreach ($_SESSION['artikelen'] as $pId => $items) {
//            Kijkt of het product al in de winkelmand zit zoja dan veranderd deze het aantal
            if ($items["id"] == $products["idartikel"]) {
                $_SESSION["artikelen"][$pId]["aantal"] = $_POST["aantal"];
//                Zet de indicator op 1 wat inhoud dat het product al in de winkelmand zit
                $indicator = 1;
            }
        }
//        als het product nog niet in de winkelmand zit zet hij deze in de winkelmand
        if ($indicator == 0) {
            $product = array("id" => $products["idartikel"], "aantal" => $_POST["aantal"], "naam" => $products["naam"], "afbeelding" => $products["afbeelding"], "prijs" => $products["prijs"], "categorie" => $products['artikel_idCategorie']);
            array_push($_SESSION["artikelen"], $product);
        }
//Wanneer er geen aantal is ingevuld word er een alert getoont
    } else {
        echo '<script language="javascript">';
        echo 'alert("Voer een aantal in")';
        echo '</script>';
    }
//Wanneer er op submit is geklikt en het product niet in onderhoud is
} elseif (isset($_POST["submitHuur"]) && $products["onderhoud"] == 0) {
//    Maakt datumobjecten aan voor de start en einddatum en maakt een datumobject aan voor nu
    $start = new DateTime($_POST["startDatum"]);
    $eind = new DateTime($_POST["eindDatum"]);
    $now = new DateTime();

//    Wanneer er datums zijn ingevuld
    if ($_POST["startDatum"] && $_POST["eindDatum"]) {
//        Kijkt of de startdatum voor de einddatum komt
        if ($start > $eind) {
            echo '<script language="javascript">';
            echo 'alert("Einddatum moet na start datum zijn")';
            echo '</script>';
//Kijkt of startdatum in de toekomst is
        } else if ($start < $now) {
            echo '<script language="javascript">';
            echo 'alert("Begindatum moet in de toekomst zijn")';
            echo '</script>';
        } else {
//Berekend het aantal dagen en weken tussen de ingevulde data's
            $interval = date_diff($start, $eind);
            $days = $interval->format("%a");
            $weeks = round($days / 7, 2);
            $whole = floor($weeks);
            $comma = round($weeks - $whole, 2);

//Hiermee word gekeken hoeveel dagen er zijn
            switch ($comma) {
                case 0.14:
                    $huurdagen = 1;
                    break;
                case 0.29:
                    $huurdagen = 2;
                    break;
                case 0.43:
                    $huurdagen = 3;
                    break;
                case 0.57:
                    $huurdagen = 4;
                    break;
                case 0.71:
                    $huurdagen = 5;
                    break;
                case 0.86:
                    $huurdagen = 6;
                    break;
                case 0:
                default:
                    $huurdagen = 0;
                    break;
            }
            $indicator = 0;
            foreach ($_SESSION['artikelen'] as $pId => $items) {
//            Kijkt of het product al in de winkelmand zit zoja dan veranderd deze het aantal
                if ($items["id"] == $products["idartikel"]) {
                    $_SESSION["artikelen"][$pId]["aantal"] = $_POST["aantal"];
                    $_SESSION["artikelen"][$pId]["startDatum"] = $_POST["startDatum"];
                    $_SESSION["artikelen"][$pId]["eindDatum"] = $_POST["eindDatum"];
                    $_SESSION["artikelen"][$pId]["weken"] = $whole;
                    $_SESSION["artikelen"][$pId]["dagen"] = $huurdagen;
//                Zet de indicator op 1 wat inhoud dat het product al in de winkelmand zit
                    $indicator = 1;
                }
            }
//        als het product nog niet in de winkelmand zit zet hij deze in de winkelmand
            if ($indicator == 0) {
//            Zet het product in de winkelwagen
                $product = array("id" => $products["idartikel"], "naam" => $products["naam"], "afbeelding" => $products["afbeelding"], "prijs" => $products["prijsDag"], "aantal" => $_POST["aantal"], "startDatum" => $_POST["startDatum"], "eindDatum" => $_POST["eindDatum"], "categorie" => $products['artikel_idCategorie'], "weken" => $whole, "dagen" => $huurdagen, "weekPrijs" => $products["prijsWeek"], "dagPrijs" => $products["prijsDag"]);
                array_push($_SESSION["artikelen"], $product);
            }
        }
//        Wanneer er geen aantal is gevult wordt er een alert getoont
    } else if ($_POST["aantal"] < 0 || $_POST["aantal"] == "") {
        echo '<script language="javascript">';
        echo 'alert("Voer een aantal in")';
        echo '</script>';
//        Wanneer er geen datums zijn ingevuld wordt er een alert getoont
    } else if (!$_POST["eindDatum"] || !$_POST["startDatum"]) {
        echo '<script language="javascript">';
        echo 'alert("Vul een start en einddatum in!")';
        echo '</script>';
    }
//    Wanneer het product in onderhoud is wordt er een alert getoont
} else if ((isset($_POST["submitHuur"]) || isset($_POST["submitKoop"])) && $products["onderhoud"] == 1) {
    echo '<script language="javascript">';
    echo 'alert("Dit product is in onderhoud")';
    echo '</script>';
}
?>
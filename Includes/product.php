<?php
$query = "SELECT * FROM artikel WHERE idArtikel = ?";
$stmt = $db->prepare($query);
$stmt->execute(array($_GET['id']));
$products = $stmt->fetch();

if (!$_SESSION["artikelen"]) {
    $_SESSION["artikelen"] = array();

}
//$_SESSION["artikelen"] = array();
?>

<html>
<div class="Content">
    <?php
    $afbeelding = $products['afbeelding']; ?>
    <?php
    if (isset($_SESSION["ID"]) && $_SESSION["STATUS"] === 1) {
        ?>
        <form method="POST">
            <div class="onderhoud">
                <?php
                if ($products["onderhoud"] == 0) {
                    ?>
                    <input type="checkbox" value="unchecked" name="unchecked"
                           onchange="this.form.submit();"> In
                    onderhoud
                    <?php
                } else if ($products["onderhoud"] == 1) {
                    ?>
                    <input type="checkbox" value="checked" name="checked" onchange="this.form.submit()"> <a
                            style="color: red">In onderhoud</a>
                    <?php
                }
                ?>
            </div>
        </form>
        <?php
        if (isset($_POST["unchecked"])) {
            $query = "UPDATE artikel SET onderhoud='1' WHERE idartikel=" . $products['idartikel'];
            $db->exec($query);
            header("Refresh:0");

        } else if (isset($_POST["checked"])) {
            $query = "UPDATE artikel SET onderhoud='0' WHERE idartikel=" . $products['idartikel'];
            $db->exec($query);
            header("Refresh:0");
        }

    }
    ?>


    <div class="product-item">
        <div class="product-image">
            <img style="max-height: 100%; max-width: 100%; margin: 0 auto; display: block;"
                 src="../FFF/Images/<?php echo $products["afbeelding"]; ?>">
        </div>
        <div class="product-tile-footer">
            <div class="product-title">
                <h1><?php echo $products["naam"]; ?></h1>
            </div>
            <hr>
            <div class="product-description">
                <?php echo $products["beschrijving"]; ?>
            </div>
            <hr>
            <?php if ($products["artikel_idCategorie"] == 1) { ?>
                <div class="product-price">Prijs: <?php echo "€" . $products["prijs"]; ?>
                </div>
                <form class="product-form" method="POST">
                    <input type="number" name="aantal" placeholder="Aantal">Aantal<br><br>
                    <?php
                    if ($products["onderhoud"] == 1) {
                        ?>
                        <input type="submit" name="submitKoop" value="Reserveer" disabled>
                        <?php
                    } else {
                        ?>
                        <input type="submit" name="submitKoop" value="Reserveer">
                        <?php
                    }
                    ?>
                </form>
            <?php } elseif ($products["artikel_idCategorie"] == 2) { ?>
                <div class="product-price">Prijs per
                    dag: <?php echo "€" . $products["prijsDag"]; ?>
                </div>
                <div class="product-price">Prijs per
                    week: <?php echo "€" . $products["prijsWeek"]; ?>
                </div>
                <form class="product-form" method="POST">
                    <input type="number" name="aantal" placeholder="Aantal">Aantal<br><br>
                    <input type="date" name="startDatum" placeholder="Begin datum"> Begin Datum
                    <input type="date" name="eindDatum" placeholder="Eind datum"> Eind Datum
                    <?php
                    if ($products["onderhoud"] == 1) {
                        ?>
                        <input type="submit" name="submitHuur" value="Reserveer" disabled>
                        <?php
                    } else {
                        ?>
                        <input type="submit" name="submitHuur" value="Reserveer">
                        <?php
                    }
                    ?>
                </form>
            <?php }

            if (isset($_POST["submitKoop"]) && $products["onderhoud"] == 0) {
                if ($_POST["aantal"] > 0 && $_POST["aantal"] !== "") {
                    $indicator = 0;
                    foreach ($_SESSION['artikelen'] as $pId => $items) {
                        if ($items["id"] == $products["idartikel"]) {
                            $_SESSION["artikelen"][$pId]["aantal"] = $_POST["aantal"];
                            $indicator = 1;
                        }
                    }
                    if ($indicator == 0) {
                        $product = array("id" => $products["idartikel"], "aantal" => $_POST["aantal"], "naam" => $products["naam"], "afbeelding" => $products["afbeelding"], "prijs" => $products["prijs"], "categorie" => $products['artikel_idCategorie']);
                        array_push($_SESSION["artikelen"], $product);
                    }


                } else {
                    echo("Voer een aantal in");
                }


            } elseif (isset($_POST["submitHuur"]) && $products["onderhoud"] == 0) {
                $start = new DateTime($_POST["startDatum"]);
                $eind = new DateTime($_POST["eindDatum"]);
                $now = new DateTime();
                $ingevuldeData = array('start' => $start, 'end' => $eind);

                if ($_POST["startDatum"] && $_POST["eindDatum"]) {
                    if ($start > $eind) {
                        echo("Einddatum moet na start datum zijn");

                    } else if ($start < $now) {
                        echo("Begindatum moet in de toekomst zijn");

                    } else {

                        $interval = date_diff($start, $eind);
                        $days = $interval->format("%a");
                        $weeks = round($days / 7, 2);
                        $whole = floor($weeks);
                        $comma = round($weeks - $whole, 2);

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
                        $product = array("id" => $products["idartikel"], "naam" => $products["naam"], "afbeelding" => $products["afbeelding"], "prijs" => $products["prijsDag"], "aantal" => $_POST["aantal"], "startDatum" => $_POST["startDatum"], "eindDatum" => $_POST["eindDatum"], "categorie" => $products['artikel_idCategorie'], "weken" => $whole, "dagen" => $days, "weekPrijs" => $products["prijsWeek"], "dagPrijs" => $products["prijsDag"]);
                        array_push($_SESSION["artikelen"], $product);
                    }
                } else if ($_POST["aantal"] < 0 || $_POST["aantal"] == "") {
                    echo("Voer een aantal in");
                } else if (!$_POST["eindDatum"] || !$_POST["startDatum"]) {
                    echo("Vul een start en einddatum in!");
                }
            } else if ((isset($_POST["submitHuur"]) || isset($_POST["submitKoop"])) && $products["onderhoud"] == 1) {
                echo "Dit product is in onderhoud";
            }
            ?>
        </div>
    </div>

</div>
</html>
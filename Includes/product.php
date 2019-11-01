<?php
$query = "SELECT * FROM artikel WHERE idArtikel = ?";
$stmt = $db->prepare($query);
$stmt->execute(array($_GET['id']));
$products = $stmt->fetch();

if ($_SESSION["artikelen"]) {

} else {
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
                $product = array("id" => $products["idartikel"], "aantal" => $_POST["aantal"]);
                array_push($_SESSION["artikelen"], $product);
//                $_SESSION["artikelen"] = array_merge($_SESSION["artikelen"], $product);

            } elseif (isset($_POST["submitHuur"]) && $products["onderhoud"] == 0) {
                $product = array($products["idartikel"], $_POST["aantal"], $_POST["startDatum"], $_POST["eindDatum"]);
                $_SESSION["artikelen"] = array_merge($_SESSION["artikelen"], $product);
            } else if ((isset($_POST["submitHuur"]) || isset($_POST["submitKoop"])) && $products["onderhoud"] == 1) {
                echo "Dit product is in onderhoud";
            }
            var_dump($_SESSION["artikelen"])

            ?>
        </div>
    </div>

</div>
</html>
<?php
$query = "SELECT * FROM artikel WHERE idArtikel = ?";
$stmt = $db->prepare($query);
$stmt->execute(array($_GET['id']));
$products = $stmt->fetch();
?>

<html>
<div class="Content">
    <?php
    $afbeelding = $products['afbeelding']; ?>

    <div id="product-item" style="cursor: pointer">
        <div class="product-image">
            <img style="max-height: 100%; max-width: 100%; margin: 0 auto; display: block;"
                 src="../FFF/Images/<?php echo $products["afbeelding"]; ?>">
        </div>
        <div class="product-tile-footer">
            <div class="product-title">Naam: <?php echo $products["naam"]; ?>
            </div>
            <?php if ($products["artikel_idCategorie"] == 1) { ?>
                <div class="product-price">Prijs: <?php echo "€" . $products["prijs"]; ?>
                </div>
            <?php } elseif ($products["artikel_idCategorie"] == 2) { ?>
                <div class="product-price">Prijs per
                    dag: <?php echo "€" . $products["prijsDag"]; ?>
                </div>
                <div class="product-price">Prijs per
                    week: <?php echo "€" . $products["prijsWeek"]; ?>
                </div>
            <?php } ?>
        </div>
    </div>

</div>
</html>
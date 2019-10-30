<html>
<div class="content">
    <div class="itemContainer">
        <div class="categorie">
            <form method="POST">

                <select name="categorie" onchange="this.form.submit()">
                    <option>------------</option>
                    <option value="allArticles">alle artikelen</option>
                    <?php
                    $categorie = "SELECT * FROM categorie;";
                    $stmt = $db->prepare($categorie);
                    $stmt->execute(array());
                    $type = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($type as $key => $value) {
                        echo '<option value="' . $type[$key]["type"] . '">' . $type[$key]["type"] . '</option>';
                    } ?>

                </select>
            </form>
        </div>

        <?php
        $articles = "SELECT * FROM artikel";
        $stmt = $db->prepare($articles);
        $stmt->execute(array());
        $product_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (isset($_POST['categorie']))
            if ($_POST['categorie'] == 'Huur') {
                $articles = "SELECT * FROM artikel WHERE artikel_idCategorie = 2";
                $stmt = $db->prepare($articles);
                $stmt->execute(array());
                $product_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else if ($_POST['categorie'] == 'Koop') {
                $articles = "SELECT * FROM artikel WHERE artikel_idCategorie = 1";
                $stmt = $db->prepare($articles);
                $stmt->execute(array());
                $product_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $articles = "SELECT * FROM artikel";
                $stmt = $db->prepare($articles);
                $stmt->execute(array());
                $product_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        if (!empty($product_array)) {
            foreach ($product_array as $key => $value) {
                ?>
                <div class="product-item" onclick="">
                    <div class="product-image"><img style="max-height: 100%; max-width: 100%"
                                                    src="../FFF/Images/<?php echo $product_array[$key]["afbeelding"]; ?>">
                    </div>
                    <div class="product-tile-footer">
                        <div class="product-title">Naam: <?php echo $product_array[$key]["naam"]; ?>
                        </div>
                        <?php if ($product_array[$key]["artikel_idCategorie"] == 1) { ?>
                            <div class="product-price">Prijs: <?php echo "€" . $product_array[$key]["prijs"]; ?>
                            </div>
                        <?php } elseif ($product_array[$key]["artikel_idCategorie"] == 2) { ?>
                            <div class="product-price">Prijs per
                                dag: <?php echo "€" . $product_array[$key]["prijsDag"]; ?>
                            </div>
                            <div class="product-price">Prijs per
                                week: <?php echo "€" . $product_array[$key]["prijsWeek"]; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php

            }
        }
        ?>
    </div>
</div>
</html>

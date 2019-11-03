<html>

<div class="content">
    <div id="cartItems">
        <?php
        $artikelen = $_SESSION['artikelen'];
        echo "<br>";
        foreach ($artikelen = $_SESSION['artikelen'] as $artikel) {
            ?>
            <div class="artikelKarWrap">
                <?php
                $totaalprijs = "";
                foreach ($artikel as $key => $value) {
                    ?>
                    <?php
                    if ($key == "id") {
                        $query = "SELECT * FROM artikel WHERE idArtikel = ?";
                        $stmt = $db->prepare($query);
                        $stmt->execute(array($value));
                        $products = $stmt->fetch();
                        ?>


                        <div class="artikelKarAfbeelding">
                            <img style="max-height: 100%; max-width: 100%; margin: 0 auto; display: block;"
                                 src="../FFF/Images/<?php echo $products["afbeelding"]; ?>">
                        </div>
                        <div class="contentcart">
                            <h2><?php echo $products["naam"]; ?></h2>
                        </div>
                        <?php
                        if ($products['artikel_idCategorie'] == 1) {
                            ?>
                            <div class="contentcart">
                                <h2>€<?php echo $products["prijs"];
                                    $totaalprijs = $products["prijs"] ?>
                                </h2>

                            </div>
                            <?php
                        } elseif ($products['artikel_idCategorie'] == 0) {
                            ?>
                            <h2>€<?php echo $products["dagPrijs"];
                                $products['weekPrijs'];
                                ?>
                            </h2>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    if ($key == "startDatum") {
                        $_SESSION["startDatum"] = $value;
                    }
                    if ($key == "eindDatum") {
                        $_SESSION["eindDatum"] = $value;
                    }
                    $tussentijd = $_SESSION["eindDatum"] - $_SESSION["startDatum"];
                    $gerond = round($tussentijd / (60 * 60 * 24));
                    echo("kiwi" . $gerond);
                    if ($key == "aantal") {
                        ?>
                        <div class="contentcart">
                            <h3>Aantal: <?php echo $value; ?></h3>
                        </div>
                        <div class="contentcart">
                            <h3>Totaalprijs: €<?php echo $totaalprijs * $value ?></h3>
                        </div>
                        <div class="prullenbak">
                            <img style="height: 70%" src="../FFF/Images/prullenbak.png">
                        </div>
                        <?php
                        $totaalprijs = "";
                    }
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <form id="cartForm" method="POST">
        <input class="cartFormInput" type="text" name="naam" placeholder="Naam"> Naam<br>
        <input class="cartFormInput" type="text" name="tussenvoegsel" placeholder="Tussenvoegsel"> Tussenvoegsel<br>
        <input class="cartFormInput" type="text" name="achternaam" placeholder="Achternaam"> Achternaam<br>
        <input class="cartFormInput" type="email" name="email" placeholder="Email"> Email<br>
        <input class="cartFormInput" type="text" name="straatnaam" placeholder="Straatnaam"> Straatnaam<br>
        <input class="cartFormInput" type="number" name="huisnummer" placeholder="Huisnummer"> Huisnummer<br>
        <input class="cartFormInput" type="text" name="postcode" placeholder="Postcode"> Postcode<br>
        <input class="cartFormInput" type="text" name="woonplaats" placeholder="Wooonplaats"> Woonplaats<br>
        <input class="cartFormInput" type="radio" name="bezorgen"> Bezorg + €50,-<br><br>
        <input type="submit" value="Reserveer">
    </form>
</div>
</html>
<html>

<div class="content">
    <div id="cartItems">
        <?php
        $artikelen = $_SESSION['artikelen'];
        echo "<br>";
        foreach ($artikelen = $_SESSION['artikelen'] as $artikel) {
            foreach ($artikel as $key => $value) {
                ?>
                <?php
                if ($key == "id") {
                    $query = "SELECT * FROM artikel WHERE idArtikel = ?";
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($value));
                    $products = $stmt->fetch();
                    ?>
                    <div class="artikelKarWrap">

                        <div class="artikelKarAfbeelding">
                            <img style="max-height: 100%; max-width: 100%; margin: 0 auto; display: block;"
                                 src="../FFF/Images/<?php echo $products["afbeelding"]; ?>">
                        </div>
                        <h2><?php echo $products["naam"]; ?></h2>

                    </div>

                    <?php
                }
            }
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
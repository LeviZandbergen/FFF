<html>

<div class="content">
    <div id="cartItems">
        <?php
        $totaalprijs = 0;
        if (isset($_SESSION['artikelen'])) {
            foreach ($_SESSION['artikelen'] as $pId => $items) {
                ?>
                <div class="artikelKarWrap">
                    <div class="productAfbeelding">
                        <img style="max-height: 99%; max-width: 99%"
                             src="../FFF/Images/<?php echo $items['afbeelding'] ?>">
                    </div>
                    <pre class="tab">
                <div>
                <form method="POST" name="aantalForm">
                Aantal<input onchange="this.form.submit()" style="width: 50px;" type="number" name="nieuwAantal"
                             value="<?php echo $items['aantal'] ?>">
                <input type="hidden" name="submitAantal" value="<?php echo $pId ?>">
                </form>
                </div>
                <a class="info"><?php echo $items['naam'] ?></a>
                        <?php
                        if ($items["categorie"] == 2) {
                            ?>
                            <a class="info"><?php echo $items['startDatum'] ?></a>
                            <a class="info"><?php echo $items['eindDatum'] ?></a>
                            <a class="info">€ <?php echo $items['prijs'] ?></a>
                            <a class="info">€ <?php echo $items["totaalprijs"] = (float)(($items["weken"] * $items["weekPrijs"]) + ($items["dagen"] * $items["dagPrijs"]) * $items["aantal"]) ?></a>
                            <?php
                        } else {
                            ?>
                            <a class="info"></a>
                            <a class="info"></a>
                            <a class="info">€ <?php echo $items['prijs'] ?></a>
                            <a class="info">€ <?php echo $items["totaalprijs"] = (float)($items['prijs'] * $items["aantal"]) ?></a>
                            <?php
                        }
                        ?>
            </pre>
                    <form class="prullenbak" method="POST">
                        <button style="background: none; border: none; font-size: 1em;" type="submit"
                                name="verwijderen">
                            <img style="max-height: 50px; cursor: pointer" src="../FFF/Images/prullenbak.png"
                                 name="verwijderen">
                            <input type="hidden" name="id" value="<?php echo $pId ?>">
                        </button>
                    </form>
                </div>
                <?php
                ($totaalprijs += $items["totaalprijs"]);
            }
            ?>
            <div style="float: right; margin-right: 10px; margin-top: 10px" id="totaalprijs">
                <?php
                echo 'Totaalprijs: €' . $totaalprijs;
                ?>
            </div>
            <?php
        } else {
            echo 'Er zit geen product in uw winkelwagen';
        }
        ?>
    </div>
    <form id="cartForm" method="POST">
        <input class="cartFormInput" type="text" name="naam" placeholder="Naam"> Naam<br>
        <input class="cartFormInput" type="text" name="tussenvoegsel" placeholder="Tussenvoegsel"> Tussenvoegsel<br>
        <input class="cartFormInput" type="text" name="achternaam" placeholder="Achternaam"> Achternaam<br>
        <input class="cartFormInput" type="email" name="email" placeholder="Email"> Email<br>
        <input class="cartFormInput" type="text" name="straatnaam" placeholder="Straatnaam"> Straatnaam<br>
        <input class="cartFormInput" type="text" name="huisnummer" placeholder="Huisnummer"> Huisnummer<br>
        <input class="cartFormInput" type="text" name="postcode" placeholder="Postcode"> Postcode<br>
        <input class="cartFormInput" type="text" name="woonplaats" placeholder="Woonplaats"> Woonplaats<br>
        <input class="cartFormInput" id="bezorgen" type="checkbox" name="bezorgen"> Bezorg +
        €50,-<br><br>
        <input type="submit" name="submit" onclick="document.forms['aantalForm'].submit()">
    </form>
</div>
</html>


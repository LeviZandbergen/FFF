<html>

<div class="content">
    <div id="cartItems">
        <?php
        if (isset($_SESSION['artikelen'])) {
            foreach ($_SESSION['artikelen'] as $pId => $items) {
                echo '<div class="artikelKarWrap">';
                echo '<div class="productAfbeelding">';
                echo '<img style="max-height: 99%; max-width: 99%" src="../FFF/Images/' . $items["afbeelding"] . '">';
                echo '</div>';
                echo '<pre class="tab">';
                echo '<div>';
                echo 'Aantal<input style="width: 50px;" type="text" value="' . ($items['aantal']) . '">';
                echo '</div>';
                echo '<a class="info">' . $items['naam'] . '</a > ';
                echo '<a class="info" > ' . $items['startDatum'] . ' </a > ';
                echo '<a class="info" > ' . $items['eindDatum'] . ' </a > ';
                echo '<a class="info" > €' . $items['prijs'] . ' </a > ';
                echo '<a class="info" > €' . $items['totaalprijs'] . ' </a > ';
                echo '</pre > ';
                echo '<form class="prullenbak" method = "POST" >';
                echo '<button style = "background: none; border: none; font-size: 1em;" type = "submit"
                    name = "verwijderen" >';
                echo '<img style = "max-height: 50px; cursor: pointer" src = "../FFF/Images/prullenbak.png"
                     name = "verwijderen" >';
                echo '<input type = "hidden" name = "id" value = "' . $pId . '" >';
                echo '</button >';
                echo '</form >';
                echo '</div >';
            }
        } else {
            echo 'Er zit geen product in uw winkelwagen';
        }
        if (isset($_POST["verwijderen"])) {
            $id = $_POST["id"];
            unset($_SESSION["artikelen"][$id]);
            header("Refresh:0");
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
        <input type="submit" name="submit">
    </form>
    <?php
    if (isset($_POST["submit"])) {
        $naam = htmlspecialchars($_POST["naam"]);
        $tussenvoegsel = htmlspecialchars($_POST["tussenvoegsel"]);
        $achternaam = htmlspecialchars($_POST["achternaam"]);
        $email = htmlspecialchars($_POST["email"]);

        $straatnaam = htmlspecialchars($_POST["straatnaam"]);
        $huisnummer = htmlspecialchars($_POST["huisnummer"]);
        $postcode = htmlspecialchars($_POST["postcode"]);
        $woonplaats = htmlspecialchars($_POST["woonplaats"]);
        //        $bezorgen = htmlspecialchars($_POST["bezorgen"]);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $checkedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            $sql = "SELECT email, idklant FROM klant WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute(array('email' => $email));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $response = null;
            $idKlant = $result["idklant"];
            if ($result > 0) {
                $sql = "SELECT * FROM address WHERE address_idklant = :idklant";
                $stmt = $db->prepare($sql);
                $stmt->execute(array('idklant' => $idKlant));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
//                $idAdres = $result['idaddress'];
                if ($result['straat'] == $straatnaam && $result['huisnummer'] == $huisnummer && $result['woonplaats'] == $woonplaats && $result['postcode'] == $postcode) {
//                    order($idKlant, $idAdres);
                } else {
                    $query = "INSERT INTO address(address_idKlant, straat, huisnummer, postcode, woonplaats)  VALUES('$idKlant', '$straatnaam', '$huisnummer', '$postcode', '$woonplaats')";
                    $db->exec($query);
                    if ($query) {
                        $sql = "SELECT idaddress FROM address WHERE straat = $straatnaam, address_idklant = $idKlant, huisnummer = $huisnummer, postcode = $postcode, woonplaats = $woonplaats";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array());
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $idAdres = $result['idaddress'];
                        order($idKlant, $idAdres);
                    }
                }
            } else {
                $query = "INSERT INTO klant(naam, tussenvoegsel, achternaam, email)  VALUES('$naam', '$tussenvoegsel', '$achternaam', '$email')";
                $db->exec($query);
                if ($query) {
                    $sql = "SELECT idklant FROM klant WHERE email = :email";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array('email' => $email));
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $idKlant = $result["idklant"];
                    if ($sql) {
                        $query = "INSERT INTO address(address_idKlant, straat, huisnummer, postcode, woonplaats)  VALUES('$idKlant', '$straatnaam', '$huisnummer', '$postcode', '$woonplaats')";
                        $db->exec($query);
                        order();
                    }
                }
            }
        }
        function order()
        {

        }

    }
    ?>
</div>
</html>

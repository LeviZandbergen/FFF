<?php if (isset($_SESSION["ID"]) && $_SESSION["STATUS"] === 1) { ?>
<html>
<div class="content">
    <div class="medewerkerNavigatie">
        <a href="../FFF/lijsten.php" id="button1">Lijsten</a>
        <a href="../FFF/bestelRetour.php" id="button2">Bestelling/Retour</a>
    </div>
    <?php
    $now = date('Y-m-d');
    $query = "SELECT * FROM orders INNER JOIN orderregel ON orderRegel_idOrders = idOrders INNER JOIN klant ON orders_idKlant = idKlant  WHERE retourDatum = '$now' AND bezorgen = 0 OR bestelDatum = '$now' AND bezorgen = 0;";
    $stmt = $db->prepare($query);
    $stmt->execute(array());
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $id => $test) { ?>
        <div class="retourBestelWrap">
            <pre class="tabRetBest">
                <a class="info" style="margin-right: 30px">Order ID <?php echo $test["idOrders"] ?></a>
                <a class="info"> <?php echo wordwrap(ucfirst($test["naam"]) . " " . $test["tussenvoegsel"] . " " . ucfirst($test["achternaam"]), 30, "<br>\n") ?> </a>
            </pre>

            <form method="POST" class="betaaldForm">
                <?php
                if ($test["betaald"] == 0) { ?>
                    Betaald<input type="checkbox" name="unchecked" value="unchecked" onchange="this.form.submit()">
                    <input type="hidden" name="id" value="<?php echo $test['idOrders'] ?>">
                <?php } else { ?>
                    Betaald<input type="checkbox" checked name="checked" value="checked"
                                  onchange="this.form.submit()">
                    <input type="hidden" name="id" value="<?php echo $test['idOrders'] ?>">
                    <input type="hidden" name="test" value="checked">
                <?php } ?>
            </form>
        </div>
    <?php }

    } else if (isset($_SESSION["ID"]) && $_SESSION["STATUS"] === 2) { ?>
    <html>
    <div class="content">
        <?php
        $now = date('Y-m-d');
        $query = "SELECT * FROM orders INNER JOIN orderregel ON orderRegel_idOrders = idOrders INNER JOIN klant ON orders_idKlant = idKlant INNER JOIN fff.address
ON orders_idAddress = idAddress WHERE retourDatum = '$now' AND bezorgen = 1 OR bestelDatum = '$now' AND bezorgen = 1 ORDER BY postcode ASC;";
        $stmt = $db->prepare($query);
        $stmt->execute(array());
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $id => $test) { ?>
            <div class="retourBestelWrap">
            <pre class="tabRetBestC">
                <a class="infoBR" style="margin-right: 30px">Order ID <?php echo $test["idOrders"] ?></a>
                <a class="infoBR"> <?php echo wordwrap(ucfirst($test["naam"]) . " " . $test["tussenvoegsel"] . " " . ucfirst($test["achternaam"]), 30, "<br>\n") ?> </a>
                <a class="infoBR"><?php echo ucfirst($test["straat"]) . $test["huisnummer"] ?></a>
                <a class="infoBR"><?php echo strtoupper($test["postcode"]) ?></a>
                <a class="infoBR"><?php echo ucfirst($test["woonplaats"]) ?></a>
            </pre>

                <form method="POST" class="betaaldForm">
                    <?php
                    if ($test["betaald"] == 0) { ?>
                        Betaald<input type="checkbox" name="unchecked" value="unchecked" onchange="this.form.submit()">
                        <input type="hidden" name="id" value="<?php echo $test['idOrders'] ?>">
                    <?php } else { ?>
                        Betaald<input type="checkbox" checked name="checked" value="checked"
                                      onchange="this.form.submit()">
                        <input type="hidden" name="id" value="<?php echo $test['idOrders'] ?>">
                        <input type="hidden" name="test" value="checked">
                    <?php } ?>
                </form>
            </div>
        <?php }

        } else {
            header("Location:index.php");
        }

        if (isset($_POST["unchecked"])) {
            $id = $_POST["id"];
            $query = "UPDATE orders SET betaald = '1' WHERE idOrders='" . $id . "'";
            $stmt = $db->prepare($query);
            $stmt->execute(array());
            echo "<script>window.location = 'bestelRetour.php';</script>";
        }
        if (isset($_POST["test"])) {
            $id = $_POST["id"];
            $query = "UPDATE orders SET betaald = '0' WHERE idOrders='" . $id . "'";
            $stmt = $db->prepare($query);
            $stmt->execute(array());
            echo "<script>window.location = 'bestelRetour.php';</script>";
        }
        ?>
    </div>
    </html>
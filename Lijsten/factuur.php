<!doctype html>
<html>
<div id="source-html">
    <head>
        <meta charset="utf-8">
        <title>A simple, clean, and responsive HTML invoice template</title>

        <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 16px;
                line-height: 24px;
                font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: right;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.item td {
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
        </style>
    </head>
    <?php
    //    Pakt de datum van vandaag
    $now = date('Y-m-d');
    //    Haalt alle gegevens op van de orders van vandaag
    $query = "SELECT * FROM orders INNER JOIN orderregel ON orderRegel_idOrders = idOrders INNER JOIN klant ON orders_idKlant = idKlant INNER JOIN fff.address
ON orders_idAddress = idAddress WHERE retourDatum = '$now' OR bestelDatum = '$now' GROUP BY idOrders ORDER BY idOrders ASC;";
    $stmt = $db->prepare($query);
    $stmt->execute(array());
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $key => $value) {
//Haalt alle orderregels en artikel gegevens op
        $query = "SELECT * FROM orderregel INNER JOIN artikel ON orderRegel_idArtikel = idArtikel WHERE orderregel_idOrders =" . $value["idOrders"];
        $stmt = $db->prepare($query);
        $stmt->execute(array());
        $orderregels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <body>
        <br style="page-break-before: always">
        <div class="invoice-box">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="title">

                                </td>

                                <td>
                                    Factuurnummer #: <?php echo $value["idOrders"] ?><br>
                                    Gemaakt: <?php echo $now ?><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="information">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td>
                                    <h3>Afzender</h3>
                                    Freds FeestFabriek.<br>
                                    Nieuwe Straat 15<br>
                                    7777HH Hardenberg
                                </td>

                                <td>
                                    <h3>Klant Gegevens</h3>
                                    <?php echo ucfirst($value["naam"]) . " " . ucfirst($value["achternaam"]) ?><br>
                                    <?php echo ucfirst($value["straat"]) . " " . $value["huisnummer"] ?><br>
                                    <?php echo strtoupper($value["postcode"]) . " " . ucfirst($value["woonplaats"]) ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="heading">
                    <td>
                        Artikel
                    </td>
                    <td>
                        Prijs
                    </td>
                </tr>
                <?php
                $NieuweTotaalprijs = 0;
                foreach ($orderregels as $orderregel) {
// Berekent het aantal dagen
                    $start = new DateTime($orderregel["bestelDatum"]);
                    $eind = new DateTime($orderregel["retourDatum"]);
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
                    ?>
                    <tr class="item">
                        <td>
                            <?php echo $orderregel["naam"] ?>
                        </td>

                        <td>
                            <?php
                            //                            Berekent de totaalprijs
                            if ($orderregel['artikel_idCategorie'] == 2) {
                                echo $totaalprijs = (float)((($whole * $orderregel["prijsWeek"]) + ($huurdagen * $orderregel["prijsDag"])) * $orderregel["aantal"]);
                                $NieuweTotaalprijs += $totaalprijs;
                            } else {
                                echo $totaalprijs = (float)($orderregel['prijs'] * $orderregel["aantal"]);
                                $NieuweTotaalprijs += $totaalprijs;
                            } ?>
                        </td>
                    </tr>
                <?php }
                if ($value['bezorgen'] == 1){
                ?>
                <tr>
                    <td>
                        <strong>Bezorgen</strong>
                    </td>
                    <td>
                        <strong>50</strong>
                    </td>
                </tr>
                <tr class="total">
                    <?php } ?>
                    <td>
                    </td>
                    <td>
                        <!--                        Berekening met korting en bezorgkosten bij de totaalprijs op-->
                        Totaal: <?php
                        $korting = (100 - $value['korting']);
                        if ($value['bezorgen'] == 1) {
                            $bezorgen = 50;
                        } else {
                            $bezorgen = 0;
                        }
                        echo(($NieuweTotaalprijs / 100 * $korting) + $bezorgen) ?>
                    </td>
                </tr>
            </table>
        </div>
        </body>
    <?php } ?>
</div>
</html>
<script>
    window.onload = function () {
        exportHTML();
        location.href = "lijsten.php"
    };

    //Functie om de pagina naar word te zetten
    function exportHTML() {
        var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
            "xmlns:w='urn:schemas-microsoft-com:office:word' " +
            "xmlns='http://www.w3.org/TR/REC-html40'>" +
            "<head><meta charset='utf-8'></head><body>";
        var footer = "</body></html>";
        var sourceHTML = header + document.getElementById("source-html").innerHTML + footer;

        var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
        var fileDownload = document.createElement("a");
        document.body.appendChild(fileDownload);
        fileDownload.href = source;
        fileDownload.download = 'document.doc';
        fileDownload.click();
        document.body.removeChild(fileDownload);
    }
</script>
<?php
//Kijkt of de gebruiker een medewerker is
if (isset($_SESSION["ID"]) && $_SESSION["STATUS"] === 1) {

} else {
    header("Location:../index.php");
}
//De dag van nu
$now = date('Y-m-d');
//Query die alle gegevens van alle orders pakt met de datum van vandaag
$query = "SELECT * FROM orders INNER JOIN orderregel ON orderRegel_idOrders = idOrders INNER JOIN klant ON orders_idKlant = idKlant INNER JOIN fff.address
ON orders_idAddress = idAddress WHERE retourDatum = '$now' AND bezorgen = 1 OR bestelDatum = '$now' AND bezorgen = 1 GROUP BY idOrders ORDER BY postcode ASC;";
$stmt = $db->prepare($query);
$stmt->execute(array());
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//Split de array in 3en voor de drie chauffeurs
$result = array_chunk($result, ceil(count($result) / 3));
//Zet de juiste waarde van result voor de juiste chauffeur
if ($_GET["id"] === '1') {
    $result = $result[0];
    $name = "Chauffeur1";
} else if ($_GET["id"] === '2') {
    $result = $result[1];
    $name = "Chauffeur2";
} else if ($_GET["id"] === '3') {
    $result = $result[2];
    $name = "Chauffeur3";
}
?>
<a id="dlink" style="display:none;"></a>
<input type="button" onclick="tableToExcel('testTable', 'W3C Example Table', '<?php echo $now . $name ?>.xls')"
       value="Export to Excel">

<table id="testTable" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides"
       border="2">

    <colgroup align="center"></colgroup>
    <colgroup align="left"></colgroup>
    <colgroup span="1" align="center"></colgroup>
    <colgroup span="2" align="center"></colgroup>
    <colgroup span="3" align="center"></colgroup>
    <thead valign="top">
    <tr>
        <th>Factuur
            <br>ID
        </th>
        <th>Naam</th>
        <th>Adres</th>
        <th>Woonplaats</th>
        <th>Postcode</th>
        <th>Retour/Bestelling</th>
        <th>Bedrag</th>
    </tr>
    </thead>
    <?php
    //    Zet alle waarden in een table
    foreach ($result as $id => $value) { ?>
        <tbody>
        <tr>
            <td><?php echo $value["idOrders"] ?></td>
            <td><?php echo strtoupper($value["naam"][0]) . '.' . ucfirst($value["achternaam"]) ?></td>
            <td><?php echo ucfirst($value["straat"]) . $value["huisnummer"] ?></td>
            <td><?php echo ucfirst($value["woonplaats"]) ?></td>
            <td><?php echo strtoupper($value["postcode"]) ?></td>

            <?php if ($value["retourDatum"] == $now) { ?>
                <td>Retour</td>
            <?php } else { ?>
                <td>Bestelling</td>
            <?php } ?>
            <td> <?php echo $value["totaalprijs"] ?></td>
        </tr>
        </tbody>
    <?php } ?>
</table>
<script>
    //    Wanneer de pagina wordt geladen word de functie uitgevoerd en word je teruggeleid naar de leisten pagina
    window.onload = function () {
        tableToExcel('testTable', 'W3C Example Table', '<?php echo $now . $name ?>.xls');
        location.href = "lijsten.php"
    };
    //Functie voor het downloaden van excel lijst
    var tableToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,',
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            }, format = function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                })
            }
        return function (table, name, filename) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {
                worksheet: name || 'Worksheet',
                table: table.innerHTML
            }
            document.getElementById("dlink").href = uri + base64(format(template, ctx));
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
        }
    })()
</script>
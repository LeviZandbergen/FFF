<?php
$productID = $_POST['productID'];

$query = "SELECT * FROM product WHERE productID = '" . $productID . "'";
$stmt = $db->prepare($query);
$stmt->execute(array());
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="./css/article.css">
</head>
<body>

<div id="page-wrapper">

    <?php
    foreach ($products as $product) {
        $afbeelding = $product['afbeelding'];

        echo '<div class="productAfbeelding"><img src="' . $afbeelding . '" alt="Productafbeelding"></div>';
        echo '<br>';

        echo '<div class="descBox">';
        echo '<h1>' . $product['naam'] . '</h1>';

        echo '<p>' . $product['beschrijving'] . '</p>';
        echo '<br>';

        if ($product['prijs'] === NULL) {
            echo '<br>';
            echo 'Prijs per dag: €' . $product['prijsDag'] / 100;
            echo '<br>';
            echo 'Prijs per week: €' . $product['prijsWeek'] / 100;
            echo '<br>';
        } else {
            echo 'Koopprijs: €' . $product['prijs'] / 100;
            echo '<br><br><br>';
        }
        echo '</div>';
    }
    ?>

</div>
</body>
</html>
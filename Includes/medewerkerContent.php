<?php
if (isset($_SESSION["ID"]) && $_SESSION["STATUS"] === 1) {

} else {
    header("Location:index.php");
}
?>

<html>
<div class="content">
    <div class="medewerkerNavigatie">
        <a href="../FFF/lijsten.php" id="button1">Lijsten</a>
        <a href="../FFF/bestelRetour.php" id="button2">Bestelling/Retour</a>
    </div>
    <div class="medewerkerKorting">
        <form method="POST">
            <input type="email" name="email">Email
            <select name="korting">
                <option>5</option>
                <option>10</option>
                <option>15</option>
                <option>20</option>
                <option>25</option>
                <option>30</option>
                <option>35</option>
                <option>40</option>
                <option>45</option>
                <option>50</option>
                <option>55</option>
                <option>60</option>
                <option>65</option>
                <option>70</option>
                <option>75</option>
                <option>80</option>
                <option>85</option>
                <option>90</option>
                <option>95</option>
                <option>100</option>
            </select>%
            <input type="submit" name="submit" value="Geef korting">
        </form>
    </div>
</div>
</html>
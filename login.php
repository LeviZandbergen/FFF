<link href="../FFF/Style/style.css" rel="stylesheet">
<?php
include "Includes/header.php";
include "Includes/loginContent.php";
include "Includes/footer.php";
?>
<?php
$error = "";
//Wanneer er op login wordt geklikt
if (isset($_POST["submit"])) {
//    Pakt de variablen die zijn ingevuld
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["wachtwoord"]);

//    kijkt gebruiker bestaat
    try {
        $sql = "SELECT * FROM medewerker WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

//Kijkt of wachtwoord overeenkomt
        if ($result) {
            $hash = $result["wachtwoord"];

//            Zet gegevens in sessie
            if (password_verify($password, $hash)) {
                // $mijnSession = session_id();
                $_SESSION["ID"] = 1;
                $_SESSION["EMAIL"] = $result["email"];
                $_SESSION["STATUS"] = 1;
                echo "<script>location.href='/project-sites/fff/medewerker.php';</script>";
            } else {
                $error .= "Inloggegevens ongeldig. <br>";
            }
        } else {
            $error .= "Inloggegevens ongeldig test. <br>";
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
echo "<div id='meldingen'>" . $error . "</div>";
?>
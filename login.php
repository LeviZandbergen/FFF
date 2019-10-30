<link href="../FFF/Style/style.css" rel="stylesheet">
<?php
include('DBconfig.php');

include "Includes/header.php";
include "Includes/menu.php";
include "Includes/loginContent.php";
include "Includes/footer.php";
?>
<?php
$error = "";
if (isset($_POST["submit"])) {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["wachtwoord"]);

    try {
        $sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $hash = $result["wachtwoord"];

            if (password_verify($password, $hash)) {
                // $mijnSession = session_id();
                $_SESSION["ID"] = 1;
                $_SESSION["EMAIL"] = $result["email"];
                $_SESSION["STATUS"] = 1;
                echo "<script>location.href='http://localhost/project-sites/show-no-mercy/lijsten.php';</script>";
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
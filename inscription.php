<?php
//Données d'entête
header("Access-Control-Allow-Credentials:	true");
header("Access-Control-Allow-Headers:	X-API-KEY, X-FIELDS, CONTENT-TYPE, ACCEPT, ACCEPT-CHARSET, ACCEPT-LANGUAGE, CACHE-CONTROL, CONTENT-ENCODING, CONTENT-LENGTH, CONTENT-SECURITY-POLICY, CONTENT-TYPE, COOKIE, ETAG, HOST, IF-MODIFIED-SINCE, KEEP-ALIVE, LAST-MODIFIED, ORIGIN, REFERER, USER-AGENT, X-FORWARDED-FOR, X-FORWARDED-PORT, X-FORWARDED-PROTO");
header("Access-Control-Allow-Methods:	PUT, HEAD, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age:	21600");
//header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: public");
header("Pragma: public");
header("X-XSS-Protection:	1; mode=block");

$image = "../images/";
// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0)
{
        // Testons si le fichier n'est pas trop gros
        if ($_FILES['image']['size'] <= 1000000)
        {
                // Testons si l'extension est autorisée
                $infosfichier = pathinfo($_FILES['image']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array($extension_upload, $extensions_autorisees))
                {
                        // On peut valider le fichier et le stocker définitivement
                        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . basename($_FILES['image']['name']));
                        //echo "L'envoi a bien été effectué ! <br>";
                        $image = 'http://santesecurite.ci/api/images/' . basename($_FILES['image']['name']);
                }
        }
}

//Connection à la base de données

$servername = "localhost";
$username = "premices_secu";
$password = "omni2018";
$dbname = "premices_secu";
$charset = "utf8";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset", $username, $password);

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // prepare sql and bind parameters
    $stmt = $conn->prepare("INSERT INTO info_securite (categorie, lien, image, titre, contenu, date) VALUES (:categorie, :lien, :image, :titre, :contenu, NOW())");
    $stmt->bindParam(':categorie', $categorie);
    $stmt->bindParam(':lien', $lien);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':contenu', $contenu);

    // insert
    $categorie = $_POST['categorie'];
    $lien = $_POST['lien'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];

    $stmt->execute();
    // set the resulting

    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$conn = null;
echo('Information ajoutée avec succès!');
?>
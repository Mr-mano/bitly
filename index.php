<?php
//connection BD
try {
$bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', 'root');
} catch (Exception $e) {
    die('Erreur :' . $e->getMessage());
}


if (isset($_POST['url'])) {
    //création de la variable 
    $url = $_POST['url'];
    //vérifier si l'url valide
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        // n'est pas bonne
        header('location: ../bitly/?error=true&message=Adresse url non valide');
        //si on utilise plusieur header, stopper le script avec exit()
        exit();
    }

    

    //variable raccourcir l'url
    $shortcup = crypt($url, rand());
    

    //vérifier si l'url à déjà était proposée
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
    $req->execute(array($url));

    while ($result = $req->fetch()) {
        if ($result['x'] != 0) {
            header('location: ../bitly/?error=true&message=Adresse délà raccourcie');
            exit();
        }
    }
    //envoie en base de données
    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)')
    or die (print_r($bdd->errorInfo()));
    $req->execute(array($url, $shortcut));
    header('location: ../bitly/?short=' . $shortcup);
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/pnd" href="pictures/favico.png">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <title>Bitly</title>
</head>

<body>

    <section id="hello">
        <div class="container">
            <header>
                <img id="logo" src="pictures/logo.png" alt="logo bitly">
            </header>
            <h1>Une url longue ? Raccourcissez-là</h1>
            <h2>largement meilleur et plus court que les autres.</h2>
            <form method="post" action="index.php">
                <input type="url" name="url" placeholder="Placez votre url ici">
                <input type="submit" value="Raccourcir">
            </form>
            <?php
            if (isset($_GET['error']) && isset($_GET['message'])) { ?>
                <div class="center">
                    <div id="result">
                        <b><?php echo htmlspecialchars($_GET['message']); ?></b>
                    </div>
                </div>
            <?php } else if (isset($_GET['short'])) { ?>
                <div class="center">
                    <div id="result">
                        <b>URL RACCOURCIE : </b>http://localhost/q=<?php echo htmlspecialchars($_GET['short']); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        
    </section>
    <section id="brands">
        <div class="container">
            <h3>Ces marques nous font confiance</h3>
            <img class="picture" src="pictures/1.png" alt="logo">
            <img class="picture" src="pictures/2.png" alt="logo">
            <img class="picture" src="pictures/3.png" alt="logo">
            <img class="picture" src="pictures/4.png" alt="logo">
        </div>
    </section>
    <footer>
        <div class="container">
            <img class="logo_footer" src="pictures/logo2.png" alt="logo bitly">
            <p>2018 &copy Bitly</p>
            <a href="#">Contact</a> - <a href="#">A propos</a>
        </div>
    </footer>
</body>

</html>
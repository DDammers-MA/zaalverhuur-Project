<?php
require 'config.php';
require 'function.php';
$connection = dbConnect();

$sql = "SELECT id, naam, image FROM zaalen LIMIT 10";

$stmt = $connection->prepare($sql);
$stmt->execute();
$zaalen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="mainImg">

        <header class="header">
            <nav class="header__list">
                <li class="header__listItem header__listItem--left">
                    <img class="header__listImage" src="img/Almere zaalverhuur.png" alt="">
                </li>

                <div class="header__div">
                    <li class="header__listItem"><a href="">Contact</a></li>
                    <li class="header__listItem"><a href="">FAQ</a></li>
                </div>
            </nav>
        </header>

        <main class="main">

            <article class="main__articleTitle">
                <h1 class="main__title">De ideale locatie voor jouw speciale gelegenheid</h1>
            </article>

            <article class="main__secondArticle">
                <h2 class="main__secondTitle">Zalen</h2>

                <ul class="main__zalenList">
                    <?php foreach ($zaalen as $zaal) : ?>

                        <a href="zaal.php?id=<?php echo $zaal['id']; ?>">
                        <li class="main__zalenListItem">
                        <img class="main__zaalImg" src="img/<?php echo $zaal['image']; ?>" alt="">
                            <p class="main__zaalNaam"><?php echo $zaal['naam'];?></p>
                        </li>

                        </a>
                 
                    <?php endforeach; ?>
                </ul>
            </article>

        </main>
    </div>
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="views/styles/navbar.css">
    <link rel="stylesheet" href="views/styles/main.css">
    <link rel="stylesheet" href="views/styles/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="views/styles/state.css">
    <title>Написать статью</title>
</head>
<?php
$user_name = @$_SESSION['user']['user_data']['author_name'];
?>
<body>
<header>
    <nav>
        <ul class="navbar">
            <li class="logo"><a href="index.php" class="logo_link">Иллуин</a></li>
            <li><a href="index.php">Главная</a></li>
            <li><a href="">Проекты</a></li>
            <li><a href="">Рейтинг</a></li>
            <li><a href="">Контакты</a></li>
        </ul>
        <?php
            echo "<div class='account'><a href=''>$user_name</a>";
            echo "<a href='logout'>Выход</a></div>";
        ?>
    </nav>
</header>
<main>
    <form action="write" method="post">
        <input type="text" name="title" placeholder="Заголовок"><br>
        <textarea name="text"></textarea><br>
        <input type="submit" value="Опубликовать">
    </form>
</main>
</body>
</html>
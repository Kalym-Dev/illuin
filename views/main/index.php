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
    <?php
    if(!@$_SESSION['user']['authorization']){

    }else{
        $authorize = true;
        $user_name = @$_SESSION['user']['user_data']['author_name'];
    }
    ?>
    <title>Главная страница</title>
</head>
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
        <?php if(@$authorize){
            echo "<div class='account'><a href=''>$user_name</a>";
            if(@$_SESSION['user']['user_data']['status'] == 1) {
                echo "<a href='write' title='Напишем?'>Написать</a>";
            }
            echo "<a href='logout'>Выход</a></div>";
        }else{
            echo "<div class='account'><a href='register'>Регистрация</a><a href='login' title=''>Вход</a></div>";
        }
        ?>
    </nav>
</header>
<?php foreach ($data as $data){ //$data - это аргумент функции render в классе View
    $id = $data['id'];
    ?>
<main>
    <article style="background-color: white; box-shadow: 0 5px 10px 0 rgba(0,0,0,.1);">
        <a href="<?php echo  '?id=' . $data['id'];?>" class="title"><h3><?=$data['title'];?></h3></a>
        <p class="date">Дата публикации: <?=$data['date'];?></p>
        <p class="author">Автор: <?=$data['author'];?></p>
        <?php if((@$authorize) and @$_SESSION['user']['user_data']['status'] == 1 and ($_SESSION['user']['user_data']['author_id'] == $data['author_id'])){
        echo "<div class='tool'><a href=\"edit?id=$id\" class='pe-7s-pen' title='Изменить'></a><a href=\"delete?id=$id\" class='pe-7s-close-circle' title='Удалить'></a></div>";
        }?>

        <div class="text">
            <p><?=html_entity_decode($data['text']);?></p>
        </div>
        <?php if(empty($_GET['id'])) echo "<div class='read_but'><a href='?id=" . $data['id'] . "'" . "class='read'>Читать дальше</a></div>"; ?>

    </article>
</main>
<?php } ?>
</body>
</html>

<!--TODO убрать отсюда все что связанно с оформлением данных-->


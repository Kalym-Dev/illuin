<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="views/styles/form.css">
    <title>Вход</title>
</head>
<body>
<main>
    <div class="cont">
        <h3>Добро пожаловать!</h3>
        <div class="logo">Иллуин</div>
    </div>
    <form action="login" method="post">
        <input type="text" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Пароль"><br>
        <input type="submit" value="Войти" class="submit">
    </form>
</main>
</body>
</html>
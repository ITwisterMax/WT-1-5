<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>DBMS_PA</title>
</head>
<body>
    <form name="main_form" method="POST" action="analyzer.php">
        <div class="container">
            <label>Введите количество итераций:</label><input type="text" name="iterations"><br>
            <label>Введите SQL-запрос:</label><input type="text" name="query"><br>
        </div>
        <input type="submit" name="execute" value="Выполнить запрос">
    </form>
</body>

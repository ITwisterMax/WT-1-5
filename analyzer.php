<link rel="stylesheet" href="style.css">
<title>DBMS_PA</title>

<?php

include "graphic.php";

// проверка корректности ввода
function check($link) {
    if (!$link) return False;
    if (isset($_POST["iterations"]) && isset($_POST["query"]))
        if (ctype_digit($_POST["iterations"]) && intval($_POST["iterations"]) > 0 && !empty($query = mysqli_query($link, $_POST["query"])))
            return True;
        else
            return False;
}

// выполнение запроса и сбор данных
function send($iterations, $query, $link) {
    while ($iterations)
    {
        $time1 = microtime(True);
        $send_query = mysqli_query($link, $query);
        $time2 = microtime(True);
        $interval = round($time2 - $time1, 10);
        $data["time"][] = $interval;
        $iterations--;
    }
    return $data;
}

// сбор общей статистики
function print_general($data, $query) {
    $result = "<b>Количество итераций:</b> " . count($data["time"]) . "<br><b>Запрос:</b> " . $query .
            "<br><b>Минимальное время, затраченное на запрос (сек):</b> " . min($data["time"]) .
            "<br><b>Максимальное время, затраченное на запрос (сек):</b> " . max($data["time"]) .
            "<br><b>Среднее время, затраченное на запрос (сек):</b> " . round(array_sum($data["time"]) / count($data["time"]), 10) .
            "<br><b>Общее время, затраченное на запросы (сек):</b> " . array_sum($data["time"]) . "<br><br>";
    return $result;
}

// сбор статистики по каждому запросу
function print_query($data, $query) {
    $result = "";
    for ($i = 0; $i < count($data["time"]); $i++)
        $result .= "<b>Номер итерации:</b> " . ($i + 1) . "<br><b>Запрос:</b> " . $query .
                "<br><b>Время, затраченное на запрос (сек):</b> " . $data["time"][$i] . "<br><br>";
    return $result;
}

// главная часть программы
error_reporting(0);
if (check($link = mysqli_connect("", "", "")))
{
    // сохранение значений
    $iterations = $_POST["iterations"];
    $query = $_POST["query"];

    // выполнение запросов, сбор данных и отрисовка графика
    $data = send($iterations, $query, $link);
    draw($data, 850, 580);
    $result = "<div class=\"left_container\"><div class=\"left\"><b>Общая статистика по запросам:</b></div><br><div class=\"left\">" .
            print_general($data, $query) . "</div><br><div class=\"left\"><b>Статистика по каждому запросу:</b></div><br><div class=\"left\">" .
            print_query($data, $query) . "</div></div><div class=\"right_container\"><img src=\"graphic.png\"></div>";

    // вывод данных
    echo $result;

    // Закрытие соединения с СУБД
    mysqli_close($link);
}
else
    echo "<div class=\"left\">Ошибка! Проверьте введенные данные...</div>";

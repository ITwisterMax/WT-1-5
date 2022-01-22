<?
function draw($data, $width, $height)
{
    // параметры

    asort($data["time"]);
    // отступы от границы картинки
    $bottom = $width / 30;
    $left = $height / 50;
    $top_right = $width / 70;
    // ширина одного символа
    $letter_width = imagefontwidth(2);
    // корректировка левого отступа
    $text_width = strlen(strval(max($data["time"])));
    $left += ($text_width + 3) * $letter_width;
    // количество точек на графике
    $count = count($data["time"]);

    // изображение

    // создание изображения
    $image = imagecreate($width, $height);
    // цвет фона
    $background = imagecolorallocate($image, 231, 231, 231);
    // цвет сетки
    $grid_color = imagecolorallocate($image, 184, 184, 184);
    // цвет текста
    $text_color = imagecolorallocate($image, 136, 136, 136);
    // Цвета для линии графика
    $line_color = imagecolorallocate($image, 65, 170, 191);
    // реальные размеры графика
    $graphic_width = $width - $left - $top_right;
    $graphic_height = $height - $bottom - $top_right;
    // координаты нуля
    $X0 = $left;
    $Y0 = $height - $bottom;
    // шаг сетки
    $stepX = $graphic_width / $count;
    $stepY = $graphic_height / $count;

    // рисование

    // главная рамка графика
    imagefilledrectangle($image, $X0, $Y0 - $graphic_height, $X0 + $graphic_width, $Y0, $background);
    imagerectangle($image, $X0, $Y0, $X0 + $graphic_width, $Y0 - $graphic_height, $grid_color);
    // точка (0, 0)
    imagestring($image, 2, $X0 - $letter_width, $Y0 + ($left - $text_width) / 9, "0", $text_color);
    // сетка (ось OX)
    for ($i = 0; $i <= $count; $i++) 
    {
        $X = $X0 + $stepX * $i;
        imageline($image, $X, $Y0, $X, $Y0 - $graphic_height, $grid_color);
        imageline($image, $X, $Y0, $X, $Y0 + ($left - $text_width) / 10, $grid_color);
    }
    // надпись (ось OX)
    for ($i = 0; $i < $count; $i++) 
    {
        $str = strval($i + 1);
        $X = $X0 + $stepX * ($i + 1) - strlen($str) * $letter_width;
        imagestring($image, 2, $X, $Y0 + ($left - $text_width) / 9, $str, $text_color);
    }
    // сетка (ось OY)
    for ($i = 0; $i <= $count; $i++) 
    {
        $Y = $Y0 - $stepY * $i;
        imageline($image, $X0, $Y, $X0 + $graphic_width, $Y, $grid_color);
        imageline($image, $X0, $Y, $X0 - ($left - $text_width) / 10, $Y, $grid_color);
    }
    // надпись (ось OY)
    $i = 0;
    foreach ($data["time"] as $key => $value) 
    {
        $str = strval($value);
        $Y = $Y0 - $stepY * ($i + 1);
        imagestring($image, 2, $X0 - $left + $text_width, $Y, $str, $text_color);
        $i++;
    }
    // линия графика
    $points = Array();
    foreach ($data["time"] as $key => $value) 
        $points[] = $key + 1;
    asort($points);
    $prev_x = $X0;
    $prev_y = $Y0;
    foreach ($points as $key => $value)
    {
        $X = $X0 + $stepX * $value;
        $Y = $Y0 - $stepY * ($key + 1);
        imageline($image, $prev_x, $prev_y, $X, $Y, $line_color);
        $prev_x = $X;
        $prev_y = $Y;
    }

    // генерация изображения
    ImagePNG($image, "./graphic.png");
    imagedestroy($image);
}

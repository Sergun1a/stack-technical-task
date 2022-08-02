<?php
/**
 * Отображение элемента из json файла
 * @param $degreeOfNesting - степень вложенности
 * @param $text - текст элемента
 * @param $finite - конечный ли элемент (нет вложенных элементов)
 * @return void
 */
function printElement($degreeOfNesting, $text, $finite = false)
{
    if (is_bool($text)) {
        $text ? $text = "true" : $text = "false";
    }
    $outputHtml = "<pre>";
    // добавляю отступы
    $outputHtml .= str_repeat("     ", $degreeOfNesting);
    $finite ? $outputHtml .= "-" : $outputHtml .= "+ ";
    $outputHtml .= $finite ? $text . "</pre>" : "<b><i>" . $text . "</i></b></pre>";
    echo $outputHtml;
}

/**
 * Функция для рекурсивного обхода json файла
 * @param $data - данные из json файла
 * @param $degreeOfNesting - уровень вложенности
 * @return void
 */
function recursiveByPass($data, $degreeOfNesting)
{
    foreach ($data as $key => $value) {
        if (isset($value['isFolder']) && $value['isFolder']) {
            printElement($degreeOfNesting, $value['title']);
            recursiveByPass($value['children'], $degreeOfNesting + 1);
        } else {
            printElement($degreeOfNesting, $value['title'], true);
        }
    }
}

$pathToFile = "json.json";

$jsonFile = file_get_contents($pathToFile, "r");
//echo "<pre>" . $jsonFile . "</pre>";

if (!$jsonFile) {
    echo "<span>Json файл по пути \"" . $pathToFile . "\" не был найден </span>";
    return;
}
// конвертирую json в массив
$data = json_decode($jsonFile, true);

recursiveByPass($data, 1);

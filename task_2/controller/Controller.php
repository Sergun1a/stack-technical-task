<?php
require __DIR__ . "/../model/Book.php";

class Controller
{
    public function actionCreate()
    {
        $requestParams = $_GET;
        if (!isset($requestParams['name'])) {
            echo "Не указан необходимый параметр \"name\"";
            return;
        }
        if (!isset($requestParams['author'])) {
            echo "Не указан необходимый параметр \"author\"";
            return;
        }
        if (!isset($requestParams['year'])) {
            echo "Не указан необходимый параметр \"year\"";
            return;
        }
        $book = new Book($requestParams['name'], $requestParams['author'], $requestParams['year']);
        if ($book->save()) {
            echo "Успешно добавил новую книгу: " . $book->id . ", " . $requestParams['name'] . ", " . $requestParams['author'] . ", " . $requestParams['year'];
        } else {
            echo "Произошла ошибка по время добавления записи. Текст ошибки: " . $book->getErrorMessage();
        }
    }

    public function actionRead()
    {
        $requestParams = $_GET;
        $dbReqParams = [
            'name' => isset($requestParams['name']) ? $requestParams['name'] : null,
            'id' => isset($requestParams['id']) ? $requestParams['id'] : null,
            'author' => isset($requestParams['author']) ? $requestParams['author'] : null,
            'year' => isset($requestParams['year']) ? $requestParams['year'] : null
        ];
        header("content-type:json");
        echo json_encode(Book::find($dbReqParams));
    }

    public function actionUpdate()
    {
        $requestParams = $_GET;
        if (!isset($requestParams['id'])) {
            echo "Не указан необходимый параметр \"id\"";
            return;
        }
        $book = Book::findOneById($requestParams['id']);
        if (empty($book)) {
            echo "Книга с id=" . $requestParams['id'] . " не найдена";
            return;
        }
        $dbReqParams = [
            'name' => isset($requestParams['name']) ? $requestParams['name'] : null,
            'author' => isset($requestParams['author']) ? $requestParams['author'] : null,
            'year' => isset($requestParams['year']) ? $requestParams['year'] : null
        ];
        foreach ($dbReqParams as $key => $dbReqParam) {
            if (!empty($dbReqParam)) {
                $book->$key = $dbReqParam;
            }
        }
        if ($book->save()) {
            echo "Успешно обновил книгу: " . $book->id . ", " . $book->name . ", " . $book->author . ", " . $book->year;
        } else {
            echo "Произошла ошибка во время обновления записи. Текст ошибки: " . $book->getErrorMessage();
        }
    }

    public function actionDelete()
    {
        $requestParams = $_GET;
        if (!isset($requestParams['id'])) {
            echo "Не указан необходимый параметр \"id\"";
            return;
        }
        $book = Book::findOneById($requestParams['id']);
        if (empty($book)) {
            echo "Книга с id=" . $requestParams['id'] . " не найдена";
            return;
        }
        if ($book->delete()) {
            echo "Успешно удалил книгу с id=" . $requestParams['id'];
        } else {
            echo "Книга с id=" . $requestParams['id'] . " не была удалена";
        }
    }
}
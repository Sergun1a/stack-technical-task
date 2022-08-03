<?php
require __DIR__ . '/../config/db.php';


class Book
{
    private $db;
    private $errorMessage;
    private $isNew;


    function __construct($name, $author, $year, $id = null, $isNew = true)
    {
        $this->id = $id;
        $this->db = self::getDbInstance();
        $this->name = $name;
        $this->author = $author;
        $this->year = $year;
        $this->isNew = $isNew;
    }


    public static function table()
    {
        return "books";
    }

    public $id;
    public $name;
    public $author;
    public $year;

    /**
     * Возвращаю описание последней ошибки
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Проверка данных на корректность
     * @return void
     */
    public function validate()
    {
        $this->errorMessage = null;
        if (!(is_string($this->name) || is_numeric($this->name))) {
            $this->errorMessage = "Значение в поле \"name\" должно быть строкой или числом";
        }
        if (!(is_string($this->author) || is_numeric($this->author))) {
            $this->errorMessage = "Значение в поле \"author\" должно быть строкой или числом";
        }
        if (!(is_int((int)$this->year) && $this->year <= date("Y"))) {
            $this->errorMessage = "Значение в поле \"year\" должно быть целым числом не превышающим текущий год";
        }
    }

    /**
     * Добавляю/обновляю запись в базе данных
     * @param $runValidation
     * @return false|void
     */
    public function save($runValidation = true)
    {
        if ($runValidation) {
            $this->validate();
            if (!empty($this->errorMessage)) {
                return false;
            }
        }
        if ($this->isNew) {
            $query = "insert into " . self::table() . "(name, author, year) values(\"" . mysqli_real_escape_string($this->db, $this->name) . "\",\""
                . mysqli_real_escape_string($this->db, $this->author) . "\",\"" . mysqli_real_escape_string($this->db, $this->year) . "\")";
            $res = $this->db->query($query);
            if ($res) {
                $this->id = $this->db->insert_id;
                $this->isNew = false;
                return true;
            }
            return false;
        }
        $query = "update " . self::table() . " set name=\"" . mysqli_real_escape_string($this->db, $this->name) . "\", author=\"" .
            mysqli_real_escape_string($this->db, $this->author) . "\", year=\"" . mysqli_real_escape_string($this->db, $this->year) . "\" where id=\"" . mysqli_real_escape_string($this->db, $this->id) . "\"";
        $res = $this->db->query($query);
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * Возвращаю класс для работы с базой данных
     * @return mysqli
     */
    private static function getDbInstance()
    {
        $dbConfig = include(__DIR__ . "/../config/db.php");
        return new \mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
    }

    /**
     * Поиск записей в таблице
     * @param $paramsArr
     * @return array
     */
    public static function find($paramsArr = [])
    {
        $db = self::getDbInstance();
        $query = "select * from " . self::table();
        $wherePart = "";
        foreach ($paramsArr as $key => $value) {
            if (!empty($value)) {
                $wherePart .= " " . $key . "=\"" . mysqli_real_escape_string($db, $value) . "\" and ";
            }
        }
        if (!empty($wherePart)) {
            $query .= " where " . $wherePart;
            // удаляю последний and из запроса
            $query = substr($query, 0, strlen($query) - 5);
        }
        $res = $db->query($query);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Поиск книги по id
     * @param $id
     * @return Book
     */
    public static function findOneById($id)
    {
        $db = self::getDbInstance();
        $query = "select * from " . self::table() . " where id = \"" . mysqli_real_escape_string($db, $id) . "\"";
        $res = $db->query($query);
        $book = $res->fetch_assoc();
        if (!empty($book)) {
            return new Book($book['name'], $book['author'], $book['year'], $book['id'], false);
        }
        return null;
    }

    /**
     * Удаляю запись из базы данных
     * @return bool
     */
    public function delete()
    {
        $res = $this->db->query("Delete from " . self::table() . " where id=" . $this->id);
        if (mysqli_affected_rows($this->db) > 0) {
            $this->id = null;
            $this->isNew = true;
            return true;
        }
        return false;
    }

}
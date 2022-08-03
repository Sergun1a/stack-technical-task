# Тестовое задание №2

Postman коллекция лежит в корне этой папке в файле postman_collection. Формат JSON.

В папке db лежит sql файл с дампом структуры базы данных. Данные для подключения к базе данных лежат в config/db.php. Не
забудьте поправить их под себя для корректной работы.

В controller/Controller.php происходит выполнения действия указанного в запросе. Большая часть логики реализована в
model/Book.php.

В качестве СУБД использовался MySQL.

# Немного о запросах
1. {{hostname}}/task_2?action=read - получение списка книг из таблицы.
2. {{hostname}}/task_2?action=read&(name|author|year|id)=value - поиск книг по имени, автору, году или id. Поиск можно вести по одному или нескольким параметрам. 
3. {{hostname}}/task_2?action=create&name=book_name&author=book_author&year=2022 - добавление в базу новой книги. Все указанные параметры в этом запросе обязательны.
4. {{hostname}}/task_2?action=create&id=1&name=another_book_name - обновить данные книги по её id. 
5. {{hostname}}/task_2?action=delete&id=1 - удалить книгу по id.
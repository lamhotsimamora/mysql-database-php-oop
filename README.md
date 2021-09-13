# mysql-database-php-oop

MySQL OOP Class For connect, insert, update, delete, select, and more. And also with beautiful syntax.

## Example Syntax


```
$db = new DB();

$obj = $db->select('*')
            ->from('table_name')
            ->get();

/*
* @return JSON
*/
echo $obj->toJson();

```
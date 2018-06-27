## Подключение к базе данных
```php
$db = new.PDO('mysql:host=localhost;dbname=filmoteka','root','');
```

## Выборка данных
```php
$result = $db->query($sql);
```

## Получение записей по одной
```php
while ($film = $result->fetch(PDO::FETCH_ASSOC)){
	//....
}
```

## Получение всех записей в массив
```php
$films = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($films as $film) {
  //...
}
```

## Сопоставление поля переменной 
```php
$result->bindColumn('genre', $genre);
// $genre можно использовать в цикле while fetch...
```

## Количество обработанных записей
```php
$result->rowCount();
```

## Защита он инъекций в ручном режиме
```php
$title = $db->quote($title);
$title = strtr($title, array('_' => '\_', '%' => '\%'));
```


## Установка именoванных параметров
```php
$sql = "select * from films where title = :title and genre = :genre limit 1";
$result = $db->prepare($sql);
$result->bindValue('title',$title);
$result->bindValue('genre',$genre);
$result->execute();
//либо в методе execute
//$result->execute(array('title'=>$title, 'genre'=>$genre));
```

## Установка не именoванных параметров
```php
$sql = "select * from films where title = ? and genre = ? limit 1";
$result = $db->prepare($sql);
$result->bindValue(1,$title);
$result->bindValue(2,$genre);
$result->execute();
//либо в методе execute 
//$result->execute(array($title,$genre));
```

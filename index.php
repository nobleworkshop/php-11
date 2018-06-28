<?php

 $db = new PDO('mysql:host=localhost;dbname=filmoteka','root','');

//  $sql = "SELECT * FROM films";
//  $result = $db->query($sql);

//  echo "<h2>Вывод записей из результата по одной: </h2>";
//  while ( $film = $result->fetch(PDO::FETCH_ASSOC) ) {
//  print_r($film);
//  }

// echo "<hr />";

// $sql = "SELECT * FROM films";
// $result = $db->query($sql);
// $films = $result->fetchAll(PDO::FETCH_ASSOC);

// echo "<h2>Выборка все записей в массив и вывод на экран: </h2>";
// foreach ($films as $film) {
//   echo "Название фильма: " . $film['title'] . "<br>";
//  echo "Жанр фильма: " . $film['genre'] . "<br>";
//  echo "<br><br>";
// }

echo "<hr />";

$sql = "SELECT * FROM films";
$result = $db->query($sql);

$result->bindColumn('id', $id);
$result->bindColumn('title', $title);
$result->bindColumn('genre', $genre);
$result->bindColumn('year', $year);

echo "<h2>Вывод записей с привязкой данных к переменным: </h2>";
while ( $result->fetch() ) {
  echo "ID: {$id} <br>";
  echo "Название: {$title} <br>";
  echo "Жанр: {$genre} <br>";
  echo "Год: {$year} <br>";
  echo "<br><br>";
}


// 1. Выборка без защиты от SQL инъекции 
 $title = '8 подруг Оушена';
 $genre = 'боевик, комедия';

 $sql = "select * from films where title = '{$title}' and genre = '{$genre}' limit 1";
 $result = $db->query($sql);

 echo "<h2>Выборка записи без защиты от SQL инъекции:</h2>";
// print_r( $result->fetch(PDO::FETCH_ASSOC) );
 if ( $result->rowCount() == 1 ) {
  $film = $result->fetch(PDO::FETCH_ASSOC);
  echo "Фильм: {$film['title']} <br>"; 
  echo "Жанр: {$film['genre']} <br>"; 
 }

//Защита от иньекций в ручном режиме
 $title = "8 подруг Оушена";
 $genre = 'боевик, комедия';

$title = $db->quote($title);
$title = strtr($title, array('_' => '\_', '%' => '\%'));
$genre = $db->quote($genre);
$genre = strtr($genre, array('_' => '\_', '%' => '\%'));


 $sql = "select * from films where title = {$title} and genre = {$genre} limit 1";
 $result = $db->query($sql);

 echo "<h2>Выборка записи с защитой от SQL инъекции В ручном режиме:</h2>";
// print_r( $result->fetch(PDO::FETCH_ASSOC) );
 if ( $result->rowCount() == 1 ) {
  $film = $result->fetch(PDO::FETCH_ASSOC);
  echo "Фильм: {$film['title']} <br>"; 
  echo "Жанр: {$film['genre']} <br>"; 
 }

 $title = "<script> alert('Hello!'); </script>";
 echo '<h3>Без защиты</h3>';
 echo  $title;
echo '<h3>С защитой</h3>';
 $title = htmlentities($title);
 echo  $title;

//3. Автоматическая защита от инъекций (использование параметров запросов)
$title = "8 подруг Оушена";
$genre = 'боевик, комедия';

$sql = "select * from films where title = :title and genre = :genre limit 1";
$result = $db->prepare($sql);
// $result->bindValue('title',$title);
// $result->bindValue('genre',$genre);
//$result->execute();
$result->execute(array('title'=>$title, 'genre'=>$genre));

 echo "<h2>Автоматическая защита от инъекций (использование параметров запросов):</h2>";
// print_r( $result->fetch(PDO::FETCH_ASSOC) );
 if ( $result->rowCount() == 1 ) {
  $film = $result->fetch(PDO::FETCH_ASSOC);
  echo "Фильм: {$film['title']} <br>"; 
  echo "Жанр: {$film['genre']} <br>"; 
 } 

//4. Не именнованные параметры
$sql = "select * from films where title = ? and genre = ? limit 1";
$result = $db->prepare($sql);
// $result->bindValue(1,$title);
// $result->bindValue(2,$genre);
//$result->execute();
$result->execute(array($title,$genre));
 if ( $result->rowCount() == 1 ) {
  $film = $result->fetch(PDO::FETCH_ASSOC);
  echo "Фильм: {$film['title']} <br>"; 
  echo "Жанр: {$film['genre']} <br>"; 
 } 


 //Вставка записи

 echo "<h2>Вставка записи:</h2>";
$sql = "INSERT INTO films (title, genre) VALUES (:title, :genre )";
$query = $db->prepare($sql);

$title = "Taxi 2";
$genre = 'боевик';

$query->execute(array('genre' => $genre, 'title'=>$title)); 

echo "<p>Было затронуто строк: " . $query->rowCount() . "</p>";
echo "<p>ID вставленной записи: " . $db->lastInsertId() . "</p>";
$filmId = $db->lastInsertId();

 echo "<h2>Изменение записи:</h2>";
$sql = "UPDATE films SET title = :title, genre = :genre WHERE id=:id LIMIT 1";
$query = $db->prepare($sql);

$title = "Taxi 4";
$genre = 'комедия';

$query->execute(array('genre' => $genre, 'title'=>$title, 'id' => $filmId)); 

echo "<p>Было изменено строк: " . $query->rowCount() . "</p>";

 echo "<h2>Удаление записи:</h2>";
$sql = "DELETE FROM films WHERE id=:id LIMIT 1";
$query = $db->prepare($sql);


$query->execute(array('id' => $filmId)); 

echo "<p>Было удалено строк: " . $query->rowCount() . "</p>";

?>
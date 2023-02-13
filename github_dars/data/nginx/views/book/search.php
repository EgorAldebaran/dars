<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?> 

<!doctype html>
<html lang="en">
    <head>
	<meta charset="UTF-8"/>
	<title>search</title>
    </head>
    <body>
	<h3><?= $message  ?></h3>
	<form action="http://localhost/book/genre" method="GET">
	    <h4>Введите данные для поиска жанра</h4>
	    <input name="search" type="text" value=""/>
	    <input type="submit" value="Найти"/><br> 
	</form>
	<br/>
	<form action="http://localhost/book/author" method="GET">
	    <h4>Введите имя Автора</h4>
	    <input name="search" type="text" value=""/>
	    <input type="submit" value="Найти"/><br> 
	</form>
	<br/>
	<form action="http://localhost/book/book" method="GET">
	    <h4>Введите название книги</h4>
	    <input name="search" type="text" value=""/>
	    <input type="submit" value="Найти"/><br> 
	</form>
	<!-- Отсюда пусть выводится с пагинацией все книги которые есть -->
	<table class="table table-striped table-hover">
	    <tr>
		<td>Автор</td>
		<td>Название Книги</td>
		<td>Публикация</td>
	    </tr>
	    <?php  foreach ($books as $book): ?> 
		<tr>
		    <td><?= Html::encode("{$book->title}")  ?></td>
		    <td><?= Html::encode("{$book->author->name}")  ?></td>
		    <td><?= Html::encode("{$book->publication_date}")  ?></td>
		</tr>
	    <?php endforeach;  ?>
	</table>
	<?= LinkPager::widget(['pagination' => $pagination]) ?>


<!doctype html>
<html lang="en">
    <head>
	<meta charset="UTF-8"/>
	<title>gate</title>
    </head>
    <body>
	<form action="http://localhost/book/genre" method="GET">
	    <h4>Введите данные для поиска жанра</h4>
	    <input name="search" type="text" value=""/>
	    <input type="submit" value="Найти"/><br> 
	    <?= $message  ?>
	</form>
    </body>
</html>


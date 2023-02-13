<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

?>
    <a href="<?= Url::toRoute(['book/search'])  ?>">НАЗАД к поисковику</a>

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

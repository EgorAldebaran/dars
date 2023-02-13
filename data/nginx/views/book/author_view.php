<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

?>

<a href="<?= Url::toRoute(['book/search'])  ?>">НАЗАД к поисковику</a>


<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        //  'id',
        'name',
        'country',
    ],
]); ?>

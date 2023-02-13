<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
    <h4>Введите почту и пароль для входа</h4>
    
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password') ?>
<?= $message  ?>

<div class="form-group">
<?= Html::submitButton('Войти', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

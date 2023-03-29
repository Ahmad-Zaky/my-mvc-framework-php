<h1>Login</h1>

<?php $form = \App\Core\Form\Form::begin('', "POST", $model); ?>

    <?= $form->field("email")->email() ?>
    <?= $form->field("password")->password() ?>
    
    <button type="submit" class="btn btn-primary">Submit</button>

<?= \App\Core\Form\Form::end(); ?>
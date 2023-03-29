<h1>Login</h1>

<?php $form = \App\Core\Form\Form::begin('', "POST", $model); ?>

    <?= $form->input("email")->email() ?>
    <?= $form->input("password")->password() ?>
    
    <button type="submit" class="btn btn-primary">Submit</button>

<?= \App\Core\Form\Form::end(); ?>
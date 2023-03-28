<h1>Register</h1>

<?php $form = \App\Core\Form\Form::begin('', "POST", $model); ?>

<div class="row">
    <div class="col">
        <?= $form->field("first_name") ?>
    </div>
    <div class="col">
        <?= $form->field("last_name") ?>
    </div>
</div>

<?= $form->field("email")->email() ?>
<?= $form->field("password")->password() ?>
<?= $form->field("password_confirmation")->password() ?>

<button type="submit" class="btn btn-primary">Submit</button>

<?= \App\Core\Form\Form::end(); ?>

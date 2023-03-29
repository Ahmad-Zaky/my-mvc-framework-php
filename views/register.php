<h1>Register</h1>

<?php $form = \App\Core\Form\Form::begin('', "POST", $model); ?>

<div class="row">
    <div class="col">
        <?= $form->input("first_name") ?>
    </div>
    <div class="col">
        <?= $form->input("last_name") ?>
    </div>
</div>

<?= $form->input("email")->email() ?>
<?= $form->input("password")->password() ?>
<?= $form->input("password_confirmation")->password() ?>

<button type="submit" class="btn btn-primary">Submit</button>

<?= \App\Core\Form\Form::end(); ?>

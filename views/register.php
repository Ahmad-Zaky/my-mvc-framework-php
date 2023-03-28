<h1>Register</h1>

<form method="POST">

    <div class="row">
        <div class="col">
            <div class="form-group my-2">
                <label for="first_name">First Name</label>
                
                <input 
                    type="text"
                    class="form-control <?= $model->hasErrors("first_name") ? 'is-invalid' : '' ?>"
                    name="first_name"
                    id="first_name"
                    value="<?= $model->first_name ?? '' ?>"
                >

                <div class="invalid-feedback">
                    <ul>
                        <?php foreach ($model->errors["first_name"] as $error): ?>
                        
                            <li><?= $error ?></li>

                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group my-2">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name">
            </div>
        </div>
    </div>

    <div class="form-group my-2">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email">
    </div>

    <div class="form-group my-2">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password">
    </div>

    <div class="form-group my-2">
        <label for="password_confirmation">Password Confirmation</label>
        <input type="password_confirmation" class="form-control" name="password_confirmation" id="password_confirmation">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

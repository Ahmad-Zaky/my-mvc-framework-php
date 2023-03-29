<?php use App\Core\Application; ?>

<h1>Profile</h1>

<h2>Name: <?= Application::auth()->name() ?> </h2>
<h2>Email: <?= Application::auth()->email ?> </h2>
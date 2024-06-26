<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

// Validate the form input
$errors = [];

if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a password of at least 7 characters and max 255 characters.';
}

if (!empty($errors)) {
    return view('registration/create.view.php', [
        'errors' => $errors
    ]);
}

$db = App::resolve(Database::class);
// Check if the account already exists
$user = $db->query('SELECT * FROM users WHERE email =:email', [
    // Send the email through
    'email' => $email
])->find();

// If yes, redirect to a logged in page
if ($user) {
    header('location: /');
    exit();
} else {
    // If not, save the user to the databse, log the user in, and redirect
    $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    // Mark that the user has logged in
    $_SESSION['user'] = [
        'email' => $email
    ];

    header('location: /');
    exit();
}

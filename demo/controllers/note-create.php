<?php

$heading = 'Create New Note';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    dd($_POST);
}

require 'views/note-create.view.php';
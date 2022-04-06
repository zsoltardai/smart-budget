<?php

include_once 'header.php';

$firstname = $lastname = $email = '';

var_dump($_POST);

if (isset($_POST['first-name']) && isset($_POST['last-name']) && isset($_POST['id'])) {

    $id = $_POST['id'];
    $firstname = trim($_POST['first-name']);
    $lastname = trim($_POST['last-name']);

    $path = 'users/'.$id;

    var_dump([$id, $firstname, $lastname]);

    if (FileSystem::directory_exists($path, $error)) {
        if (FileSystem::read_file($path.'/user.json', $json, $error)) {

            $user = User::fromJSON($json);

            $user->setFirstname($firstname);
            $user->setLastname($lastname);

            if (FileSystem::write_file($path.'/user.json', $user->toJSON(), $error)) {

                header('location: profile.php?id='.$id);

            }
        }
    }

    header('location: profile.php?id='.$id.'&error=Failed to update profile data!');
}


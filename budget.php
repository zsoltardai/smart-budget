<?php

include_once 'base/Common.php';
include_once 'base/FileSystem.php';
include_once 'base/User.php';
include_once 'base/Item.php';

session_start();

global $categories;

var_dump($_POST);

if (isset($_POST['id']) && isset($_POST['product-name']) && isset($_POST['product-price'])
    && isset($_POST['product-category'])) {

    $id = $name = $price = $category = '';
    $id_error = $name_error = $category_error = '';

    if (empty(trim($_POST['id']))) {
        $id_error = 'You did not provide a valid id!';
    }

    $id = trim($_POST['id']);

    if (empty(trim($_POST['product-name']))) {
        $id_error = 'You did not provide a valid name!';
    }

    $name = trim($_POST['product-name']);

    if (empty(trim($_POST['product-price']))) {
        $id_error = 'You did not provide a valid price!';
    }

    if (!floatval($_POST['product-price'])) {
        $id_error = 'The provided price was invalid!';
    }

    $price = trim($_POST['product-price']);

    if (empty(trim($_POST['product-category'])) || !in_array(trim($_POST['product-category']), $categories)) {
        $id_error = 'You did not provide a valid category!';
    }

    $category = trim($_POST['product-category']);

    $path = 'users/'.$id;

    if (FileSystem::directory_exists($path, $error)) {

        if (!FileSystem::file_exists($path.'/budget.json', $error)) {
            $budget = [];
            if (!FileSystem::write_file($path.'/budget.json', AES::encrypt($_SESSION['key'], json_encode($budget)),  $error)) {
                header('location: index.php?status=failed&error=Failed to add a new item to the budget!');
            }
        }

        if (FileSystem::read_file($path.'/budget.json', $json, $error)) {

            $budget = json_decode(AES::decrypt($_SESSION['key'], $json), true);

            if (!key_exists(date('Y-m'), $budget)) {
                $budget[date('Y-m')] = [];
            }

            $budget[date('Y-m')][] = new Item($name, floatval($price), $category);

            if (FileSystem::write_file($path.'/budget.json', AES::encrypt($_SESSION['key'], json_encode($budget)), $error)) {

                header('location: index.php?status=success');

            }
        }
    }
}


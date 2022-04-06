<?php

include_once 'base/FileSystem.php';
include_once 'base/Logger.php';

$LogType = array(
    'System' => 'Sys',
    'Registration' => 'Rgr',
    'Login' => 'Lgn'
);

function createAlert($alert_message) : string {
    $alert_id = 'alert-id-'.uniqid();
    return <<< ALERT
            <div id="$alert_id" class="alert alert-danger d-flex justify-content-between align-items-center p-2 m-4">
                <p>$alert_message</p>
                <i class="fa-solid fa-x" style="cursor: pointer;" onclick="removeElementById('$alert_id')">X</i>
            </div>
ALERT;
}

$categories = array();

function readCategories(array &$categories) : void {

    global $LogType;

    $path = 'static/categories.json';

    if (!FileSystem::file_exists($path, $error)) {

        if (!FileSystem::write_file($path, '[]', $error)) {
            Logger::log($LogType['System'], 'Failed to create the following file: ');
        }
    }

    if (FileSystem::read_file($path, $json, $error)) {
        $categories = json_decode($json);
    }
}

function addCategory(string $category) : void {
    global $categories;

    $categories[] = $category;

    $json = json_encode($categories);

    if (!FileSystem::write_file('static/categories.json', $json, $error)) {
        return;
    }

    readCategories($categories);
}

readCategories($categories);
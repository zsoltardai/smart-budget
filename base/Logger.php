<?php

include_once 'FileSystem.php';

class Logger
{
    public static function log(string $type, string $message) {
        $text = '';

        if (FileSystem::file_exists('static/logs.txt', $error)) {
            if (FileSystem::read_file('static/logs.txt', $text, $error)) {
                return;
            }
        }

        $text = $text.'['.$type.'] '.$message.";\n";

        FileSystem::write_file('static/logs.txt', $text, $error);
    }
}

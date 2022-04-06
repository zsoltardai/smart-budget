<?php

class FileSystem
{
    public static function read_file(string $file_name, ?string &$text, ?string &$error) : bool
    {
        $file = fopen($file_name, 'r');
        if(!$file) {
            $error = "The file: $file_name does not exist.";
            return false;
        }
        while ($line = fgets($file)) {
            $text = $text.$line;
        }
        fclose($file);
        return true;
    }

    public static function write_file(string $path, string $text, ?string &$error) : bool
    {
        $file = fopen($path, 'w');
        if (!$file) {
            $error = "Failed to write to the file: $path";
            return false;
        }
        if(!fwrite($file, $text)) return false;
        if (!fclose($file)) return false;
        return true;
    }

    public static function create_file(string $path, ?string &$error) : bool
    {
        if(is_file($path)) {
            $error = "The file: $path, already exists.";
            return false;
        }

        $file = fopen($path, "w");

        if(!$file) {
            $error = "Failed to create the file: $path.";
            return false;
        }

        if(!fclose($file)) {
            return false;
        }

        return true;
    }

    public static function is_empty_directory(string $path, ?string &$error) : bool
    {
        if(!is_dir($path)) {
            $error = "The directory: $path, does not exist.";
            return false;
        }

        if(count(scandir($path))!==2) {
            $error = "The directory: $path, is not empty.";
            return false;
        }

        return true;
    }

    public static function list_directory(string $path, ?array &$files, ?string &$error) : bool
    {

        if(self::is_empty_directory($path, $error)) {
            return false;
        }

        $files = scandir($path);
        $files = array_diff($files, [".", ".."]);

        return true;
    }

    public static function create_directory(string $path, ?string &$error) : bool
    {
        if(is_dir($path)) {
            $error = "The directory: $path already exists.";
            return false;
        }

        if(!mkdir($path)) {
            $error = "Failed to create the directory: $path.";
            return false;
        }

        return true;
    }

    public static function file_exists(string $path, ?string &$error) : bool
    {
        if (file_exists($path)) return true;
        $error = 'The requested file does not exist!';
        return false;
    }

    public static function directory_exists(string $path, ?string &$error) : bool
    {
        if (is_dir($path)) return true;
        $error = 'The requested directory does not exist!';
        return false;
    }
}

<?php

function getFiles($dir)
{
    require './config/conf.php';
    $directory = $share . $dir;  // Change this to the path of your directory
    $filelist=[];
    // Check if the directory exists
    if (is_dir($directory)) {

        // Get all entries in the directory
        $entries = scandir($directory);

        // Filter out "." and ".." (current directory and parent directory)
        $files = array_filter($entries, function ($entry) use ($directory) {
            return is_file($directory . '/' . $entry) && $entry != '.' && $entry != '..';
        });

        foreach ($files as $file){
            $path = $directory ."/" . $file;
            $hash = md5_file($path);
            $filelist[] = ['path' => $path,'filename'=> $file, 'hash' => $hash, 'tag' => $dir];
        };

        return $filelist;
    } else {

        echo "The specified directory does not exist.\n";
    }
}
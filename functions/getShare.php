<?php
function getShare(){
    require './config/conf.php';
// Check if the directory exists
if (is_dir($share)) {

    // Get all entries in the directory
    $entries = scandir($share);

    // Filter out "." and ".." (current directory and parent directory)
    $folders = array_filter($entries, function ($entry) use ($share) {
        return is_dir($share . '/' . $entry) && $entry != '.' && $entry != '..';
    });

    // Convert the array of folders to a simple indexed array
    $folderArray = array_values($folders);

    // Print the list of folders
    return $folderArray;

} else {
    echo "The specified directory does not exist.\n";
}

}
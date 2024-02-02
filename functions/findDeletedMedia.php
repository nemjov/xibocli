<?php
function findDeletedMedia($files, $hash, $tag){
    $rowFound = false;

// Loop through the array to find the row
    foreach ($files as $row) {
        if ($row['hash'] == $hash && $row['tag'] == $tag) {
            $rowFound = true;
            break;
        }
    }
    if ($rowFound) {
    } else {
        return false;
    }
}
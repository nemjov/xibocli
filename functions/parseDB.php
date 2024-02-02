<?php
function parseDB($mediaDB,$hash,$tag){
    $rowFound = false;

// Loop through the array to find the row
    foreach ($mediaDB as $row) {
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

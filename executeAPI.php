<?php
require './config/conf.php';
require './functions/auth.php';
require './functions/getMedia.php';
require './functions/getShare.php';
require './functions/getFiles.php';
require './functions/parseDB.php';
require './functions/sendFiles.php';
require './functions/setMediaLibrary.php';
require './functions/findDeletedMedia.php';
require './functions/deleteMedia.php';

// CREATE LOG FOLDER IF NOT EXIST
if (!is_dir($logPath)) {
    if (mkdir($logPath, 0777, true)) {
    }
}
// AUTHENTICATION
$accessToken = getAuth();

// SHARE / DIRECTORY -> GET ALL FOLDERS
$directories = getShare();

// INSERT FOR EACH LOOP HERE FOR DIRECTORIES
$files = [];
foreach ($directories as $directory) {
    // GET FILES
    $files = array_merge($files, getFiles($directory));
}

// SET MEDIA FOLDER IN XIBO CMS
$mediaLibrary = setMediaLibrary($accessToken); // $folder is defined in conf.php

// MEDIA INFO
$notFound = [];
$mediaDB = [];
foreach ($directories as $tag) {
    $fullMediaData = getMedia($tag, $accessToken);
    $fullMediaData = json_decode($fullMediaData, true);

    // FOR EACH FILE INSIDE A DIRECTORY -> CREATE AN ARRAY FOR PARSING DATA
    foreach ($fullMediaData as $item) {
        $mediaDB[] = [
            'mediaId' => $item['mediaId'],
            'hash' => $item['md5'],
            'tag' => $item['tags'][0]['tag']
        ];
    }
}

// FIND FILES NOT MATCHING SERVER CONTENT
foreach ($files as $row) {
    $state = parseDB($mediaDB, $row['hash'], $row['tag']);
    if ($state === false) {
        $notFound[] = [
            'path' => $row['path'],
            'hash' => $row['hash'],
            'tag' => $row['tag']
        ];
    }
}

// UPLOAD FILES IF THEY ARE NOT FOUND ON THE XIBO CMS
foreach ($notFound as $row) {
    sendFiles($accessToken, $row['path'], $row['tag'], $mediaLibrary);
    file_put_contents('./logs/upload.log', "Files uploaded: ".$row['path']. ", TAG: ". $row['tag']."\n",FILE_APPEND);
}

// CHECK IF MEDIA ON THE SERVER HAS BEEN DELETED FROM THE SHARES
$deleted = [];
foreach ($mediaDB as $row) {
    $isDeleted = findDeletedMedia($files, $row['hash'], $row['tag']);
    if ($isDeleted === false) {
        $deleted[] = [
            'id' => $row['mediaId'],
            'tag' => $row['tag'],
        ];
    }
}

foreach ($deleted as $row) {
    deleteMedia($row['id'], $accessToken);
    file_put_contents('./logs/delete.log', "Media deleted: TAG: ".$row['tag'].", MediaID: ".$row['id']."\n",FILE_APPEND);
}

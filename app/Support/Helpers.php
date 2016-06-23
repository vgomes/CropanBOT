<?php
use Doctrine\Common\Cache\FilesystemCache;
use RemoteImageUploader\Factory;

/**
 * Get a list of allowed users from the .env file
 * @return array
 */
function getAllowedUsers()
{
    $users = env('ALLOWED_USERS');
    return explode('|', $users);
}

/**
 * Check if the given user id is within the allowed users' ids
 * @param $id
 * @return bool
 */
function isAllowedUser($id)
{
    return in_array($id, getAllowedUsers());
}

/**
 * Gets the url to a Telegram photo
 * @param $photos
 * @return string
 */
function urlFromTelegramPhoto($photos)
{
    $photo = $photos[count($photos) - 1];
    $path = Telegram::getFile(['file_id' => $photo['file_id']])->getFilePath();

    $url = getTelegramMediaFileUrl($path);
    return $url;
}

/**
 * Builds the url to a Telegram file
 * @param $path
 * @return string
 */
function getTelegramMediaFileUrl($path)
{
    $token = env('TELEGRAM_BOT_TOKEN');
    $url = "https://api.telegram.org/file/bot$token/$path";

    return $url;
}

/**
 * Gets the url to a Telegram document
 * @param $document
 * @return string
 */
function urlFromTelegramDocument($document)
{
    $path = Telegram::getFile(['file_id' => $document->getFileId()])->getFilePath();

    $url = getTelegramMediaFileUrl($path);
    return $url;
}

/**
 * Uploads image to Imgur from a url
 * @param $url
 * @return string
 */
function uploadToImgur($url) {
    $cacher = new FilesystemCache('/tmp');
    $uploader = Factory::create('Imgur', [
        'cacher' => $cacher,
        'api_key' => env('IMGUR_KEY'),
        'api_secret' => env('IMGUR_SECRET'),
        'refresh_token' => env('IMGUR_REFRESH_TOKEN')
    ]);

    $url = $uploader->transload($url);
    return $url;
}
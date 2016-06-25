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
    $users = explode('|', $users);

    $allowed_users = [];

    foreach ($users as $user) {
        $aux = explode('#', $user);
        $u['nickname'] = $aux[0];
        $u['telegram_id'] = $aux[1];

        $allowed_users[] = $u;
    }

    return $allowed_users;
}

/**
 * Check if the given user id is within the allowed users' ids
 * @param $id
 * @return bool
 */
function isAllowedUserId($id)
{
    $users = getAllowedUsers();

    foreach ($users as $user) {
        if ($user['telegram_id'] == $id) {
            return true;
        }
    }

    return false;
}

function isAllowedUserNickname($nickname)
{
    $users = getAllowedUsers();

    foreach ($users as $user) {
        if (strtoupper($user['nickname']) == strtoupper($nickname)) {
            return true;
        }
    }

    return false;
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
function uploadToImgur($url)
{
    $cacher = new FilesystemCache('/tmp');
    $uploader = Factory::create('Imgur', [
        'cacher' => $cacher,
        'api_key' => env('IMGUR_KEY'),
        'api_secret' => env('IMGUR_SECRET'),
        'refresh_token' => env('IMGUR_REFRESH_TOKEN')
    ]);

    try {
        $result = $uploader->transload($url);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        $result = $url;
    }
    return $result;
}
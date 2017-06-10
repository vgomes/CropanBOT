<?php
use Imgur\Client;
use InstagramScraper\Exception\InstagramException;
use InstagramScraper\Instagram;
use Telegram\Bot\Objects\Update;

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
    $client = new Client();
    $client->setOption('client_id', env('IMGUR_KEY'));
    $client->setOption('client_secret', env('IMGUR_SECRET'));

    $imageData = [
        'image' => $url,
        'type'  => 'url',
    ];

    try {
        $response = $client->api('image')->upload($imageData);
        $result = $response['link'];

    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        $result = $url;
    }
    return $result;
}

function getPictureUrlFromTelegram(Update $update) {
    if (is_null($update->getMessage())) {
        $message = $update->getEditedMessage();
    } else {
        $message = $update->getMessage();
    }

    $text = $message->getText();

    $document = $message->getDocument();
    if (!is_null($document)) {
        switch ($document->getMimeType()) {
            case 'image/png' :
            case 'image/jpeg' :
            case 'image/jpg' :
                $url = urlFromTelegramDocument($document);
                $text = uploadToImgur($url);
                break;
        }
    }

    $photo = $message->getPhoto();
    if (!is_null($photo)) {
        $url = urlFromTelegramPhoto($message->getPhoto());
        $text = uploadToImgur($url);
    }

    return $text;
}

function getPictureUrlFromInstagram($url) {

    try {
        $image = Instagram::getMediaByUrl($url);

        if ($image->type === 'image') {
            return (!is_null($image->imageHighResolutionUrl)) ? $image->imageHighResolutionUrl : $image->imageStandardResolutionUrl;
        }
    } catch (InstagramException $exception) {}

    return null;
}
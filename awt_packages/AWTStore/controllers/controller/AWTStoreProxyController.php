<?php

use controller\Controller;
use redirect\Redirect;
use view\View;

/**
 * This is a proxy controller that can add Access-Control-Allow-Origin header.
 * To use it call it's route /route/example with GET parameter
 * /route/example?url=<url>
 */
final class AWTStoreProxyController extends Controller
{
    #[\JetBrains\PhpStorm\NoReturn]
    public function index(array|string $params): Redirect|View
    {
        $targetUrl = $_GET["url"] ?? null;

        if($targetUrl === null)
            die("No URL provided");

        $ch = curl_init($targetUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $headers = [];
        foreach (getallheaders() as $key => $value) {
            if (strtolower($key) !== 'host' && strtolower($key) !== 'origin') {
                $headers[] = "$key: $value";
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET");
        header("Content-Type: " . ($contentType ?: "application/octet-stream"));

        die($response);
    }
}
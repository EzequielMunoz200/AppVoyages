<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class Translate
{
    public function translateToFrench($text)
    {
        $order   = ["\r\n", "\n", "\r"];
        $replace = ' ';
        $newText = str_replace($order, $replace, $text);

        //https://cloud.ibm.com/docs/language-translator?topic=language-translator-customizing#check-model-example-request
        $url = $_ENV['URL_TRANSLATE'] . '/v3/translate?version=2018-05-01';

        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, "{\"text\": \"$newText\" , \"model_id\":\"en-fr\"}");
        curl_setopt($handle, CURLOPT_USERPWD, 'apikey' . ':' . $_ENV['API_KEY_TRANSLATE']);

        $headers[] = 'Content-Type: application/json';
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($handle);
        $responseDecoded = json_decode($response, true);
        $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);      
        curl_close($handle);
        if ($responseCode != 200) {
            return  new JsonResponse(['responseCode' => $responseCode, 'message' => $responseDecoded['error']], $responseCode);
        } else {
            return  new JsonResponse(['Source' => $text, 'Translation' => $responseDecoded['translations'][0]['translation']], $responseCode);
        }
    }
}

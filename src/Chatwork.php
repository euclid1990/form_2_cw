<?php

namespace Src;

define('CREDENTIALS_CW', __DIR__ . '/../credentials/chatwork.json');

class Chatwork
{
    // URI to chatwork API
    const URI = 'https://api.chatwork.com/v1/rooms/%s/messages';
    // Token of chatwork API
    protected $token = null;
    // Chatwork room id
    protected $roomId = null;
    // Message to be sent
    const MESSAGE = "※ Created at: %s\n%s\n";

    function __construct()
    {
        $credentials = json_decode(file_get_contents(CREDENTIALS_CW), true);
        $this->token = $credentials["access_token"];
        $this->roomId = $credentials["room_id"];
    }

    /**
     * Create message to sending
     * @param  array object $result
     */
    function createMessage($result)
    {
        if (empty($result)) {
            return "";
        }
        $message = "[info][title]Recently Update [In minutes][/title]";
        foreach ($result as $item) {
            $message .= sprintf(self::MESSAGE, $item->created_at, $item->data);
        }
        $message .= "[/info]";
        return $message;
    }

    /**
     * Send message to chatwork
     * @param  string $message
     */
    function sendMessage($message)
    {
        // Not send any message if dont have any update
        if (empty($message)) {
            return;
        }

        $params = array(
            'body' => $message,
        );

        // Init cURL session
        $ch = curl_init();
        // Set Options on the cURL session
        // Set the URL to fetch
        curl_setopt($ch, CURLOPT_URL, sprintf(self::URI, $this->roomId));
        // Set HTTP header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ChatWorkToken: '. $this->token));
        // Set method to POST
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set data to post
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
        // Set return the transfer as a string  of the return value of curl_exec()
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Perform cURL session
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);

        return $response;
    }
}

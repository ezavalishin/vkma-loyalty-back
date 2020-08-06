<?php

namespace App\Services;

use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkClient
{

    protected VKApiClient $client;
    private string $accessToken;

    public const API_VERSION = '5.107';
    public const LANG = 'ru';


    public function __construct($accessToken = null)
    {
        $this->client = new VKApiClient(self::API_VERSION, self::LANG);

        if ($accessToken) {
            $this->accessToken = $accessToken;
        } else {
            $this->accessToken = config('services.vk.app.service_key');
        }

    }

    /**
     * @param $ids
     * @param array $fields
     * @return mixed
     * @throws VKApiException
     * @throws VKClientException
     */
    public function getUsers($ids, array $fields)
    {
        $isFew = is_array($ids);

        $response = $this->client->users()->get($this->accessToken, [
            'user_ids' => $isFew ? $ids : [$ids],
            'fields' => $fields,
        ]);

        return $isFew ? $response : $response[0];
    }

    public function getFriends($userId, $fields): array
    {
        try {
            $response = $this->client->friends()->get($this->accessToken, [
                'user_id' => $userId,
                'fields' => $fields
            ]);
            $users = $response['items'];
        } catch (\Exception $e) {
            $users = [];
        }

        return $users;
    }
}

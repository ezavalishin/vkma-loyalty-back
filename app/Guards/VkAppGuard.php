<?php

namespace App\Guards;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JsonException;

class VkAppGuard
{
    public const CACHE_PREFIX = 'auth_v2_uid_';

    public function __invoke(Request $request)
    {
        if ($request->has('transport')) {
            $vkParams = $request->query('vk-params');
        } else {
            $vkParams = $request->header('vk-params');
        }

        $cachedVkUser = $this->fromCache($vkParams);

        if ($cachedVkUser) {
            return $cachedVkUser;
        }

        $params = $this->validate($vkParams);
        $this->checkSign($params);

        $user = $this->getUser($params);

        Cache::put(self::CACHE_PREFIX . $vkParams, $user->vk_user_id, Carbon::now()->addMinutes(15));

        return $user;
    }

    private function fromCache($vkParams): ?User
    {
        if (!Cache::has(self::CACHE_PREFIX . $vkParams)) {
            return null;
        }

        $userId = Cache::get(self::CACHE_PREFIX . $vkParams);

        return User::byVkId($userId);
    }

    /**
     * @param $params
     * @return mixed
     * @throws ValidationException
     */
    private function validate($params)
    {
        if (!$params) {
            abort(403, 'required Vk-Params header');
        }

        try {
            $params = json_decode(base64_decode($params), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            abort(403, 'invalid json');
        }

        Validator::make($params, [
            'vk_user_id' => 'required|integer',
            'utc_offset' => 'required|integer',
            'vk_are_notifications_enabled' => 'required|boolean',
            'sign' => 'required|string'
        ])->validate();

        return $params;
    }

    private function getSecret()
    {
        return config('vk.app.secret');
    }

    private function checkSign($params): void
    {
        if (app()->environment() !== 'production') {
            return;
        }

        $usefulParams = $this->collectUsefulParams($params);

        /* Формируем строку вида "param_name1=value&param_name2=value"*/
        $sign_params_query = $usefulParams->map(static function ($value, $key) {
            return "{$key}=$value";
        })->join('&');

        /* Получаем хеш-код от строки, используя защищенный ключ приложения. Генерация на основе метода HMAC. */
        $sign = rtrim(strtr(base64_encode(hash_hmac(
            'sha256', $sign_params_query, $this->getSecret(), true
        )), '+/', '-_'), '=');

        if (!($sign === $params['sign'])) {
            abort(403, 'Bad sign');
        }
    }

    private function collectUsefulParams($params): Collection
    {
        return collect($params)->map(static function ($param) {
            return $param ?? '';
        })->filter(static function ($param, $key) {
            return Str::startsWith($key, 'vk_');
        })->sortKeys();
    }


    /**
     * @param $params
     * @return User
     * @throws \Exception
     */
    private function getUser($params): User
    {
        return retry(5, static function () use ($params) {
            return User::query()->updateOrCreate([
                'vk_user_id' => $params['vk_user_id'],
            ], [
                'notifications_are_enabled' => $params['vk_are_notifications_enabled'],
                'visited_at' => Carbon::now()
            ]);
        });
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class InternalApiService
{
    /**
     * Membuat internal request langsung ke Router Laravel tanpa via HTTP/cURL
     */
    protected static function executeInternalRequest(string $method, string $endpoint, array $data = [], $token = null)
    {
        $uri = '/api/v1/' . ltrim($endpoint, '/');

        // 1. Buat instance Request internal
        $request = Request::create($uri, strtoupper($method), $data);

        // 2. Set Headers agar dikenali sebagai API Request
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Content-Type', 'application/json');

        // 3. Teruskan token autentikasi jika ada
        $authToken = $token ?? session('auth_token');
        if ($authToken) {
            $request->headers->set('Authorization', 'Bearer ' . $authToken);
        } elseif (Auth::check()) {
            // Jika user login via Session Web, teruskan objek User ke request internal
            $request->setUserResolver(fn () => Auth::user());
        }

        // 4. Eksekusi request secara internal melalui Kernel Route Laravel
        $response = Route::dispatch($request);

        // 5. Decode hasil JSON
        return json_decode($response->getContent(), true) ?? [];
    }

    public static function get($endpoint, array $queryParams = [], $token = null)
    {
        return static::executeInternalRequest('GET', $endpoint, $queryParams, $token);
    }

    public static function post($endpoint, array $data = [], $token = null)
    {
        return static::executeInternalRequest('POST', $endpoint, $data, $token);
    }

    public static function put($endpoint, array $data = [], $token = null)
    {
        return static::executeInternalRequest('PUT', $endpoint, $data, $token);
    }

    public static function delete($endpoint, array $data = [], $token = null)
    {
        return static::executeInternalRequest('DELETE', $endpoint, $data, $token);
    }
}
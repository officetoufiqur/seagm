<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SeagmHelper
{
    // Get server time
    public static function getServerTime()
    {
        $baseUrl = config('services.seagm.url');

        $response = Http::get($baseUrl.'time');

        if (! $response->successful()) {
            throw new \Exception('Failed to fetch SEAGM server time');
        }

        return $response->json('data');
    }

    // Generate signature
    public static function generateSignature(array $queryParams = [], array $bodyParams = [])
    {
        $params = array_merge($queryParams, $bodyParams);

        unset($params['signature']);

        ksort($params);

        $queryString = urldecode(http_build_query($params));

        
        $data = hash_hmac(
            'sha256',
            $queryString,
            config('services.seagm.secret_key')
            );

        return $data;
    }

    // Make GET request (auto signature)
    public static function get($endpoint, array $params = [])
    {
        $baseUrl = config('services.seagm.url');
        $uid = config('services.seagm.uid');

        $timestamp = self::getServerTime();
        
        $baseParams = array_merge($params, [
            'timestamp' => $timestamp,
            'uid' => $uid,
            ]);
            
            $signature = self::generateSignature($baseParams);
            
            $response = Http::get($baseUrl.$endpoint, array_merge($baseParams, [
                'signature' => $signature,
                ]));

        if (! $response->successful()) {
            throw new \Exception(
                'SEAGM Error: '.$response->body()
            );
        }

        return $response->json();
    }

    public static function post($endpoint, array $params = [])
    {
        $baseUrl = config('services.seagm.url');
        $uid = config('services.seagm.uid');

        $timestamp = self::getServerTime();
        
        $baseParams = array_merge($params, [
            'timestamp' => $timestamp,
            'uid' => $uid,
            ]);
            
            $signature = self::generateSignature($baseParams);
            
            $response = Http::post($baseUrl.$endpoint, array_merge($baseParams, [
                'signature' => $signature,
                ]));

        if (! $response->successful()) {
            throw new \Exception(
                'SEAGM Error: '.$response->body()
            );
        }

        return $response->json();
    }
}

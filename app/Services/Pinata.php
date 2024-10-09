<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Pinata
{
    private PendingRequest $request;
    private PendingRequest $uploadRequest;

    public function __construct(){
        $this->request = Http::baseUrl(config("pinata.api_host"))
            ->withToken(config("pinata.api_key"))
            ->acceptJson();
        $this->uploadRequest = Http::baseUrl(config("pinata.upload_host"))
            ->withToken(config("pinata.api_key"))
            ->acceptJson();
    }

    /**
     * @throws ConnectionException
     */
    public function testAuth(): bool
    {
        $response = $this->request->get("/data/testAuthentication");
        return $response->successful();
    }

    /**
     * @throws ConnectionException
     */
    public function uploadFile(string $name, ?string $groupId, $fileContents){
        $response = $this->uploadRequest->asMultipart()
            ->attach("file", $fileContents, $name)
            ->post("v3/files", [
                "name" => $name
            ]);

        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function getSignedUrl($cid){
        return Cache::remember("signed-" . $cid, 60*60*23, function() use ($cid){
            $response = $this->request->post("v3/files/sign", [
                "url" => config("pinata.gateway") . "files/" . $cid,
                "date" => now()->unix(),
                "expires" => 60 * 60 * 24,
                "method" => "GET"
            ]);

            return $response->json("data");
        });
    }
}

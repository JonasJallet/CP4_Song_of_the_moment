<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\SongUploadCoverInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SongUploadCover implements SongUploadCoverInterface
{
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function upload($url, $name): string
    {
        $httpClient = HttpClient::create();
        $fileUrl = $httpClient->request('GET', $url);
        $cleanedName = str_replace(["'", " ", "*", "/", ".", "?", "!"], "", strtolower($name));
        $fileName = $cleanedName . ".avif";
        $fileFolder = $this->kernel->getProjectDir() . '/public/songs/covers/';
        file_put_contents($fileFolder . '/' . $fileName, $fileUrl->getContent());
        return $fileName;
    }
}

<?php

namespace App\Infrastructure\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class YoutubeLinkTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
    // Transform the YouTube link format to the desired format for display
    // For example, extract the video ID from the link
        if ($value) {
            $videoId = $this->extractVideoId($value);
            return 'https://www.youtube.com/watch?v=' . $videoId;
        }

        return $value;
    }

    public function reverseTransform($value)
    {
    // Reverse transform the displayed format to the YouTube link format
    // For example, extract the video ID from the displayed format
        if ($value) {
            $videoId = $this->extractVideoId($value);
            return 'https://www.youtube.com/watch?v=' . $videoId;
        }

        return $value;
    }

    private function extractVideoId($url)
    {
    // Extract the video ID from the YouTube link
    // This implementation assumes the video ID is always in the same position
        $parts = parse_url($url);
        parse_str($parts['query'], $query);
        if (isset($query['v'])) {
            return $query['v'];
        } else {
            throw new TransformationFailedException('Invalid YouTube link.');
        }
    }
}

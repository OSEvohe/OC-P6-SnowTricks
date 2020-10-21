<?php

namespace App\Service;


/**
 * Class YoutubeHelper
 * @package App\Service
 */
class YoutubeHelper
{

    const YOUTUBE_URL_SHORT = 'Youtube_Short';
    const YOUTUBE_URL_LONG = 'Youtube_Long';
    const YOUTUBE_URL_EMBED = 'Youtube_Embed';

    private $patterns = [];

    public function __construct()
    {
        $this->patterns[self::YOUTUBE_URL_SHORT] = '/^(?:https?:\/\/)?(?:youtu\.be\/([\w-]+))$/ui';
        $this->patterns[self::YOUTUBE_URL_LONG] = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=([\w-]+))(?:&[\w\-=]*)*$/ui';
        $this->patterns[self::YOUTUBE_URL_EMBED] = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/embed\/([\w-]+))$/ui';
    }


    /**
     * Return the video id from an URL
     * @param string $url
     * @return mixed
     */
    public function getId(string $url)
    {
        $url = trim($url);
        return $this->parseUrl($url)['id'];
    }


    /**
     * Return the url type from an URL
     * @param string $url
     * @return mixed
     */
    public function getType(string $url)
    {
        return $this->parseUrl($url)['type'];
    }


    /**
     * Return the type and id from a URL
     * @param $url
     * @return array|false
     */
    private function parseUrl($url)
    {
        foreach ($this->patterns as $type => $pattern) {
            if (preg_match($pattern, $url, $id)) {
                return ['type' => $type, 'id' => $id[1]];
            }
        }
        return false;
    }
}
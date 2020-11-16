<?php

namespace App\Tests\Service;

use App\Service\YoutubeHelper;
use PHPUnit\Framework\TestCase;

class YoutubeHelperTest extends TestCase
{
    /**
     * @var YoutubeHelper
     */
    private $youtubeHelper;


    /**
     * YoutubeHelperTest constructor.
     */
    public function __construct()
    {
        $this->youtubeHelper = new YoutubeHelper();
        parent::__construct();
    }

    public function testGetId()
    {
        $result = $this->youtubeHelper->getId('https://www.youtube.com/watch?v=4JfBfQpG77o&ab_channel=SnowboardProCamp');
        $this->assertEquals('4JfBfQpG77o', $result);

        $result = $this->youtubeHelper->getId('https://youtu.be/4JfBfQpG77o');
        $this->assertEquals('4JfBfQpG77o', $result);

        $result = $this->youtubeHelper->getId('https://www.youtube.com/embed/4JfBfQpG77o');
        $this->assertEquals('4JfBfQpG77o', $result);
    }

    public function testGetType()
    {
        $result = $this->youtubeHelper->getType('https://www.youtube.com/watch?v=4JfBfQpG77o&ab_channel=SnowboardProCamp');
        $this->assertEquals(YoutubeHelper::YOUTUBE_URL_LONG, $result);

        $result = $this->youtubeHelper->getType('https://youtu.be/4JfBfQpG77o');
        $this->assertEquals(YoutubeHelper::YOUTUBE_URL_SHORT, $result);

        $result = $this->youtubeHelper->getType('https://www.youtube.com/embed/4JfBfQpG77o');
        $this->assertEquals(YoutubeHelper::YOUTUBE_URL_EMBED, $result);
    }

    public function testGetUrlFromId()
    {
        $result = $this->youtubeHelper->getUrlFromId('4JfBfQpG77o');
        $this->assertEquals('https://www.youtube.com/embed/4JfBfQpG77o', $result);
    }
}

<?php

error_reporting(E_ALL);

require __DIR__ . '/../Lib/MP3.php';
require __DIR__ . '/../Lib/MP3Header.php';

class MP3Test extends PHPUnit_Framework_TestCase {

    public function testLoadFileName()
    {
        $fileName = 'song.mp3';
        $mp3 = new MP3($fileName);
        $this->assertEquals($mp3->getFileName(), $fileName);
    }
    
    public function testGetFileSize()
    {
        $fileName = __DIR__ . '/test.mp3';
        $mp3 = new MP3($fileName);
        $this->assertEquals($mp3->getFileSize(), filesize($fileName));

        $mp3 = new MP3('\loremipsumfilenotexists');
        $this->assertEquals($mp3->getFileSize(), null);

        $mp3 = new MP3('');
        $this->assertEquals($mp3->getFileSize(), null);

    }

    public function testGetMp3Header()
    {
        $mp3 = new MP3(__DIR__ . '/test.mp3');
        $header = $mp3->getHeader();
        $this->assertInstanceOf('MP3Header', $header);

        $this->assertEquals($header->getLayerVersion(),      MP3Header::LAYER_VERSION_1);
        $this->assertEquals($header->getLayerDescription(),  MP3Header::LAYER_DESCRIPTION_3);
        $this->assertEquals($header->getProtection(),        MP3Header::LAYER_UNPROTECTED);
        $this->assertEquals($header->getBitrate(),           256);
    }
}

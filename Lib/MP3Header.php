<?php

class MP3Header {

    const LAYER_VERSION_2_5         = 0;
    const LAYER_VERSION_UNKNOWN     = 1;
    const LAYER_VERSION_2           = 2;
    const LAYER_VERSION_1           = 3;

    const LAYER_DESCRIPTION_UNKNOWN = 0;
    const LAYER_DESCRIPTION_3       = 1;
    const LAYER_DESCRIPTION_2       = 2;
    const LAYER_DESCRIPTION_1       = 3;

    const LAYER_PROTECTED           = 0;
    const LAYER_UNPROTECTED         = 1;


    protected $bitrateHash = array(
        0 => array(
            0, 0, 0, 0, 0
        ),
        1 => array(
            32, 32, 32, 32, 8
        ),
        2 => array(
            64, 48, 40, 48, 16
        ),
        3 => array(
            96, 56, 48, 56, 24
        ),
        4 => array(
            128, 64, 56, 64, 32
        ),
        5 => array(
            160, 80, 64, 80, 40
        ),
        6 => array(
            192, 96, 80, 96, 48
        ),
        7 => array(
            224, 112, 96, 112, 56
        ),
        8 => array(
            256, 128, 112, 128, 64
        ),
        9 => array(
            288, 160, 128, 144, 80
        ),
        10 => array(
            320, 192, 160, 160, 96
        ),
        11 => array(
            352, 224, 192, 176, 112
        ),
        12 => array(
            384, 256, 224, 192, 128
        ),
        13 => array(
            416, 320, 256, 224, 144
        ),
        14 => array(
            448, 384, 320, 256, 160
        ),
        15 => array(
            -1, -1, -1, -1, -1
        )
    );


    /**
     * Layer version of frame
     */
    protected $layerVersion;

    /**
     * Layer version of frame
     */
    protected $layerDescription;

    /**
     * CRS protection of frame
     */
    protected $protection;

    /**
     * Bitrate of frame
     */
    protected $bitrate;


    /**
     * Create header object from raw data
     * @param string data Raw binary data of mp3 header
     */
    public function __construct($data)
    {
        $this->parseData($data);
    }

    /*
     * Parse binary header string to fields
     * @see http://www.mp3-tech.org/programmer/frame_header.html
     * @param data string
     *
     * @return void
     */
    public function parseData($data)
    {
        if (strlen($data) !== 4) {
            throw new \InvalidArgumentException('Header length must be 4 bytes length');
        }
        $data = unpack('C4', $data);
        #printf("\n%08b\n", $data[2]);
        $this->layerVersion     = ($data[2] >> 3) & 0x3;
        $this->layerDescription = ($data[2] >> 1) & 0x3;
        $this->protection       = ($data[2]) & 0x1;
        $bitrateBit       = ($data[3] >> 4) & 0xF;
        switch($this->layerVersion) {
            case self::LAYER_VERSION_1:
                switch($this->layerDescription) {
                    case self::LAYER_DESCRIPTION_1:
                        $this->bitrate = $this->bitrateHash[$bitrateBit][0];
                        break;
                    case self::LAYER_DESCRIPTION_2:
                        $this->bitrate = $this->bitrateHash[$bitrateBit][1];
                        break;
                    case self::LAYER_DESCRIPTION_3:
                        $this->bitrate = $this->bitrateHash[$bitrateBit][2];
                        break;
                }
                break;
            case this::LAYER_VERSION_2:
                switch($this->layerDescription) {
                    case self::LAYER_DESCRIPTION_1:
                        $this->bitrate = $this->bitrateHash[$bitrateBit][3];
                        break;
                    case self::LAYER_DESCRIPTION_2:
                    case self::LAYER_DESCRIPTION_3:
                        $this->bitrate = $this->bitrateHash[$bitrateBit][4];
                        $this->bitrate = $this->bitrateHash[$bitrateBit][4];
                        break;
                }
                break;
        }
    }

    public function getLayerVersion()
    {
        return $this->layerVersion;
    }

    public function getLayerDescription()
    {
        return $this->layerDescription;
    }

    public function getProtection()
    {
        return $this->protection;
    }

    public function getBitrate()
    {
        return $this->bitrate;
    }
}

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


    /**
     * Layer version of mp3 file
     */
    protected $layerVersion = self::LAYER_VERSION_UNKNOWN;


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
        printf("\n%08b\n", $data[2]);
        $this->layerVersion     = ($data[2] >> 3) & 0x3;
        $this->layerDescription = ($data[2] >> 1) & 0x3;
        $this->protection       =  ($data[2]) & 0x1;
        
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
}

<?php


class MP3 {

    /**
     * Name of loaded file
     *
     * @var string
     */
    protected $fileName;

    /**
     * Creates new instance of MP3
     */
    public function __construct($fileName) 
    {
        $this->fileName = $fileName;
    }

    /**
     * Returns name of loaded file
     *
     * @return string|null
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Returns file size in bytes if file exists
     * or null otherwise
     *
     * @return int|null
     */
    public function getFileSize()
    {
        if (is_file($this->fileName) && is_readable($this->fileName)){
            return filesize($this->filesize);
        }

        return null;
    }
    
    /**
     * Returns string with contents of mp3 file
     * or null if errors
     *
     * @return string|null
     */
    public function getData()
    {
        return file_get_contents($this->fileName);
    }

    /**
     * Returns header with fields of mp3 file
     *
     * @return MP3Header
     */
    public function getHeader()
    {
        error_reporting(E_ALL);
        $data = $this->getData();
        $idx = strpos($data, 0xFF);
        $id3Header = substr($data, 0, $idx - 1);
        $mp3Header = substr($data, $idx, 4);
        return new MP3Header($mp3Header);
    }
}

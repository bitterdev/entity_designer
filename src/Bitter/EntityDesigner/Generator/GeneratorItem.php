<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator;

class GeneratorItem
{
    protected $fileName;
    protected $fileContents;
    protected $table;

    public function __construct($fileName = '', $fileContents = '', $table = '')
    {
        $this->fileName = $fileName;
        $this->fileContents = $fileContents;
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return GeneratorItem
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileContents()
    {
        return $this->fileContents;
    }

    /**
     * @param string $fileContents
     * @return GeneratorItem
     */
    public function setFileContents($fileContents)
    {
        $this->fileContents = $fileContents;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return GeneratorItem
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

}
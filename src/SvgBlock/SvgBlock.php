<?php
/** Freesewing\SvgBlock class */
namespace Freesewing;

/**
 * Abstract class for different parts of an SVG document
 *
 * @author Joost De Cock <joost@decock.org>
 * @copyright 2016 Joost De Cock
 * @license http://opensource.org/licenses/GPL-3.0 GNU General Public License, Version 3
 */
abstract class SvgBlock
{
    /** Classes extending this must implemen the load() method */
    abstract public function load();

    /** @var $data The data stored in the object */
    private $data = false;

    /**
     * Triggered when object is used as a string
     *
     * @return string the data in the object as string
     */
    public function __toString()
    {
        $data = '';
        if (is_array($this->data)) {
            foreach ($this->data as $origin) {
                $data .= implode("\n    ", $origin)." ";
            }
        }

        return $data;
    }

    /**
     * Returns the data property
     *
     * Actually, this is used to check whether data
     * has been added, because it returns false if not
     *
     * @return array $data The data in the object
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Adds data Returns the data property
     *
     * This adds data to a multilevel array
     * The first level key is the filename calling this function
     * The second level is an array for each line in $data
     * This allows developers to keep track of what file added what
     *
     * @param string $data The data to add
     * @param array $replace Optional array of things to replace in the data
     */
    public function add($data)
    {
        $caller = debug_backtrace()[0]['file'];
        if (!isset($this->data[$caller])) {
            $this->data[$caller] = array();
        }
        foreach (explode("\n", $data) as $line) {
            if( $line != '') array_push($this->data[$caller], $line);
        }
    }
}

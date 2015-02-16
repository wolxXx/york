<?php
namespace York\HTML;

/**
 * interface for html elements
 *
 * @package \York\HTML
 * @version $version$
 * @author wolxXx
 */
interface DomElementInterface
{
    /**
     * calls the html element generator
     *
     * @return $this
     */
    public function display();

    /**
     * get the generated markup
     *
     * @return string
     */
    public function getMarkup();

    /**
     * returns the default config for this element
     *
     * @return array
     */
    public static function getDefaultConf();

    /**
     * returns the ID of the element
     *
     * @return string
     */
    public function getId();

    /**
     * setter for id
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * returns the name of the element
     *
     * @return string
     */
    public function getName();

    /**
     * setter for required flag
     *
     * @param boolean $required
     *
     * @return $this
     */
    public function setIsRequired($required = true);

    /**
     * factory function
     *
     * @param array $data
     *
     * @return $this
     */
    public static function Factory($data = array());

    /**
     * getter for the label
     *
     * @return \York\HTML\Element\Label
     */
    public function getLabel();
}

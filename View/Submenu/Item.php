<?php
namespace York\View\Submenu;
/**
 * a submenu item
 *
 * @author wolxXx
 * @version 3.0
 * @package York\View\Submenu
 */
class Item{
	/**
	 * container for metadata
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * constructor
	 * config array can contain meta data
	 *
	 * @param array $config
	 */
	public function __construct($config = array()){
		$this->data = \York\Helper\Set::merge(self::getDefaultArray(), $config);
	}

	/**
	 * factory for having simple access to new item
	 *
	 * @param string $href
	 * @param string $text
	 * @param array $config
	 * @return \York\View\Submenu\Item
	 */
	public static function Factory($href, $text, $config = array()){
		return new self(\York\Helper\Set::merge(array('href' => $href, 'text' => $text), $config));
	}

	/**
	 * setter for meta data
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value){
		$this->data[$key] = $value;
	}

	/**
	 * generates the link string
	 *
	 * @return string
	 */
	public function getOutput(){
		return sprintf('<a href="%s" class="%s" id="%s">%s</a>', $this->data['href'], $this->data['class'], $this->data['id'], $this->data['text']);
	}

	/**
	 * echoes the generated link string
	 * @return \York\View\Submenu\Item
	 */
	public function display(){
		echo $this->getOutput();
		return $this;
	}

	/**
	 * generates the default config
	 *
	 * @return array
	 */
	public static function getDefaultArray(){
		return $defaultData = array(
			'href' => '#',
			'class' => 'menu',
			'id' => ''
		);
	}
}
<?php
namespace York\HTML;
/**
 * collection of html element generators
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML
 */
class Core{
	/**
	 * generates a unique id string and appends it to id and name
	 * so every element has a unique id and name
	 *
	 * @return array
	 */
	public static function getUniqueIdAndName(){
		$uniqueId = uniqid();
		return array(
			'id' => 'dom_elem_id_'.$uniqueId,
			'name' => 'dom_elem_name_'.$uniqueId
		);
	}

	/**
	 * echoes something and prints the new line
	 */
	public static function out(){
		foreach(func_get_args() as $current){
			echo $current;
		}
		echo PHP_EOL;
	}

	/**
	 * opens a single tag like <img />
	 *
	 * @param string $name
	 * @param array $args
	 * @return string
	 */
	public static function openSingleTag($name, $args = array()){
		$return = sprintf('<%s', $name);
		unset($args['required']);

		foreach($args as $key => $value){
			if(null === $value){
				continue;
			}
			$return .= sprintf(' %s="%s"', $key, $value);
		}
		return $return;
	}

	/**
	 * opens a tag like <textarea>
	 *
	 * @param string $name
	 * @param array $args
	 * @return string
	 */
	public static function openTag($name, $args = array()){
		return self::openSingleTag($name, $args).'>';
	}

	/**
	 * closes a tag like </textarea>
	 *
	 * @param string $name
	 * @return string
	 */
	public static function closeTag($name){
		return sprintf('</%s>', $name);
	}

	/**
	 * closes a single tag like <img />
	 *
	 * @return string
	 */
	public static function closeSingleTag(){
		return ' />';
	}
}
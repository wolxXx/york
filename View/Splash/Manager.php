<?php
namespace York\View\Splash;
use York\Dependency\Manager as Dependency;
use York\View\Splash\Item;
use York\View\Splash\ItemInterface;

/**
 * splash manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Splash
 */
class Manager {
	/**
	 * session key for splashes
	 */
	const sessionKey = 'york.session.splashes';

	/**
	 * retrieves all set splashes
	 *
	 * @return ItemInterface[]
	 */
	public function getSplashes(){
		return Dependency::get('session')->getSafely(self::sessionKey, array());
	}

	/**
	 * shortcut for add new splash
	 *
	 * @param $text
	 */
	public function addText($text){
		$this->addSplash(new Item($text));
	}

	/**
	 * adds a splash to the splash set
	 * you can append (default) or prepend (set append to false) the splash
	 *
	 * @param ItemInterface $splash
	 * @param boolean $append
	 * @return \York\View\Splash\Manager
	 */
	public function addSplash(ItemInterface $splash, $append = true){
		$splashes = $this->getSplashes();
		if(true === $append){
			$splashes[] = $splash;
		}else{
			$splashes = array_unshift($splashes, $splash);
		}
		Dependency::get('session')->set(self::sessionKey, $splashes);
		return $this;
	}

	/**
	 * clears all set splashes
	 *
	 * @return \York\View\Splash\Manager
	 */
	public function clearSplashes(){
		Dependency::get('session')->set(self::sessionKey, array());
		return $this;
	}
}

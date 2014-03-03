<?

class Div extends ContainableDomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public function display(){
		HTML::out(HTML::openTag('div', $this->getData()));
		foreach($this->children as $current){
			$current->display();
		}
		HTML::out(HTML::closeTag('div'));
		return $this;
	}
}
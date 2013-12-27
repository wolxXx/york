<?php
namespace York\HTML\Element;
/**
 * a date picker element
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML\Element;
 */
class Date extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Date
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * sets the default config
	 * @return array
	 */
	public static function getDefaultConf(){
		return array(
			'value' => \York\Helper\Date::getDate(),
			'type' => 'text',
			'autocomplete' => 'off',
			'readonly' => null,
			'class' => 'datepicker'
		);
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return Date
	 */
	public function setValue($value){
		$this->set('value', $value);
		return $this;
	}

	/**
	 * adds all needed css and js files
	 */
	protected function loadAdditionalScripts(){
		\York\View\Manager::getInstance()->addJavascriptFile('/Library/York//HTML/Element/js/Locale.de-DE.DatePicker.js');
		\York\View\Manager::getInstance()->addJavascriptFile('/Library/York//HTML/Element/js/date.js');
		\York\View\Manager::getInstance()->addJavascriptFile('/Library/York//HTML/Element/js/Picker.js');
		\York\View\Manager::getInstance()->addJavascriptFile('/Library/York//HTML/Element/js/Picker.Attach.js');
		\York\View\Manager::getInstance()->addJavascriptFile('/Library/York//HTML/Element/js/Picker.Date.js');
		\York\View\Manager::getInstance()->addCssFile('/Library/York//HTML/Element/js/datepicker_bootstrap/datepicker_bootstrap.css');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->displayLabelBefore();
		$format = $this->getSavely('format', '%Y-%m-%d %H:%M');
		$this->removeData('format');

		$conf = $this->getConf();

		\York\HTML\Core::out(
			\York\HTML\Core::openSingleTag('input', $conf),
			\York\HTML\Core::closeSingleTag()
		);

		?>
			<script>
				window.addEvent('domready', function(){
					new Picker.Date($('<?= $this->getId() ?>'), {
						timePicker: true,
						positionOffset: {x: 0, y: 0},
						pickerClass: 'datepicker_bootstrap',
						useFadeInOut: !Browser.ie,
						format: '<?= $format ?>'
					});
				})
			</script>
		<?php
		$this->loadAdditionalScripts();
		$this->displayLabelAfter();
		return $this;
	}
}
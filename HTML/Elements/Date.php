<?
/**
 * a date picker element
 *
 * @author wolxXx
 * @version 0.1
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Date extends DomElementAbstract{
	/**
	 * sets the default config
	 * @return array
	 */
	public static function getDefaultConf(){
		return array(
			'value' => Helper::getDate(),
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
		$this->data->set('value', $value);
		return $this;
	}

	/**
	 * adds all needed css and js files
	 */
	protected function loadAdditionalScripts(){
		Load::getInstance()->addJavascriptFile('/Lib/wolxXxMVC/HTML/Elements/js/Locale.de-DE.DatePicker.js');
		Load::getInstance()->addJavascriptFile('/Lib/wolxXxMVC/HTML/Elements/js/date.js');
		Load::getInstance()->addJavascriptFile('/Lib/wolxXxMVC/HTML/Elements/js/Picker.js');
		Load::getInstance()->addJavascriptFile('/Lib/wolxXxMVC/HTML/Elements/js/Picker.Attach.js');
		Load::getInstance()->addJavascriptFile('/Lib/wolxXxMVC/HTML/Elements/js/Picker.Date.js');
		Load::getInstance()->addCssFile('/Lib/wolxXxMVC/HTML/Elements/js/datepicker_bootstrap/datepicker_bootstrap.css');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->displayLabelBefore();
		$format = $this->data->getSavely('format', '%Y-%m-%d %H:%M');
		$this->data->removeData('format');
		HTML::renderDate($this->data->getData());
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
		<?
		$this->loadAdditionalScripts();
		$this->displayLabelAfter();
		return $this;
	}
}
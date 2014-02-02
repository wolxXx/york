<?
/**
 * a date picker element
 *
 * @author wolxXx
 * @version 0.1
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Time extends Date{
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
						timePickerOnly: true,
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
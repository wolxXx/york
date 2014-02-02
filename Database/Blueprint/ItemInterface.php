<?php
namespace York\Database\Blueprint;
/**
 * Interface BlueprintInterface
 */
interface ItemInterface{
	/**
	 * init your references
	 *
	 * @return ItemInterface
	 */
	public function initReferencing();

	/**
	 * validate yourself
	 *
	 * @return ItemInterface
	 */
	public function validate();
}

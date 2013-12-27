<?php
namespace York\View;

interface LayoutInterface {
	public function render();
	public function getContent();
	public function get($key);
}

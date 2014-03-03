<?
/**
 * @codeCoverageIgnore
 */
class HTMLTest extends \PHPUnit_Framework_TestCase{
	public function testDate(){
		$this->expectOutputRegex('/value="1234"/');
		\York\HTML\Element\Date::Factory()->setValue('1234')->display();
	}

	public function testGetLabel(){
		$element = \York\HTML\Element\Input::Factory();
		$this->assertNull($element->getLabel());
		$element->addLabel('foo');
		$this->assertSame('foo', $element->getLabel()->get('text'));
	}

	public function testBreak(){
		$this->expectOutputString(PHP_EOL.'<br />'.PHP_EOL);
		\York\HTML\Element\Br::Factory()->display();
	}

	public function testButton(){
		$this->expectOutputRegex('/moo!/');
		$this->expectOutputRegex('/<\/button>/');
		$this->expectOutputRegex('/<button/');
		\York\HTML\Element\Button::Factory()
			->setText('moo!')
			->display()
		;
	}

	public function testCheckbox(){
		$this->expectOutputRegex('/value="miau"/');
		$this->expectOutputRegex('/checked="checked"/');
		\York\HTML\Element\Checkbox::Factory()
			->setIsChecked(true)
			->setNameAndId('foo')
			->setValue('miau')
			->display()
		;
	}

	public function testCheckbox2(){
		$this->expectOutputRegex('/value="miau"/');
		$this->expectOutputRegex('/checked="checked"/');
		\York\HTML\Element\Checkbox::Factory()
		->setChecked(true)
		->setNameAndId('foo')
		->setValue('miau')
		->display()
		;
	}

	public function testClearElem(){
		$this->expectOutputRegex('/class="clear"/');
		$this->expectOutputRegex('/<div/');
		\York\HTML\Element\Clear::Factory()->display();
	}

	public function testDropdownAddNonRadioElem(){
		$this->setExpectedException('\York\Exception\HTTMLGenerator');
		$group = \York\HTML\Element\Dropdown::Factory();
		$group->addChild(\York\HTML\Element\Submit::Factory());
	}

	public function testDropdown(){
		$this->expectOutputRegex('/value="foo">/');
		$this->expectOutputRegex('/selected="select">/');
		$this->expectOutputRegex('/bar/');
		\York\HTML\Element\Dropdown::Factory()
			->addChild(
				\York\HTML\Element\DropdownGroup::Factory()
					->addLabel('foo')
					->addChild(
						\York\HTML\Element\DropdownElement::Factory()
							->setValueAndText('bar')
							->setIsSelected(true)
					)
			)
			->addChild(
				\York\HTML\Element\DropdownElement::Factory()
				->setValueAndText('pewpew')
			)
			->display()
		;
	}

	public function testSelectedDropdown(){
		$this->expectOutputRegex('/value="foo">/');
		$this->expectOutputRegex('/selected="select">/');
		$this->expectOutputRegex('/bar/');
		\York\HTML\Element\DropdownElement::Factory()
			->setNameAndId('foo')
			->setValueAndText('bar')
			->setIsSelected(true)
			->display()
		;
	}

	public function testDropdownGroup(){
		$this->expectOutputRegex('/value="foo">/');
		$this->expectOutputRegex('/value="bar">/');
		$this->expectOutputRegex('/ahoibrause/');
		$group = \York\HTML\Element\DropdownGroup::Factory();
		$group->addLabel('ahoibrause mit vodka!');
		$group->addChild(\York\HTML\Element\DropdownElement::Factory()->setValueAndText('foo'));
		$group->addChild(\York\HTML\Element\DropdownElement::Factory()->setValueAndText('bar'));
		$group->display();
	}

	/**
	 * @expectedException Exception
	 */
	public function testDropdownGroupAddNonRadioElem(){
		$group = \York\HTML\Element\DropdownGroup::Factory();
		$group->addChild(\York\HTML\Element\Submit::Factory());
	}

	/**
	 * @expectedException Exception
	 */
	public function testRemoveProperty(){
		$form = \York\HTML\Element\Form::Factory();
		$form->setData(array('foo' => 'bar'));
		$this->assertSame('bar', $form->get('foo'));
		$form->removeProperty('foo')->get('foo');
	}

	public function testSetData(){
		$this->assertSame('bar', \York\HTML\Element\Form::Factory()->setData(array('foo' => 'bar'))->get('foo'));
	}

	public function testAddData(){
		$data = array('foo' => 'bar');
		$this->assertSame($data, \York\HTML\Element\Form::Factory()->clearData()->addData($data)->getData());
		$this->assertSame('bar', \York\HTML\Element\Form::Factory()->addData($data)->get('foo'));
	}

	public function testAddClass(){
		$this->expectOutputRegex('/class="bar foo"/');
		\York\HTML\Element\Form::Factory()->addClass('foo')->addClass('bar')->display();
	}

	public function testSetClass(){
		$this->expectOutputRegex('/class="foo"/');
		\York\HTML\Element\Form::Factory()->setClass('foo')->display();
	}

	public function testLabelAfter(){
		$this->expectOutputRegex('/foo/');
		\York\HTML\Element\Password::Factory()->addLabel('foo', 'after')->display();

	}

	public function testGetId(){
		$this->assertSame('foo', \York\HTML\Element\Form::Factory()->setNameAndId('foo')->getId());
	}

	public function testUploadFormGeneration(){
		$this->expectOutputRegex('/method="post"/');
		$this->expectOutputRegex('/enctype="multipart\/form-data"/');
		\York\HTML\Element\Form::Factory()
		->setIsUploadForm(true)
		->display()
		;
	}

	function testGrid(){
		$this->expectOutputRegex('/<div class="grid_4">/');
		$this->expectOutputRegex('/<div class="clear">/');
		\York\HTML\Element\Grid::Factory()
			->setSize(4)
			->addChild(
				\York\HTML\Element\Span::Factory()
					->setText('moo')
			)
			->display()
			->clear()
		;
	}

	function testHeadline(){
		$this->expectOutputRegex('/<h3/');
		$this->expectOutputRegex('/ahoibrause/');
		$this->expectOutputRegex('/\/h3>/');
		\York\HTML\Element\Headline::Factory()->setSize(3)->setText('ahoibrause')->display();
	}

	function testInputType(){
		$this->expectOutputRegex('/type="moo"/');
		\York\HTML\Element\Input::Factory()->setType('moo')->display();
	}

	function testLabel(){
		$this->expectOutputRegex('/foo/');
		$this->expectOutputRegex('/for="moo"/');
		$label = \York\HTML\Element\Label::Factory();
		$label->setText('foo');
		$label->setPosition('foo');
		$this->assertSame('after', $label->getPosition());
		$label->setPosition('before');
		$this->assertSame('before', $label->getPosition());
		$label->setPosition();
		$this->assertSame('after', $label->getPosition());
		$label->setLabel(\York\HTML\Element\Label::Factory());
		$label->addLabel('foo');
		$label->setFor('moo');
		$label->display();
	}

	function testPlaintext(){
		$this->expectOutputRegex('/foo/');
		\York\HTML\Element\Plaintext::Factory()->addText('foo')->addText('foo')->setText('foo')->display();
	}

	/**
	 * @expectedException Exception
	 */
	function testAddNonRadioOptionToRadioElem(){
		\York\HTML\Element\Radio::Factory()->addChild(\York\HTML\Element\Submit::Factory());
	}

	function testRadioElem(){
		$radio = \York\HTML\Element\Radio::Factory();
		$this->expectOutputRegex('/value="foo!"/');
		$this->expectOutputRegex('/value="bar!"/');
		$this->expectOutputRegex('/name="'.$radio->getName().'"/');
		$radio->addChild(\York\HTML\Element\RadioOption::Factory()->setValue('foo!'));
		$radio->addChild(\York\HTML\Element\RadioOption::Factory()->setValue('bar!'));
		$this->assertSame(2, sizeof($radio->getChildren()));
		$radio->display();
	}

	function testRenderRadioOption(){
		$this->expectOutputRegex('/\<input/');
		$this->expectOutputRegex('/id="ahoibrause"/');
		$this->expectOutputRegex('/name="ahoibrause"/');
		$this->expectOutputRegex('/value="foo!"/');
		$this->expectOutputRegex('/type="radio"/');
		$this->expectOutputRegex('/\/>/');
		\York\HTML\Element\RadioOption::Factory()->setNameAndId('ahoibrause')->setValue('foo!')->display();
	}

	function testFactory(){
		$elems = array(
			'Br',
			'Button',
			'Checkbox',
			'Clear',
			'Dropdown',
			'DropdownElement',
			'DropdownGroup',
			'Form',
			'Grid',
			'Headline',
			'Input',
			'Label',
			'Password',
			'Plaintext',
			'Radio',
			'RadioOption',
			'Span',
			'Submit',
			'Textarea',
		);
		foreach($elems as $elem){
			$object = call_user_func(array('\York\HTML\Element\\'.$elem, 'Factory'));
			#$object = \York\HTML\Element\$elem::Factory();
			$this->assertTrue($object instanceof \York\HTML\DomElementAbstract);
		}
	}

	function testAddChild(){
		$form = \York\HTML\Element\Form::Factory();
		$form->addChild(\York\HTML\Element\Submit::Factory());
		$children = $form->getChildren();
		$this->assertSame(1, sizeof($children));
		$this->assertTrue($children[0] instanceof \York\HTML\Element\Submit);
	}

	function testAddChildren(){
		$amount = 5;
		$form = \York\HTML\Element\Form::Factory();
		$children = array();
		foreach(range(1, $amount) as $counter){
			$children[] = \York\HTML\Element\Submit::Factory();
		}
		$form->addChildren($children);
		$this->assertSame($amount, sizeof($form->getChildren()));
		foreach($form->getChildren() as $current){
			$this->assertTrue($current instanceof \York\HTML\Element\Submit);
		}
	}

	function testRenderSubmit(){
		\York\HTML\Element\Submit::Factory()->setId('pewpew')->setValue('pewpew')->display();
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/type="submit"/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/value="pewpew"/');
		$this->expectOutputRegex('/\/>/');
	}

	function testRenderPassword(){
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/type="password"/');
		$this->expectOutputRegex('/ \/>/');
		\York\HTML\Element\Password::Factory()->setId('pewpew')->setName('password')->display();
	}

	function testFactoryInitWithArgs(){
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/type="text"/');
		$this->expectOutputRegex('/ \/>/');
		\York\HTML\Element\Input::Factory(array(
			'id' => 'pewpew',
			'name' => 'password'
		))->display();
	}

	function testRenderInput(){
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/type="text"/');
		$this->expectOutputRegex('/ \/>/');
		\York\HTML\Element\Input::Factory()->setId('pewpew')->setName('password')->display();
	}

	public function testFormGeneration(){
		$this->expectOutputRegex('/<form/');
		$this->expectOutputRegex('/method="post"/');
		$this->expectOutputRegex('/action="\/auth\/login"/');
		$this->expectOutputRegex('/id="myform"/');
		$this->expectOutputRegex('/<label/');
		$this->expectOutputRegex('/for="password"/');
		$this->expectOutputRegex('/password/');
		$this->expectOutputRegex('/<\/label>/');
		$this->expectOutputRegex('/<input /');
		$this->expectOutputRegex('/label for="email"/');
		$this->expectOutputRegex('/name="email"/');
		$this->expectOutputRegex('/value="Submit"/');
		$this->expectOutputRegex('/<\/form>/');
		\York\HTML\Element\Form::Factory()
			->setAction('/auth/login')
			->setMethod('post')
			->setNameAndId('myform')
			->setIsUploadForm(false)
			->addChild(
				\York\HTML\Element\Password::Factory()
					->setNameAndId('password')
					->setLabel(
						\York\HTML\Element\Label::Factory(array(
							'id' => null,
							'text' => 'password',
							'for' => 'password'
						))
					)
			)
			->addChild(
				\York\HTML\Element\Input::Factory()
					->setNameAndId('email')
					->setLabel(
						\York\HTML\Element\Label::Factory(array(
							'id' => null,
							'text' => 'email',
							'for' => 'email'
						))
					)
					->setValue('email')
			)
			->addChild(
				\York\HTML\Element\Submit::Factory()
					->setValue('Submit')
					->setId('submit')
			)
			->display()
		;
	}

	function testRenderTextarea(){
		$this->expectOutputRegex('/\<textarea/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/rows="1000"/');
		$this->expectOutputRegex('/cols="1000"/');
		$this->expectOutputRegex('/>test'.PHP_EOL.'</');
		$this->expectOutputRegex('/<\/textarea>/');
		\York\HTML\Element\Textarea::Factory()->setId('pewpew')->setName('password')->setText('test')->display();
	}

	function testSetRowsAndColumsForTextArea(){
		$this->expectOutputRegex('/rows="50"/');
		$this->expectOutputRegex('/cols="100"/');
		\York\HTML\Element\Textarea::Factory()->setRows(50)->setCols(100)->display();
	}

	function testRenderSpan(){
		$this->expectOutputRegex('/\<span/');
		$this->expectOutputRegex('/id="ahoibrause"/');
		$this->expectOutputRegex('/name="ahoibrause"/');
		$this->expectOutputRegex('/>foo!'.PHP_EOL.'</');
		$this->expectOutputRegex('/<\/span>/');
		\York\HTML\Element\Span::Factory()->setNameAndId('ahoibrause')->setText('foo!')->display();
	}
}
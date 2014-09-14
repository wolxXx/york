<?php
/**
 * @codeCoverageIgnore
 */
class AssetManagerTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\View\Asset\Manager
	 */
	protected $manager;

	public function setup(){

		$this->manager = $this
			->getMockBuilder('\York\View\Asset\Manager')
			->disableOriginalConstructor()
			->setMethods(array(
				'getTimeStamp'
			))->getMock();

		$this->manager
			->expects($this->any())
			->method('getTimeStamp')
			->will(
				$this->returnValue(42)
			);

		$this->manager->clear();

		parent::setUp();
	}

	public function testInstantiation(){
		$this->assertInstanceOf('\York\View\Asset\Manager', $this->manager);
	}

	public function testFactory(){
		$this->assertInstanceOf('\York\View\Asset\Manager', \York\View\Asset\Manager::Factory());
	}

	public function testIsClearAtStartup(){
		$this->assertEmpty($this->manager->getCssFiles());
		$this->assertEmpty($this->manager->getJavaScriptFiles());
		$this->assertEmpty($this->manager->getLessFiles());

		$this->assertSame('', $this->manager->getCssText());
		$this->assertSame('', $this->manager->getJavaScriptText());

		$this->assertSame('', $this->manager->getCss());
		$this->assertSame('', $this->manager->getJavaScript());
	}

	public function testAddRemoveGetJavaScript(){
		$javaScriptTextA = 'alert("luuuuke!");';
		$javaScriptTextB = 'alert("darth?!");';
		$javaScriptFileA = __DIR__.'/fixtures/foobar.js';
		$javaScriptFileB = __DIR__.'/fixtures/foobbr.js';

		$this->assertEmpty($this->manager->getJavaScriptFiles());
		$this->assertSame('', $this->manager->getJavaScriptText());
		$this->assertSame('', $this->manager->getJavaScript());

		$this->manager->addJavaScriptText($javaScriptTextA);
		$this->assertSame(PHP_EOL.$javaScriptTextA, $this->manager->getJavaScriptText());

		$this->manager->addJavaScriptText($javaScriptTextB, true);
		$this->assertSame($javaScriptTextB.PHP_EOL.PHP_EOL.$javaScriptTextA, $this->manager->getJavaScriptText());

		$this->manager->addJavaScriptFile($javaScriptFileA);
		$this->assertSame(array($javaScriptFileA), $this->manager->getJavaScriptFiles());
		$this->manager->removeJavaScriptFile($javaScriptFileA);
		$this->assertEmpty($this->manager->getJavaScriptFiles());

		$this->manager->addJavaScriptFile($javaScriptFileA);
		$this->manager->addJavaScriptFile($javaScriptFileB, true);
		$this->assertSame(array($javaScriptFileB, $javaScriptFileA), $this->manager->getJavaScriptFiles());

		$this->manager->removeJavaScriptFile($javaScriptFileA);
		$this->assertSame(array($javaScriptFileB), $this->manager->getJavaScriptFiles());

		$this->manager->clear();

		$assertion = <<<ASSERTION
<script>

{$javaScriptTextA}
</script>
<script src="{$javaScriptFileA}?ts=42"></script>
<script src="{$javaScriptFileB}?ts=42"></script>

ASSERTION;


		$this->manager
			->addJavaScriptFiles(array($javaScriptFileB))
			->addJavaScriptFile($javaScriptFileA, true)
			->addJavaScriptText($javaScriptTextA);
		$this->assertSame($assertion, $this->manager->getJavaScript());

	}

	public function testCssAddRemoveAndGet(){
		$cssFileA = __DIR__.'/fixtures/cssA.css';
		$cssFileB = __DIR__.'/fixtures/cssB.css';

		$cssTextA = '#chewbacca {background-color: brown;}';
		$cssTextB = '#darth {background-color: #000;}';

		$this->manager->clear();
		$this->assertSame('', $this->manager->getCss());

		$this->manager->addCssFile($cssFileA);
		$this->assertSame(array($cssFileA), $this->manager->getCssFiles());

		$this->manager->addCssFile($cssFileB, true);
		$this->assertSame(array($cssFileB, $cssFileA), $this->manager->getCssFiles());

		$this->manager->removeCssFile($cssFileA);
		$this->assertSame(array($cssFileB), $this->manager->getCssFiles());

		$this->manager->addCssFile($cssFileA);
		$this->assertSame(array($cssFileB, $cssFileA), $this->manager->getCssFiles());

		$this->manager->clear();
		$this->manager->addCssFiles(array($cssFileB))->addCssFile($cssFileA);

		$this->assertSame(array($cssFileB, $cssFileA), $this->manager->getCssFiles());

		$this->assertSame('', $this->manager->getCssText());
		$this->manager->addCssText('');
		$this->assertSame(PHP_EOL, $this->manager->getCssText());
		$this->manager->addCssText($cssTextA);
		$this->assertSame(PHP_EOL.PHP_EOL.$cssTextA, $this->manager->getCssText());

		$this->manager
			->clearCssText()
			->addCssText($cssTextA)
			->addCssText($cssTextB, true);

		$this->assertSame($cssTextB.PHP_EOL.PHP_EOL.$cssTextA, $this->manager->getCssText());

		$assertion = <<<ASSERTION
<style type="text/css">
{$cssTextB}

{$cssTextA}
</style>
<link rel="stylesheet" type="text/css" href="{$cssFileB}?ts=42">
<link rel="stylesheet" type="text/css" href="{$cssFileA}?ts=42">
ASSERTION;

		$this->assertSame($assertion, $this->manager->getCss());
	}

	public function testAddRemoveGetLess(){
		$lessFileA = __DIR__.'/fixtures/lessA.less';
		$lessFileB = __DIR__.'/fixtures/lessB.less';

		$this->assertSame('', $this->manager->getLess());
		$this->assertEmpty($this->manager->getLessFiles());

		$this->manager->addLessFile($lessFileA);
		$this->assertSame(array($lessFileA), $this->manager->getLessFiles());
		$this->manager->addLessFile($lessFileB, true);
		$this->assertSame(array($lessFileB, $lessFileA), $this->manager->getLessFiles());

		$this->manager->addLessFiles(array($lessFileA, $lessFileB));
		$this->assertSame(array($lessFileB, $lessFileA), $this->manager->getLessFiles());

		$this->manager->removeLessFile($lessFileA);
		$this->assertSame(array($lessFileB), $this->manager->getLessFiles());

		$this->manager->addLessFiles(array($lessFileA, $lessFileB));

		$asertion = <<<ASSERTION
<link rel="stylesheet/less" type="text/css" href="{$lessFileB}?ts=42" />
<link rel="stylesheet/less" type="text/css" href="{$lessFileA}?ts=42" />

<script type="text/javascript">
	less = {
		env: "development", // or "production"
		async: false,	   // load imports async
		fileAsync: false,   // load imports async when in a page under
		poll: 1000,		 // when in watch mode, time in ms between polls
		functions: {},	  // user functions, keyed by name
		dumpLineNumbers: "all", // or "mediaQuery" or "all"
		relativeUrls: true,// whether to adjust url's to be relative
	};
</script><script src="/js/less.js?ts=42" type="text/javascript"></script>

ASSERTION;

		$this->assertSame($asertion, $this->manager->getLess());


	}

	public function testGetSetPathToLess(){
		$this->manager->setPathToLessParser();
		$this->assertSame('/js/less.js', $this->manager->getPathToLessParser());

		$this->manager->setPathToLessParser('asdf');
		$this->assertSame('asdf', $this->manager->getPathToLessParser());
	}

	public function testGetAll(){
		$this->assertSame('', $this->manager->getAll());
	}

	public function testDisplay(){
		$this->expectOutputString('');
		$this->manager->display();
	}

}

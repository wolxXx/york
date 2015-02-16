<?php
class HelperNetTest  extends \PHPUnit_Framework_TestCase{
	public function testResizeImage(){
		$this->assertSame(\York\Helper\Translator::translate('Konnte Datei nicht schreiben.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_CANT_WRITE));
		$this->assertSame(\York\Helper\Translator::translate('Dateityp nicht akzeptiert.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_EXTENSION));
		$this->assertSame(\York\Helper\Translator::translate('Datei zu groß.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_FORM_SIZE));
		$this->assertSame(\York\Helper\Translator::translate('Datei zu groß.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_INI_SIZE));
		$this->assertSame(\York\Helper\Translator::translate('Keine Datei gesendet.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_NO_FILE));
		$this->assertSame(\York\Helper\Translator::translate('Kein Temp-Ordner gefunden.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_NO_TMP_DIR));
		$this->assertSame(\York\Helper\Translator::translate('Kein Fehler aufgetreten.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_OK));
		$this->assertSame(\York\Helper\Translator::translate('Unvollständiger Upload.'), \York\Helper\Net::uploadErrorNumberToString(UPLOAD_ERR_PARTIAL));
		$this->assertSame(\York\Helper\Translator::translate('Unbekannter Fehler. Mulder und Scully ermitteln schon!'), \York\Helper\Net::uploadErrorNumberToString(PHP_INT_MAX));
	}

	public function testIsURLSyntaxOk(){
		$this->assertTrue(\York\Helper\Net::isURLSyntaxOk('http://www.foo.bar'));
		$this->assertFalse(\York\Helper\Net::isURLSyntaxOk('foo.bar'));
	}

	public function testIsMailSyntaxOk(){
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('asdf'));
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('foo@bar'));
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('foo@bar.'));
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('@bar.'));
		$this->assertTrue(\York\Helper\Net::isMailSyntaxOk('foo@bar.org'));
	}

	public function testGetUserIP(){
		$this->assertSame('127.0.0.1', \York\Helper\Net::getUserIP());
		$_SERVER['REMOTE_ADDR'] = '127.0.0.127';
		$this->assertSame('127.0.0.127', \York\Helper\Net::getUserIP());
	}

	public function testGetCurrentUrl(){
		$this->assertSame('localhost', \York\Helper\Net::getCurrentURL());
		$_SERVER['REQUEST_URI'] = '/asdf';
		$_SERVER['HTTP_HOST'] = 'foobar';
		$this->assertSame('http://foobar/asdf', \York\Helper\Net::getCurrentURL());

		$_SERVER['HTTPS'] = 'on';
		$this->assertSame('https://foobar/asdf', \York\Helper\Net::getCurrentURL());
	}

	public function testGetRequestProtocol(){
		$this->assertSame('http', \York\Helper\Net::getRequestProtocol());
		$_SERVER['HTTPS'] = 'on';
		$this->assertSame('https', \York\Helper\Net::getRequestProtocol());
	}


	public function testGetCurrentURI(){
		$this->assertSame('localhost', \York\Helper\Net::getCurrentURI());
		$_SERVER['REQUEST_URI'] = '/asdf';
		$this->assertSame('/asdf', \York\Helper\Net::getCurrentURI());
	}

	public function testGetRequestedProtocol(){
		$applicationConfiguration = new \York\Storage\Simple();
		\York\Dependency\Manager::setDependency('applicationConfiguration', $applicationConfiguration);
		$applicationConfiguration->set('use_https', false);
		$this->assertSame('http://', \York\Helper\Net::getRequestedProtocol());
		$applicationConfiguration->set('use_https', true);
		$this->assertSame('https://', \York\Helper\Net::getRequestedProtocol());
	}
}

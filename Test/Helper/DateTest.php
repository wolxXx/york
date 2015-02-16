<?php
class HelperDateTest  extends \PHPUnit_Framework_TestCase{
	public function testGetRemainingSecondsFromDates(){
		$date1 = '2000-01-01 10:00:00';
		$date2 = '2000-01-01 10:00:10';

		$this->assertSame(10, \York\Helper\Date::getRemainingSecondsFromDates($date1, $date2));
	}

	public function testIsDateInPast(){
		$this->assertFalse(\York\Helper\Date::isDateInPast(new DateTime('2100-01-01 10:00:00')));
		$this->assertTrue(\York\Helper\Date::isDateInPast(new DateTime('2000-01-01 10:00:00')));
	}

	public function testGetDateByTimeStamp(){
		$timeStamp = 11;
		$date = \York\Helper\Date::getDateByTimestamp(null, $timeStamp);
		$this->assertSame('1970-01-01 01:00:11', $date);
		$date = \York\Helper\Date::getDateByTimestamp();
		$this->assertTrue(\York\Helper\String::startsWith($date, date('Y')));
	}

	public function testSecondsToRemainingTime(){
		$this->assertSame('30', \York\Helper\Date::secondsToRemainingTime(30, true));
		$this->assertSame('01:30', \York\Helper\Date::secondsToRemainingTime(90, true));
		$this->assertSame(0, \York\Helper\Date::secondsToRemainingTime(0, true));
		$this->assertSame(0, \York\Helper\Date::secondsToRemainingTime(0));
		$this->assertSame('10',\York\Helper\Date::secondsToRemainingTime(10));
		$this->assertSame('01:30', \York\Helper\Date::secondsToRemainingTime(90));
		$this->assertSame('00:01:30', \York\Helper\Date::secondsToRemainingTime(90, false));
		$this->assertSame('00:00:01', \York\Helper\Date::secondsToRemainingTime(1, false));
	}

	public function testCreateUTCDate(){
		$this->assertSame('Sun, 02 Jan 2000 02:04:05 +0000', \York\Helper\Date::createUTCDate('2000-01-02 03:04:05'));
		$this->assertSame('Fri, 31 Dec 1999 23:00:01 +0000', \York\Helper\Date::createUTCDate('2000-01-01 00:00:01'));
	}

	public function testRenderUTCDate(){
		$this->expectOutputString('03.02.2001, 04:05');
		\York\Helper\Date::renderUTCDate('2001-02-03 04:05:06');
	}

	public function testFormatDateUsingDateTime(){
		$this->expectOutputString('');
		$date = new DateTime('2001-02-03 04:05:06');
		$this->assertSame('03.02.2001, 04:05', \York\Helper\Date::formatDate($date));
		$this->assertSame('03.02.2001, 04:05:06', \York\Helper\Date::formatDate($date, 'd.m.Y, H:i:s'));
	}
}

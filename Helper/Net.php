<?php
namespace York\Helper;
/**
 * net helper utilities class
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Helper
 */
class Net{
	/**
	 * creates a more understandable error message for file upload error numbers
	 *
	 * @param $errorNumber
	 * @return string
	 */
	public static function uploadErrorNumberToString($errorNumber){
		switch($errorNumber){
			case UPLOAD_ERR_CANT_WRITE:{
				return Translator::translate('Konnte Datei nicht schreiben.');
			}break;
			case UPLOAD_ERR_EXTENSION:{
				return Translator::translate('Dateityp nicht akzeptiert.');
			}break;
			case UPLOAD_ERR_FORM_SIZE:{
				return Translator::translate('Datei zu groß.');
			}break;
			case UPLOAD_ERR_INI_SIZE:{
				return Translator::translate('Datei zu groß.');
			}break;
			case UPLOAD_ERR_NO_FILE:{
				return Translator::translate('Keine Datei gesendet.');
			}break;
			case UPLOAD_ERR_NO_TMP_DIR:{
				return Translator::translate('Kein Temp-Ordner gefunden.');
			}break;
			case UPLOAD_ERR_OK:{
				return Translator::translate('Kein Fehler aufgetreten.');
			}break;
			case UPLOAD_ERR_PARTIAL:{
				return Translator::translate('Unvollständiger Upload.');
			}break;
			default:{
				return Translator::translate('Unbekannter Fehler. Mulder und Scully ermitteln schon!');
			}break;
		}
	}
	/**
	 * checks if the syntax of the given string is a valid url
	 *
	 * @param string $url
	 * @return boolean
	 */
	public static function isURLSyntaxOk($url){
		return \York\Helper\String::isURLSyntaxOk($url);
	}

	/**
	 * checks if the given mail address is syntactically correct
	 *
	 * @param $mailAddress
	 * @return boolean
	 */
	public static function isMailSyntaxOk($mailAddress){
		return \York\Helper\String::isMailSyntaxOk($mailAddress);
	}

	/**
	 * retrieves the user's ip address
	 *
	 * @return string
	 */
	public static function getUserIP(){
		if(false === isset($_SERVER['REMOTE_ADDR'])){
			return '127.0.0.1';
		}
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * retrieves the absolute and complete url of the current site
	 *
	 * @return string
	 */
	public static function getCurrentURL(){
		if(false === isset($_SERVER['REQUEST_URI']) || false === isset($_SERVER['HTTP_HOST'])){
			return 'localhost';
		}
		return self::getRequestProtocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	/**
	 * retrieves the relative uri of the current site
	 *
	 * @return string
	 */
	public static function getCurrentURI(){
		if(false === isset($_SERVER['REQUEST_URI'])){
			return 'localhost';
		}
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * returns https if the current request is a https request, http otherwise
	 *
	 * @return string
	 */
	public static function getRequestProtocol(){
		$result = 'http';
		if(true === self::requestIsHTTPS()){
			$result .= 's';
		}
		return $result;
	}

	/**
	 * checks if the current request is a https request
	 *
	 * @return boolean
	 */
	public static function requestIsHTTPS(){
		return isset($_SERVER['HTTPS']) && in_array($_SERVER['HTTPS'], array('on', '1', true));
	}

	/**
	 * returns https if set in the config, else http
	 * as postfix ://
	 *
	 * @return string
	 */
	public static function getRequestedProtocol(){
		return 'http'.(true === \York\Dependency\Manager::get('applicationConfiguration')->getsafely('use_https', false)? 's' : '').'://';
	}

	/**
	 * searches for images on google
	 *
	 * @param string $search
	 * @return array
	 */
	public static function grabGoogleImageSearch($search){
		$search = 'http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q='.urlencode($search);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
		curl_setopt($curl, CURLOPT_URL, $search);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result);
		return $result->responseData->results;
	}

	/**
	 * copies an external source to the local file system
	 * e.g. an image from youtube: http://img.youtube.com/vi/JKPvx38D4GM/default.jpg to files/yt/JKPvx38D4GM.jpg
	 * returns true if the $path is a file after the operation and the file size is greater than 0 kb
	 * if the directory does not exist, it creates the directory recursively
	 *
	 * @param string $url
	 * @param string $path
	 * @return boolean
	 */
	public static function saveWebDataToFileSystem($url, $path){
		$directory = str_replace(basename($path), '', $path);
		$directory = preg_replace('/\w+\/\.\.\//', '', $directory);
		if(false === is_dir($directory)){
			mkdir($directory, 0777, true);
		}
		$url = escapeshellarg($url);
		$file = escapeshellarg($path);
		$command = 'wget -t 2 -q -o log/grabimages --timeout=2 --no-check-certificate -O %s %s';
		$command = sprintf($command, $file, $url);
		exec($command);
		$result = is_file($path) && filesize($path) > 0;
		return $result;
	}
}

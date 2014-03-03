<?php
namespace York;
/**
 * Tool collection for daily usage
 * https://www.youtube.com/watch?v=rtBq9QKJTq8
 *
 * @author wolxXx
 * @version 3.0
 * @package York
 *
 */
class Helper2{
	/**
	 * logs a message that the access is deprecated
	 * leave param for having function and class as default!
	 * @param string | null $message
	 */
	public static function logDeprecatedAccess($message = null){
		$trace = debug_backtrace();
		$trace = $trace[1];
		if(null === $message){
			$message = 'called deprecated method '.$trace['function'];
			if(true === isset($trace['class'])){
				$message .= ' in Class '.$trace['class'];
			}
		}
		Helper::logToFile(Helper::getDate().' | '.$message.' on line '.$trace['line'].' in file '.$trace['file'].' | url: '.Helper::getCurrentURL(), 'deprecated');
	}

	/**
	 * sends args to debug method and dies
	 */
	public static function dieDebug(){
		foreach(func_get_args() as $arg){
			Helper::debug($arg);
		}
		die();
	}

	/**
	 * checks if debugging is enabled
	 * @return boolean
	 */
	public static function isDebugEnabled(){
		return true === in_array(Stack::getInstance()->get('debug'), array('1', true, 'true'));
	}

	/**
	 * var_dumps all provided elements if debugging is enabled in stack
	 */
	public static function debug(){
		$stack = Stack::getInstance();
		if(false === in_array($stack->get('debug'), array('1', true, 'true'))){
			return;
		}
		$backtrace = debug_backtrace(true);
		$trace = $backtrace[0];
		if('CoreHelper.php' === Helper::getFileName($trace['file'])){
			$trace = $backtrace[1];
		}
		$line = isset($trace['line'])? $trace['line'] : 666;
		$file = isset($trace['file'])? $trace['file'] : 'somewhere';
		echo '<div class="debug"><pre>debug from '.(str_replace(Helper::getDocRoot(), '', $file)).' line '.$line.':</pre>';
		foreach(func_get_args() as $arg){
			var_dump($arg);
		}
		echo '</div>';
	}







	/**
	 * cuts a string
	 * if forceHardCut is not set to true, it takes the next letters until end of senctence(.,!,?) or end of word(' ',-,")
	 * @param string $text
	 * @param integer $maxLength
	 * @param string $suffix
	 * @param boolean $forceHardCut
	 * @return string
	 */
	public static function cutText($text, $maxLength = 50, $suffix = '...', $forceHardCut = false){
		if(strlen($text) < $maxLength){
			return $text;
		}

		if(null === $suffix){
			$suffix = '...';
		}
		if(true === $forceHardCut){
			if(strlen($suffix) > $maxLength){
				return substr($text, 0, $maxLength);
			}
			$text = substr($text, 0, $maxLength - strlen($suffix)).$suffix;
			return $text;
		}

		$return = substr($text, 0, $maxLength);
		$length = strlen($text);

		$endOfString = true;

		while($maxLength < $length){
			if(true === in_array($text[$maxLength], array(' ', '.', '!', '?', '-', '"', ','))){
				$endOfString = false;
				$return .= $text[$maxLength];
				break;
			}
			$return .= $text[$maxLength];
			$maxLength++;
		}
		if(false === $endOfString){
			$return .= $suffix;
		}

		return $return;
	}



	/**
	 *
	 * logs an error message
	 * @param string $message
	 */
	public static function logerror($message){
		$trace = debug_backtrace();
		$trace = $trace[1];
		#$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : 'CLI';
		$user = ' | user: '.(true === \York\Auth\Manager::isLoggedIn()? \York\Auth\Manager::getUserNick() : 'arno nym');
		$url = ' | url: '.\York\Helper::getCurrentURI();
		$ref = isset($_SERVER['HTTP_REFERER'])? ' | ref: '.$_SERVER['HTTP_REFERER'] : '';
		$line = isset($trace['line'])? $trace['line'] : 666;
		$file = isset($trace['file'])? $trace['file'] : 'somewhere';
		$occurrenced = ' | file: '.(str_replace(\York\Helper::getDocRoot(), '', $file)).' line '.$line;
		error_log($message.$user.$url.$ref.$occurrenced);
	}




	/**
	 * writes a string to a given logfile
	 * @param string $text
	 * @param string $dest
	 */
	public static function logToFile($text, $dest){
		if(false === is_dir('log')){
			mkdir('log', 0777, true);
		}
		$text .= "\n";
		$file = fopen(self::getDocRoot().'log'.DIRECTORY_SEPARATOR.$dest, 'a+');
		fputs($file, $text);
		fclose($file);
	}

	/**
	 *
	 * puts a string into the splash-stack which is called from the view
	 * @param string $string
	 */
	public static function addSplash($string){
		$stack = Stack::getInstance();
		$splashes = $stack->get('splash');
		if(null === $splashes){
			$splashes = array();
		}
		$splashes[] = $string;
		$stack->set('splash', $splashes);
	}

	/**
	 *
	 * gets all splashes from the stack. clears all splashes in the stack. then calls the partial from the view loader.
	 */
	public static function getSplash(){
		$stack = Stack::getInstance();
		$splashes = $stack->get('splash');
		if(null === $splashes || true === empty($splashes)){
			return null;
		}
		self::clearSplashes();
		$load = Load::getInstance();
		$load->partial('layout/splash', $splashes, true);
	}

	/**
	 * returns the array that contains all splashes
	 */
	public static function getPlainSplashes(){
		$stack = Stack::getInstance();
		return $stack->get('splash');
	}


	/**
	 * deletes all splashes
	 * caution! can not be undone!
	 */
	public static function clearSplashes(){
		$stack = Stack::getInstance();
		$stack->set('splash', null);
	}

	/**
	 * transcodes the php error codes to string
	 *
	 * @param integer $code
	 * @return string
	 */
	public static function errorCodeToString($code){
		$return = '';
		switch($code){
			case E_ERROR:{
				$return = 'E_ERROR'; // 1
			}break;
			case E_WARNING:{
				$return = 'E_WARNING'; // 2
			}break;
			case E_PARSE:{
				$return = 'E_PARSE'; // 4
			}break;
			case E_NOTICE:{
				$return = 'E_NOTICE'; // 8
			}break;
			case E_CORE_ERROR:{
				$return = 'E_CORE_ERROR'; // 16
			}break;
			case E_CORE_WARNING:{
				$return = 'E_CORE_WARNING'; // 32
			}break;
			case E_CORE_ERROR:{
				$return = 'E_COMPILE_ERROR'; // 64
			}break;
			case E_CORE_WARNING:{
				$return = 'E_COMPILE_WARNING'; // 128
			}break;
			case E_USER_ERROR:{
				$return = 'E_USER_ERROR'; // 256
			}break;
			case E_USER_WARNING:{
				$return = 'E_USER_WARNING'; // 512
			}break;
			case E_USER_NOTICE:{
				$return = 'E_USER_NOTICE'; // 1024
			}break;
			case E_STRICT:{
				$return = 'E_STRICT'; // 2048
			}break;
			case E_RECOVERABLE_ERROR:{
				$return = 'E_RECOVERABLE_ERROR'; // 4096
			}break;
			case E_DEPRECATED:{
				$return = 'E_DEPRECATED'; // 8192
			}break;
			case E_USER_DEPRECATED:{
				$return = 'E_USER_DEPRECATED'; // 16384
			}break;
		}
		return $return;
	}

	/**
	 * creates a more understandable error message for file upload errnos
	 * @param integer $errno
	 * @return string
	 */
	public static function uploadErrorNumberToString($errno){
		$return = '';
		switch($errno){
			case UPLOAD_ERR_CANT_WRITE:{
				$return = Translator::translate('Konnte Datei nicht schreiben.');
			}break;
			case UPLOAD_ERR_EXTENSION:{
				$return = Translator::translate('Dateityp nicht akzeptiert.');
			}break;
			case UPLOAD_ERR_FORM_SIZE:{
				$return = Translator::translate('Datei zu groß.');
			}break;
			case UPLOAD_ERR_INI_SIZE:{
				$return = Translator::translate('Datei zu groß.');
			}break;
			case UPLOAD_ERR_NO_FILE:{
				$return = Translator::translate('Keine Datei gesendet.');
			}break;
			case UPLOAD_ERR_NO_TMP_DIR:{
				$return = Translator::translate('Kein Temp-Ordner gefunden.');
			}break;
			case UPLOAD_ERR_OK:{
				$return = Translator::translate('Kein Fehler aufgetreten.');
			}break;
			case UPLOAD_ERR_PARTIAL:{
				$return = Translator::translate('Unvollständiger Upload.');
			}break;
			default:{
				$return = Translator::translate('Unbekannter Fehler. Mulder und Scully ermitteln schon!');
			}break;
		}
		return $return;
	}

	/**
	 * resizes a file
	 * caution! if the file is smaller, it will be forced to grow!!
	 * @param string $filepath
	 * @param integer $width
	 * @param integer $height
	 * @deprecated use resizeImage instead!
	 */
	public static function resize($filepath, $width = 600, $height = 600){
		Helper::logDeprecatedAccess('use resizeImage instead!');
		Helper::resizeImage($filepath, $width, $height);
	}

	/**
	 * resizes a file
	 * caution! if the file is smaller, it will be forced to grow!!
	 * @param string $filepath
	 * @param integer $width
	 * @param integer $height
	 */
	public static function resizeImage($filepath, $width = 600, $height = 600){
		passthru('mogrify -resize '.$width.'x'.$height.' '.$filepath);
	}

	/**
	 * creates a thumbnail for an image
	 * forces be $width x $height pixel
	 * it will be stretched down or up!
	 * @param string $source
	 * @param string $target
	 * @param integer $width
	 * @param integer $height
	 * @return boolean
	 */
	public static function createThumbnail($source, $target, $width = 200, $height = 200){
		$command = 'convert "'.$source.'" -antialias -resize '.$width.'x'.$height.'! "'.$target.'"';
		$return = $output = null;
		exec($command, $output, $return);
		return 0 === $return;
	}

	/**
	 * retrieves information about a youtube video
	 * provide just the plain youtube video id
	 * not https://www.youtube.com/watch?v=V9bwo4N1AAE => just V9bwo4N1AAE
	 * the object that is returned contains the title and the duration in seconds
	 * @param string $ytid
	 * @return stdClass
	 */
	public static function getYoutubeVideoInformation($ytid){
		$return = new stdClass();
		$url = 'http://gdata.youtube.com/feeds/api/videos?q='.$ytid;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$feed = curl_exec($curl);
		curl_close($curl);
		$xml = simplexml_load_string($feed);
		if(true === in_array($xml, array(null, false), true)){
			throw new Exception('could not parse youtube information for ytid = '.$ytid);
		}
		$entry = $xml->entry[0];
		if(null === $entry){
			throw new Exception('could not parse youtube information for ytid = '.$ytid);
		}
		$media = $entry->children('media', true);
		$group = $media->group;
		$return->title = ucwords(strtolower($group->title));
		$content_attributes = $group->content->attributes();
		$return->duration = intval($content_attributes['duration'].'');
		return $return;
	}

	/**
	 * function for getting the first pages for a google search
	 * @todo guggn, obs geht
	 * @param string $terms
	 * @param integer $numpages
	 * @param string $user_agent
	 * @return boolean
	 */
	public static function fetch_google($terms = 'sample search', $numpages = 1, $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0'){
		$searched="";
		for($i = 0; $i <= $numpages; $i++){
			$curl = curl_init();
			$url="http://www.google.com/searchbyimage?hl=en&image_url=".urlencode($terms);
			curl_setopt ($curl, CURLOPT_URL, $url);
			curl_setopt ($curl, CURLOPT_USERAGENT, $user_agent);
			curl_setopt ($curl, CURLOPT_HEADER, 0);
			curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($curl, CURLOPT_REFERER, 'http://www.google.com/');
			curl_setopt ($curl,CURLOPT_CONNECTTIMEOUT,120);
			curl_setopt ($curl,CURLOPT_TIMEOUT,120);
			curl_setopt ($curl,CURLOPT_MAXREDIRS,10);
			curl_setopt ($curl,CURLOPT_COOKIEFILE,"cookie.txt");
			curl_setopt ($curl,CURLOPT_COOKIEJAR,"cookie.txt");
			$searched=$searched.curl_exec ($curl);
			curl_close ($curl);
		}

		$matches = array();
		preg_match('/Best guess for this image:[^<]+<a[^>]+>([^<]+)/', $searched, $matches);
		return (count($matches) > 1 ? $matches[1] : false);
	}

	/**
	 * sends a mail..
	 * force = true forces sending the mail even if not in production mode
	 * the files array wants to have strings for the full file path
	 *
	 * @param string $sender
	 * @param string $reciever
	 * @param string $subject
	 * @param string $mailText
	 * @param array $files
	 * @param boolean $force
	 * @return boolean
	 */
	public static function sendMail($sender, $reciever, $subject, $mailText, $files = array(), $force = false){
		$stack = Stack::getInstance();
		$sending = false;
		if(true === $force || 'production' == $stack->get('mode')){
			$sending = true;
		}

		if(null === $sender){
			$sender = Stack::getInstance()->get('admin_email');
		}

		$text = PHP_EOL.PHP_EOL.'___________________________'.PHP_EOL;
		if(false === $sending){
			$text .= 'DUMMY! NOT SENDING THIS!'.PHP_EOL;
		}
		$text .= 'date: '.self::getDate().PHP_EOL;
		$text .= 'reciever: '.$reciever.PHP_EOL;
		$text .= 'sender: '.$sender.PHP_EOL;
		$text .= 'subject: '.$subject.PHP_EOL;
		$text .= 'files: '.PHP_EOL;
		if(true === empty($files)){
			$text .= '-none-';
		}else{
			foreach($files as $current){
				$text .= $current.PHP_EOL;
			}
		}
		$text .= PHP_EOL;
		$text .= 'text: '.PHP_EOL.$mailText.PHP_EOL.PHP_EOL;
		$text .= PHP_EOL.'___________________________'.PHP_EOL;

		Helper::logToFile($text, 'maillog');

		if(false === $sending){
			return true;
		}
		//yes, really send this fucking email out to the nasty fucking shit reciever!
		$headers = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=UTF-8";
		$headers[] = "From: $sender";
		$headers[] = "Reply-To: $sender";
		$headers[] = "X-Mailer: PHP/".phpversion();
		$headers[] = "";

		return mail($reciever, '=?UTF-8?B?'.base64_encode($subject).'?=', $mailText, implode(PHP_EOL, $headers));
	}
}

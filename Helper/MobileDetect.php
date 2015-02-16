<?php
namespace York\Helper;

/**
 * Class MobileDetect
 *
 * @package York\Helper
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version    SVN: $Id: Mobile_Detect.php 4 2011-05-26 08:04:20Z vic.stanciu@gmail.com $
 *
 */
class MobileDetect
{
    /**
     * @var string
     */
    protected $accept;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var boolean
     */
    protected $isMobile = false;

    /**
     * @var boolean
     */
    protected $isAndroid = false;

    /**
     * @var boolean
     */
    protected $isBlackberry = false;

    /**
     * @var boolean
     */
    protected $isOpera = false;

    /**
     * @var boolean
     */
    protected $isPalm = false;

    /**
     * @var boolean
     */
    protected $isWindows = false;

    /**
     * @var boolean
     */
    protected $isGeneric = false;

    /**
     * @var boolean
     */
    protected $isIphone = false;

    /**
     * @var string[]
     */
    protected $devices = array(
        'android' => 'android',
        'blackberry' => 'blackberry',
        'iphone' => '(iphone|ipod)',
        'opera' => 'opera mini',
        'palm' => '(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)',
        'windows' => 'windows ce; (iemobile|ppc|smartphone)',
        'generic' => '(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)'
    );

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $this->accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            $this->isMobile = true;
        } elseif (strpos($this->accept, 'text/vnd.wap.wml') > 0 || strpos($this->accept, 'application/vnd.wap.xhtml+xml') > 0) {
            $this->isMobile = true;
        } else {
            foreach ($this->devices as $device => $regexp) {
                if ($this->isDevice($device)) {
                    $this->isMobile = true;
                }
            }
        }
    }

    /**
     * Overloads isAndroid() | isBlackberry() | isOpera() | isPalm() | isWindows() | isGeneric() through isDevice()
     *
     * @param string    $name
     * @param array     $arguments
     *
     * @return boolean
     *
     * @codeCoverageIgnore
     */
    public function __call($name, $arguments)
    {
        $device = substr($name, 2);

        if ($name == 'is' . ucfirst($device)) {
            return $this->isDevice($device);
        } else {
            trigger_error(sprintf('Method %s not defined', $name), E_USER_ERROR);
        }
    }

    /**
     * Returns true if any type of mobile device detected, including special ones
     *
     * @return boolean
     *
     * @codeCoverageIgnore
     */
    public function isMobile()
    {
        return $this->isMobile;
    }

    /**
     * @param string $device
     *
     * @return boolean
     *
     * @codeCoverageIgnore
     */
    protected function isDevice($device)
    {
        $var = 'is' . ucfirst($device);
        $return = $this->$var === null ? (bool)preg_match('/' . $this->devices[$device] . '/i', $this->userAgent) : $this->$var;

        if ($device != 'generic' && $return == true) {
            $this->isGeneric = false;
        }

        return $return;
    }
}

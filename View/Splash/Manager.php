<?php
namespace York\View\Splash;

/**
 * splash manager
 *
 * @package \York\View\Splash
 * @version $version$
 * @author wolxXx
 */
class Manager
{
    /**
     * session key for splashes
     */
    const sessionKey = 'york.session.splashes';

    /**
     * retrieves all set splashes
     *
     * @return \York\View\Splash\ItemInterface[]
     */
    public function getSplashes()
    {
        return \York\Dependency\Manager::getSession()->getSafely(self::sessionKey, array());
    }

    /**
     * check if splashes are available
     *
     * @return boolean
     */
    public function hasSplashes()
    {
        return 0 !== sizeof($this->getSplashes());
    }

    /**
     * shortcut for add new splash
     *
     * @param string    $text
     * @param boolean   $append
     *
     * @return $this
     */
    public function addText($text, $append = true)
    {
        return $this->addSplash(new \York\View\Splash\Item($text), $append);
    }

    /**
     * adds a splash to the splash set
     * you can append (default) or prepend (set append to false) the splash
     *
     * @param \York\View\Splash\ItemInterface   $splash
     * @param boolean                           $append
     *
     * @return $this
     */
    public function addSplash(\York\View\Splash\ItemInterface $splash, $append = true)
    {
        $splashes = $this->getSplashes();

        if (true === $append) {
            $splashes[] = $splash;
        } else {
            array_unshift($splashes, $splash);
        }

        \York\Dependency\Manager::getSession()->set(self::sessionKey, $splashes);

        return $this;
    }

    /**
     * clears all set splashes
     *
     * @return $this
     */
    public function clearSplashes()
    {
        \York\Dependency\Manager::getSession()->set(self::sessionKey, array());

        return $this;
    }
}

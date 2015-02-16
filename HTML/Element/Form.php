<?php
namespace York\HTML\Element;

/**
 * container for form elements
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 *
 * @todo $isUploadForm as class member? not sure, dude :)
 */
class Form extends \York\HTML\ContainableDomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Form
     */
    public static function Factory($data = array())
    {
        return parent::Factory($data);
    }

    /**
     * flag if the enctype is multipart/form-data
     *
     * @var boolean
     */
    protected $isUploadForm = false;

    /**
     * @inheritdoc
     */
    public static function getDefaultConf()
    {
        return array(
            'action' => '',
            'method' => 'post',
            'enctype' => null
        );
    }

    /**
     * setter for the method
     * only post or get are allowed!
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->set('method', 'post' === $method ? 'post' : 'get');

        return $this;
    }

    /**
     * setter for the action
     *
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->set('action', $action);

        return $this;
    }

    /**
     * setter for upload form flag
     *
     * @param boolean $isUploadForm
     *
     * @return $this
     */
    public function setIsUploadForm($isUploadForm = true)
    {
        $this->isUploadForm = true === $isUploadForm;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function display()
    {
        $this->displayLabelBefore();

        $conf = $this->getConf();

        if (true === $this->isUploadForm) {
            $conf['enctype'] = 'multipart/form-data';
            $conf['method'] = 'post';
        }

        \York\HTML\Core::out(
            \York\HTML\Core::openTag('form', $conf)
        );

        foreach ($this->children as $current) {
            $current->display();
        }

        \York\HTML\Core::out(
            \York\HTML\Core::closeTag('form')
        );

        $this->displayLabelAfter();

        return $this;
    }
}

<?php
namespace York\View;
/**
 * view helper for pagination
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\View
 */
class Paginator{
	/**
	 * if the paginator should really be disabled
	 *
	 * @var boolean
	 */
	protected $hidePaginator;

	/**
	 * the current page number
	 *
	 * @var integer
	 */
	protected $pageNumber;

	/**
	 * the amount of pages available
	 *
	 * @var integer
	 */
	protected $pages;

	/**
	 * the prefix for urls
	 *
	 * @var string
	 */
	protected $urlPrefix;

	/**
	 * constructor for the paginator information value object
	 *
	 * @param boolean $hidePaginator
	 * @param integer $pageNumber
	 * @param integer $pages
	 * @param string $urlPrefix
	 */
	public function __construct($hidePaginator = false, $pageNumber = 1, $pages = 1, $urlPrefix = ''){
		$this
			->setHidePaginator($hidePaginator)
			->setPageNumber($pageNumber)
			->setPages($pages)
			->setUrlPrefix($urlPrefix);
	}

	/**
	 * getter for hiding pagination
	 * returning false if manually set to false or if only one page is available
	 *
	 * @return boolean
	 */
	public function isHidePaginator(){
		return $this->hidePaginator;
	}

	/**
	 * getter for the current page number
	 *
	 * @return integer
	 */
	public function getPageNumber(){
		return $this->pageNumber;
	}

	/**
	 * getter for the number of pages
	 *
	 * @return integer
	 */
	public function getPages(){
		return $this->pages;
	}

	/**
	 * getter for the url prefix
	 *
	 * @return string
	 */
	public function getUrlPrefix(){
		return $this->urlPrefix;
	}

	/**
	 * setter for hiding paginator manually
	 *
	 * @param boolean $hidePaginator
	 * @return \York\View\Paginator
	 */
	public function setHidePaginator($hidePaginator = true){
		$this->hidePaginator = true === $hidePaginator;
		return $this;
	}

	/**
	 * setter for amount of available pages
	 *
	 * @param integer $pages
	 * @return \York\View\Paginator
	 */
	public function setPages($pages){
		$this->pages = (int) $pages;
		return $this;
	}

	/**
	 * setter for the current page number
	 *
	 * @param integer $pageNumber
	 * @return \York\View\Paginator
	 */
	public function setPageNumber($pageNumber){
		$this->pageNumber = (int) $pageNumber;
		return $this;
	}

	/**
	 * setter for the url prefix
	 *
	 * @param string $urlPrefix
	 * @return \York\View\Paginator
	 */
	public function setUrlPrefix($urlPrefix){
		$this->urlPrefix = $urlPrefix;
		return $this;
	}
}
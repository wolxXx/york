<?php
namespace York\AccessCheck;

/**
 * checks the right to access actions in the controller
 *
 * @package York\AccessCheck
 * @version $version$
 * @author  wolxXx
 */
class Manager
{
    /**
     * is the current user logged in?
     *
     * @var boolean
     */
    protected $userIsLoggedIn;

    /**
     * which level has the current user?
     *
     * @var integer
     */
    protected $userLevel;

    /**
     * set of AccessRules
     *
     * @var \York\AccessCheck\Rule[]
     */
    protected $rules;

    /**
     * constructor
     *
     * @param boolean $userIsLoggedIn
     * @param integer $userLevel
     */
    public function __construct($userIsLoggedIn = false, $userLevel = 0)
    {
        $this
            ->clearRules()
            ->setUserIsLoggedIn($userIsLoggedIn)
            ->setUserLevel($userLevel);
    }

    /**
     * setter for user is logged in
     *
     * @param boolean $userIsLoggedIn
     *
     * @return $this
     */
    public function setUserIsLoggedIn($userIsLoggedIn)
    {
        $this->userIsLoggedIn = $userIsLoggedIn;

        return $this;
    }

    /**
     * checks if the user is logged in
     *
     * @return boolean
     */
    public function isUserLoggedIn()
    {
        return $this->userIsLoggedIn;
    }

    /**
     * setter for the user level
     *
     * @param integer $userLevel
     *
     * @return $this
     */
    public function setUserLevel($userLevel)
    {
        $this->userLevel = $userLevel;

        return $this;
    }

    /**
     * getter for the user user
     *
     * @return integer
     */
    public function getUserLevel()
    {
        return $this->userLevel;
    }

    /**
     * adds a rule to the rule set
     *
     * @param \York\AccessCheck\Rule $rule
     *
     * @return $this
     */
    public function addRule(\York\AccessCheck\Rule $rule)
    {
        $this->rules[$rule->getActionName()] = $rule;

        return $this;
    }

    /**
     * returns all set rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * removes a rule from the rule set
     *
     * @param string $actionName
     *
     * @return $this
     */
    public function removeRule($actionName)
    {
        if (array_key_exists($actionName, $this->rules)) {
            unset($this->rules[$actionName]);
        }

        return $this;
    }

    /**
     * clears all set rules
     *
     * @return $this
     */
    public function clearRules()
    {
        $this->rules = array();

        return $this;
    }

    /**
     * checks the possibility to access the requested action
     * if no rule was found it returns true
     * otherwise return the result of the checked rule
     *
     * @param string $actionName
     *
     * @return boolean
     * @throws \York\Exception\Apocalypse
     */
    public function checkAccess($actionName)
    {
        $rule = null;
        if (true === $this->hasRuleForAction($actionName)) {
            $rule = $this->rules[$actionName];
        } elseif (true === $this->hasRuleForAction('*')) {
            $rule = $this->rules['*'];
        }

        if (null !== $rule) {
            return $this->checkAccessRule($rule);
        }

        throw new \York\Exception\Apocalypse();
    }

    /**
     * checks if a rule was added for an explicit action
     *
     * @param string $actionName
     *
     * @return boolean
     */
    public function hasRuleForAction($actionName)
    {
        return array_key_exists($actionName, $this->rules);
    }

    /**
     * checks if the requested action requires a logged in user
     *
     * @param string $actionName
     *
     * @return boolean
     */
    public function requiresAuth($actionName)
    {
        if (true === $this->hasRuleForAction($actionName)) {
            return $this->rules[$actionName]->isAuthNeeded();
        }

        if (true === $this->hasRuleForAction('*')) {
            return $this->rules['*']->isAuthNeeded();
        }

        return false;
    }

    /**
     * checks if the current user is allowed to run the requested action
     *
     * @param \York\AccessCheck\Rule $rule
     *
     * @return boolean
     */
    protected function checkAccessRule(\York\AccessCheck\Rule $rule)
    {
        if (false === $rule->isAuthNeeded()) {
            return true;
        }

        if (false === $this->userIsLoggedIn) {
            return false;
        }

        if ($rule->getLevelNeeded() <= $this->userLevel) {
            return true;
        }

        return false;
    }
}

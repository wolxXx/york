<?php
namespace Application\Controller;

/**
 * backend management tool
 *
 * @author York Framework
 * @version 1.0
 * @package Application\Controller
 */
class Admin extends \York\Controller
{
    /**
     * list all models
     *
     * @return $this
     *
     * @throws \York\Exception\KeyNotFound
     */
    public function modelsAction()
    {
        $this->viewManager->set('models', \York\Database\Information::getAllTables(\Application\Configuration\Dependency::getDatabaseConfiguration()->get('db_schema')));

        return $this;
    }

    /**
     * create new data set
     *
     * @return $this
     *
     * @throws \York\Exception\KeyNotFound
     */
    public function createModelAction()
    {
        $table = func_get_arg(2);

        if (false === $this->request->isPost()) {
            $columns = \York\Database\Information::getColumnsForTable(\York\Dependency\Manager::getDatabaseConfiguration()->get('db_schema'), $table);

            $this->viewManager->set('table', $table);
            $this->viewManager->set('columns', $columns);

            return $this;
        }

        $newItem = \York\Database\Accessor\Factory::getSaveObject($table);

        foreach ($this->request->dataObject->getRawPOST() as $key => $value) {
            $newItem->set($key, $value);
        }

        $result = $newItem->save();

        $this->registerRedirect('/admin/editModel/' . $table . '/' . $result->getLastInsertId());

        return $this;
    }

    /**
     * edit a data set
     *
     * @return $this
     *
     * @throws \York\Exception\KeyNotFound
     */
    public function editModelAction()
    {
        $table = func_get_arg(2);
        $id = func_get_arg(3);
        $columns = \York\Database\Information::getColumnsForTable(\York\Dependency\Manager::getDatabaseConfiguration()->get('db_schema'), $table);
        $model = new \York\Database\Model();
        $item = $model->findOne($table, $id);

        $this->viewManager->set('table', $table);
        $this->viewManager->set('id', $id);
        $this->viewManager->set('columns', $columns);
        $this->viewManager->set('item', $item);

        if (false === $this->request->isPost()) {
            return $this;
        }

        $this->registerRedirect('/admin/editModel/' . $table . '/' . $id, \York\Redirect::$historyBack);

        $update = \York\Database\Accessor\Factory::getUpdateObject($table, $id);

        foreach ($this->request->dataObject->getRawPOST() as $key => $value) {
            $update->set($key, $value);
        }

        $update->update();

        return $this;
    }

    /**
     * clear a table
     *
     * @return $this
     */
    public function clearTableAction()
    {
        $model = func_get_arg(2);
        \Application\Configuration\Dependency::get($model . 'Manager')->clearAll();

        return $this->registerRedirect('/admin/models', \York\Redirect::$historyBack);
    }

    /**
     * delete a single data set
     *
     * @return $this
     */
    public function deleteModelAction()
    {
        $this->registerRedirect('/admin/models', \York\Redirect::$historyBack);
        \York\Database\Accessor\Factory::getDeleteObject(func_get_arg(2), func_get_arg(3))->delete();

        return $this;
    }

    /**
     * list data sets for model
     *
     * @return $this
     *
     * @throws \York\Exception\KeyNotFound
     */
    public function listModelAction()
    {
        if (3 === func_num_args()) {
            return $this->registerRedirect($_SERVER['REQUEST_URI'] . '/page/1');
        }

        $entriesPerPage = 20;
        $currentPage = func_get_arg(4);
        $table = func_get_arg(2);
        $paginator = new \York\View\Paginator(false, $currentPage, 0, sprintf('/admin/listModel/%s', $table));
        $model = new \York\Database\Model();
        $columns = \York\Database\Information::getColumnsForTable(\York\Dependency\Manager::getDatabaseConfiguration()->get('db_schema'), $table);

        $conditions = array(
            'from' => array(
                $table
            ),
            'fields' => array(
                '*'
            ),
            'order' => 'id DESC'
        );

        $paginator->setPages((int)ceil($model->count($conditions) / $entriesPerPage));

        if ($paginator->getPages() < 2) {
            $paginator->setHidePaginator();
        }

        $conditions['limit'] = sprintf('%s, %s', $entriesPerPage * ($currentPage - 1), $entriesPerPage);

        $select = new \York\Database\QueryBuilder\Select($conditions);

        $items = $model->findAllByQueryString($select->getQueryString());

        $this->viewManager->set('columns', $columns);
        $this->viewManager->set('table', func_get_arg(2));
        $this->viewManager->set('items', $items);
        $this->viewManager->set('paginator', $paginator);

        return $this;
    }

    /**
     * list all users
     *
     * @return $this
     */
    public function usersAction()
    {
        $this->viewManager->set('users', \Application\Configuration\Dependency::getUserManager()->findAll());

        return $this;
    }

    /**
     * list all new users
     *
     * @return $this
     */
    public function newUsersAction()
    {
        $users = \Application\Configuration\Dependency::getUserManager()->find(new \York\Database\QueryBuilder\Select(array(
            'from' => array(
                \Application\Configuration\Dependency::getUserManager()->getTableName()
            ),
            'where' => array(
                'status' => \York\Configuration::$USER_STATUS_PENDING
            )
        )));

        $this->viewManager->set('users', $users);

        return $this;
    }

    /**
     * update the status of a user
     *
     * @return $this
     */
    public function setUserStatusAction()
    {
        $this->registerRedirect('/admin/users', \York\Redirect::$historyBack);

        \Application\Configuration\Dependency::getUserManager()->getById(func_get_arg(2))->setStatus(func_get_arg(3))->save();

        if (func_get_arg(3) == \York\Configuration::$USER_STATUS_ACTIVATED) {
            \Application\Helper::sendActivationMail(\Application\Configuration\Dependency::getUserManager()->getById(func_get_arg(2)));
        }

        return $this;
    }

    /**
     * update the type of a user
     *
     * @return $this
     */
    public function setUserTypeAction()
    {
        \Application\Configuration\Dependency::getUserManager()
            ->getById(func_get_arg(2))
            ->setType(func_get_arg(3))
            ->save();

        return $this->registerRedirect('/admin/users', \York\Redirect::$historyBack);
    }

    /**
     * index pages lists new users and all database tables
     *
     * @return $this
     */
    public function indexAction()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    function setAccessRules()
    {
        $this->accessChecker->addRule(new \York\AccessCheck\Rule('*', true, \York\Configuration::$USER_TYPE_ADMIN));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function afterRun()
    {
        $this->viewManager->setLayout('admin');

        return $this;
    }
}

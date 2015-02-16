<?php
namespace Application\Migration;

/**
 * yet another migration
 *
 * @package Application\Migration
 * @version 1.0
 * @author York Framework
 * @generated %%generationDate%%
 */
class Migration%%number%% extends \York\Database\Migration
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        /**
         * this is where the main procedure takes place. drop your code here!
         */
        $yourSQL = 'select * from foobar where 1 = 1';
        $this->query($yourSQL);
    }
}

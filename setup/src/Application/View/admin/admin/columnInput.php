<?php
/**
 * @param \stdClass $column
 * @param mixed     $value
 *
 * @return \York\HTML\DomElementAbstract
 */
function getInput($column, $value)
{
    switch ($column->DATA_TYPE) {
        case 'int':
            if ('id' === $column->COLUMN_NAME) {
                return getIdInput($column, $value);
            }

            return getTextInput($column, $value);

            break;

        case 'varchar':
            return getTextInput($column, $value);

            break;

        case 'text':
            return getTextarea($column, $value);

            break;

        case 'tinyint':
            return getTinyintInput($column, $value);

            break;

        case 'datetime':
            return getDatetime($column, $value);

            break;

        default:
            \York\Helper\Application::debug('unable to generate input for type ' . $column->DATA_TYPE);

            return null;

            break;
    }
}

/**
 * @param \stdClass $column
 * @param mixed     $value
 *
 * @return \York\HTML\Element\Date
 */
function getDatetime($column, $value)
{
    return \York\HTML\Element\Date::Factory()
        ->addLabel(sprintf('%s (%s)', $column->COLUMN_NAME, $column->DATA_TYPE))
        ->setValue(null === $value ? \York\Helper\Date::getDate() : $value)
        ->setNameAndId($column->COLUMN_NAME);
}

/**
 * @param \stdClass $column
 * @param mixed     $value
 *
 * @return \York\HTML\Element\Radio
 */
function getTinyintInput($column, $value)
{
    return \York\HTML\Element\Radio::Factory()
        ->setNameAndId($column->COLUMN_NAME)
        ->addChild(
            \York\HTML\Element\RadioOption::Factory()
                ->set('checked', 1 == $value)
                ->setValue(1)
        )->addChild(
            \York\HTML\Element\RadioOption::Factory()
                ->set('checked', 0 == $value)
                ->setValue(0)
        );
}

/**
 * @param \stdClass $column
 * @param mixed     $value
 *
 * @return \York\HTML\Element\Textarea
 */
function getTextarea($column, $value)
{
    return \York\HTML\Element\Textarea::Factory()
        ->setRows(1000)
        ->setCols(1000)
        ->setNameAndId($column->COLUMN_NAME)
        ->setText($value)
        ->addLabel(sprintf('%s (%s)', $column->COLUMN_NAME, $column->DATA_TYPE));
}

/**
 * @param \stdClass $column
 * @param mixed     $value
 *
 * @return \York\HTML\Element\Input
 *
 * @throws \York\Exception\Translator
 */
function getIdInput($column, $value)
{
    return \York\HTML\Element\Input::Factory()
        ->set('readonly', 'readonly')
        ->setValue($value)
        ->setId($column->COLUMN_NAME)
        ->setName('')
        ->addLabel(\Application\Configuration\Dependency::getTranslator()->translate('ID (wird automatisch vergeben!)'));
}

/**
 * @param \stdClass $column
 * @param mixed     $value
 *
 * @return \York\HTML\Element\Input
 *
 * @throws \York\Exception\Translator
 */
function getTextInput($column, $value)
{
    return \York\HTML\Element\Input::Factory()
        ->setValue($value)
        ->setNameAndId($column->COLUMN_NAME)
        ->addLabel(sprintf('%s (%s%s)', $column->COLUMN_NAME, $column->DATA_TYPE, null !== $column->CHARACTER_MAXIMUM_LENGTH ? ' (' . \Application\Configuration\Dependency::getTranslator()->translate('max %s Zeichen', $column->CHARACTER_MAXIMUM_LENGTH) . ')' : ''));
}

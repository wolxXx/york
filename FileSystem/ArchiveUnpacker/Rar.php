<?php
namespace York\FileSystem\ArchiveUnpacker;

/**
 * rar archive unpacker
 *
 * @package York\FileSystem\ArchiveUnpacker
 * @version $version$
 * @author wolxXx
 *
 */
class Rar extends Unpacker
{
    /**
     * @inheritdoc
     */
    public function unpack()
    {
        $result = new \York\Type\Boolean(false);

        try {
            $result->set(true);
        } catch (\Exception $exception) {

        }

        return $result;
    }
}

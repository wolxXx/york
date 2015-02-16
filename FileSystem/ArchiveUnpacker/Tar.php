<?php
namespace York\FileSystem\ArchiveUnpacker;

/**
 * Class Tar
 *
 * @package York\FileSystem\ArchiveUnpacker
 * @version $version$
 * @author wolxXx
 */
class Tar extends Unpacker
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

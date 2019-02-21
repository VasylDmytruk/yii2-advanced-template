<?php

namespace common\services\files;

/**
 * Class FileMoverService
 */
class FileMoverService extends BaseFilerService
{
    /**
     * Moves (rename) files or directory.
     *
     * > Note: Method does not checks paths on valid.
     * You can use before [[BaseFilerService::getResolvedDirPath()]] to validate, resolve and if need create path.
     *
     * @param string $fromResolved Full path from.
     * @param string $toResolved Full path to.
     *
     * @return bool Returns `true` on success move, otherwise returns `false`.
     */
    public function move(string $fromResolved, string $toResolved)
    {
        $moved = rename($fromResolved, $toResolved);

        return $moved;
    }
}

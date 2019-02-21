<?php

namespace common\services\files;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

/**
 * Class BaseFilerService
 */
abstract class BaseFilerService extends BaseObject
{
    /**
     * Gets dir path, if dir path not exists creates it. Resolves path alias if need.
     *
     * @param string $dirPath Dir path.
     *
     * @return string Returns resolved exist dir path.
     *
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function getResolvedDirPath(string $dirPath): string
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $dirPath = Yii::getAlias($dirPath);

        if (!file_exists($dirPath)) {
            $created = FileHelper::createDirectory($dirPath);
            if (!$created) {
                throw new InvalidConfigException('Can not create directory ' . $dirPath);
            }
        }

        return $dirPath;
    }

    /**
     * Generates full path from and to. Appends `$name` to `$fromPath` and to `$toPath`.
     *
     * @param string $fromPath From path.
     * @param string $toPath To path.
     * @param string $name Name to append ro paths.
     *
     * @return array Returns array of `[$fullFromPath, $fullToPath]`.
     */
    public function getFullFromTo(string $fromPath, string $toPath, string $name): array
    {
        $fullFrom = rtrim($fromPath, '/') . '/' . $name;
        $fullTo = rtrim($toPath, '/') . '/' . $name;

        return [$fullFrom, $fullTo];
    }

    /**
     * Deletes file or directory (recursively) if exist.
     *
     * @param string $fullPathTo Path to file or directory.
     *
     * @throws \yii\base\ErrorException
     */
    public function deleteIfExist(string $fullPathTo): void
    {
        if (!file_exists($fullPathTo)) {
            return;
        }

        if (is_dir($fullPathTo)) {
            FileHelper::removeDirectory($fullPathTo);
        } else {
            unlink($fullPathTo);
        }
    }
}

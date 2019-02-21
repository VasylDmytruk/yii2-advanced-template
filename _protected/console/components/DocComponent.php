<?php

namespace console\components;

use common\services\files\FileMoverService;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class DocComponent
 */
class DocComponent extends Component
{
    public $assetsDirName = 'assets';
    public $jsSearchName = 'jssearch.index.js';

    /**
     * @var FileMoverService
     */
    protected $fileMover;


    /**
     * DocComponent constructor.
     *
     * @param FileMoverService $fileMover
     * @param array $config
     */
    public function __construct(FileMoverService $fileMover, array $config = [])
    {
        $this->fileMover = $fileMover;

        parent::__construct($config);
    }

    /**
     * Moves doc assets.
     *
     * @param string $from Path to move from.
     * @param string $to Path to move to.
     *
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function moveAssets(string $from, string $to)
    {
        $fromResolved = $this->fileMover->getResolvedDirPath($from);
        $toResolved = $this->fileMover->getResolvedDirPath($to);

        $this->moveAssetsDir($fromResolved, $toResolved);
        $this->moveJsSearch($fromResolved, $toResolved);
    }

    /**
     * @param string $from
     * @param string $to
     */
    private function moveAssetsDir(string $from, string $to): void
    {
        list($fullPathFrom, $fullPathTo) = $this->fileMover->getFullFromTo($from, $to, $this->assetsDirName);

        $this->move($fullPathFrom, $fullPathTo);
    }

    /**
     * @param string $from
     * @param string $to
     */
    private function moveJsSearch(string $from, string $to): void
    {
        list($fullPathFrom, $fullPathTo) = $this->fileMover->getFullFromTo($from, $to, $this->jsSearchName);

        $this->move($fullPathFrom, $fullPathTo);
    }

    /**
     * @param string $fullPathFrom
     * @param string $fullPathTo
     */
    private function move(string $fullPathFrom, string $fullPathTo): void
    {
        $errorMessage = "Can not move from '$fullPathFrom' to '$fullPathTo'";

        try {
            $this->fileMover->deleteIfExist($fullPathTo);
            $moved = $this->fileMover->move($fullPathFrom, $fullPathTo);

            if (!$moved) {
                Yii::error($errorMessage, self::class);
            }
        } catch (\Throwable $e) {
            Yii::error(
                $errorMessage . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString(),
                self::class
            );
        }
    }
}

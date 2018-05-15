<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use FilesystemIterator;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SeederController extends Controller
{

    /**
     * @var \tebazil\yii2seeder\Seeder
     */
    private $seeder;
    private $generator;

    protected function prepare()
    {
        $this->seeder    = new \tebazil\yii2seeder\Seeder();
        $this->generator = $this->seeder->getGeneratorConfigurator();
    }

    /**
     * This command echoes what you have entered as the message.
     */
    public function actionSeed()
    {
        $this->prepare();

        $seedsDir = __DIR__ . '/../seeds/';

        $fs = new FilesystemIterator($seedsDir, FilesystemIterator::SKIP_DOTS|FilesystemIterator::CURRENT_AS_FILEINFO);

        foreach ($fs as $k => $fileinfo) {
            /** @var \SplFileInfo $fileinfo */
            if ($fileinfo->getExtension() !== 'php') {
                continue;
            }

            $this->stdout("Seeding {$fileinfo->getFilename()}\n", Console::BOLD);

            require_once $fileinfo->getPathname();
        }

        $this->seeder->refill();

        $this->stdout("Seeding database done\n", Console::BOLD);
    }
}

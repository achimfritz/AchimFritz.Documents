<?php
namespace AchimFritz\Documents\Surf\Task\ImageExport;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Surf".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Surf\Domain\Model\Node;
use TYPO3\Surf\Domain\Model\Application;
use TYPO3\Surf\Domain\Model\Deployment;

use TYPO3\Flow\Annotations as Flow;

/**
 * A task to create initial directories and the release directory for the current release
 *
 * This task will automatically create needed directories and create a symlink to the upcoming
 * release, called "next".
 */
class CopyAssetsTask extends Task
{

    /**
     * @var string
     */
    protected $bowerDir = 'Application/AchimFritz.Documents/Resources/Private/Resources/bower_components';


    /**
     * Executes this task
     *
     * @param \TYPO3\Surf\Domain\Model\Node $node
     * @param \TYPO3\Surf\Domain\Model\Application $application
     * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
     * @param array $options
     * @return void
     * @throws \TYPO3\Surf\Exception\TaskExecutionException
     */
    public function execute(Node $node, Application $application, Deployment $deployment, array $options = [])
    {
        $assetsDir = $this->getTargetDir($application). '/assets';
        if (@mkdir($assetsDir) === false) {
            throw new \TYPO3\Surf\Exception\TaskExecutionException('can not create dir: ' . $assetsDir);
        }
        foreach ($this->getAssets() as $asset) {
            $file = FLOW_PATH_PACKAGES . '/' . $this->bowerDir . '/' . $asset;
            if (@copy($file, $assetsDir . '/' . basename($file)) === false) {
                throw new \TYPO3\Surf\Exception\TaskExecutionException('can copy file: ' . $file);
            }
            $deployment->getLogger()->log('> copy ' . $file, LOG_DEBUG);
        }
        $fontsDir = $this->getTargetDir($application) . '/fonts';
        if (@mkdir($fontsDir) === false) {
            throw new \TYPO3\Surf\Exception\TaskExecutionException('can not create dir: ' . $fontsDir);
        }
        foreach ($this->fontsAssets as $asset) {
            $file = FLOW_PATH_PACKAGES . '/' . $this->bowerDir . '/' . $asset;
            if (@copy($file, $fontsDir . '/' . basename($file)) === false) {
                throw new \TYPO3\Surf\Exception\TaskExecutionException('can copy file: ' . $file);
            }
            $deployment->getLogger()->log('> copy ' . $file, LOG_DEBUG);
        }
    }

}

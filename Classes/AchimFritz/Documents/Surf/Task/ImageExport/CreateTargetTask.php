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
class CreateTargetTask extends Task
{

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
        $outDir = $this->getTargetDir($application);
        if (is_dir($outDir)) {
            throw new \TYPO3\Surf\Exception\TaskExecutionException('Target directory exists: ' . $outDir);
        }
        if (@mkdir($outDir) === false) {
            throw new \TYPO3\Surf\Exception\TaskExecutionException('cannot create Target directory: ' . $outDir);
        }
        $deployment->getLogger()->log('> created ' . $outDir, LOG_DEBUG);
        $imageDir = $this->getImageTargetDir($application);
        if (@mkdir($imageDir) === false) {
            throw new \TYPO3\Surf\Exception\TaskExecutionException('cannot create Target directory: ' . $imageDir);
        }
        $deployment->getLogger()->log('> created ' . $imageDir, LOG_DEBUG);
    }

}

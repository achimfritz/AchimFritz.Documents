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
class CopyFilesTask extends Task
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
        $documents = $this->getDocuments($application);
        $outDir = $this->getImageTargetDir($application);
        foreach ($documents as $document) {
            $imagePath = FLOW_PATH_WEB . $document->getWebPath();
            if (@copy($imagePath, $outDir . '/' . $document->getFileName()) === false) {
                throw new \TYPO3\Surf\Exception\TaskExecutionException('cannot copy ' . $imagePath);
            }
            $deployment->getLogger()->log('> copy ' . $imagePath, LOG_DEBUG);
        }
    }

}

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
class ThumbTask extends Task
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

        $commands = [];
        $dimensions = ['375x245', '800x523', '480x320'];
        $documents = $this->getDocuments($application);
        $outDir = $this->getImageTargetDir($application);

        foreach ($dimensions as $dimension) {
            $thumbPath = $this->getTargetDir($application) . '/' . $dimension;
            if (file_exists($thumbPath) === false) {
                $commands[] = 'mkdir ' . $thumbPath;
            }
        }
        $cnt = 0;
        foreach ($documents as $document) {
            $absolutePath = $document->getAbsolutePath();
            foreach ($dimensions AS $dimension) {
                $cnt++;
                $thumbPath = $this->getTargetDir($application) . '/' . $dimension . '/' . $document->getFileName();
                $commands[] = ' convert -thumbnail ' . $dimension . ' ' . $absolutePath . ' ' . $thumbPath;
                if ($cnt > 50) {
                    $this->shell->executeOrSimulate($commands, $node, $deployment);
                    $cnt = 0;
                    $commands = [];
                }
            }
        }

        $this->shell->executeOrSimulate($commands, $node, $deployment);
    }

}

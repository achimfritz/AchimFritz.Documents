<?php
namespace AchimFritz\Documents\Surf\Application\Image;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents\Surf".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Surf\Domain\Model\Workflow;
use TYPO3\Surf\Domain\Model\Deployment;

class ExportStaticApplication extends AbstractApplication
{

    /**
     * registerTasks
     *
     * @param \TYPO3\Surf\Domain\Model\Workflow $workflow
     * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
     * @return void
     */
    public function registerTasks(Workflow $workflow, Deployment $deployment)
    {
        $workflow->addTask('achimfritz.documents:imageExport:createTarget', 'run', $this);
        $workflow->addTask('achimfritz.documents:imageExport:copyFiles', 'run', $this);
        $workflow->addTask('achimfritz.documents:imageExport:copyAssets', 'run', $this);
        $workflow->addTask('achimfritz.documents:imageExport:thumb', 'run', $this);
        $workflow->addTask('achimfritz.documents:imageExport:zip', 'run', $this);
        $workflow->addTask('achimfritz.documents:imageExport:indexFile', 'run', $this);
    }

}

<?php
namespace AchimFritz\Documents\Surf\Application\Image;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents\Surf".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Surf\Domain\Model\Workflow;
use TYPO3\Surf\Domain\Model\Deployment;

class InitApplication  extends AbstractApplication {

	/**
	 * registerTasks
	 * 
	 * @param \TYPO3\Surf\Domain\Model\Workflow $workflow
	 * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
	 * @return void
	 */
	public function registerTasks(Workflow $workflow, Deployment $deployment) {
			#$workflow->addTask('achimfritz.surf:image:correctFsRights', 'correctFsRights', $this);
			#$workflow->addTask('achimfritz.documents:image:changeNames', 'run', $this);
			#$workflow->addTask('achimfritz.surf:image:saveTimeStamps', 'saveTimeStamps', $this);
	}


}

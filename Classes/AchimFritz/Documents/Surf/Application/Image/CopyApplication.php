<?php
namespace AchimFritz\Documents\Surf\Application\Image;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents\Surf".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Surf\Domain\Model\Workflow;
use TYPO3\Surf\Domain\Model\Deployment;

class CopyApplication  extends AbstractApplication {

	/**
	 * registerTasks
	 * 
	 * @param \TYPO3\Surf\Domain\Model\Workflow $workflow
	 * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
	 * @return void
	 */
	public function registerTasks(Workflow $workflow, Deployment $deployment) {
		$workflow->addTask('achimfritz.document.surf:image:mount', 'mount', $this);
		#$workflow->addTask('achimfritz.surf:imageone:copyToOrig', 'copyToOrig', $this);
		$workflow->addTask('achimfritz.document.surf:image:unmount', 'umount', $this);
		#$workflow->addTask('achimfritz.surf:imageone:copyToMain', 'copyToMain', $this);
		#$workflow->addTask('achimfritz.surf:imageone:correctFsRights', 'correctFsRights', $this);
		#$workflow->addTask('achimfritz.surf:imageone:changeNames', 'changeNames', $this);
		#$workflow->addTask('achimfritz.surf:imageone:saveTimeStamps', 'saveTimeStamps', $this);
	}


}

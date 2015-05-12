<?php
namespace AchimFritz\Documents\Surf;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents\Surf".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A Image one workflow
 *
 * @Flow\Scope("prototype")
 */
class Workflow extends \TYPO3\Surf\Domain\Model\SimpleWorkflow {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Surf\TaskManager
	 */
	protected $taskManager;
	
	/**
	 * Order of stages that will be executed
	 *
	 * @var array
	 */
	protected $stages = array(
		'run'
	);
	
	/**
	 * @return string
	 */
	public function getName() {
		return 'Documents Workflow';
	}


}
?>

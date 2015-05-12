<?php
namespace AchimFritz\Documents\Surf\Task\Image;

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
class ChangeNamesTask extends \TYPO3\Surf\Domain\Model\Task {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\RenameService
	 * @Flow\Inject
	 */
	protected $renameService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Surf\Domain\Service\ShellCommandService
	 */
	protected $shell;

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
	public function execute(Node $node, Application $application, Deployment $deployment, array $options = array()) {
		$target = $application->getMainPath() . '/' . $application->getTarget();

		try {
			$directoryIterator = new \DirectoryIterator($target);
		} catch (\Exception $e) {
			throw new \TYPO3\Surf\Exception\TaskExecutionException($target . ' : no directoryIterator on ' . $node->getName(), 1366541390);
		}
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->isDir() === FALSE) {
				try {
					$renamed = $this->renameService->rename($fileInfo->getRealPath());
					$deployment->getLogger()->log('> ' . $renamed, LOG_DEBUG);
				} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
					throw new \TYPO3\Surf\Exception\TaskExecutionException('cannot rename ' .$fileInfo->getRealPath() . ' on ' . $node->getName(), 1366541391);
				}
			}
		}
	}

	/**
	 * Simulate this task
	 *
	 * @param Node $node
	 * @param Application $application
	 * @param Deployment $deployment
	 * @param array $options
	 * @return void
	 */
	public function simulate(Node $node, Application $application, Deployment $deployment, array $options = array()) {
		$this->execute($node, $application, $deployment, $options);
	}

	/**
	 * Rollback this task
	 *
	 * @param \TYPO3\Surf\Domain\Model\Node $node
	 * @param \TYPO3\Surf\Domain\Model\Application $application
	 * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
	 * @param array $options
	 * @return void
	 * @todo Make the removal of a failed release configurable, sometimes it's necessary to inspect a failed release
	 */
	public function rollback(Node $node, Application $application, Deployment $deployment, array $options = array()) {
	}

}
?>

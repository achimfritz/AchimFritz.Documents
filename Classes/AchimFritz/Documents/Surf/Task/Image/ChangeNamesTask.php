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
class ChangeNamesTask extends Task {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\RenameService
	 * @Flow\Inject
	 */
	protected $renameService;

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
		$path = $this->configuration->getMountPoint() . '/' . $application->getTarget();

		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			throw new \TYPO3\Surf\Exception\TaskExecutionException($path . ' : no directoryIterator on ' . $node->getName(), 1366541390);
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

}
?>

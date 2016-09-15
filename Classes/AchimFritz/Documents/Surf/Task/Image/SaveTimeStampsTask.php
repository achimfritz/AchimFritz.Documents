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
class SaveTimeStampsTask extends Task {

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
		$mountPoint = $this->configuration->getMountPoint();
		$directory = $application->getTarget();
		$path = $mountPoint . '/' . $directory;
		$result = $this->shell->execute('test -d ' . $path, $node, $deployment, TRUE);
		if ($result === FALSE) {
			throw new \TYPO3\Surf\Exception\TaskExecutionException('Target directory "' . $path . '" not exist on node ' . $node->getName(), 1366541390);
		}

		$integrity = $this->integrityFactory->createIntegrity($directory);
		if ($integrity->getTimestampsAreInitialized() === TRUE) {
			throw new \TYPO3\Surf\Exception\TaskExecutionException('timestamps already initialized ' . $path, 1432474728);
		}
		$commands = array();
		$dataDirectory = $this->configuration->getDataDirectory();
		$commands[] = 'if [ ! -d ' . $dataDirectory . ' ]; then mkdir -p ' . $dataDirectory . '_done; fi';
		$fsDocs = $integrity->getFilesystemDocuments();
		$cnt = 0;
		foreach ($fsDocs AS $fsDoc) {
			$cnt++;
			$absolutePath = $path . '/' . $fsDoc;
			$commands[] = 'echo -n `stat -c %y ' . $absolutePath . '| sed \'s/\(.*\)\-\(.*\)\-\(.*\) \(.*\):\(.*\):\(..\).*/\1\2\3\4\5.\6/\'` >> ' . $this->configuration->getTimestampFile($directory);
			$commands[] = 'echo "|' . $absolutePath . '" >> ' . $this->configuration->getTimestampFile($directory);
			if ($cnt > 100) {
				$this->shell->executeOrSimulate($commands, $node, $deployment);
				$cnt = 0;
				$commands = array();
			}
		}
		$this->shell->executeOrSimulate($commands, $node, $deployment);
		
	}

}
?>

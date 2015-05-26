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
class ThumbTask extends Task {

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
		$fsDocs = $integrity->getFilesystemDocuments();
		$commands = array();
		$dimensions = array(array('800', '600'), array('1280', '1024'), array('320', '240'), array('64', '48'));
		foreach ($dimensions AS $dimension) {
			$thumbPath = $this->configuration->getThumbPath() . '/' . $dimension[0] . 'x' . $dimension[1] . '/' . $directory;
			if (file_exists($thumbPath) === FALSE) {
				$commands[] = 'mkdir -p ' . $thumbPath;
			}
		}
		$cnt = 0;
		foreach ($fsDocs AS $fsDoc) {
			$absolutePath = $path . '/' . $fsDoc;
			$imageSize = getimagesize($absolutePath);
			foreach ($dimensions AS $dimension) {
				$cnt ++;
				$thumbPath = $this->configuration->getThumbPath() . '/' . $dimension[0] . 'x' . $dimension[1] . '/' . $directory . '/' . $fsDoc;
				if ($imageSize[0] < $imageSize[1]) {
					$param = $dimension[0];
				} else {
					$param = 'x' . $dimension[1];
				}
				$commands[] = ' convert -geometry ' . $param . ' ' . $absolutePath . ' ' . $thumbPath;
				if ($cnt > 50) {
					$this->shell->executeOrSimulate($commands, $node, $deployment);
					$cnt = 0;
					$commands = array();
				}
			}
		}


		$this->shell->executeOrSimulate($commands, $node, $deployment);
		
	}

}

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
class RotateTask extends Task {

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
		if ($integrity->getReadyForRotation() === FALSE) {
			throw new \TYPO3\Surf\Exception\TaskExecutionException('not ready for rotation ' . $path, 1432474724);
		}
		$fsDocs = $integrity->getFilesystemDocuments();
		$commands = array();
		if ($integrity->getIsExif() === TRUE) {
			$commands[] = 'exiftran -ai ' . $path . '/*';
		} elseif ($integrity->getGeeqieMetadataExists() === TRUE) {
			foreach ($fsDocs AS $fsDoc) {
				$absolutePath = $path . '/' . $fsDoc;
				$geeqieMetadata = $this->configuration->getGeeqieMetadataPath() . $absolutePath . 'gq.xmp';
				if (file_exists($geeqieMetadata) === FALSE) {
					throw new \TYPO3\Surf\Exception\TaskExecutionException('no such file ' . $geeqieMetadata, 1432474725);
				}
				$imageSize = getimagesize($absolutePath);
				if ($imageSize[0] < $imageSize[1]) {
					$deployment->getLogger()->log('> already upright: ' . $absolutePath, LOG_DEBUG);
				} else {
					$script = array();
					$script[] = 'orientation=`grep "tiff:Orientation" ' . $geeqieMetadata . ' |awk -F "\"" {\'print $2\'}`';
					$script[] = 'if [ $orientation == 1 ]; then; jpegtran -rotate 180 ' . $absolutePath . ' > /tmp/' . $fsDoc . ' && mv /tmp/' . $fsDoc . ' ' . $absolutePath . '; fi';
					$script[] = 'if [ $orientation == 6 ]; then; jpegtran -rotate 90 ' . $absolutePath . ' > /tmp/' . $fsDoc . ' && mv /tmp/' . $fsDoc . ' ' . $absolutePath . '; fi';
					$script[] = 'if [ $orientation == 8 ]; then; jpegtran -rotate 270 ' . $absolutePath . ' > /tmp/' . $fsDoc . ' && mv /tmp/' . $fsDoc . ' ' . $absolutePath . '; fi';
					$commands[] = implode(' && ' , $script);
				}
			}
		} else {
			throw new \TYPO3\Surf\Exception\TaskExecutionException('not ready for rotation ' . $path, 1432474726);
		}

		$this->shell->executeOrSimulate($commands, $node, $deployment);
		
	}

}

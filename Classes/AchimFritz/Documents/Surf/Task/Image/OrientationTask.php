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
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * A task to create initial directories and the release directory for the current release
 *
 * This task will automatically create needed directories and create a symlink to the upcoming
 * release, called "next".
 */
class OrientationTask extends Task {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\ImageDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemPropertyFactory
	 * @Flow\Inject
	 */
	protected $fileSystemPropertyFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService
	 * @Flow\Inject
	 */
	protected $directoryService;

	/**   
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\ImageDocument\ExifService
	 * @Flow\Inject
	 */
	protected $exifService;

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
		$commands = array();
		$names = $this->directoryService->getSplFileInfosInDirectory($path, 'jpg');
		foreach ($names as $name) {
			$document = $this->documentFactory->create($directory . PathService::PATH_DELIMITER . $name, $mountPoint);
			$fileSystemProperty = $this->fileSystemPropertyFactory->create($document);
			$timestamp = $fileSystemProperty->getTimestamp();
			if ($timestamp === '') {
				throw new \TYPO3\Surf\Exception\TaskExecutionException('no timestamp" ' . $name . '" ' . $node->getName(), 1366541491);
			}
			$correctCommands = $this->exifService->getCorrectCommands($fileSystemProperty);
			foreach ($correctCommands as $correctCommand) {
				$commands[] = $correctCommand;
			}
		}
		$geeqieMetadata = $this->configuration->getGeeqieMetadataPath() . $path;
		$commands[] = 'if [ -d ' . $geeqieMetadata . ' ]; then mv ' . $geeqieMetadata . ' ' . $geeqieMetadata . '_done; fi';


		$this->shell->executeOrSimulate($commands, $node, $deployment);
		
	}

}

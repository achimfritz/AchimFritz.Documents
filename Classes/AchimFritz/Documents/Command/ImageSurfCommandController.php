<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Surf\Domain\Model\Deployment;

/**
 * Surf command controller
 */
class ImageSurfCommandController extends \TYPO3\Flow\Cli\CommandController {
	
	/**
	 * @var \TYPO3\Surf\Domain\Model\Deployment
	 */
	protected $deployment;

	/**
	 * initCommand($name, $verbose = FALSE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function initCommand($name, $verbose = FALSE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\InitApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application);
		$this->deploy($verbose);
		$target = $application->getMainPath() . '/' . $application->getTarget();
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * copyCommand($name, $verbose = FALSE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function copyCommand($name, $verbose = FALSE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\CopyApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application);
		$this->deploy($verbose);
		$target = $application->getMainPath() . '/' . $application->getTarget();
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * rotateCommand($name, $verbose = FALSE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function rotateCommand($name, $verbose = FALSE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\RotateApplication();
		// TODO
		$application->setIsExif(FALSE);
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application);
		$this->deploy($verbose);
		$target = $application->getMainPath() . '/' . $application->getTarget();
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * thumbCommand($name, $verbose = FALSE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function thumbCommand($name, $verbose = FALSE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\ThumbApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application);
		$this->deploy($verbose);
		$target = $application->getMainPath() . '/' . $application->getTarget();
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * @param \AchimFritz\Documents\Surf\Application\Image\AbstractApplication $application 
	 * @param boolean $verbose
	 * @return void
	 */
	protected function createDeployment(\AchimFritz\Documents\Surf\Application\Image\AbstractApplication $application, $verbose = FALSE) {
		$node = new \TYPO3\Surf\Domain\Model\Node('localhost');
		$node->setHostname('localhost');

		$application->setMountPoint('/mnt/sdd1');
		$application->setOrigPath('/bilder/orig');
		$application->setMainPath('/bilder/main');
		$application->setAdminPath('/bilder/admin');
		$application->addNode($node);
		$workflow = new \AchimFritz\Documents\Surf\Workflow();
		$deployment = new Deployment($application->getTarget());
		$deployment->setWorkflow($workflow);
		$deployment->addApplication($application);

		$logger = $this->createDefaultLogger($deployment->getName(), $verbose ? LOG_DEBUG : LOG_INFO);
		$deployment->setLogger($logger);

		return $deployment;
	}

	/**
	 * deploy
	 * 
	 * @param boolean $verbose
	 * @param boolean $diableAnsi
	 * @return void
	 */
	protected function deploy($verbose) {
		$this->deployment->initialize();
		$this->deployment->deploy();
	}


	/**
	 * Create a default logger with console and file backend
	 *
	 * @param string $deploymentName
	 * @param integer $severityThreshold
	 * @param boolean $disableAnsi
	 * @param boolean $addFileBackend
	 * @return \TYPO3\Flow\Log\Logger
	 */
	protected function createDefaultLogger($deploymentName, $severityThreshold, $disableAnsi = TRUE, $addFileBackend = TRUE) {
		$logger = new \TYPO3\Flow\Log\Logger();
		$console = new \TYPO3\Surf\Log\Backend\AnsiConsoleBackend(array(
					'severityThreshold' => $severityThreshold,
					'disableAnsi' => $disableAnsi
					));
		$logger->addBackend($console);
		if ($addFileBackend) {
			$file = new \TYPO3\Flow\Log\Backend\FileBackend(array(
						'logFileURL' => FLOW_PATH_DATA . 'Logs/Surf-' . $deploymentName . '.log',
						'createParentDirectories' => TRUE,
						'severityThreshold' => LOG_DEBUG,
						'logMessageOrigin' => FALSE
						));
			$logger->addBackend($file);
		}
		return $logger;
	}


	/**
	 * setResponse
	 * 
	 * @param \TYPO3\Flow\Mvc\ResponseInterface $response
	 * @return void
	 */
	public function setResponse(\TYPO3\Flow\Mvc\ResponseInterface $response) {
		$this->response = $response;
	}
}
?>

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
	 * copyCommand($name, $verbose = TRUE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function copyCommand($name, $verbose = TRUE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\CopyApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application, $verbose);
		$this->deploy($verbose);
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * initCommand($name, $verbose = TRUE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function initCommand($name, $verbose = TRUE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\InitApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application, $verbose);
		$this->deploy($verbose);
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * copyAndInitCommand($name, $verbose = TRUE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function copyAndInitCommand($name, $verbose = TRUE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\CopyAndInitApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application, $verbose);
		$this->deploy($verbose);
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * rotateCommand($name, $verbose = TRUE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function rotateCommand($name, $verbose = TRUE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\RotateApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application, $verbose);
		$this->deploy($verbose);
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * rotateAndThumbCommand($name, $verbose = TRUE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function rotateAndThumbCommand($name, $verbose = TRUE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\RotateAndThumbApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application, $verbose);
		$this->deploy($verbose);
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * thumbCommand($name, $verbose = TRUE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function thumbCommand($name, $verbose = TRUE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\ThumbApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application, $verbose);
		$this->deploy($verbose);
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * indexCommand($name, $verbose = TRUE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function indexCommand($name, $verbose = TRUE) {
		$application = new \AchimFritz\Documents\Surf\Application\Image\IndexApplication();
		$application->setTarget($name);
		$this->deployment = $this->createDeployment($application, $verbose);
		$this->deploy($verbose);
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}

	/**
	 * @param \AchimFritz\Documents\Surf\Application\Image\AbstractApplication $application 
	 * @param boolean $verbose
	 * @return void
	 */
	protected function createDeployment(\AchimFritz\Documents\Surf\Application\Image\AbstractApplication $application, $verbose = TRUE) {
		$node = new \TYPO3\Surf\Domain\Model\Node('localhost');
		$node->setHostname('localhost');

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

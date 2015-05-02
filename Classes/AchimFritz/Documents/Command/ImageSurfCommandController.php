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
class ImageSurfCommandController extends \TYPO3\Surf\Command\SurfCommandController {
	
	/**
	 * @var \TYPO3\Surf\Domain\Model\Deployment
	 */
	protected $deployment;

	/**
	 * copyCommand($name, $verbose = FALSE)
	 * 
	 * @param string $name
	 * @param boolean $verbose
	 */
	public function initCommand($name, $verbose = FALSE) {

		$application = new \AchimFritz\Documents\Surf\Application\Image\InitApplication();


		$node = new \TYPO3\Surf\Domain\Model\Node('localhost');
		$node->setHostname('localhost');

		$application->setMountPoint('/mnt/sdd1');
		$application->setOrigPath('/bilder/orig');
		$application->setMainPath('/bilder/main');
		$application->setAdminPath('/bilder/admin');
		$application->addNode($node);
		$workflow = new \AchimFritz\Documents\Surf\Workflow();
		$this->deployment = new Deployment('init');
		$this->deployment->setWorkflow($workflow);
		$this->deployment->addApplication($application);

		$application->setTarget($name);
		$this->deploy($verbose);
		$target = $application->getMainPath() . '/' . $application->getTarget();
		$this->outputLine('DONE done with exitCode ' . $this->deployment->getStatus());
		$this->response->setExitCode($this->deployment->getStatus());
	}



	/**
	 * deploy
	 * 
	 * @param boolean $verbose
	 * @param boolean $diableAnsi
	 * @return void
	 */
	protected function deploy($verbose, $disableAnsi = TRUE) {
		if ($this->deployment->getLogger() === NULL) {
			$logger = $this->createDefaultLogger($this->deployment->getName(), $verbose ? LOG_DEBUG : LOG_INFO, $disableAnsi);
			$this->deployment->setLogger($logger);
		}
		$this->deployment->initialize();
		$this->deployment->deploy();
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

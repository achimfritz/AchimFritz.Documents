<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Intranet".   *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Surf\Domain\Model\Deployment;
use TYPO3\Surf\Domain\Model\Node;

class IntranetController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Surf\Domain\Service\ShellCommandService
	 */
	protected $shell;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('hostname', $_SERVER['REMOTE_ADDR']);
	}

	/**
	 * mountAction 
	 * 
	 * @param string $hostname 
	 * @return void
	 */
	public function mountAction($hostname) {
		$mps = array(
			'/server/lucky',
			'/server/bilder/main',
			'/server/bilder/thumbs',
			'/server/mp3'
		);
		foreach ($mps AS $mp) {
			$result = $this->executeOnHost($hostname, 'sudo mount ' . $mp);
			if ($result === FALSE) {
				$this->addErrorMessage($hostname . ' mounting ' . $mp . ' failed (maybe alread mounted?)');
			} else {
				$this->addOkMessage($hostname . ' ' . $mp . ' mounted');
			}
		}
		$this->redirect('index');
	}

	/**
	 * shutdownAction 
	 * 
	 * @param string $hostname 
	 * @return void
	 */
	public function shutdownAction($hostname) {
		$result = $this->executeOnHost($hostname, 'shutdown -h 1');
		if ($result === FALSE) {
			$this->addErrorMessage($hostname . ' shutdown failed');
		} else {
			$this->addOkMessage($hostname . ' shutdown');
		}
		$this->redirect('index');
	}

	/**
	 * rebootAction 
	 * 
	 * @param string $hostname 
	 * @return void
	 */
	public function rebootAction($hostname) {
		$result = $this->executeOnHost($hostname, 'reboot');
		if ($result === FALSE) {
			$this->addErrorMessage($hostname . ' reboot failed');
		} else {
			$this->addOkMessage($hostname . ' reboot');
		}
		$this->redirect('index');
	}

	/**
	 * isOnlineAction 
	 * 
	 * @param string $hostname 
	 * @return void
	 */
	public function isOnlineAction($hostname) {
		$result = $this->executeOnHost($hostname, 'whoami');
		if ($result === FALSE) {
			$this->addErrorMessage($hostname . ' is offline');
		} else {
			$this->addOkMessage($hostname . ' is online');
		}
		$this->redirect('index');
	}

	/**
	 * executeOnHost 
	 * 
	 * @param string $hostname 
	 * @param string|array $command 
	 * @return string|boolean
	 */
	protected function executeOnHost($hostname, $command) {
		$node = $this->getNode($hostname);
		$deployment = $this->getDeployment();
		$result = $this->shell->execute($command, $node, $deployment, TRUE);
		return $result;
	}

	/**
	 * addWarningMessage
	 * 
	 * @param string $message
	 * @return void
	 */
	protected function addWarningMessage($message) {
		$this->addFlashMessage($message, '', \TYPO3\Flow\Error\Message::SEVERITY_WARNING);
	}


	/**
	 * addErrorMessage
	 * 
	 * @param string $message
	 * @return void
	 */
	protected function addErrorMessage($message) {
		$this->addFlashMessage($message, '', \TYPO3\Flow\Error\Message::SEVERITY_ERROR);
	}

	/**
	 * addOkMessage
	 * 
	 * @param string $message
	 * @return void
	 */
	protected function addOkMessage($message) {
		$this->addFlashMessage($message, '', \TYPO3\Flow\Error\Message::SEVERITY_OK);
	}

	/**
	 * getDeployment 
	 * 
	 * @return Deployment
	 */
	protected function getDeployment() {
		$deployment = new Deployment('intranet');
		$logger = new \TYPO3\Flow\Log\Logger();
		$file = new \TYPO3\Flow\Log\Backend\FileBackend(array(
					'logFileURL' => FLOW_PATH_DATA . 'Logs/Surf-Intranet.log',
					'createParentDirectories' => TRUE,
					'severityThreshold' => LOG_DEBUG,
					'logMessageOrigin' => FALSE
					));
		$logger->addBackend($file);
		$deployment->setLogger($logger);
		return $deployment;
	}

	/**
	 * getNode 
	 * 
	 * @param string $hostname 
	 * @return Node
	 */
	protected function getNode($hostname) {
		$node = new Node($hostname);
		$node->setHostname($hostname);
		$node->setOption('username', 'www-data');
		return $node;
	}

}

?>

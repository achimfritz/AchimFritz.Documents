<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Mp3Documents".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Mp3Mp3Mp3Document controller for the AchimFritz.Mp3Documents package 
 *
 * @Flow\Scope("singleton")
 */
abstract class CommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentsPersistenceManager;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * injectSettings 
	 * 
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * addErrorMessage 
	 * 
	 * @param string $msg 
	 * @return void
	 */
	protected function addErrorMessage($msg) {
		$this->outputLine('ERROR ' . $msg);
	}

	/**
	 * addOkMessage 
	 * 
	 * @param string $msg 
	 * @return void
	 */
	protected function addOkMessage($msg) {
		$this->outputLine('OK ' . $msg);
	}

	/**
	 * handeException 
	 * 
	 * @param \Exception $e 
	 * @return void
	 */
	protected function handleException(\Exception $e) {
		$this->outputLine('EXCEPTION ' . $e->getMessage() . ' ' . $e->getCode());
	}

}

?>

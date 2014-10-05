<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.ChampionShip".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Rest\Controller\RestController;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\Document;

/**
 * Action controller for the AchimFritz.ChampionShip package 
 *
 * @Flow\Scope("singleton")
 */
abstract class AbstractActionController extends RestController {

	/**
	 * addErrorMessage
	 * 
	 * @param string $message
	 */
	protected function addErrorMessage($message) {
		$this->addFlashMessage($message, '', \TYPO3\Flow\Error\Message::SEVERITY_ERROR);
	}
	
	/**
	 * addWarningMessage
	 * 
	 * @param string $message
	 */
	protected function addWarningMessage($message) {
		$this->addFlashMessage($message, '', \TYPO3\Flow\Error\Message::SEVERITY_WARNING);
	}
	/**
	 * addNoticeMessage
	 * 
	 * @param string $message
	 */
	protected function addNoticeMessage($message) {
		$this->addFlashMessage($message, '', \TYPO3\Flow\Error\Message::SEVERITY_NOTICE);
	}
	/**
	 * addOkMessage
	 * 
	 * @param string $message
	 */
	protected function addOkMessage($message) {
		$this->addFlashMessage($message, '', \TYPO3\Flow\Error\Message::SEVERITY_OK);
	}
	
	/**
	 * handleException
	 * 
	 * @param \Exception $e
	 */
	protected function handleException(\Exception $e) {
		$message = $e->getMessage();
		if ($this->request->getFormat() === 'json') {
			$title = str_replace('\\', '_', get_class($e));
			if ($e instanceof \AchimFritz\Documents\Exception === FALSE) {
				$message = 'transform to json...';
			}
		} else {
			$title =  get_class($e);
		}
		$this->addFlashMessage($message, $title, \TYPO3\Flow\Error\Message::SEVERITY_ERROR, array(), $e->getCode());
	}


}

?>

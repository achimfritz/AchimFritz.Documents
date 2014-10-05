<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the AchimFritz.Documents package 
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * Index action
	 *
	 * @param string $search
	 * @return void
	 */
	public function listAction($search = '*:*') {
		$this->view->assign('search', $search);
	}

}

?>

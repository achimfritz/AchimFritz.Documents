<?php
namespace AchimFritz\Documents\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document AS Document;


/**
 * 
 * Enter description here ...
 * @author af
 *
 */
class DocumentJsonViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;
	
	/**
	 * render
	 *
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return string the rendered string
	 */
	public function render(Document $document) {	
		$arr = array(
			'name' => $document->getName(),
			'identifier' => $this->persistenceManager->getIdentifierByObject($document),
		);
		return json_encode($arr);
	}
}

?>

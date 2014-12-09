<?php
namespace AchimFritz\Documents\Domain\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\RenameCategory;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class RenameCategoryValidator extends \TYPO3\Flow\Validation\Validator\GenericObjectValidator {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 * @Flow\Inject
	 */
	protected $pathService;

	/**
	 * @param mixed $value The value that should be validated
	 * @return void
	 */
	public function isValid($value) {
		$path = $value->getNewPath();
		$count = $this->pathService->getPathDepth($path);
		if ($count < 2) {
			$this->addError('need Depth >= 2.', 1418053369);
		}
	}

}
?>

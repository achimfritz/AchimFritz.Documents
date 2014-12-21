<?php
namespace AchimFritz\Documents\Domain\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Category;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentValidator extends \TYPO3\Flow\Validation\Validator\GenericObjectValidator {

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
		$name = $value->getName();
		$count = $this->pathService->getPathDepth($name);
		if ($count !== 4) {
			$this->addError('need Depth = 4.', 1419092873);
		}
	}

}
?>

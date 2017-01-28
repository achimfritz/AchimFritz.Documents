<?php
namespace AchimFritz\Documents\Domain\Validator\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */


use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class TvRecordingValidator extends \TYPO3\Flow\Validation\Validator\GenericObjectValidator {


	/**
	 * @param mixed $value The value that should be validated
	 * @return void
	 */
	public function isValid($value) {
		$this->validateTime($value->getStarttime());
		$this->validateTime($value->getEndtime());
		if ($value->getLength() <= 0) {
			$this->addError('length is <= 0 ' . $value->getLength() , 1485607789);
		}
	}

	/**
	 * @param $time
	 * @return void
	 */
	protected function validateTime($time) {
		$arr = explode(':', $time);
		if (count($arr) !== 2) {
			$this->addError('not in hh:mm format', 1441468579);
		} else {
			if (preg_match('/[0-9]{2}/',$arr[0]) === FALSE) {
				$this->addError('not in hh:mm format', 1441468578);
			}
			if (preg_match('/[0-9]{2}/',$arr[1]) === FALSE) {
				$this->addError('not in hh:mm format', 1441468577);
			}
		}
	}

}
?>

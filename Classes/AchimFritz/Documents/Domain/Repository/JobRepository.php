<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class JobRepository extends Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array(
		'createDate' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING
	);


}

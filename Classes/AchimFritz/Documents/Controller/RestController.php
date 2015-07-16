<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class RestController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array('json' => 'AchimFritz\\Documents\\Mvc\\View\\JsonView');

}

<?php
namespace AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("prototype")
 */
class Folder extends \AchimFritz\Documents\Domain\FileSystem\Facet\Download {

	const FILENAME = 'Folder.jpg';

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

	/**
	 * @return string
	 */
	public function getTarget() {
		return $this->configuration->getMountPoint() . PathService::PATH_DELIMITER . $this->getPath() . PathService::PATH_DELIMITER . self::FILENAME;
	}


}

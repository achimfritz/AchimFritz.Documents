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
class Cddb extends \AchimFritz\Documents\Domain\FileSystem\Facet\Download {

	const TITLE_FORMAT = 1;
	const ARTIST_TITLE_FORMAT = 2;
	const FILENAME = 'Cddb.txt';

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

	/**
	 * @var int
	 */
	protected $format = self::TITLE_FORMAT;

	/**
	 * @return int
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * @param int $format
	 */
	public function setFormat($format) {
		$this->format = $format;
	}

	/**
	 * @return string
	 */
	public function getTarget() {
		return $this->configuration->getMountPoint() . PathService::PATH_DELIMITER . $this->getPath() . PathService::PATH_DELIMITER . self::FILENAME;
	}


}

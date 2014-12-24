<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\Mp3Document AS Document;

/**
 * @Flow\Scope("singleton")
 */
class Mp3IndexService extends AbstractIndexService {

	/**
	 * @var string
	 */
	protected $extension = 'mp3';

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Factory\Mp3DocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @return string
	 */
	protected function getMountPoint() {
		return $this->settings['mp3Document']['mountPoint'];
	}

}

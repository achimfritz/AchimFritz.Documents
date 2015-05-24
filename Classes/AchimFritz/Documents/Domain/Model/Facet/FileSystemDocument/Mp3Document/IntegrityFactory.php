<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Flow\Scope("singleton")
 */
class IntegrityFactory extends \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\IntegrityFactory {

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $mp3DocumentConfiguration;

   /**
    * @Flow\Inject
    * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
    */
   protected $documentRepository;

	/**
	 * @return Integrity
	 * @throws Exception
	 */
	public function createIntegrity($directory) {
		throw new Exception('not implemented', 1432389131);
	}


	/**
	 * @return \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->mp3DocumentConfiguration;
	}
}

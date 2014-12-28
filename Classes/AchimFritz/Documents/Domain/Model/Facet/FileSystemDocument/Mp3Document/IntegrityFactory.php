<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Integrity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Flow\Scope("singleton")
 */
class IntegrityFactory {

	/**
	 * @var \AchimFritz\Documents\Solr\Helper
	 * @Flow\Inject
	 */
	protected $solrHelper;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
	}


	/**
	 * @return ArrayCollection<Integrity>
	 * @throws Exception
	 */
	public function createIntegrities() {
			throw new Exception('not implemented', 1418749455); 
	}
}

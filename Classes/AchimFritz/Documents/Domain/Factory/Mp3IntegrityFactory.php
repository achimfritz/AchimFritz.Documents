<?php
namespace AchimFritz\Documents\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Integrity;
use AchimFritz\Documents\Domain\FileSystemInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Flow\Scope("singleton")
 */
class Mp3IntegrityFactory {

	/**
	 * @var \AchimFritz\Documents\Solr\FacetFactory
	 * @Flow\Inject
	 */
	protected $facetFactory;

	/**
	 * @return ArrayCollection<Integrity>
	 * @throws Exception
	 */
	public function createIntegrities() {
			throw new Exception('not implemented', 1418749455); 
		$integrities = new ArrayCollection();
		$path = FileSystemInterface::IMAGE_MOUNT_POINT;
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			throw new Exception('cannot create DirectoryIterator with path ' . $path, 1418658022);
		}
		try {
			$facets = $this->facetFactory->find('mainDirectoryName');
		} catch (\SolrException $e) {
			throw new Exception('cannot fetch from solr', 1418658023);
		}
		$cnt = 0;
		foreach ($directoryIterator AS $outerFileInfo) {
			if ($outerFileInfo->isDir() === TRUE) { 
				$innerIterator = new \DirectoryIterator($outerFileInfo->getRealpath());
				$cnt = 0;
				foreach ($innerIterator AS $fileInfo) {
					if ($fileInfo->getExtension() === 'jpg') {
						$cnt++;
					}
				}
				$name = $outerFileInfo->getBasename();
				$solrCnt = 0;
				if (isset($facets[$name]) === TRUE) {
					$solrCnt = $facets[$name];
					unset($facets[$name]);
				}
				$integrity = new Integrity($name, $cnt, $solrCnt);
				$integrities->add($integrity);
			}
		}
		return $integrities;
	}
}

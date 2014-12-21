<?php
namespace AchimFritz\Documents\Domain\Model\Facet\ImageDocument;

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
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @var \AchimFritz\Documents\Solr\FacetFactory
	 * @Flow\Inject
	 */
	protected $facetFactory;

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
		$integrities = new ArrayCollection();
		$path = $this->settings['imageDocument']['mountPoint'];
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
			if ($outerFileInfo->isDir() === TRUE && $outerFileInfo->isDot() === FALSE) { 
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
		$integrities->add($this->createByField('locations'));
		$integrities->add($this->createByField('categories'));
		$integrities->add($this->createByField('mDateTime'));
		return $integrities;
	}

	/**
	 * @param string $name 
	 * @return Integrity
	 */
	protected function createByField($field) {
		try {
			$query = new \SolrQuery();
			$query->setQuery('*:*')->setRows(0)->setStart(0)->addFilterQuery('extension:jpg');
			$queryResponse = $this->solrClientWrapper->query($query);
			$cntAll = $queryResponse->getResponse()->response->numFound;
			$query->setQuery($field . ':*')->setRows(0)->setStart(0)->addFilterQuery('extension:jpg');
			$queryResponse = $this->solrClientWrapper->query($query);
			$cntField = $queryResponse->getResponse()->response->numFound;
		} catch (\SolrException $e) {
			throw new Exception('cannot fetch from solr', 1419095649);
		}
		$integrity = new Integrity($field, $cntAll, $cntField);
		return $integrity;
	}
}

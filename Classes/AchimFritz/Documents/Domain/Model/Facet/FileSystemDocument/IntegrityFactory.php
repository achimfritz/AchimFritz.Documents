<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
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
	 * @var \AchimFritz\Documents\Solr\Helper
	 * @Flow\Inject
	 */
	protected $solrHelper;

	/**
	 * @var \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

   /**
    * @Flow\Inject
    * @var \AchimFritz\Documents\Domain\Repository\FileSystemDocumentRepository
    */
   protected $documentRepository;

   /**
    * @Flow\Inject
    * @var \AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService
    */
	protected $directoryService;

	/**
	 * @return Integrity
	 * @throws Exception
	 */
	public function createIntegrity($directory) {
		throw new Exception('not implemented');
	}

	/**
	 * @param string $path 
	 * @return array<\SplFileInfo>
	 * @throws \AchimFritz\Documents\Domain\Service\FileSystem\Exception
	 */
	protected function getDocumentDirectories($path) {
		$directories = $this->directoryService->getDirectoriesInDirectory($path);
		return $directories;
	}

	/**
	 * @param \SplFileInfo $fileInfo 
	 * @return string
	 */
	protected function getIntegrityName(\SplFileInfo $fileInfo) {
		return $fileInfo->getBaseName();
	}

	/**
	 * @return ArrayCollection<Integrity>
	 * @throws Exception
	 */
	public function createIntegrities() {
		$integrities = new ArrayCollection();
		try {
			$facets = $this->solrHelper->findFacets('mainDirectoryName');
		} catch (\SolrException $e) {
			throw new Exception('cannot fetch from solr', 1418658023);
		}

		$path = $this->getConfiguration()->getMountPoint();
		$fileExtension = $this->getConfiguration()->getFileExtension();
		try {
			$outerFileInfos = $this->getDocumentDirectories($path);
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			throw new Exception('cannot get files in ' . $path, 1419867692);
		}
		foreach ($outerFileInfos AS $outerFileInfo) {
			$cnt = $this->directoryService->getCountOfFilesByExtension($outerFileInfo->getRealpath(), $fileExtension);
			$name = $this->getIntegrityName($outerFileInfo);
			$solrCnt = 0;
			if (isset($facets[$name]) === TRUE) {
				$solrCnt = $facets[$name];
				unset($facets[$name]);
			}
			$integrity = new Integrity($name, $cnt, $solrCnt);
			$integrities->add($integrity);
		}
		return $integrities;
	}

	/**
	 * @return \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->configuration;
	}


}

<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Integrity;
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
    * @Flow\Inject
    * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
    */
   protected $documentRepository;

   /**
    * @Flow\Inject
    * @var \AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService
    */
	protected $directoryService;

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
	 * @return Integrity
	 * @throws Exception
	 */
	public function createIntegrity($directory) {

      $documents = $this->documentRepository->findByHead($directory);
		try {
			$solrDocs = $this->solrHelper->findDocumentsByFq('mainDirectoryName:' . $directory);
		} catch (\SolrException $e) {
			throw new Exception('cannot fetch from solr', 1418658029);
		}

      $path = $this->settings['imageDocument']['mountPoint'] . PathService::PATH_DELIMITER . $directory;
		try {
			$fsDocs = $this->directoryService->getFileNamesInDirectory($path, 'jpg');
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			throw new Exception('cannot get files in ' . $path, 1419867691);
		}

		$path = FLOW_PATH_WEB . PathService::PATH_DELIMITER . $this->settings['imageDocument']['webPath'] . PathService::PATH_DELIMITER . $directory;
		try {
			$thumbs = $this->directoryService->getFileNamesInDirectory($path, 'jpg');
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			$thumbs = array();
		}

		sort($thumbs);
      sort($fsDocs);
      sort($solrDocs);

		$integrity = new Integrity($directory, count($fsDocs), count($solrDocs));
		$integrity->setPersistedDocuments($documents);
		$integrity->setSolrDocuments($solrDocs);
		$integrity->setFilesystemDocuments($fsDocs);
		$integrity->setThumbs($thumbs);
		return $integrity;
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

		$path = $this->settings['imageDocument']['mountPoint'];
		try {
			$outerFileInfos = $this->directoryService->getDirectoriesInDirectory($path);
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			throw new Exception('cannot get files in ' . $path, 1419867692);
		}
		foreach ($outerFileInfos AS $outerFileInfo) {
			$cnt = $this->directoryService->getCountOfFilesByExtension($outerFileInfo->getRealpath(), 'jpg');
			$name = $outerFileInfo->getBasename();
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

}

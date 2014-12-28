<?php
namespace AchimFritz\Documents\Domain\Model\Facet\ImageDocument;

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
      $solrDocs = $this->solrHelper->findDocumentsByFq('mainDirectoryName:' . $directory);

      $path = $this->settings['imageDocument']['mountPoint'] . PathService::PATH_DELIMITER . $directory;
		$fsDocs = $this->directoryService->getFileNamesInDirectory($path, 'jpg');

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

		// TODO ...
		$path = $this->settings['imageDocument']['mountPoint'];
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			throw new Exception('cannot create DirectoryIterator with path ' . $path, 1418658022);
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
		return $integrities;
	}

}

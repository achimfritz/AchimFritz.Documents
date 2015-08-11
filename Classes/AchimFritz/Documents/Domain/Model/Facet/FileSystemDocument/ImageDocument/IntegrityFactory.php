<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument;

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
class IntegrityFactory extends \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\IntegrityFactory {

	/**
	 * @var \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $imageDocumentConfiguration;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @return Integrity
	 * @throws Exception
	 */
	public function createIntegrity($directory) {

		$documents = $this->documentRepository->findByHead($directory);

		// solr
		try {
			$solrDocs = $this->solrHelper->findDocumentsByFq('mainDirectoryName:' . $directory);
		} catch (\SolrException $e) {
			throw new Exception('cannot fetch from solr', 1418658029);
		}

		// fs
		$mountPoint = $this->getConfiguration()->getMountPoint();
		$webPath = $this->getConfiguration()->getWebPath();
		$fileExtension = $this->getConfiguration()->getFileExtension();
		$path = $mountPoint . PathService::PATH_DELIMITER . $directory;
		try {
			$fsDocs = $this->directoryService->getFileNamesInDirectory($path, $fileExtension);
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			throw new Exception('cannot get files in ' . $path, 1419867691);
		}

		// thumbs
		$path = FLOW_PATH_WEB . PathService::PATH_DELIMITER . $webPath . PathService::PATH_DELIMITER . $directory;
		try {
			$thumbs = $this->directoryService->getFileNamesInDirectory($path, $fileExtension);
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

		// timestamp
		$integrity->setTimestampsAreInitialized(file_exists($this->getConfiguration()->getTimestampFile($directory)));

		// rotated
		$path = $mountPoint . PathService::PATH_DELIMITER . $directory;
		foreach ($fsDocs AS $fsDoc) {
			$absolutePath = $path . PathService::PATH_DELIMITER . $fsDoc;
			$imageSize = @getimagesize($absolutePath);
			if (is_array($imageSize) === TRUE && $imageSize[0] < $imageSize[1]) {
				$integrity->setImageIsRotated(TRUE);
				break;
			}
		}

		// geeqie metadata
		if (file_exists($this->getConfiguration()->getGeeqieMetadataPath() . PathService::PATH_DELIMITER . $directory) === TRUE) {
			$integrity->setGeeqieMetadataExists(TRUE);
		}
		// exif
		$path = $mountPoint . PathService::PATH_DELIMITER . $directory;
		$firstDoc = $path . PathService::PATH_DELIMITER . $fsDocs[0];
		try {
			$this->linuxCommand->getExifData($firstDoc);
			$integrity->setIsExif(TRUE);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
		}

		return $integrity;
	}

	/**
	 * @return \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->imageDocumentConfiguration;
	}
}

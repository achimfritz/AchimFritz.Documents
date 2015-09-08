<?php
namespace AchimFritz\Documents\Domain\FileSystem\Factory\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocument as Document;
use AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\FileSystemProperty;

/**
 * @Flow\Scope("singleton")
 */
class FileSystemPropertyFactory {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**   
	 * @var \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

	/**
	 * @param ImageDocument $document 
	 * @return FileSystemProperty
	 */
	public function create(Document $document) {
		$fileSystemProperty = new \AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\FileSystemProperty();
		$fileSystemProperty->setAbsolutePath($document->getAbsolutePath());
		$fileSystemProperty = $this->setExifData($document, $fileSystemProperty);
		$fileSystemProperty = $this->setGeeqieMetaData($document, $fileSystemProperty);
		$fileSystemProperty = $this->setDimensions($document, $fileSystemProperty);
		$fileSystemProperty = $this->setTimestamp($document, $fileSystemProperty);
		return $fileSystemProperty;
	}

	/**
	 * @param ImageDocument $document 
	 * @param FileSystemProperty
	 * @return FileSystemProperty
	 * @throws \AchimFritz\Documents\Linux\Exception
	 */
	protected function setExifData(Document $document, FileSystemProperty $fileSystemProperty) {
		$file = $document->getAbsolutePath();
		try {
			$data = $this->linuxCommand->getExifData($file);
			$fileSystemProperty->setExifData($data);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
		}
		return $fileSystemProperty;
	}

	/**
	 * @param ImageDocument $document 
	 * @param FileSystemProperty
	 * @return FileSystemProperty
	 */
	protected function setGeeqieMetaData(Document $document, FileSystemProperty $fileSystemProperty) {
		$absolutePath = $document->getAbsolutePath();
		$geeqieMetadata = $this->configuration->getGeeqieMetadataPath() . $absolutePath . '.gq.xmp';
		try {
			$orientation = $this->linuxCommand->getImageOrientationFromGeeqieMetaDataFile($geeqieMetadata);
			$fileSystemProperty->setGeeqieOrientation($orientation);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
		}
		return $fileSystemProperty;
	}

	/**
	 * @param ImageDocument $document 
	 * @param FileSystemProperty
	 * @return FileSystemProperty
	 */
	protected function setTimestamp(Document $document, FileSystemProperty $fileSystemProperty) {
		if (file_exists($document->getAbsolutePath()) === TRUE) {
			$name = $document->getAbsolutePath();
			$directory = $document->getDirectoryName();
			$timestampFile = $this->configuration->getTimestampFile($directory);
			try {
				$timestamp = $this->linuxCommand->getImageTimestampFromTimestampFile($name, $timestampFile);
				$fileSystemProperty->setTimestamp($timestamp);
			} catch (\AchimFritz\Documents\Linux\Exception $e) {
			}
		}
		return $fileSystemProperty;
	}

	/**
	 * @param ImageDocument $document 
	 * @param FileSystemProperty
	 * @return FileSystemProperty
	 */
	protected function setDimensions(Document $document, FileSystemProperty $fileSystemProperty) {
		if (file_exists($document->getAbsolutePath()) === TRUE) {
			$imageSize = getimagesize($document->getAbsolutePath());
			$fileSystemProperty->setWidth($imageSize[0]);
			$fileSystemProperty->setHeight($imageSize[1]);
		}
		return $fileSystemProperty;
	}
}

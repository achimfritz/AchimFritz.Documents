<?php
namespace AchimFritz\Documents\Configuration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentConfiguration extends FileSystemDocumentConfiguration {

	/**
	 * @var string
	 */
	protected $documentName = 'imageDocument';

	/**
	 * @var string
	 */
	protected $fileExtension = 'jpg';

	/**
	 * @return string
	 */
	public function getWebBigPath() {
		return $this->settings[$this->documentName]['webBigPath'];
	}

	/**
	 * @return string
	 */
	public function getWebPreviewPath() {
		return $this->settings[$this->documentName]['webPreviewPath'];
	}

	/**
	 * @return string
	 */
	public function getThumbPath() {
		return $this->settings[$this->documentName]['thumbPath'];
	}

	/**
	 * @return string
	 */
	public function getWebThumbPath() {
		return $this->settings[$this->documentName]['webThumbPath'];
	}

	/**
	 * @return string
	 */
	public function getBackupPath() {
		return $this->settings[$this->documentName]['backupPath'];
	}

	/**
	 * @return string
	 */
	public function getGeeqieMetadataPath() {
		return $this->settings[$this->documentName]['geeqieMetadataPath'];
	}

	/**
	 * @return string
	 */
	public function getDataDirectory() {
		return FLOW_PATH_DATA . '/AchimFritz.Documents/Image/tstamp/init/';
	}

	public function getTimestampFile($directory) {
		return FLOW_PATH_DATA . '/AchimFritz.Documents/Image/tstamp/init/' . $directory . '.txt';
	}

	/**
	 * @return string
	 */
	public function getUsbMountPoint() {
		return $this->settings[$this->documentName]['usbMountPoint'];
	}

}

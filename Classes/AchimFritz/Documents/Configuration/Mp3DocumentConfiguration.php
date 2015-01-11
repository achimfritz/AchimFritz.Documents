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
class Mp3DocumentConfiguration extends FileSystemDocumentConfiguration {

	/**
	 * @var string
	 */
	protected $documentName = 'mp3Document';

}

<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\FileSystem\AbstractExportService;

/**
 * @Flow\Scope("singleton")
 */
class ExportService extends AbstractExportService {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\FileSystemFactory
	 * @Flow\Inject
	 */
	protected $fileSystemFactory;

}

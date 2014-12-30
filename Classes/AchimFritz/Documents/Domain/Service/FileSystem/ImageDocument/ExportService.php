<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\ImageDocument;

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
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory
	 * @Flow\Inject
	 */
	protected $fileSystemFactory;

}

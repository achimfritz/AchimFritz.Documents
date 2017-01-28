<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\MovieDocument as Document;

/**
 * @Flow\Scope("singleton")
 */
class MovieDocumentCommandController extends FileSystemDocumentCommandController {


	/**
	 * @var \AchimFritz\Documents\Domain\Repository\MovieDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;
		
	/**
	 * @var \AchimFritz\Documents\Domain\Factory\MovieDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Factory\MovieDocument\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\MovieIndexService
	 * @Flow\Inject
	 */
	protected $indexService;

	/**
	 * show --name=2006_10_23_roland_scan_roland_gisela/fritzam_digcam_hochzeit_britta_dsci0048.jpg
	 *
	 * @param string $name
	 * @return void
	 */
	public function showCommand($name) {
		$document = $this->documentRepository->findOneByName($name);
		if ($document instanceof Document) {
			$this->outputLine($document->getName() . ' - ' . $document->getFileHash());
			$json = $document->getFfmpeg();
			var_dump($json);
			$this->outputLine('has ' . count($json['streams']) . ' streams');
			foreach($json['streams'] as $stream) {
				$this->outputLine('codec: ' . $stream['codec_type'] . ' ' . $stream['codec_name']);
			}
		} else {
			$this->outputLine('WARNING: no document found ' . $name);
		}
	}

}

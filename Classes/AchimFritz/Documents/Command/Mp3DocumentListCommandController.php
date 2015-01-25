<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentListCommandController extends DocumentListCommandController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\Mp3DocumentListService
	 */
	protected $documentListService;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Model\Mp3DocumentListFactory
	 */
	protected $documentListFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Id3TagFactory
	 * @Flow\Inject
	 */
	protected $id3TagFactory;

	/**
	 * showCommand($path = 'af/list/test')
	 *
	 * @param string $path
	 * @return void
	 */
	public function showCommand($path = 'af/list/test') {
		$documentList = $this->documentListRepository->findOneByCategoryPath($path);
		if ($documentList instanceof DocumentList === FALSE) {
			$this->outputLine('WARNING: documentList not found with category.path ' . $path);
			$this->quit();
		}
		$cnt = 1;
		foreach ($documentList->getDocumentListItems() AS $item) {
			$pre = sprintf('%02s', $cnt);
			$id3Tag = $this->id3TagFactory->create($item->getDocument());
			$this->outputLine($pre. ' ' . $id3Tag->getArtist() . ' - ' . $id3Tag->getTitle());
			$cnt++;
		}
	}
}

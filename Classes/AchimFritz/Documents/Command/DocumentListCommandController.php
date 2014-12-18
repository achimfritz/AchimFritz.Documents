<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\Cagegory;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\FileSystemInterface;

/**
 * @Flow\Scope("singleton")
 */
class DocumentListCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Facet\Id3TagFactory
	 * @Flow\Inject
	 */
	protected $id3TagFactory;

	/**
	 * @param string $path
	 * @return void
	 */
	public function indexCommand($path = 'af/list/test') {
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

	/**
	 * @param string $path
	 * @return void
	 */
	public function exportCommand($path = 'af/list/test') {
		$documentList = $this->documentListRepository->findOneByCategoryPath($path);
		if ($documentList instanceof DocumentList === FALSE) {
			$this->outputLine('WARNING: documentList not found with category.path ' . $path);
			$this->quit();
		}
		$directory = implode('_', explode(PathService::PATH_DELIMITER, $path));
		$directory = FileSystemInterface::IMAGE_EXPORT . PathService::PATH_DELIMITER . $directory;
		if (file_exists($directory)) {
			$this->outputLine('ERROR: directory ' . $directory . ' exists');
			$this->quit();
		}
		if (@mkdir($directory) === FALSE) {
			$this->outputLine('ERROR: cannot create directory ' . $directory);
			$this->quit();
		}
		$content = '';
		$cnt = 1;
		foreach ($documentList->getDocumentListItems() AS $item) {
			$splFileInfo = $item->getDocument()->getSplFileInfo();
			$ext = $splFileInfo->getExtension();
			$pre = sprintf('%02s', $cnt);
			$content .= $pre . ' ' . $splFileInfo->getBasename('.' . $ext);
			$content .= "\n";
			if (@copy($splFileInfo->getRealPath(), $directory . PathService::PATH_DELIMITER . $pre . '-' . $splFileInfo->getBasename()) === FALSE) {
				$this->outputLine('WARNING: cannot copy file ' . $splFileInfo->getRealPath());
			}
			$cnt++;

		}
		if (@file_put_contents($directory . PathService::PATH_DELIMITER . 'index.txt', $content) === FALSE) {
			$this->outputLine('WARNING: cannot write index.txt');
		}
		$this->outputLine($content);
	}

}

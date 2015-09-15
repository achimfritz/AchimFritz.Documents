<?php
namespace AchimFritz\Documents\Domain\FileSystem\Service\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Folder;

/**
 * @Flow\Scope("singleton")
 */
class FolderService {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\DownloadService
	 * @Flow\Inject
	 */
	protected $downloadService;

	/**
	 * @param Folder $folder
	 * @return void
	 * @throws \AchimFritz\Documents\Domain\FileSystem\Service\Exception
	 */
	public function update(Folder $folder) {
		$this->downloadService->fetchUrlContent($folder);
		$documents = $this->documentRepository->findByHead($folder->getPath());
		// update solr via FLOW Persistence
		foreach ($documents as $document) {
			$this->documentRepository->update($document);
		}
	}
}

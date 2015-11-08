<?php
namespace AchimFritz\Documents\Domain\FileSystem\Service\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Facet\RenameCategory;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class RenameId3TagsService {

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\Mp3Document\Id3TagWriterService
	 * @Flow\Inject
	 */
	protected $id3TagWriterService;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Solr\Helper
	 * @Flow\Inject
	 */
	protected $solrHelper;

	/**
	 * @param $path
	 * @return string $renamed
	 * @throws \AchimFritz\Documents\Exception
	 */
	public function rename(RenameCategory $renameCategory) {
		$fq = $this->buildFilterQuery($renameCategory->getOldPath());
		try {
			$docs = $this->solrHelper->findDocumentsByFq($fq);
		} catch (\SolrException $e) {
			throw new Exception('got solr exception for fq ' . $fq, 1446833628);
		}
		list($tagName, $tagValue) = explode('/', $renameCategory->getNewPath());
		$documents = $this->documentRepository->findByNames($docs);
		foreach ($documents as $document) {
			$this->id3TagWriterService->tagDocument($document, $tagName, $tagValue);
			$this->documentRepository->update($document);
		}

	}

	/**
	 * @param string $path
	 * @return string
	 */
	protected function buildFilterQuery($path) {
		$arr = explode('/', $path);
		$facet = array_shift($arr);
		$fq = $facet . ':"' . implode('/', $arr) . '"';
		return $fq;
	}


}

<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Document;

class ImageIntegrityController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'directory';

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\ImageDocument\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @return void
	 */
	public function listAction() {
		try {
			$integrities = $this->integrityFactory->createIntegrities();
		} catch (\AchimFritz\Documents\Domain\Model\Facet\ImageDocument\Exception $e) {
			$this->addFlashMessage('Cannot create integrities ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
		}
		$this->view->assign('integrities', $integrities);
	}

	/**
	 * @param string $directory
	 * @return void
	 */
	public function showAction($directory) {
		$documents = $this->documentRepository->findByHead($directory);
		$solrDocs = array();
		$fsDocs = array();
		$query = new \SolrQuery();
		$query->setQuery('*:*')->setRows(1000)->setStart(0)->addFilterQuery('mainDirectoryName:' . $directory);
		$queryResponse = $this->solrClientWrapper->query($query);
		if ($queryResponse->getResponse()->response->docs) {
			foreach ($queryResponse->getResponse()->response->docs AS $doc) {
				$solrDocs[] = $doc->fileName;
			}
		}
		$path = $this->settings['imageDocument']['mountPoint'] . '/' . $directory;
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			throw new Exception('cannot create DirectoryIterator with path ' . $path, 1418658022);
		}
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->getExtension() === 'jpg') {
				$fsDocs[] = $fileInfo->getBasename();
			}
		}
		sort($fsDocs);
		sort($solrDocs);
		$this->view->assign('documents', $documents);
		$this->view->assign('fsDocs', $fsDocs);
		$this->view->assign('solrDocs', $solrDocs);
		$this->view->assign('directory', $directory);
	}

}

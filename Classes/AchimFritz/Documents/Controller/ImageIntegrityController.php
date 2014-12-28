<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;

class ImageIntegrityController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'directory';

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\ImageIndexService
	 * @Flow\Inject
	 */
	protected $indexService;

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array('json' => 'AchimFritz\\Documents\\Mvc\\View\\JsonView');

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
		try {
			$integrity = $this->integrityFactory->createIntegrity($directory);
		} catch (\AchimFritz\Documents\Domain\Model\Facet\ImageDocument\Exception $e) {
			$this->addFlashMessage('Cannot create integrities ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
		}
		$this->view->assign('integrity', $integrity);
	}

	/**
	 * @param string $directory
	 * @return void
	 */
	public function updateAction($directory) {
		try {
			$cnt = $this->indexService->indexDirectory($directory);
			$this->addFlashMessage($cnt . ' documents indexed', '',  Message::SEVERITY_OK);
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->addFlashMessage('Cannot index directory with ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
		}
		$this->redirect('index', NULL, NULL, array('directory' => $directory));
	}
}

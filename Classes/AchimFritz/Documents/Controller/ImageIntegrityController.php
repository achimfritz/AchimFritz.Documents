<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;

class ImageIntegrityController extends RestController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'directory';

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Factory\ImageDocument\IntegrityFactory
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
	 * @return void
	 */
	public function listAction() {
		try {
			$integrities = $this->integrityFactory->createIntegrities();
			$this->view->assign('integrities', $integrities);
		} catch (\AchimFritz\Documents\Domain\FileSystem\Facet\Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * @param string $directory
	 * @return void
	 */
	public function showAction($directory) {
		try {
			$integrity = $this->integrityFactory->createIntegrity($directory);
		} catch (\AchimFritz\Documents\Domain\FileSystem\Factory\ImageDocument\Exception $e) {
			$this->handleException($e);
		}
		$this->view->assign('integrity', $integrity);
	}

}

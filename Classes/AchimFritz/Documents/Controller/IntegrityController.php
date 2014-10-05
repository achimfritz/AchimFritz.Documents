<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;

class IntegrityController extends AbstractActionController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentsPersistenceManager;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'document';

	/**
	 * @var \AchimFritz\Documents\Domain\Solr\Repository
	 * @Flow\Inject
	 */
	protected $solrRepository;

	/**
	 * @return void
	 */
	public function listAction() {
		$countDb = $this->documentRepository->countAll();
		$countSolr = $this->solrRepository->countAll();
		$this->view->assign('countDb', $countDb);
		$this->view->assign('countSolr', $countSolr);
	}

}

?>

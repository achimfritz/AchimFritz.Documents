<?php
namespace AchimFritz\Documents\Persistence;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\DBAL\DBALException;

/**
 * DocumentsPersistenceManager
 *
 * @Flow\Scope("singleton")
 * @api
 */
class DocumentsPersistenceManager {

	/**
	 * @var \TYPO3\Flow\Persistence\Doctrine\PersistenceManager
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @return void
	 * @throws Exception
	 */
	public function persistAll() {
		try {
			$this->persistenceManager->persistAll();
			try {
				$this->solrClientWrapper->commit();
			} catch (\SolrException $e) {
				throw new Exception('cannot commit solr with ' . $e->getMessage() . ' - ' . $e->getCode(), 1417273992);
			}
		} catch (DBALException $e) {
			try {
				$this->solrClientWrapper->rollback();
			} catch (\SolrException $e) {
				throw new Exception('cannot rollback solr with ' . $e->getMessage() . ' - ' . $e->getCode(), 1417273993);
			}
			throw new Exception('cannot perstistAll with ' . $e->getMessage() . ' - ' . $e->getCode(), 1417273994);
		}
	}


}

?>

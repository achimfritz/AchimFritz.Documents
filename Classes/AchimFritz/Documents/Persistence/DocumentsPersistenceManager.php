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
	 * @var \AchimFritz\Documents\Domain\Solr\Client
	 * @Flow\Inject
	 */
	protected $solrClient;

	/**
	 * persistAll 
	 * 
	 * @return void
	 */
	public function persistAll() {
		try {
			$this->persistenceManager->persistAll();
			$this->solrClient->commit();
		} catch (\Exception $e) {
			$this->solrClient->rollback();
			throw $e;
		}
	}


}

?>

<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class FileSystemDocumentRepository extends DocumentRepository {

	/**
	 * @Flow\Inject
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	protected $entityManager;

	/**
	 * @param integer $cnt
	 * @param integer $maxResults
	 * @return array<\AchimFritz\Documents\Domain\Model\FileSystemDocument>
	 */
	public function findNotUniq($cnt, $maxResults) {
		$documents = array();
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$queryBuilder->select('f AS document', 'count(f.fileHash) AS cnt')
			->from('\AchimFritz\Documents\Domain\Model\FileSystemDocument', 'f')
			->groupBy('f.fileHash')
			->having('cnt >= ' . $cnt)
			->setMaxResults($maxResults);
		$results = $queryBuilder->getQuery()->getResult();
		foreach ($results AS $result) {
			$document = $result['document'];
			$all = $this->findByFileHash($document->getFileHash());
			foreach ($all AS $one) {
				$documents[] = $one;
			}
		}
		return $documents;
	}

}

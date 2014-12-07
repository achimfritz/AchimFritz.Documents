<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class ImportCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 * @Flow\Inject
	 */
	protected $categoryRepository;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;


	/**
	 * @access public
	 * @return void
	 */
	public function runCommand() {
		$allCat = $this->categoryRepository->findAll();
		$cats = array();
		foreach ($allCat as $cat) {
			if (isset($cats[$cat->getPath()]) === FALSE) {
				$cats[$cat->getPath()] = $cat;
			}
		}
		$allDoc = $this->documentRepository->findAll();
		$docs = array();
		foreach ($allDoc AS $doc) {
			$docs[$doc->getName()] = $doc;
		}
		$content = file_get_contents('/tmp/t1.csv');
		$lines = explode("\n", $content);
		$add = 0;
		$update = 0;
		foreach ($lines AS $line) {
			if (trim($line) !== '') {
				$arr = explode('|', $line);
				if (count($arr) !== 3) {
					$this->outputLine('WARNING: line ' . $line);
					continue;
				}
				$name = str_replace('/bilder/main/', '', $arr[0]);
				$mtime = new \DateTime();
				$mtime->setTimestamp($arr[1]);
				$path = $arr[2];
				if (isset($cats[$path]) === TRUE) {
					$category = $cats[$path];
				} else {
					$category = new Category();
					$category->setPath($path);
					$cats[$path] = $category;
				}
				if (isset($docs[$name]) === TRUE) {
					$document = $docs[$name];
				} else {
					$document = new ImageDocument();
					$document->setName($name);
					$document->setMDateTime($mtime);
					$this->documentRepository->add($document);
					$docs[$name] = $document;
					$add++;
				}
				if ($document->hasCategory($category) === FALSE) {
					$document->addCategory($category);
					$this->documentRepository->update($document);
					$update++;
				}
			}
		}
		$this->outputLine('SUCCES: add: ' . $add . ' Documents');
		$this->outputLine('SUCCES: update: ' . $update . ' Documents');
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCES: persisted');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: cannot persist with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}
}

?>

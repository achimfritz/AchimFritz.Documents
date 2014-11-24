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
		foreach ($lines AS $line) {
			if (trim($line) !== '') {
				$arr = explode('|', $line);
				if (count($arr) !== 3) {
					$this->outputLine('ERROR: line ' . $line);
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
					$document->setMountPoint('/bilder/main');
					$document->setName($name);
					$document->setMDateTime($mtime);
					$this->documentRepository->add($document);
				}
				if ($document->hasCategory($category) === FALSE) {
					$document->addCategory($category);
					$this->documentRepository->update($document);
				}
			}
		}
	}
}

?>

<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;

/**
 * @Flow\Scope("singleton")
 */
class ExportCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 * @Flow\Inject
	 */
	protected $categoryRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\DocumentCollectionFactory
	 * @Flow\Inject
	 */
	protected $documentCollectionFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Image\ExportService
	 * @Flow\Inject
	 */
	protected $exportService;

	/**
	 * @param string $name
	 * @param string $paths
	 * @return void
	 */
	public function runCommand($name, $paths) {
		$name = trim($name);
		$arr = explode(',', $paths);
		$categories = new \Doctrine\Common\Collections\ArrayCollection();
		foreach ($arr as $path) {
			$category = $this->categoryRepository->findOneByPath($path);
			if ($category instanceof Category === FALSE) {
				$this->outputLine('WARNING: category not found ' . $path);
			}
			$categories->add($category);
		}
		$documentCollection = $this->documentCollectionFactory->createInCategories($name, $categories);
		$this->exportService->run('/bilder/export/' . $name, $documentCollection->getDocuments());
		$this->outputLine('SUCCESS: ' . count($documentCollection->getDocuments()) . ' documents');
	}
}

?>

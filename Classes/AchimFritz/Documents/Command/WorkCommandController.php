<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Category;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class WorkCommandController extends \TYPO3\Flow\Cli\CommandController
{
    /**
     * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
     * @Flow\Inject
     */
    protected $categoryRepository;

    /**
     * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
     * @Flow\Inject
     */
    protected $imageDocumentRepository;

    /**
     * @var \AchimFritz\Documents\Domain\Service\PathService
     * @Flow\Inject
     */
    protected $pathService;

    /**
     * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
     * @Flow\Inject
     */
    protected $documentPersistenceManager;

    /**
     * @return void
     */
    public function mottoToTagsCommand()
    {
        $categories = $this->categoryRepository->findByPathHead('motto');
        foreach ($categories as $category) {
            $documents = $this->imageDocumentRepository->findByCategory($category);
            $this->outputLine($category->getPath() . ' ' . count($documents));
            $tagPath = $this->pathService->replacePath($category->getPath(), 'motto', 'tags');
            $this->outputLine($tagPath);
            $tagCategory = $this->categoryRepository->findOneByPath($tagPath);
            if ($tagCategory === null) {
                $tagCategory = new Category();
                $tagCategory->setPath($tagPath);
                $this->categoryRepository->add($tagCategory);
            }
            foreach ($documents as $document) {
                if ($document->hasCategory($tagCategory) === false) {
                    $document->addCategory($tagCategory);
                }
                $document->removeCategory($category);
                $this->imageDocumentRepository->update($document);
            }
            $this->categoryRepository->remove($category);
        }
        $this->documentPersistenceManager->persistAll();
    }
}

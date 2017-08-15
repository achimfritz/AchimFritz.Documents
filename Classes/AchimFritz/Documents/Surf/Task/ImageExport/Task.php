<?php
namespace AchimFritz\Documents\Surf\Task\ImageExport;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Surf".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Surf\Domain\Model\Node;
use TYPO3\Surf\Domain\Model\Application;
use TYPO3\Surf\Domain\Model\Deployment;

use TYPO3\Flow\Annotations as Flow;

/**
 * A task to create initial directories and the release directory for the current release
 *
 * This task will automatically create needed directories and create a symlink to the upcoming
 * release, called "next".
 */
abstract class Task extends \TYPO3\Surf\Domain\Model\Task
{

    /**
     * @var array
     */
    protected $jsAssets = [
        'jquery/dist/jquery.min.js',
        'lightgallery/lib/picturefill.min.js',
        'lightgallery/dist/js/lightgallery.min.js',
        'lightgallery/modules/lg-thumbnail.min.js',
        'lightgallery/modules/lg-fullscreen.js',
        'lightgallery/lib/jquery.mousewheel.min.js'
    ];

    /**
     * @var array
     */
    protected $cssAssets = [
        'lightgallery/dist/css/lightgallery.min.css'
    ];

    /**
     * @var array
     */
    protected $fontsAssets = [
        'lightgallery/src/fonts/lg.woff',
        'lightgallery/src/fonts/lg.ttf'
    ];

    /**
     * @Flow\Inject
     * @var \TYPO3\Surf\Domain\Service\ShellCommandService
     */
    protected $shell;

    /**
     * @var \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
     * @Flow\Inject
     */
    protected $configuration;

    /**
     * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
     * @Flow\Inject
     */
    protected $imageDocumentRepository;

    /**
     * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
     * @Flow\Inject
     */
    protected $categoryRepository;

    protected function getAssets()
    {
        return array_merge($this->jsAssets, $this->cssAssets);
    }

    /**
     * @param Application $application
     * @return string
     */
    protected function getTargetDir(Application $application)
    {
        $path = $application->getTarget();
        $outDir = '/tmp/' . str_replace('/', '_', $path);
        return $outDir;
    }

    /**
     * @param Application $application
     * @return string
     */
    protected function getImageTargetDir(Application $application) {
        return $this->getTargetDir($application) . '/images';
    }

    /**
     * @param Application $application
     * @return \TYPO3\Flow\Persistence\QueryResultInterface
     * @throws \TYPO3\Surf\Exception\TaskExecutionException
     */
    protected function getDocuments(Application $application)
    {
        $path = $application->getTarget();
        $category = $this->categoryRepository->findOneByPath($path);
        if ($category === null) {
            throw new \TYPO3\Surf\Exception\TaskExecutionException('no such category: ' . $path);
        }
        $documents = $this->imageDocumentRepository->findByCategory($category);
        if (count($documents) === 0) {
            throw new \TYPO3\Surf\Exception\TaskExecutionException('no documents found: ' . $path);
        }
        return $documents;
    }

    /**
     * Simulate this task
     *
     * @param Node $node
     * @param Application $application
     * @param Deployment $deployment
     * @param array $options
     * @return void
     */
    public function simulate(Node $node, Application $application, Deployment $deployment, array $options = [])
    {
        $this->execute($node, $application, $deployment, $options);
    }

    /**
     * Rollback this task
     *
     * @param \TYPO3\Surf\Domain\Model\Node $node
     * @param \TYPO3\Surf\Domain\Model\Application $application
     * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
     * @param array $options
     * @return void
     * @todo Make the removal of a failed release configurable, sometimes it's necessary to inspect a failed release
     */
    public function rollback(Node $node, Application $application, Deployment $deployment, array $options = [])
    {
    }

}

?>

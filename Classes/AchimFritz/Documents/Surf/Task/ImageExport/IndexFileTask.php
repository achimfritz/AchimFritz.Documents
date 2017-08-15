<?php
namespace AchimFritz\Documents\Surf\Task\ImageExport;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Surf".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Fluid\View\StandaloneView;
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
class IndexFileTask extends Task
{

    /**
     * Executes this task
     *
     * @param \TYPO3\Surf\Domain\Model\Node $node
     * @param \TYPO3\Surf\Domain\Model\Application $application
     * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
     * @param array $options
     * @return void
     * @throws \TYPO3\Surf\Exception\TaskExecutionException
     */
    public function execute(Node $node, Application $application, Deployment $deployment, array $options = [])
    {
        $view = new StandaloneView();
        $view->setTemplatePathAndFilename(FLOW_PATH_PACKAGES . 'Application/AchimFritz.Documents/Resources/Private/Templates/ImageStaticExport.html');
        $jsAssets = [];
        foreach ($this->jsAssets as $asset) {
            $jsAssets[] = 'assets/' . baseName($asset);
        }
        $cssAssets = [];
        foreach ($this->cssAssets as $asset) {
            $cssAssets[] = 'assets/' . baseName($asset);
        }
        $documents = $this->getDocuments($application);
        $view->assign('documents', $documents);
        $view->assign('title', $application->getTarget());
        $view->assign('cssAssets', $cssAssets);
        $view->assign('jsAssets', $jsAssets);
        $content = $view->render();
        file_put_contents($this->getTargetDir($application) . '/index.html', $content);
    }

}

<?php
namespace AchimFritz\Documents\Mvc\View;

use TYPO3\Flow\Annotations as Flow;

/**
 * FileTransferView
 */
class FileTransferView extends \TYPO3\Flow\Mvc\View\AbstractView {

	/**
	 * @return void
	 */
	public function render() {
		if (isset($this->variables['fileName']) === FALSE) {
			throw new Exception('fileName is not assigned', 1421780902);
		}
		$splFileInfo = new \SplFileInfo($this->variables['fileName']);
		if ($splFileInfo->isFile() === FALSE) {
			throw new Exception('no such file ' . $splFileInfo->getRealPath(), 1421780903);
		}
		$response = $this->controllerContext->getResponse();
		$response->setHeader('Content-Description', ' File Transfer');
		$response->setHeader('Content-type', 'application/octet-stream');
		$response->setHeader('Content-Disposition', 'attachment; filename="' . $splFileInfo->getRealPath() . '"');
		$response->setHeader('Content-Transfer-Encoding', 'binary');
		$content = file_get_contents($splFileInfo->getRealPath());
		$response->setContent($content);
	}


}

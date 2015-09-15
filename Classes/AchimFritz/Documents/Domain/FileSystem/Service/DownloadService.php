<?php
namespace AchimFritz\Documents\Domain\FileSystem\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\FileSystem\Facet\Download;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class DownloadService {

	/**
	 * @param Download $download
	 * @return void
	 * @throws Exception
	 */
	public function fetchUrlContent(Download $download) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $download->getUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$content = curl_exec($ch);
		if(curl_errno($ch)) {
			$errorCode = curl_errno($ch);
			curl_close($ch);
			throw new Exception('curl not success for url ' . $download->getUrl() . ' with error code ' . $errorCode, 1437307735);

		}
		curl_close($ch);
		if (@file_put_contents($download->getTarget(), $content) === FALSE) {
			throw new Exception('cannot write file . ' . $download->getTarget(), 1437307737);
		}
	}

	/**
	 * @param Download $download
	 * @return void
	 * @throws Exception
	 */
	public function convertToUtf8(Download $download) {
		$content = $this->getFileContent($download);
		$content = mb_convert_encoding($content, 'UTF-8', "ISO-8859-1");
		if (@file_put_contents($download->getTarget(), $content) === FALSE) {
			throw new Exception('cannot write file . ' . $download->getTarget(), 1437307737);
		}
	}

	/**
	 * @param Download $download
	 * @return string
	 * @throws Exception
	 */
	public function getFileContent(Download $download) {
		$content = @file_get_contents($download->getTarget());
		if ($content === FALSE) {
			throw new Exception('cannot read content of ' . $download->getTarget(), 1442327449);
		}
		return $content;
	}


}

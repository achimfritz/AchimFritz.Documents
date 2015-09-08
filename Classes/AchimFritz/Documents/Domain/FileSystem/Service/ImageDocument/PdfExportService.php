<?php
namespace AchimFritz\Documents\Domain\FileSystem\Service\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\PdfExport;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
class PdfExportService {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @param PdfExport $documentExport
	 * @throws Exception
	 * @return string
	 */
	public function createPdf(PdfExport $documentExport) {
		$cmd = $this->getCommand($documentExport);
		try {
			$this->linuxCommand->executeCommand($cmd);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			throw new Exception('cannot execute command ' . $cmd, 1420993448);
		}
		return $this->getOutName();
	}

	/**
	 * @param Document $document 
	 * @return string
	 */
	protected function createFromPath($document) {
		$from = $document->getAbsoluteWebThumbPath();
		return $from;
	}

	/**
	 * @return string
	 */
	protected function getOutName() {
		return PathService::TEMP_FOLDER . PathService::PATH_DELIMITER . 'out.pdf';
	}

	/**
	 * @param PdfExport $pdfExport
	 * @return string
	 */
	protected function getCommand(PdfExport $pdfExport) {
		$cmd = 'montage';
		foreach ($pdfExport->getDocuments() as $document) {
			$from = $this->createFromPath($document);
			$cmd .= ' ' . $from;
		}
		$cmd .= ' -tile ' . $pdfExport->getColumns() . 'x' . $pdfExport->getRows();
		$cmd .= ' -geometry ' . $pdfExport->getWidth() . 'x' . $pdfExport->getHeight();
		$cmd .= '+' . $pdfExport->getBorder() . '+' . $pdfExport->getBorder();
		$cmd .= ' -density ' . $pdfExport->getDpi() . 'x' . $pdfExport->getDpi();
		$cmd .= ' ' . $this->getOutName();
		return $cmd;
/*
A4 - 72 PPI	595 Pixels	842 Pixels 
A4 - 300 PPI		2480 Pixels		3508 Pixels 

-tile 3x5 -geometry 160x120+10+10 -density 72x72
 -> 540x700
 540 = 160*3 + 10*2*3 = (160 + 10*2) * 3
 700 = 120*5 + 10*2*5 = (160 * (3/4) + 10*2) * 5


landscape images
$rows = floor($columns * (4/3) * sqrt(2));
$width + 2 * $border = $paperWidth / $columns;
$width + 2 * ($width / 20) = $paperWidth / $columns;
$width + ($width / 10)  = $paperWidth / $columns;
(11/10) * $width  = $paperWidth / $columns;
$widthWithBorder = (10/11) * ($paperWidth / $columns);
$width = int(floor((10/11) * $widthWithBoarder);
$border = int(floor(1/2) * (1/11) * $widthWithBoarder);
$test = $columns * ($width + 2 * $border);
*/
	}

}

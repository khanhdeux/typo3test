## How to use?

It is very easy to integrate PHPExcel to your projects. The extension provides a service
for TYPO3, which helps you to instanciate the PHPExcel library.

Example:
```
    /** @var \Arrabiata\PdfGenerator\Service\PdfGenerator $pdfGeneratorService */
	$pdfGeneratorService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstanceService('pdfgenerator');
	$pdfGenerator = $pdfGeneratorService->getPdfGenerator();

	// Your pdf magic goes here...

	/** @var \PHPExcel_Writer_Excel2007 $excelWriter */
	$excelWriter = $phpExcelService->getInstanceOf('PHPExcel_Writer_Excel2007', $pdfGenerator);
	$excelWriter->save('...');
```
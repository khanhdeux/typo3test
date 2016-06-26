<?php
/** PdfGenerator root directory */
if (!defined('PDFGENERATOR_ROOT')) {
    define('PDFGENERATOR_ROOT', dirname(__FILE__) . '/');
    require(PDFGENERATOR_ROOT . 'MakePdf/Autoloader.php');
}

if (!defined('MAKEPDF_FONTPATH')) {
    define('MAKEPDF_FONTPATH', PDFGENERATOR_ROOT . '/MakePdf/XPDF/font_1.6/');
}

/**
 * Class AbstractMakePdf
 */
class MakePdf
{
    public $pdf;   //PDF Object
    public $pdfContent; //übergebener Content
    public $xpdfMode = 'I';  //Output mode
    public $pdfType = 0;  // PDF-Typ
    public $docFontSize;
    public $background;
    public $border;
    public $spaceBefore;
    public $spaceAfter;
    public $marginTop = 65; //75;	//Seitenrand oben
    public $marginLeft = 10; //Seitenrand links
    public $marginRight = 10; //Seitenrand rechts
    public $lineWidth = 1;
    public $lineHeight = 6;
    public $tags = [];

    function __construct()
    {
        $this->pdf = new \MakePdf\XPDF\XPDF();
        $this->pdf->SetDisplayMode('fullpage');
        $this->pdf->SetFont('arial', '', $this->docFontSize);

        $this->pdf->SetMargins($this->marginLeft, $this->marginTop, $this->marginRight);
        $this->pdf->addPage();
        \MakePdf\MakePdfLib::pdfLoadFonts($this->pdf);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetXY(0, 0);
    }

    /**
     * Generate PDF
     */
    public function generate()
    {
        //WriteTag settings
        $this->docFontSize = 12;
        $this->background = 0;
        $this->border = 0;
        $this->spaceBefore = 6;
        $this->spaceAfter = 100;

        foreach ($this->tags as $tag) {
            \MakePdf\MakePdfLib::write($this->pdf, $tag);
        }

        $this->pdf->Output('PDF_' . time() . '.pdf', $this->xpdfMode);
        $this->pdf = null;
        unset($this->pdf);
        exit();
    }

    /**
     * Add tag to list
     *
     * @param $tag
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Get String with by font font familiy
     *
     * @param $str
     * @param $fontFamily
     * @param $size
     */
    public function getStringWidthByFont($str, $fontFamily, $size)
    {
        $this->pdf->SetFont($fontFamily, '', $size);
        return $this->pdf->GetStringWidth($str);
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
}


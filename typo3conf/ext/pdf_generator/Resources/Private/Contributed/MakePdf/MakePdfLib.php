<?php
namespace MakePdf;

class MakePdfLib
{
    /**
     * Write Tag
     *
     * @param $pdf
     * @param $tag
     */
    public static function write($pdf, $tag)
    {
        /** @var \MakePdf\Tag $tag */
        if ($tag->getType() == 'text') {
            $tagContent = '';
            /* @var \MakePdf\Tag $tag */
            foreach ($tag->getTagElements() as $tagElement) {
                $tagContent .= '<' . $tagElement->getName() . '>' . $tagElement->getContent() . '</' . $tagElement->getName() . '> ';
                $pdf->SetStyle($tagElement->getName(), $tagElement->getFont(), $tagElement->getStyle(), $tagElement->getSize(), $tagElement->getColor(), $tagElement->getIndent());
            }

            $pdf->SetXY($tag->getPositionX(), $tag->getPositionY());

            $extra = $tag->getExtra();
            $tagContent = ($tag->getExtra()) ? ('<' . $extra . '>' . $tagContent . '</' . $extra . '>') : $tagContent;
            $pdf->WriteTag($tag->getWidth(), $tag->getHeight(), $tagContent, $tag->getBorder(), "L", $tag->getBackground(), 1);
            $pdf->Ln($tag->getLineHeight());
        } else if ($tag->getType() == 'image') {
            foreach ($tag->getTagElements() as $element) {
                if ($element instanceof \MakePdf\ImageElement) {
                    self::placeImage($pdf, $element);
                }
            }
        }
    }

    /**
     * @param $pdf
     * @param \MakePdf\ImageElement $tagElement
     */
    public static function placeImage($pdf, $tagElement)
    {
        $pdf->Image(PATH_site . $tagElement->getSource(), $tagElement->getX(), $tagElement->getY(), $tagElement->getSize());
    }

//    /**
//     * Write tag to pdf
//     *
//     * @param $pdf
//     * @param $x
//     * @param $y
//     * @param $tag
//     * @param string $extra
//     */
//    public function placeText($pdf, $tagElement)
//    {
//        $tagContent = '';
//        /* @var \MakePdf\Tag $tag */
//        foreach ($tag->getTagElements() as $tagElement) {
//            $tagContent .= '<' . $tagElement->getName() . '>' . $tagElement->getContent() . '</' . $tagElement->getName() . '> ';
//            $pdf->SetStyle($tagElement->getName(), $tagElement->getFont(), $tagElement->getStyle(), $tagElement->getSize(), $tagElement->getColor(), $tagElement->getIndent());
//        }
//
//        $tagContent = ($extra) ? ('<' . $extra . '>' . $tagContent . '</' . $extra . '>') : $tagContent;
//        $pdf->WriteTag($x, $y, $tagContent, $tag->getBorder(), "L", $tag->getBackground(), 1);
//        $pdf->Ln($tag->getLineHeight());
//    }

    /**
     * Write tag to pdf
     *
     * @param $pdf
     * @param $x
     * @param $y
     * @param $tag
     * @param string $extra
     */
    public function writeTagToPDF($pdf, $x, $y, $tag, $extra = '')
    {
        $tagContent = '';
        /* @var \MakePdf\Tag $tag */
        foreach ($tag->getTagElements() as $tagElement) {
            $tagContent .= '<' . $tagElement->getName() . '>' . $tagElement->getContent() . '</' . $tagElement->getName() . '> ';
            $pdf->SetStyle($tagElement->getName(), $tagElement->getFont(), $tagElement->getStyle(), $tagElement->getSize(), $tagElement->getColor(), $tagElement->getIndent());
        }

        $tagContent = ($extra) ? ('<' . $extra . '>' . $tagContent . '</' . $extra . '>') : $tagContent;
        $pdf->WriteTag($x, $y, $tagContent, $tag->getBorder(), "L", $tag->getBackground(), 1);
        $pdf->Ln($tag->getLineHeight());
    }

    /**
     * Place image to pdf
     *
     * @param $pdf
     * @param $x
     * @param $y
     * @param $size
     * @param $source
     */
    public function placeImageToPDF($pdf, $x, $y, $size, $source)
    {
        $pdf->Image(PATH_site . $source, $x, $y, $size);
    }

    /**
     * Euro convert
     *
     * @param $string
     * @return string
     */
    public function euroHack($string)
    {
        return utf8_encode(str_replace(utf8_encode("?"), chr(164), utf8_decode($string)));
    }

    /**
     * Load PDF font
     */
    public function pdfLoadFonts($pdf)
    {
        //ben�tigte Fonts hinzuf�gen
        $pdf->AddFont('ftisymfon');
        $pdf->AddFont('thesans');
        $pdf->AddFont('thesansb');
        $pdf->AddFont('thesanseb');
    }
}
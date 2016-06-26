<?php

/**
 * Class MakePdf
 */
class MakePdfMove extends AbstractMakePdf {

    /**
     * Override layout
     */
    public function setLayout() {
        $this->pdf->SetAutoPageBreak(true, 87);
        \MakePdf\MakePdfLib::placeImageToPDF($this->pdf, 185, 192, 15, '/typo3conf/ext/fti_easyflyer/Resources/Public/Images/Icons/fti_logo_2cm_sw.png');

        $color = "0,0,0";
        $this->pdf->SetXY(10, 101);
        $pricePrefixTag = new \MakePdf\Tag();
        $pricePrefixTag->addTagElement(new \MakePdf\TagElement('preisprefix', 'ab', "thesansb", "N", 36, $color));
        $pricePrefixTag->addTagElement(new \MakePdf\TagElement('euro', \MakePdf\MakePdfLib::euroHack(chr(164)), "thesanseb", "N", 36, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 14, $pricePrefixTag, 'p');
        $this->pdf->SetFont('thesansb', '', 32);
        $width = $this->pdf->GetStringWidth('ab' . ' ') + 7;
        $this->pdf->SetXY(10 + $width, 88);

        $priceTag = new \MakePdf\Tag();
        $priceTag->addTagElement(new \MakePdf\TagElement('preis', '539', "thesanseb", "N", 160, $color));
        $priceTag->setLineHeight(12);
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 14, $priceTag, 'p');

        $this->pdf->Ln(5);
        $titleTag = new \MakePdf\Tag();
        $titleTag->addTagElement(new \MakePdf\TagElement('title', 'DUBAI', "thesanseb", "N", 46, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 14, $titleTag, 'p');

        $hotelTag = new \MakePdf\Tag();
        $hotelTagElement = new \MakePdf\TagElement('hotel', 'Burj Al Arab', "thesansb", "N", 18, $color);
        $categoryTagElement = new \MakePdf\TagElement('category', '*****', "ftisymfon", "N", 10, $color);

        $hotelTag->addTagElement($hotelTagElement);
        $hotelTag->addTagElement($categoryTagElement);

        $this->pdf->Ln(0);
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, $this->lineHeight + 4, $hotelTag, 'p');
        $this->pdf->Ln(0);

        $codeTag = new \MakePdf\Tag();
        $codeTag->addTagElement(new \MakePdf\TagElement('code','DXB306 PX F', "thesansb", "N", 12, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, $this->lineHeight + 4, $codeTag);

        $arr = Array
        (
            "1 Nacht in der Deluxe Suite inkl. Frühstück",
            "Anreise z . B . am 15.07.2016",
            "Preis pro Person ab"
        );

        $txt = implode(", ", $arr);

        $infoTag = new \MakePdf\Tag();
        $infoTag->addTagElement(new \MakePdf\TagElement('p', $txt, "thesans", "N", 14, $color, 0));
        $this->pdf->SetMargins(10, $this->marginTop, 10);
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, $this->lineHeight, $infoTag);
        $this->pdf->Ln(5);

        $this->pdf->SetXY(10, 196);

        $footerTag = new \MakePdf\Tag();
        $footerTagElement = new \MakePdf\TagElement('fuss', 'Zwischenverkauf und Druckfehler vorbehalten! Es gelten die Reise- und Zahlungsbedingungen des jeweiligen FTI Kataloges. FTI Touristik GmbH, Landsberger Str. 88, 80339 MÃ¼nchen', "thesansb", "N", 9, $color, 0);
        $footerTag->addTagElement($footerTagElement);

        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 145, 4, $footerTag);
    }
}
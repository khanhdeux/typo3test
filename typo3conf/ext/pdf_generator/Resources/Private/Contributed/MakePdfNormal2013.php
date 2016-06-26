<?php

class MakePdfNormal2013 extends AbstractMakePdf {

    /**
     * Override layout
     */
    public function setLayout() {
        $this->pdf->SetXY(10, 85);

        \MakePdf\MakePdfLib::placeImageToPDF($this->pdf, 0, 0, 210, 'fileadmin/users/Produktvertrieb/Header_15/Orient_So15_Head.jpg');
        \MakePdf\MakePdfLib::placeImageToPDF($this->pdf, 0, 237, 210, 'fileadmin/users/Produktvertrieb/Bildleisten/Dubai.jpg');

        $color = "80,80,80";

        $headerTag = new \MakePdf\Tag();
        $headerTag->addTagElement(new \MakePdf\TagElement('preisknaller', 'WELTNEUHEIT THE TERRACE', "thesanseb", "N", 34, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 12, $headerTag, 'p');

        $titleTag = new \MakePdf\Tag();
        $titleTag->addTagElement(new \MakePdf\TagElement('title', 'DUBAI', "thesanseb", "N", 34, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 12, $titleTag, 'p');

        $placeTag = new \MakePdf\Tag();
        $placeTag->addTagElement(new \MakePdf\TagElement('ort', 'Vereinigte Arabische Emirate', "thesansb", "N", 20, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 12, $placeTag, 'p');

        $hotelTag = new \MakePdf\Tag();
        $hotelTagElement = new \MakePdf\TagElement('hotel', 'Burj Al Arab', "thesansb", "N", 18, $color);
        $categoryTagElement = new \MakePdf\TagElement('category', '*****', "ftisymfon", "N", 10, $color);

        $hotelTag->addTagElement($hotelTagElement);
        $hotelTag->addTagElement($categoryTagElement);

        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 12, $hotelTag, 'p');

        $freeText1Tag = new \MakePdf\Tag();
        $freeText1Tag->addTagElement(new \MakePdf\TagElement('p', '1 Nacht in der Deluxe Suite inkl. Frühstück', "thesans", "N", 18, $color, 0));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 6, $freeText1Tag, '');

        $freeText2Tag = new \MakePdf\Tag();
        $freeText2Tag->addTagElement(new \MakePdf\TagElement('p', 'Anreise z.B. am 15.07.2016', "thesans", "N", 18, $color, 0));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 6, $freeText2Tag, '');

        $top = 200;
        $left = 10;
        $this->pdf->SetXY($left, $top);

        $pricePrefixTag = new \MakePdf\Tag();
        $pricePrefixTag->addTagElement(new \MakePdf\TagElement('preisprefix', 'Preis pro Person ab', "thesansb", "N", 18, $color, 0));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 14, $pricePrefixTag);

        $width = $this->pdf->GetStringWidth(\MakePdf\MakePdfLib::euroHack('Preis pro Person ab') . ' ') + 5;
        $this->pdf->SetXY($left + $width, $top - 7);

        $priceTag = new \MakePdf\Tag();
        $priceTag->addTagElement(new \MakePdf\TagElement('preis', '539', "thesanseb", "N", 90, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 14, $priceTag);

        $width += $this->pdf->GetStringWidth('539') + 2;
        $this->pdf->SetXY($left + $width, $top - 11);
        $txt = \MakePdf\MakePdfLib::euroHack(chr(164));

        $euroTag = new \MakePdf\Tag();
        $euroTag->addTagElement(new \MakePdf\TagElement('euro', \MakePdf\MakePdfLib::euroHack(chr(164)), "thesanseb", "N", 55, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, 14, $euroTag);

        $this->pdf->SetXY(10, 212);

        $codeTag = new \MakePdf\Tag();
        $codeTag->setLineHeight($this->spaceAfter);
        $codeTag->addTagElement(new \MakePdf\TagElement('code','DXB306 PX F', "thesans", "N", 13, $color));
        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 190, $this->lineHeight + 4, $codeTag);

        $this->pdf->SetXY(10, 225);

        $footerTag = new \MakePdf\Tag();
        $footer1TagElement = new \MakePdf\TagElement('fussb', 'Zwischenverkauf und Druckfehler vorbehalten! Es gelten die Reise- und Zahlungsbedingungen des jeweiligen FTI Kataloges.', "thesansb", "N", 8, $color, 0);
        $footer2TagElement = new \MakePdf\TagElement('fuss', 'FTI Touristik GmbH, Landsberger Str. 88, 80339 München', "thesans", "N", 8, $color, 0);

        $footerTag->addTagElement($footer1TagElement);
        $footerTag->addTagElement($footer2TagElement);

        \MakePdf\MakePdfLib::writeTagToPDF($this->pdf, 200, 3, $footerTag);
    }
}
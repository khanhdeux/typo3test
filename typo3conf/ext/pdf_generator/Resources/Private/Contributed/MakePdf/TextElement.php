<?php
namespace MakePdf;

class TextElement extends TagElement {
    public $name;
    public $content;
    public $font;
    public $style;
    public $size;
    public $color;
    public $indent;

    /**
     * TextElement constructor.
     * @param $name
     * @param $content
     * @param $border
     * @param string $font
     * @param string $style
     * @param string $size
     * @param string $color
     * @param string $indent
     */
    public function __construct($name, $content, $font = "thesans", $style = "N", $size = "18", $color = "0,0,0", $indent = -1) {
        $this->name = $name;
        $this->content = $content;
        $this->font = $font;
        $this->style = $style;
        $this->size = $size;
        $this->color = $color;
        $this->indent = $indent;
    }

    /**
     * @return mixed
     */
    public function getBackground() {
        return $this->background;
    }

    /**
     * @param mixed $background
     */
    public function setBackground($background) {
        $this->background = $background;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getBorder() {
        return $this->border;
    }

    /**
     * @param mixed $border
     */
    public function setBorder($border) {
        $this->border = $border;
    }

    /**
     * @return string
     */
    public function getFont() {
        return $this->font;
    }

    /**
     * @param string $font
     */
    public function setFont($font) {
        $this->font = $font;
    }

    /**
     * @return string
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle($style) {
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize($size) {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color) {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getIndent() {
        return $this->indent;
    }

    /**
     * @param string $indent
     */
    public function setIndent($indent) {
        $this->indent = $indent;
    }
}
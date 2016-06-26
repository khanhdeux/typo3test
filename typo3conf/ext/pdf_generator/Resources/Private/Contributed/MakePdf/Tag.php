<?php
namespace MakePdf;

class Tag {
    public $positionX;
    public $positionY;
    public $width;
    public $height;

    public $border;
    public $align;
    public $background;
    public $padding;
    public $lineHeight;
    public $tagElements = [];
    public $type;
    public $extra;

    /**
     * @param string $type
     * @param int $positionX
     * @param int $positionY
     * @param int $width
     * @param int $height
     * @param int $border
     * @param string $align
     * @param int $background
     * @param int $padding
     * @param int $lineHeight
     */
    public function __construct($type = 'text', $positionX = 0, $positionY = 0, $width = 190, $height = 12, $border = 0, $align = 'J', $background = 0, $padding = 0, $lineHeight = 0) {
        $this->type = $type;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->width = $width;
        $this->height = $height;

        $this->border = $border;
        $this->align = $align;
        $this->background = $background;
        $this->padding = $padding;
        $this->lineHeight = $lineHeight;
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
     * @return array
     */
    public function getTagElements() {
        return $this->tagElements;
    }

    /**
     * @param array $tagElements
     */
    public function setTagElements($tagElements) {
        $this->tagElements = $tagElements;
    }

    /**
     * @param $tag
     */
    public function addTagElement($tag) {
        $this->tagElements[] = $tag;
    }

    /**
     * @return int
     */
    public function getBackground() {
        return $this->background;
    }

    /**
     * @param int $background
     */
    public function setBackground($background) {
        $this->background = $background;
    }

    /**
     * @return string
     */
    public function getAlign() {
        return $this->align;
    }

    /**
     * @param string $align
     */
    public function setAlign($align) {
        $this->align = $align;
    }

    /**
     * @return int
     */
    public function getPadding() {
        return $this->padding;
    }

    /**
     * @param int $padding
     */
    public function setPadding($padding) {
        $this->padding = $padding;
    }

    /**
     * @return int
     */
    public function getLineHeight() {
        return $this->lineHeight;
    }

    /**
     * @param int $lineHeight
     */
    public function setLineHeight($lineHeight) {
        $this->lineHeight = $lineHeight;
    }

    /**
     * @return int
     */
    public function getPositionX()
    {
        return $this->positionX;
    }

    /**
     * @param int $positionX
     */
    public function setPositionX($positionX)
    {
        $this->positionX = $positionX;
    }

    /**
     * @return int
     */
    public function getPositionY()
    {
        return $this->positionY;
    }

    /**
     * @param int $positionY
     */
    public function setPositionY($positionY)
    {
        $this->positionY = $positionY;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
}
<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing NoteType
 *
 * Note
 * XSD Type: NoteType
 */
class NoteType
{

    /**
     * Content Code
     *
     * @var \horstoeko\orderx\entities\extended\udt\CodeType $contentCode
     */
    private $contentCode = null;

    /**
     * Content Text
     *
     * @var string[] $content
     */
    private $content = [
        
    ];

    /**
     * Subject Code
     *
     * @var \horstoeko\orderx\entities\extended\udt\CodeType $subjectCode
     */
    private $subjectCode = null;

    /**
     * Gets as contentCode
     *
     * Content Code
     *
     * @return \horstoeko\orderx\entities\extended\udt\CodeType
     */
    public function getContentCode()
    {
        return $this->contentCode;
    }

    /**
     * Sets a new contentCode
     *
     * Content Code
     *
     * @param  \horstoeko\orderx\entities\extended\udt\CodeType $contentCode
     * @return self
     */
    public function setContentCode(?\horstoeko\orderx\entities\extended\udt\CodeType $contentCode = null)
    {
        $this->contentCode = $contentCode;
        return $this;
    }

    /**
     * Adds as content
     *
     * Content Text
     *
     * @return self
     * @param  string $content
     */
    public function addToContent($content)
    {
        $this->content[] = $content;
        return $this;
    }

    /**
     * isset content
     *
     * Content Text
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetContent($index)
    {
        return isset($this->content[$index]);
    }

    /**
     * unset content
     *
     * Content Text
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetContent($index)
    {
        unset($this->content[$index]);
    }

    /**
     * Gets as content
     *
     * Content Text
     *
     * @return string[]
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets a new content
     *
     * Content Text
     *
     * @param  string $content
     * @return self
     */
    public function setContent(array $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Gets as subjectCode
     *
     * Subject Code
     *
     * @return \horstoeko\orderx\entities\extended\udt\CodeType
     */
    public function getSubjectCode()
    {
        return $this->subjectCode;
    }

    /**
     * Sets a new subjectCode
     *
     * Subject Code
     *
     * @param  \horstoeko\orderx\entities\extended\udt\CodeType $subjectCode
     * @return self
     */
    public function setSubjectCode(?\horstoeko\orderx\entities\extended\udt\CodeType $subjectCode = null)
    {
        $this->subjectCode = $subjectCode;
        return $this;
    }
}

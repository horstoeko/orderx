<?php

namespace horstoeko\orderx\entities\basic\ram;

/**
 * Class representing NoteType
 *
 * Note
 * XSD Type: NoteType
 */
class NoteType
{

    /**
     * Content Text
     *
     * @var string $content
     */
    private $content = null;

    /**
     * Subject Code
     *
     * @var string $subjectCode
     */
    private $subjectCode = null;

    /**
     * Gets as content
     *
     * Content Text
     *
     * @return string
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
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Gets as subjectCode
     *
     * Subject Code
     *
     * @return string
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
     * @param string $subjectCode
     * @return self
     */
    public function setSubjectCode($subjectCode)
    {
        $this->subjectCode = $subjectCode;
        return $this;
    }


}


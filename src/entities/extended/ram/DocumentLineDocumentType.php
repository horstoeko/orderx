<?php

namespace horstoeko\orderx\entities\extended\ram;

/**
 * Class representing DocumentLineDocumentType
 *
 * Document Line
 * XSD Type: DocumentLineDocumentType
 */
class DocumentLineDocumentType
{

    /**
     * Line ID
     *
     * @var \horstoeko\orderx\entities\extended\udt\IDType $lineID
     */
    private $lineID = null;

    /**
     * Status Code
     *
     * @var string $lineStatusCode
     */
    private $lineStatusCode = null;

    /**
     * Note
     *
     * @var \horstoeko\orderx\entities\extended\ram\NoteType[] $includedNote
     */
    private $includedNote = [
        
    ];

    /**
     * Gets as lineID
     *
     * Line ID
     *
     * @return \horstoeko\orderx\entities\extended\udt\IDType
     */
    public function getLineID()
    {
        return $this->lineID;
    }

    /**
     * Sets a new lineID
     *
     * Line ID
     *
     * @param  \horstoeko\orderx\entities\extended\udt\IDType $lineID
     * @return self
     */
    public function setLineID(\horstoeko\orderx\entities\extended\udt\IDType $lineID)
    {
        $this->lineID = $lineID;
        return $this;
    }

    /**
     * Gets as lineStatusCode
     *
     * Status Code
     *
     * @return string
     */
    public function getLineStatusCode()
    {
        return $this->lineStatusCode;
    }

    /**
     * Sets a new lineStatusCode
     *
     * Status Code
     *
     * @param  string $lineStatusCode
     * @return self
     */
    public function setLineStatusCode($lineStatusCode)
    {
        $this->lineStatusCode = $lineStatusCode;
        return $this;
    }

    /**
     * Adds as includedNote
     *
     * Note
     *
     * @return self
     * @param  \horstoeko\orderx\entities\extended\ram\NoteType $includedNote
     */
    public function addToIncludedNote(\horstoeko\orderx\entities\extended\ram\NoteType $includedNote)
    {
        $this->includedNote[] = $includedNote;
        return $this;
    }

    /**
     * isset includedNote
     *
     * Note
     *
     * @param  int|string $index
     * @return bool
     */
    public function issetIncludedNote($index)
    {
        return isset($this->includedNote[$index]);
    }

    /**
     * unset includedNote
     *
     * Note
     *
     * @param  int|string $index
     * @return void
     */
    public function unsetIncludedNote($index)
    {
        unset($this->includedNote[$index]);
    }

    /**
     * Gets as includedNote
     *
     * Note
     *
     * @return \horstoeko\orderx\entities\extended\ram\NoteType[]
     */
    public function getIncludedNote()
    {
        return $this->includedNote;
    }

    /**
     * Sets a new includedNote
     *
     * Note
     *
     * @param  \horstoeko\orderx\entities\extended\ram\NoteType[] $includedNote
     * @return self
     */
    public function setIncludedNote(array $includedNote = null)
    {
        $this->includedNote = $includedNote;
        return $this;
    }
}

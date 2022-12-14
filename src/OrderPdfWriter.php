<?php

declare(strict_types=1);

/**
 * This file is a part of horstoeko/orderx.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\orderx;

use \setasign\Fpdi\Fpdi as PdfFpdi;

/**
 * Class representing some tools for pdf generation
 *
 * @category Order-X
 * @package  Order-X
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/orderx
 *
 * @codeCoverageIgnore
 */
class OrderPdfWriter extends PdfFpdi
{
    /**
     * Full path to the ICC-Profile
     */
    const ICC_PROFILE_PATH = __DIR__ . '/assets/sRGB_v4_ICC.icc';

    /**
     * Option Name for "Created at"
     */
    const DATE_CREATED_NAME = "created";

    /**
     * Option Name for "Modified at"
     */
    const DATE_MODIFIED_NAME = "modified";

    /**
     * Contains all attached files
     *
     * @var array
     */
    protected $files = array();

    /**
     * Contains meta data
     *
     * @var array
     */
    protected $metaDataDescriptions = [];

    /**
     * Contains meta data
     *
     * @var array
     */
    protected $metaDataInfos = [];

    /**
     * Internal index
     *
     * @var integer
     */
    protected $fileSpecDictionnaryIndex = 0;

    /**
     * Internal index
     *
     * @var integer
     */
    protected $descriptionIndex = 0;

    /**
     * Internal index
     *
     * @var integer
     */
    protected $outputIntentIndex = 0;

    /**
     * Internal index
     *
     * @var integer
     */
    protected $n_files;

    /**
     * Internal flag that indicates that the attachment
     * pane should be shown by default
     *
     * @var boolean
     */
    protected $openAttachmentPane = false;

    /**
     * Set the PDF version.
     *
     * @param string $version     Contains the PDF version number.
     * @param bool   $binary_data This is true for binary data
     *
     * @return void
     */
    public function setPdfVersion($version = '1.3', $binary_data = false): void
    {
        $this->PDFVersion = sprintf('%.1F', $version);

        if (true == $binary_data) {
            $this->PDFVersion .=
                "\n" . '%' .
                chr(rand(128, 256)) .
                chr(rand(128, 256)) .
                chr(rand(128, 256)) .
                chr(rand(128, 256));
        }
    }

    /**
     * Attach file to PDF.
     *
     * @param  mixed  $file
     * Data to embed to the pdf
     * @param  string $name
     * The visible attachment filename
     * @param  string $desc
     * The description for the attached file
     * @param  string $relationship
     * The type of the relationship of the attached file
     * @param  string $mimetype
     * The url-encoded mimetype of the attached file
     * @param  bool   $isUTF8
     * Set to true, if the attached file is UTF-8 encoded
     * @return void
     */
    public function attach($file, $name = '', $desc = '', $relationship = 'Unspecified', $mimetype = '', $isUTF8 = false): void
    {
        if ('' == $name) {
            $p = strrpos($file, '/');
            if (false === $p) {
                $p = strrpos($file, '\\');
            }
            if (false !== $p) {
                $name = substr($file, $p + 1);
            } else {
                $name = $file;
            }
        }

        if (!$isUTF8) {
            $desc = utf8_encode($desc);
        }

        if ('' == $mimetype) {
            $mimetype = mime_content_type($file);
            if (!$mimetype) {
                $mimetype = 'application/octet-stream';
            }
        }

        $mimetype = str_replace('/', '#2F', $mimetype);
        $this->files[] = array('file' => $file, 'name' => $name, 'desc' => $desc, 'relationship' => $relationship, 'subtype' => $mimetype);
    }

    /**
     * Open attachment panel on PDF.
     *
     * @return void
     */
    public function openAttachmentPane(): void
    {
        $this->openAttachmentPane = true;
    }

    /**
     * Add metadata description node.
     *
     * @param  string $description
     * The description of the metadata
     * @return void
     */
    public function addMetadataDescriptionNode($description): void
    {
        $this->metaDataDescriptions[] = $description;
    }

    /**
     * Set PDF metadata infos.
     *
     * @param  array $metaDataInfos
     * The array with metadata information applied to the pdf
     * @return void
     */
    public function setPdfMetadataInfos(array $metaDataInfos): void
    {
        $this->metaDataInfos = $metaDataInfos;
    }

    /**
     * Put files.
     *
     * @throws \Exception
     * @return void
     *
     * @codingStandardsIgnoreStart
     */
    protected function _putfiles(): void
    {
        foreach ($this->files as $i => &$info) {
            $this->putFileSpecification($info);
            $info['file_index'] = $this->n;
            $this->putFileStream($info);
        }

        $this->putFileDictionary();
    }

    /**
     * Put file attachment specification.
     *
     * @param array $file_info
     * @return void
     */
    protected function putFileSpecification(array $file_info): void
    {
        $this->_newobj();
        $this->fileSpecDictionnaryIndex = $this->n;
        $this->_put('<<');
        $this->_put('/F (' . $this->_escape($file_info['name']) . ')');
        $this->_put('/Type /Filespec');
        $this->_put('/UF ' . $this->_textstring(utf8_encode($file_info['name'])));
        if ($file_info['relationship']) {
            $this->_put('/AFRelationship /' . $file_info['relationship']);
        }
        if ($file_info['desc']) {
            $this->_put('/Desc ' . $this->_textstring($file_info['desc']));
        }
        $this->_put('/EF <<');
        $this->_put('/F ' . ($this->n + 1) . ' 0 R');
        $this->_put('/UF ' . ($this->n + 1) . ' 0 R');
        $this->_put('>>');
        $this->_put('>>');
        $this->_put('endobj');
    }

    /**
     * Put file stream.
     *
     * @param array $file_info
     * @throws \Exception
     */
    protected function putFileStream(array $file_info): void
    {
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/Filter /FlateDecode');
        if ($file_info['subtype']) {
            $this->_put('/Subtype /' . $file_info['subtype']);
        }
        $this->_put('/Type /EmbeddedFile');
        if (is_string($file_info['file']) && @is_file($file_info['file'])) {
            $fc = file_get_contents($file_info['file']);
        } else {
            $stream = $file_info['file']->getStream();
            \fseek($stream, 0);
            $fc = stream_get_contents($stream);
        }
        if (false === $fc) {
            $this->Error('Cannot open file: ' . $file_info['file']);
        }
        if (is_string($file_info['file'])) {
            $md = @date('YmdHis', filemtime($file_info['file']));
        } else {
            $md = @date('YmdHis');
        }
        $fc = gzcompress($fc);
        $this->_put('/Length ' . strlen($fc));
        $this->_put("/Params <</ModDate (D:$md)>>");
        $this->_put('>>');
        $this->_putstream($fc);
        $this->_put('endobj');
    }

    /**
     * Put file dictionnary.
     *
     * @return void
     */
    protected function putFileDictionary(): void
    {
        $this->_newobj();
        $this->n_files = $this->n;
        $this->_put('<<');
        $s = '';
        $files = $this->files;
        usort($files, function ($a, $b) { // Sorting files in name order as PDF specs (if not, issue with Acrobat Reader when trying to download attachments)
            return strcmp($a['name'], $b['name']);
        });
        foreach ($files as $info) {
            $s .= sprintf('%s %s 0 R ', $this->_textstring($info['name']), $info['file_index']);
        }
        $this->_put(sprintf('/Names [%s]', $s));
        $this->_put('>>');
        $this->_put('endobj');
    }

    /**
     * Put metadata descriptions.
     *
     * @return void
     */
    protected function putMetadataDescriptions(): void
    {
        $s = '<?xpacket begin="" id="W5M0MpCehiHzreSzNTczkc9d"?>' . "\n";
        $s .= '<x:xmpmeta xmlns:x="adobe:ns:meta/">' . "\n";
        $s .= '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">' . "\n";
        $this->_newobj();
        $this->descriptionIndex = $this->n;
        foreach ($this->metaDataDescriptions as $i => $desc) {
            $s .= $desc . "\n";
        }
        $s .= '</rdf:RDF>' . "\n";
        $s .= '</x:xmpmeta>' . "\n";
        $s .= '<?xpacket end="w"?>';
        $this->_put('<<');
        $this->_put('/Length ' . strlen($s));
        $this->_put('/Type /Metadata');
        $this->_put('/Subtype /XML');
        $this->_put('>>');
        $this->_putstream($s);
        $this->_put('endobj');
    }

    /**
     * Put resources including files and metadata descriptions.
     *
     * @return void
     * @throws \Exception
     * @codingStandardsIgnoreStart
     */
    protected function _putresources(): void
    {
        parent::_putresources();

        if (!empty($this->files)) {
            $this->_putfiles();
        }

        $this->_putoutputintent();

        if (!empty($this->metaDataDescriptions)) {
            $this->putMetadataDescriptions();
        }
    }

    /**
     * Put output intent with ICC profile.
     *
     * @return void
     * @codingStandardsIgnoreStart
     */
    protected function _putoutputintent(): void
    {
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/Type /OutputIntent');
        $this->_put('/S /GTS_PDFA1');
        $this->_put('/OuputCondition (sRGB)');
        $this->_put('/OutputConditionIdentifier (Custom)');
        $this->_put('/DestOutputProfile ' . ($this->n + 1) . ' 0 R');
        $this->_put('/Info (sRGB V4 ICC)');
        $this->_put('>>');
        $this->_put('endobj');
        $this->outputIntentIndex = $this->n;

        $icc = file_get_contents($this::ICC_PROFILE_PATH);
        $icc = gzcompress($icc);

        $this->_newobj();
        $this->_put('<<');
        $this->_put('/Length ' . strlen($icc));
        $this->_put('/N 3');
        $this->_put('/Filter /FlateDecode');
        $this->_put('>>');
        $this->_putstream($icc);
        $this->_put('endobj');
    }

    /**
     * Put catalog node, including associated files.
     *
     * @return void
     * @codingStandardsIgnoreStart
     */
    protected function _putcatalog(): void
    {
        parent::_putcatalog();

        if (!empty($this->files)) {
            if (is_array($this->files)) {
                $files_ref_str = '';
                foreach ($this->files as $file) {
                    if ('' != $files_ref_str) {
                        $files_ref_str .= ' ';
                    }
                    $files_ref_str .= sprintf('%s 0 R', $file['file_index']);
                }
                $this->_put(sprintf('/AF [%s]', $files_ref_str));
            } else {
                $this->_put(sprintf('/AF %s 0 R', $this->n_files));
            }

            if (0 != $this->descriptionIndex) {
                $this->_put(sprintf('/Metadata %s 0 R', $this->descriptionIndex));
            }

            $this->_put('/Names <<');
            $this->_put('/EmbeddedFiles ');
            $this->_put(sprintf('%s 0 R', $this->n_files));
            $this->_put('>>');
        }

        if (0 != $this->outputIntentIndex) {
            $this->_put(sprintf('/OutputIntents [%s 0 R]', $this->outputIntentIndex));
        }

        if ($this->openAttachmentPane) {
            $this->_put('/PageMode /UseAttachments');
        }
    }

    /**
     * Put trailer including ID.
     *
     * @return void
     * @codingStandardsIgnoreStart
     */
    protected function _puttrailer(): void
    {
        parent::_puttrailer();

        $created_id = md5($this->generateMetadataString(self::DATE_CREATED_NAME));
        $modified_id = md5($this->generateMetadataString(self::DATE_MODIFIED_NAME));

        $this->_put(sprintf('/ID [<%s><%s>]', $created_id, $modified_id));
    }

    /**
     * Generate metadata string.
     *
     * @param string|null $dateType
     * The type of the metadata date
     * @return string
     * @codingStandardsIgnoreStart
     */
    protected function generateMetadataString(?string $dateType = null)
    {
        $dateType = $dateType ?? self::DATE_CREATED_NAME;
        $metaDataString = '';

        if (isset($this->metaDataInfos['title'])) {
            $metaDataString .= $this->metaDataInfos['title'];
        }

        if (isset($this->metaDataInfos['subject'])) {
            $metaDataString .= $this->metaDataInfos['subject'];
        }

        if ($dateType == self::DATE_MODIFIED_NAME && isset($this->metaDataInfos['modifiedDate'])) {
            $metaDataString .= $this->metaDataInfos['modifiedDate'];
        }

        if ($dateType == self::DATE_CREATED_NAME && isset($this->metaDataInfos['createdDate'])) {
            $metaDataString .= $this->metaDataInfos['createdDate'];
        }

        return $metaDataString;
    }
}

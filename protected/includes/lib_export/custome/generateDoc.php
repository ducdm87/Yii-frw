<?php

class generateDoc extends CreateDocument {

    const NAMESPACEWORD = 'w';
    const SCHEMA_IMAGEDOCUMENT = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image';
    const SCHEMA_OFFICEDOCUMENT = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument';

    public static $PHPDOCXStyles;
    public static $customLists;
    public static $numUL;
    public static $numOL;
    public static $orderedListStyle;
    public static $unorderedListStyle;
    public $fileGraphicTemplate;
    private $footerAdded;
    private $headerAdded;
    public $graphicTemplate;
    public static $intIdWord;
    public static $log;
    public static $sectionProperties = array(
        'w:footnotePr',
        'w:endnotePr',
        'w:type',
        'w:pgSz',
        'w:pgMar',
        'w:paperSrc',
        'w:pgBorders',
        'w:lnNumType',
        'w:pgNumType',
        'w:cols',
        'w:formProt',
        'w:vAlign',
        'w:noEndnote',
        'w:titlePg',
        'w:textDirection',
        'w:bidi',
        'w:rtlGutter',
        'w:docGrid',
        'w:printerSettings',
        'w:sectPrChange'
    );
    public static $settings = array(
        'w:writeProtection',
        'w:view',
        'w:zoom',
        'w:removePersonalInformation',
        'w:removeDateAndTime',
        'w:doNotDisplayPageBoundaries',
        'w:displayBackgroundShape',
        'w:printPostScriptOverText',
        'w:printFractionalCharacterWidth',
        'w:printFormsData',
        'w:embedTrueTypeFonts',
        'w:embedSystemFonts',
        'w:saveSubsetFonts',
        'w:saveFormsData',
        'w:mirrorMargins',
        'w:alignBordersAndEdges',
        'w:bordersDoNotSurroundHeader',
        'w:bordersDoNotSurroundFooter',
        'w:gutterAtTop',
        'w:hideSpellingErrors',
        'w:hideGrammaticalErrors',
        'w:activeWritingStyle',
        'w:proofState',
        'w:formsDesign',
        'w:attachedTemplate',
        'w:linkStyles',
        'w:stylePaneFormatFilter',
        'w:stylePaneSortMethod',
        'w:documentType',
        'w:mailMerge',
        'w:revisionView',
        'w:trackRevisions',
        'w:doNotTrackMoves',
        'w:doNotTrackFormatting',
        'w:documentProtection',
        'w:autoFormatOverride',
        'w:styleLockTheme',
        'w:styleLockQFSet',
        'w:defaultTabStop',
        'w:autoHyphenation',
        'w:consecutiveHyphenLimit',
        'w:hyphenationZone',
        'w:doNotHyphenateCaps',
        'w:showEnvelope',
        'w:summaryLength',
        'w:clickAndTypeStyle',
        'w:defaultTableStyle',
        'w:evenAndOddHeaders',
        'w:bookFoldRevPrinting',
        'w:bookFoldPrinting',
        'w:bookFoldPrintingSheets',
        'w:drawingGridHorizontalSpacing',
        'w:drawingGridVerticalSpacing',
        'w:displayHorizontalDrawingGridEvery',
        'w:displayVerticalDrawingGridEvery',
        'w:doNotUseMarginsForDrawingGridOrigin',
        'w:drawingGridHorizontalOrigin',
        'w:drawingGridVerticalOrigin',
        'w:doNotShadeFormData',
        'w:noPunctuationKerning',
        'w:characterSpacingControl',
        'w:printTwoOnOne',
        'w:strictFirstAndLastChars',
        'w:noLineBreaksAfter',
        'w:noLineBreaksBefore',
        'w:savePreviewPicture',
        'w:doNotValidateAgainstSchema',
        'w:saveInvalidXml',
        'w:ignoreMixedContent',
        'w:alwaysShowPlaceholderText',
        'w:doNotDemarcateInvalidXml',
        'w:saveXmlDataOnly',
        'w:useXSLTWhenSaving',
        'w:saveThroughXslt',
        'w:showXMLTags',
        'w:alwaysMergeEmptyNamespace',
        'w:updateFields',
        'w:hdrShapeDefaults',
        'w:footnotePr',
        'w:endnotePr',
        'w:compat',
        'w:docVars',
        'w:rsids',
        'm:mathPr',
        'w:uiCompat97To2003',
        'w:attachedSchema',
        'w:themeFontLang',
        'w:clrSchemeMapping',
        'w:doNotIncludeSubdocsInStats',
        'w:doNotAutoCompressPictures',
        'w:forceUpgrade',
        'w:captions',
        'w:readModeInkLockDown',
        'w:smartTagType',
        'sl:schemaLibrary',
        'w:shapeDefaults',
        'w:doNotEmbedSmartTags',
        'w:decimalSymbol',
        'w:listSeparator'
    );
    private $_background;
    private $_backgroundColor;
    private $_baseTemplateFilesPath;
    private $_baseTemplatePath;
    private $_baseTemplateZip;
    private $_bookmarksIds;
    private $_compatibilityMode;
    private $_contentTypeC;
    private $_defaultFont;
    private $_debug;
    private $_defaultPHPDOCXStyles;
    private $_defaultTemplate;
    private $_docm;
    private $_docPropsAppC;
    private $_docPropsAppT;
    private $_docPropsCoreC;
    private $_docPropsCoreT;
    private $_docPropsCustomC;
    private $_docPropsCustomT;
    private static $_encodeUTF;
    private $_extension;
    private $_idImgHeader;
    private $_idRels;
    private $_idWords;
    private $_language;
    private $_macro;
    private $_markAsFinal;
    private $_parsedStyles;
    private $_phpdocxconfig;
    private $_relsHeader;
    private $_relsHeaderFooterImage;
    private $_relsHeaderFooterImageExternal;
    private $_relsHeaderFooterLink;
    private $_relsFooter;
    private $_relsNotesExternalImage;
    private $_relsNotesImage;
    private $_relsNotesLink;
    private $_relsRelsC;
    private $_relsRelsT;
    private $_sectPr;
    private $_tempDir;
    private $_tempFile;
    private $_tempFileXLSX;
    private $_templateCustomNumberings;
    private $_templateNumberings;
    private $_uniqid;
    private $_wordDocumentC;
    private $_wordDocumentT;
    private $_wordDocumentStyles;
    private $_wordEndnotesC;
    private $_wordEndnotesT;
    private $_wordFontTableC;
    private $_wordFontTableT;
    private $_wordFooterC;
    private $_wordFooterT;
    private $_wordFootnotesC;
    private $_wordFootnotesT;
    private $_wordHeaderC;
    private $_wordHeaderT;
    private $_wordNumberingC;
    private $_wordNumberingT;
    private $_wordRelsDocumentRelsC;
    private $_wordRelsDocumentRelsT;
    private $_wordRelsFooterRelsC;
    private $_wordRelsFooterRelsT;
    private $_wordRelsHeaderRelsC;
    private $_wordRelsHeaderRelsT;
    private $_wordSettingsC;
    private $_wordSettingsT;
    private $_wordStylesC;
    private $_wordStylesT;
    private $_wordThemeThemeT;
    private $_wordThemeThemeC;
    private $_wordWebSettingsC;
    private $_wordWebSettingsT;
    private $_zipDocx;

    public function __construct($baseTemplatePath = PHPDOCX_BASE_TEMPLATE) {
        $this->_debug = Debug::getInstance();
        $this->_phpdocxconfig = PhpdocxUtilities::parseConfig();
        $this->_background = '';
        $this->_backgroundColor = 'FFFFFF';
        $this->_baseTemplateFilesPath;
        if ($baseTemplatePath == 'docm') {
            $this->_baseTemplatePath = PHPDOCX_BASE_FOLDER . 'phpdocxBaseTemplate.docm';
            $this->_docm = true;
            $this->_defaultTemplate = true;
            $this->_extension = 'docm';
        } else if ($baseTemplatePath == 'docx') {
            $this->_baseTemplatePath = PHPDOCX_BASE_FOLDER . 'phpdocxBaseTemplate.docx';
            $this->_docm = false;
            $this->_defaultTemplate = true;
            $this->_extension = 'docx';
        } else {
            if ($baseTemplatePath == PHPDOCX_BASE_TEMPLATE) {
                $this->_defaultTemplate = true;
            } else {
                $this->_defaultTemplate = false;
            }
            $this->_baseTemplatePath = $baseTemplatePath;
            $extensionArray = explode('.', $this->_baseTemplatePath);
            $extension = array_pop($extensionArray);
            $this->_extension = $extension;
            if ($extension == 'docm') {
                $this->_docm = true;
            } else if ($extension == 'docx') {
                $this->_docm = false;
            } else {
//PhpdocxLogger::logger ( 'Invalid base template extension', 'fatal' );
            }
        }
        $this->_baseTemplateZip = new ZipArchive ();
        $this->_bookmarksIds = array();
        $this->_idRels = array();
        $this->_idWords = array();
        $this->_idImgHeader = 1;
        $this->_idRels = 1;
        self::$intIdWord = rand(9999999, 99999999);
        self::$_encodeUTF = 0;
        $this->_language = 'en-US';
        $this->_markAsFinal = 0;
        $this->graphicTemplate = array();
        $this->fileGraphicTemplate = array();
        $this->_zipDocx = new ZipArchive ();
        if ($this->_phpdocxconfig ['settings'] ['temp_path']) {
            $this->_tempDir = $this->_phpdocxconfig ['settings'] ['temp_path'];
        } else {
            $this->_tempDir = self::getTempDir();
        }
        $this->_tempFile = tempnam($this->_tempDir, 'document');
        $this->_templateNumberings;
        $this->_templateCustomNumberings;
        $this->_zipDocx->open($this->_tempFile, ZipArchive::OVERWRITE);
        $this->_compatibilityMode = false;
//PhpdocxLogger::logger ( 'Create a temp file to use as initial ZIP file. ' . 'DOCX is a ZIP file.', 'info' );
        $this->_sign = false;
        $this->_relsRelsC = '';
        $this->_relsRelsT = '';
        $this->_contentTypeC = '';
        $this->_contentTypeT = NULL;
        $this->_defaultFont = '';
        $this->_docPropsAppC = '';
        $this->_docPropsAppT = '';
        $this->_docPropsCoreC = '';
        $this->_docPropsCoreT = '';
        $this->_docPropsCustomC = '';
        $this->_docPropsCustomT = '';
        $this->_macro = 0;
        $this->_relsHeader = array();
        $this->_relsFooter = array();
        $this->_parsedStyles = array();
        $this->_relsHeaderFooterImage = array();
        $this->_relsHeaderFooterImageExternal = array();
        $this->_relsHeaderFooterLink = array();
        $this->_relsNotesExternalImage = array();
        $this->_relsNotesImage = array();
        $this->_relsNotesLink = array();
        $this->_sectPr = NULL;
        $this->_tempFileXLSX = array();
        $this->_uniqid = 'phpdocx_' . uniqid();
        $this->_wordDocumentT = '';
        $this->_wordDocumentC = '';
        $this->_wordDocumentStyles = '';
        $this->_wordEndnotesC = '';
        $this->_wordEndnotesT = '';
        $this->_wordFontTableT = '';
        $this->_wordFontTableC = '';
        $this->_wordFooterC = array();
        $this->_wordFooterT = array();
        $this->_wordFootnotesC = '';
        $this->_wordFootnotesT = '';
        $this->_wordHeaderC = array();
        $this->_wordHeaderT = array();
        $this->_wordNumberingC;
        $this->_wordNumberingT;
        $this->_wordRelsDocumentRelsC = '';
        $this->_wordRelsDocumentRelsT = NULL;
        $this->_wordRelsHeaderRelsC = array();
        $this->_wordRelsHeaderRelsT = array();
        $this->_wordRelsFooterRelsC = array();
        $this->_wordRelsFooterRelsT = array();
        $this->_wordSettingsT = '';
        $this->_wordSettingsC = '';
        $this->_wordStylesT = '';
        $this->_wordStylesC = '';
        $this->_wordThemeThemeT = '';
        $this->_wordThemeThemeC = '';
        $this->_wordWebSettingsT = '';
        $this->_wordWebSettingsC = '';
        $this->_defaultPHPDOCXStyles = array(
            'Default Paragraph Font PHPDOCX',
            'List Paragraph PHPDOCX',
            'Title PHPDOCX',
            'Subtitle PHPDOCX',
            'Normal Table PHPDOCX',
            'Table Grid PHPDOCX',
            'footnote Text PHPDOCX',
            'footnote text Car PHPDOCX',
            'footnote Reference PHPDOCX',
            'endnote Text PHPDOCX',
            'endnote text Car PHPDOCX',
            'endnote Reference PHPDOCX',
            'annotation reference PHPDOCX',
            'annotation text PHPDOCX',
            'Comment Text Char PHPDOCX',
            'annotation subject PHPDOCX',
            'Comment Subject Char PHPDOCX',
            'Balloon Text PHPDOCX',
            'Balloon Text Char PHPDOCX'
        );
        $this->footerAdded = false;
        $this->headerAdded = false;
        self::$customLists = array();
        self::$PHPDOCXStyles = '<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" >
                                        <w:style w:type="character" w:styleId="DefaultParagraphFontPHPDOCX">
                                            <w:name w:val="Default Paragraph Font PHPDOCX"/>
                                            <w:uiPriority w:val="1"/>
                                            <w:semiHidden/>
                                            <w:unhideWhenUsed/>
                                        </w:style>
                                        <w:style w:type="paragraph" w:styleId="ListParagraphPHPDOCX">
                                            <w:name w:val="List Paragraph PHPDOCX"/>
                                            <w:basedOn w:val="Normal"/>
                                            <w:uiPriority w:val="34"/>
                                            <w:qFormat/>
                                            <w:rsid w:val="00DF064E"/>
                                            <w:pPr>
                                                <w:ind w:left="720"/>
                                                <w:contextualSpacing/>
                                            </w:pPr>
                                        </w:style>
                                        <w:style w:type="paragraph" w:styleId="TitlePHPDOCX">
                                            <w:name w:val="Title PHPDOCX"/>
                                            <w:basedOn w:val="Normal"/>
                                            <w:next w:val="Normal"/>
                                            <w:link w:val="TitleCarPHPDOCX"/>
                                            <w:uiPriority w:val="10"/>
                                            <w:qFormat/>
                                            <w:rsid w:val="00DF064E"/>
                                            <w:pPr>
                                                <w:pBdr>
                                                    <w:bottom w:val="single" w:sz="8" w:space="4" w:color="4F81BD" w:themeColor="accent1"/>
                                                </w:pBdr>
                                                <w:spacing w:after="300" w:line="240" w:lineRule="auto"/>
                                                <w:contextualSpacing/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:asciiTheme="majorHAnsi" w:eastAsiaTheme="majorEastAsia" w:hAnsiTheme="majorHAnsi" w:cstheme="majorBidi"/>
                                                <w:color w:val="17365D" w:themeColor="text2" w:themeShade="BF"/>
                                                <w:spacing w:val="5"/>
                                                <w:kern w:val="28"/>
                                                <w:sz w:val="52"/>
                                                <w:szCs w:val="52"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="character" w:customStyle="1" w:styleId="TitleCarPHPDOCX">
                                            <w:name w:val="Title Car PHPDOCX"/>
                                            <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                            <w:link w:val="TitlePHPDOCX"/>
                                            <w:uiPriority w:val="10"/>
                                            <w:rsid w:val="00DF064E"/>
                                            <w:rPr>
                                                <w:rFonts w:asciiTheme="majorHAnsi" w:eastAsiaTheme="majorEastAsia" w:hAnsiTheme="majorHAnsi" w:cstheme="majorBidi"/>
                                                <w:color w:val="17365D" w:themeColor="text2" w:themeShade="BF"/>
                                                <w:spacing w:val="5"/>
                                                <w:kern w:val="28"/>
                                                <w:sz w:val="52"/>
                                                <w:szCs w:val="52"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="paragraph" w:styleId="SubtitlePHPDOCX">
                                            <w:name w:val="Subtitle PHPDOCX"/>
                                            <w:basedOn w:val="Normal"/>
                                            <w:next w:val="Normal"/>
                                            <w:link w:val="SubtitleCarPHPDOCX"/>
                                            <w:uiPriority w:val="11"/>
                                            <w:qFormat/>
                                            <w:rsid w:val="00DF064E"/>
                                            <w:pPr>
                                                <w:numPr>
                                                    <w:ilvl w:val="1"/>
                                                </w:numPr>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:asciiTheme="majorHAnsi" w:eastAsiaTheme="majorEastAsia" w:hAnsiTheme="majorHAnsi" w:cstheme="majorBidi"/>
                                                <w:i/>
                                                <w:iCs/>
                                                <w:color w:val="4F81BD" w:themeColor="accent1"/>
                                                <w:spacing w:val="15"/>
                                                <w:sz w:val="24"/>
                                                <w:szCs w:val="24"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="character" w:customStyle="1" w:styleId="SubtitleCarPHPDOCX">
                                            <w:name w:val="Subtitle Car PHPDOCX"/>
                                            <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                            <w:link w:val="SubtitlePHPDOCX"/>
                                            <w:uiPriority w:val="11"/>
                                            <w:rsid w:val="00DF064E"/>
                                            <w:rPr>
                                                <w:rFonts w:asciiTheme="majorHAnsi" w:eastAsiaTheme="majorEastAsia" w:hAnsiTheme="majorHAnsi" w:cstheme="majorBidi"/>
                                                <w:i/>
                                                <w:iCs/>
                                                <w:color w:val="4F81BD" w:themeColor="accent1"/>
                                                <w:spacing w:val="15"/>
                                                <w:sz w:val="24"/>
                                                <w:szCs w:val="24"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="table" w:styleId="NormalTablePHPDOCX">
                                            <w:name w:val="Normal Table PHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:unhideWhenUsed/>
                                            <w:qFormat/>
                                            <w:pPr>
                                                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                                            </w:pPr>
                                            <w:tblPr>
                                                <w:tblInd w:w="0" w:type="dxa"/>
                                                <w:tblCellMar>
                                                    <w:top w:w="0" w:type="dxa"/>
                                                    <w:left w:w="108" w:type="dxa"/>
                                                    <w:bottom w:w="0" w:type="dxa"/>
                                                    <w:right w:w="108" w:type="dxa"/>
                                                </w:tblCellMar>
                                            </w:tblPr>
                                        </w:style>
                                        <w:style w:type="table" w:styleId="TableGridPHPDOCX">
                                            <w:name w:val="Table Grid PHPDOCX"/>
                                            <w:uiPriority w:val="59"/>
                                            <w:rsid w:val="00493A0C"/>
                                            <w:pPr>
                                                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                                            </w:pPr>
                                            <w:tblPr>
                                                <w:tblInd w:w="0" w:type="dxa"/>
                                                <w:tblBorders>
                                                    <w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                                    <w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                                    <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                                    <w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                                    <w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                                    <w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/>
                                                </w:tblBorders>
                                                <w:tblCellMar>
                                                    <w:top w:w="0" w:type="dxa"/>
                                                    <w:left w:w="108" w:type="dxa"/>
                                                    <w:bottom w:w="0" w:type="dxa"/>
                                                    <w:right w:w="108" w:type="dxa"/>
                                                </w:tblCellMar>
                                            </w:tblPr>
                                        </w:style>
                                        <w:style w:type="character" w:styleId="CommentReferencePHPDOCX">
                                            <w:name w:val="annotation reference PHPDOCX"/>
                                            <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:unhideWhenUsed/>
                                            <w:rsid w:val="00E139EA"/>
                                            <w:rPr>
                                                <w:sz w:val="16"/>
                                                <w:szCs w:val="16"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="paragraph" w:styleId="CommentTextPHPDOCX">
                                            <w:name w:val="annotation text PHPDOCX"/>
                                            <w:basedOn w:val="Normal"/>
                                            <w:link w:val="CommentTextCharPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:unhideWhenUsed/>
                                            <w:rsid w:val="00E139EA"/>
                                            <w:pPr>
                                                <w:spacing w:line="240" w:lineRule="auto"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:sz w:val="20"/>
                                                <w:szCs w:val="20"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="character" w:customStyle="1" w:styleId="CommentTextCharPHPDOCX">
                                            <w:name w:val="Comment Text Char PHPDOCX"/>
                                            <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                            <w:link w:val="CommentTextPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:rsid w:val="00E139EA"/>
                                            <w:rPr>
                                                <w:sz w:val="20"/>
                                                <w:szCs w:val="20"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="paragraph" w:styleId="CommentSubjectPHPDOCX">
                                            <w:name w:val="annotation subject PHPDOCX"/>
                                            <w:basedOn w:val="CommentTextPHPDOCX"/>
                                            <w:next w:val="CommentTextPHPDOCX"/>
                                            <w:link w:val="CommentSubjectCharPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:unhideWhenUsed/>
                                            <w:rsid w:val="00E139EA"/>
                                            <w:rPr>
                                                <w:b/>
                                                <w:bCs/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="character" w:customStyle="1" w:styleId="CommentSubjectCharPHPDOCX">
                                            <w:name w:val="Comment Subject Char PHPDOCX"/>
                                            <w:basedOn w:val="CommentTextCharPHPDOCX"/>
                                            <w:link w:val="CommentSubjectPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:rsid w:val="00E139EA"/>
                                            <w:rPr>
                                                <w:b/>
                                                <w:bCs/>
                                                <w:sz w:val="20"/>
                                                <w:szCs w:val="20"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="paragraph" w:styleId="BalloonTextPHPDOCX">
                                            <w:name w:val="Balloon Text PHPDOCX"/>
                                            <w:basedOn w:val="Normal"/>
                                            <w:link w:val="BalloonTextCharPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:unhideWhenUsed/>
                                            <w:rsid w:val="00E139EA"/>
                                            <w:pPr>
                                                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma" w:cs="Tahoma"/>
                                                <w:sz w:val="16"/>
                                            <w:szCs w:val="16"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="character" w:customStyle="1" w:styleId="BalloonTextCharPHPDOCX">
                                            <w:name w:val="Balloon Text Char PHPDOCX"/>
                                            <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                            <w:link w:val="BalloonTextPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:rsid w:val="00E139EA"/>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma" w:cs="Tahoma"/>
                                                <w:sz w:val="16"/>
                                                <w:szCs w:val="16"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="paragraph" w:styleId="footnoteTextPHPDOCX">
                                            <w:name w:val="footnote Text PHPDOCX"/>
                                            <w:basedOn w:val="Normal"/>
                                            <w:link w:val="footnoteTextCarPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:unhideWhenUsed/>
                                            <w:rsid w:val="006E0FDA"/>
                                            <w:pPr>
                                                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:sz w:val="20"/>
                                                <w:szCs w:val="20"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="character" w:customStyle="1" w:styleId="footnoteTextCarPHPDOCX">
                                            <w:name w:val="footnote Text Car PHPDOCX"/>
                                            <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                            <w:link w:val="footnoteTextPHPDOCX"/>
                                            <w:uiPriority w:val="99"/>
                                            <w:semiHidden/>
                                            <w:rsid w:val="006E0FDA"/>
                                            <w:rPr>
                                                <w:sz w:val="20"/>
                                                <w:szCs w:val="20"/>
                                            </w:rPr>
                                        </w:style>
                                        <w:style w:type="character" w:styleId="footnoteReferencePHPDOCX">
                                        <w:name w:val="footnote Reference PHPDOCX"/>
                                        <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                        <w:uiPriority w:val="99"/>
                                        <w:semiHidden/>
                                        <w:unhideWhenUsed/>
                                        <w:rsid w:val="006E0FDA"/>
                                        <w:rPr>
                                            <w:vertAlign w:val="superscript"/>
                                        </w:rPr>
                                    </w:style>
                                    <w:style w:type="paragraph" w:styleId="endnoteTextPHPDOCX">
                                        <w:name w:val="endnote Text PHPDOCX"/>
                                        <w:basedOn w:val="Normal"/>
                                        <w:link w:val="endnoteTextCarPHPDOCX"/>
                                        <w:uiPriority w:val="99"/>
                                        <w:semiHidden/>
                                        <w:unhideWhenUsed/>
                                        <w:rsid w:val="006E0FDA"/>
                                        <w:pPr>
                                            <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                                        </w:pPr>
                                        <w:rPr>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:style>
                                    <w:style w:type="character" w:customStyle="1" w:styleId="endnoteTextCarPHPDOCX">
                                        <w:name w:val="endnote Text Car PHPDOCX"/>
                                        <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                        <w:link w:val="endnoteTextPHPDOCX"/>
                                        <w:uiPriority w:val="99"/>
                                        <w:semiHidden/>
                                        <w:rsid w:val="006E0FDA"/>
                                        <w:rPr>
                                            <w:sz w:val="20"/>
                                            <w:szCs w:val="20"/>
                                        </w:rPr>
                                    </w:style>
                                    <w:style w:type="character" w:styleId="endnoteReferencePHPDOCX">
                                        <w:name w:val="endnote Reference PHPDOCX"/>
                                        <w:basedOn w:val="DefaultParagraphFontPHPDOCX"/>
                                        <w:uiPriority w:val="99"/>
                                        <w:semiHidden/>
                                        <w:unhideWhenUsed/>
                                        <w:rsid w:val="006E0FDA"/>
                                        <w:rPr>
                                            <w:vertAlign w:val="superscript"/>
                                        </w:rPr>
                                    </w:style>
                                 </w:styles>';
        self::$unorderedListStyle = '<w:abstractNum w:abstractNumId="" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" >
                                        <w:multiLevelType w:val="hybridMultilevel"/>
                                        <w:lvl w:ilvl="0" w:tplc="">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val=""/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="720" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Symbol" w:hAnsi="Symbol" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="1" w:tplc="0C0A0003" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val="o"/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="1440" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Courier New" w:hAnsi="Courier New" w:cs="Courier New" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="2" w:tplc="0C0A0005" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val=""/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="2160" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Wingdings" w:hAnsi="Wingdings" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="3" w:tplc="0C0A0001" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val=""/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="2880" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Symbol" w:hAnsi="Symbol" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="4" w:tplc="0C0A0003" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val="o"/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="3600" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Courier New" w:hAnsi="Courier New" w:cs="Courier New" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="5" w:tplc="0C0A0005" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val=""/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="4320" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Wingdings" w:hAnsi="Wingdings" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="6" w:tplc="0C0A0001" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val=""/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="5040" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Symbol" w:hAnsi="Symbol" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="7" w:tplc="0C0A0003" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val="o"/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="5760" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Courier New" w:hAnsi="Courier New" w:cs="Courier New" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="8" w:tplc="0C0A0005" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="bullet"/>
                                            <w:lvlText w:val=""/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="6480" w:hanging="360"/>
                                            </w:pPr>
                                            <w:rPr>
                                                <w:rFonts w:ascii="Wingdings" w:hAnsi="Wingdings" w:hint="default"/>
                                            </w:rPr>
                                        </w:lvl>
                                    </w:abstractNum>';
        self::$orderedListStyle = '<w:abstractNum w:abstractNumId="" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" >
                                        <w:multiLevelType w:val="hybridMultilevel"/>
                                        <w:lvl w:ilvl="0" w:tplc="">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="decimal"/>
                                            <w:lvlText w:val="%1."/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="720" w:hanging="360"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="1" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="lowerLetter"/>
                                            <w:lvlText w:val="%2."/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="1440" w:hanging="360"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="2" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="lowerRoman"/>
                                            <w:lvlText w:val="%3."/>
                                            <w:lvlJc w:val="right"/>
                                            <w:pPr>
                                                <w:ind w:left="2160" w:hanging="180"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="3" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="decimal"/>
                                            <w:lvlText w:val="%4."/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="2880" w:hanging="360"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="4" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="lowerLetter"/>
                                            <w:lvlText w:val="%5."/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="3600" w:hanging="360"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="5" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="lowerRoman"/>
                                            <w:lvlText w:val="%6."/>
                                            <w:lvlJc w:val="right"/>
                                            <w:pPr>
                                                <w:ind w:left="4320" w:hanging="180"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="6" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="decimal"/>
                                            <w:lvlText w:val="%7."/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="5040" w:hanging="360"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="7" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="lowerLetter"/>
                                            <w:lvlText w:val="%8."/>
                                            <w:lvlJc w:val="left"/>
                                            <w:pPr>
                                                <w:ind w:left="5760" w:hanging="360"/>
                                            </w:pPr>
                                        </w:lvl>
                                        <w:lvl w:ilvl="8" w:tplc="" w:tentative="1">
                                            <w:start w:val="1"/>
                                            <w:numFmt w:val="lowerRoman"/>
                                            <w:lvlText w:val="%9."/>
                                            <w:lvlJc w:val="right"/>
                                            <w:pPr>
                                                <w:ind w:left="6480" w:hanging="180"/>
                                            </w:pPr>
                                        </w:lvl>
                                    </w:abstractNum>';
        try {
            GenerateDocx::beginDocx();
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $openBaseTemplate = $this->_baseTemplateZip->open($this->_baseTemplatePath);
            if ($openBaseTemplate !== true) {
                throw new Exception('Error while opening the Base Template: please, check the path');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $this->_baseTemplateFilesPath = $this->_tempDir . '/' . uniqid(true);
            var_dump($this->_baseTemplateZip);
            $extractBaseTemplate = $this->_baseTemplateZip->extractTo($this->_baseTemplateFilesPath);
            if ($extractBaseTemplate !== true) {
                throw new Exception('Error while extracting the Base Template: there may be problems writing in the default tmp folder');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $baseTemplateDocumentT = $this->_baseTemplateZip->getFromName('word/document.xml');
            if ($baseTemplateDocumentT == '') {
                throw new Exception('Error while extracting the document.xml file from the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $baseDocument = new DOMDocument ();
        $baseDocument->loadXML($baseTemplateDocumentT);
        $docXpath = new DOMXPath($baseDocument);
        $docXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $queryDoc = '//w:body/w:sdt';
        $docNodes = $docXpath->query($queryDoc);
        if ($docNodes->length > 0) {
            if ($docNodes->item(0)->nodeName == 'w:sdt') {
                $tempDoc = new DomDocument ();
                $sdt = $tempDoc->importNode($docNodes->item(0), true);
                $newNode = $tempDoc->appendChild($sdt);
                $frontPage = $tempDoc->saveXML($newNode);
                $this->_wordDocumentC .= $frontPage;
            }
        }
        $sectPr = $baseDocument->getElementsByTagName('sectPr')->item(0);
        $this->_sectPr = new DOMDocument ();
        $sectNode = $this->_sectPr->importNode($sectPr, true);
        $this->_sectPr->appendChild($sectNode);
        try {
            $baseTemplateContentTypeT = $this->_baseTemplateZip->getFromName('[Content_Types].xml');
            if ($baseTemplateContentTypeT == '') {
                throw new Exception('Error while extracting the [Content_Types].xml file from the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $this->_contentTypeT = new DOMDocument ();
        $this->_contentTypeT->loadXML($baseTemplateContentTypeT);
        $this->generateDEFAULT('gif', 'image/gif');
        $this->generateDEFAULT('jpg', 'image/jpg');
        $this->generateDEFAULT('png', 'image/png');
        $this->generateDEFAULT('jpeg', 'image/jpeg');
        $this->generateDEFAULT('bmp', 'image/bmp');
        try {
            $baseTemplateDocumentRelsT = $this->_baseTemplateZip->getFromName('word/_rels/document.xml.rels');
            if ($baseTemplateDocumentRelsT == '') {
                throw new Exception('Error while extracting the document.xml.rels file from the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $this->_wordRelsDocumentRelsT = new DOMDocument ();
        $this->_wordRelsDocumentRelsT->loadXML($baseTemplateDocumentRelsT);
        $relationships = $this->_wordRelsDocumentRelsT->getElementsByTagName('Relationship');
        if ($this->_defaultTemplate) {
            self::$numUL = 1;
            self::$numOL = rand(9999, 999999999);
            try {
                $this->_wordNumberingT = $this->_baseTemplateZip->getFromName('word/numbering.xml');
                if ($this->_wordNumberingT == '') {
                    throw new Exception('Error while extracting the numbering file from the base template');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
        } else {
            $counter = $relationships->length - 1;
            for ($j = $counter; $j > - 1; $j --) {
                $completeType = $relationships->item($j)->getAttribute('Type');
                $target = $relationships->item($j)->getAttribute('Target');
                $tempArray = explode('/', $completeType);
                $type = array_pop($tempArray);
                $arrayCleaner = array();
                switch ($type) {
                    case 'header' :
                        array_push($this->_relsHeader, $target);
                        break;
                    case 'footer' :
                        array_push($this->_relsFooter, $target);
                        break;
                    case 'chart' :
                        $this->recursiveDelete($this->_baseTemplateFilesPath . '/word/charts');
                        $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                        break;
                    case 'embeddings' :
                        $this->recursiveDelete($this->_baseTemplateFilesPath . '/word/embeddings');
                        $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                        break;
                }
            }
            $this->importStyles(PHPDOCX_BASE_TEMPLATE, 'merge', $this->_defaultPHPDOCXStyles);
            $numRef = rand(9999999, 99999999);
            self::$numUL = $numRef;
            self::$numOL = $numRef + 1;
            if (file_exists($this->_baseTemplateFilesPath . '/word/numbering.xml')) {
                try {
                    $this->_wordNumberingT = $this->_baseTemplateZip->getFromName('word/numbering.xml');
                    if ($this->_wordNumberingT == '') {
                        throw new Exception('Error while extracting the numbering file from the base template');
                    }
                } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                }
                $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$unorderedListStyle, self::$numUL);
                $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$orderedListStyle, self::$numOL);
            } else {
                $this->_wordNumberingT = $this->generateBaseWordNumbering();
                $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$unorderedListStyle, self::$numUL);
                $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$orderedListStyle, self::$numOL);
                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . rand(99999999, 999999999), 'numbering', 'numbering.xml');
                $this->generateOVERRIDE('/word/numbering.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.numbering+xml');
            }
            if (!file_exists($this->_baseTemplateFilesPath . '/word/endnotes.xml') || !file_exists($this->_baseTemplateFilesPath . '/word/footnotes.xml') || !file_exists($this->_baseTemplateFilesPath . '/word/comments.xml')) {
                $notesZip = new ZipArchive ();
                try {
                    $openNotesZip = $notesZip->open(PHPDOCX_BASE_TEMPLATE);
                    if ($openNotesZip !== true) {
                        throw new Exception('Error while opening the standard base template to extract the word/footnotes.xml  and word/endnotes.xml file');
                    }
                } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                }
                if (!file_exists($this->_baseTemplateFilesPath . '/word/footnotes.xml')) {
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/footnotes.xml');
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . rand(99999999, 999999999), 'footnotes', 'footnotes.xml');
                    $this->generateOVERRIDE('/word/footnotes.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.footnotes+xml');
                }
                if (!file_exists($this->_baseTemplateFilesPath . '/word/_rels/footnotes.xml.rels')) {
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/footnotes.xml.rels');
                }
                if (!file_exists($this->_baseTemplateFilesPath . '/word/endnotes.xml')) {
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/endnotes.xml');
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . rand(99999999, 999999999), 'endnotes', 'endnotes.xml');
                    $this->generateOVERRIDE('/word/endnotes.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.endnotes+xml');
                }
                if (!file_exists($this->_baseTemplateFilesPath . '/word/_rels/endnotes.xml.rels')) {
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/endnotes.xml.rels');
                }
                if (!file_exists($this->_baseTemplateFilesPath . '/word/comments.xml')) {
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/comments.xml');
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . rand(99999999, 999999999), 'comments', 'comments.xml');
                    $this->generateOVERRIDE('/word/comments.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.comments+xml');
                }
                if (!file_exists($this->_baseTemplateFilesPath . '/word/_rels/comments.xml.rels')) {
                    $notesZip->extractTo($this->_baseTemplateFilesPath, 'word/comments.xml.rels');
                }
            }
        }
        $this->modifyPageLayout($this->_phpdocxconfig ['settings'] ['paper_size']);
        $this->setLanguage($this->_phpdocxconfig ['settings'] ['language']);
    }

    public function __destruct() {
        
    }

    public function __toString() {
        $this->generateTemplateWordDocument();
//PhpdocxLogger::logger ( 'Get document template content.', 'debug' );
        return $this->_wordDocumentT;
    }

    public function setExtension($extension) {
        $this->_extension = $extension;
    }

    public function getExtension() {
        return $this->_extension;
    }

    public function setTemporaryDirectory($tempDir) {
        $this->_tempDir = $tempDir;
    }

    public function getTemporaryDirectory() {
        return $this->_tempDir;
    }

    public function setXmlContentTypes($xmlContentTypes) {
        $this->_contentTypeC = $xmlContentTypes;
    }

    public function getXmlContentTypes() {
        return $this->_contentTypeC;
    }

    public function setXmlRelsRels($xmlRelsRels) {
        $this->_relsRelsC = $xmlRelsRels;
    }

    public function getXmlRelsRels() {
        return $this->_relsRelsC;
    }

    public function setXmlDocPropsApp($xmlDocPropsApp) {
        $this->_docPropsAppC = $xmlDocPropsApp;
    }

    public function getXmlDocPropsApp() {
        return $this->_docPropsAppC;
    }

    public function setXmlDocPropsCore($xmlDocPropsCore) {
        $this->_docPropsCoreC = $xmlDocPropsCore;
    }

    public function getXmlDocPropsCore() {
        return $this->_docPropsCoreC;
    }

    public function setXmlDocPropsCustom($xmlDocPropsCustom) {
        $this->_docPropsCustomC = $xmlDocPropsCustom;
    }

    public function getXmlDocPropsCustom() {
        return $this->_docPropsCustomC;
    }

    public function setXmlWordDocument($xmlWordDocument) {
        $this->_wordDocumentC = $xmlWordDocument;
    }

    public function getXmlWordDocumentContent() {
        return $this->_wordDocumentC;
    }

    public function setXmlWordDocumentStyles($xmlWordDocumentStyles) {
        $this->_wordDocumentStyles = $xmlWordDocumentStyles;
    }

    public function getXmlWordDocumentStyles() {
        return $this->_wordDocumentStyles;
    }

    public function setXmlWordEndnotes($xmlWordEndnotes) {
        $this->_wordEndnotesC = $xmlWordEndnotes;
    }

    public function getXmlWordEndnotes() {
        return $this->_wordEndnotesC;
    }

    public function setXmlWordFontTable($xmlWordFontTable) {
        $this->_wordFontTableC = $xmlWordFontTable;
    }

    public function getXmlWordFontTable() {
        return $this->_wordFontTableC;
    }

    public function setXmlWordFooter1($xmlWordFooter) {
        $this->_wordFooterC = $xmlWordFooter;
    }

    public function getXmlWordFooter1() {
        return $this->_wordFooterC;
    }

    public function setXmlWordHeader1($xmlWordHeader) {
        $this->_wordHeaderC = $xmlWordHeader;
    }

    public function getXmlWordHeader1() {
        return $this->_wordHeaderC;
    }

    public function setXmlWordRelsDocumentRels($xmlWordRelsDocumentRels) {
        $this->_wordRelsDocumentRelsC = $xmlWordRelsDocumentRels;
    }

    public function getXmlWordRelsDocumentRels() {
        return $this->_wordRelsDocumentRelsC;
    }

    public function setXmlWordSettings($xmlWordSettings) {
        $this->_wordSettingsC = $xmlWordSettings;
    }

    public function getXmlWordSettings() {
        return $this->_wordSettingsC;
    }

    public function setXmlWordStyles($xmlWordStyles) {
        $this->_wordStylesC = $xmlWordStyles;
    }

    public function getXmlWordStyles() {
        return $this->_wordStylesC;
    }

    public function setXmlWordThemeTheme1($xmlWordThemeTheme) {
        $this->_wordThemeThemeC = $xmlWordThemeTheme;
    }

    public function getXmlWordThemeTheme1() {
        return $this->_wordThemeThemeC;
    }

    public function setXmlWordWebSettings($xmlWordWebSettings) {
        $this->_wordWebSettingsC = $xmlWordWebSettings;
    }

    public function getXml_Word_WebSettings() {
        return $this->_wordWebSettingsC;
    }

    public function addBackgroundImage($path) {
        $image = pathinfo($path);
        $extension = $image ['extension'];
        $imageName = $image ['filename'];
        $tempId = uniqid(true);
        $identifier = 'rId' . $tempId;
        $this->_background = '<w:background w:color="' . $this->_backgroundColor . '">
                      <v:background id="id_' . uniqid() . '" o:bwmode="white" o:targetscreensize="800,600">
                      <v:fill r:id="' . $identifier . '" o:title="tit_' . uniqid(true) . '" recolor="t" type="frame" />
                      </v:background></w:background>';
        $this->generateDEFAULT($extension, 'image/' . $extension);
        $backgroundImage = file_get_contents($path);
        if (!is_dir($this->_baseTemplateFilesPath . '/word/media')) {
            mkdir($this->_baseTemplateFilesPath . '/word/media');
        }
        $backgroundImageHandle = fopen($this->_baseTemplateFilesPath . '/word/media/img' . $tempId . '.' . $extension, "w+");
        $contents = fwrite($backgroundImageHandle, $backgroundImage);
        fclose($backgroundImageHandle);
        $relsImage = '<Relationship Id="' . $identifier . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/img' . $tempId . '.' . $extension . '" />';
        $relsNodeImage = $this->_wordRelsDocumentRelsT->createDocumentFragment();
        $relsNodeImage->appendXML($relsImage);
        $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNodeImage);
        try {
            $settings = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "r");
            $baseTemplateSettingsT = fread($settings, 1000000);
            fclose($settings);
            if ($baseTemplateSettingsT == '') {
                throw new Exception('Error while extracting settings.xml file from the base template to insert the background image');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $this->_wordSettingsT = new DOMDocument ();
        $this->_wordSettingsT->loadXML($baseTemplateSettingsT);
        $settingsImage = $this->_wordSettingsT->createDocumentFragment();
        $settingsImage->appendXML('<w:displayBackgroundShape xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" />');
        $this->_wordSettingsT->documentElement->appendChild($settingsImage);
        $newSettings = $this->_wordSettingsT->saveXML();
        $settingsHandle = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "w+");
        $contents = fwrite($settingsHandle, $newSettings);
        fclose($settingsHandle);
    }

    public function addBasicHTML($html = '<html><body></body></html>', $options = array()) {
        $this->embedHTML($html, $options);
    }

    public function addBookmark($options = array('type' => null, 'name' => null, 'rawWordML' => false)) {
        $type = $options ['type'];
        $name = $options ['name'];
        if (empty($type) || empty($name)) {
//PhpdocxLogger::logger ( 'The addBookmark method is lacking at least one required parameter', 'fatal' );
        }
        if ($type == 'start') {
            $bookmarkId = rand(9999999, 999999999);
            $bookmark = '<w:bookmarkStart w:id="' . $bookmarkId . '" w:name="' . $name . '" />';
            $this->_bookmarksIds [$name] = $bookmarkId;
        } else if ($type == 'end') {
            if (empty($this->_bookmarksIds [$name])) {
//PhpdocxLogger::logger ( 'You are trying to end a nonexisting bookmark', 'fatal' );
            }
            $bookmark = '<w:bookmarkEnd w:id="' . $this->_bookmarksIds [$name] . '" />';
            unset($this->_bookmarksIds [$name]);
        } else {
//PhpdocxLogger::logger ( 'The addBookmark type is incorrect', 'fatal' );
        }
//PhpdocxLogger::logger ( 'Adds a bookmark' . $type . ' to the Word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $bookmark;
        } else {
            $this->_wordDocumentC .= (string) $bookmark;
        }
    }

    public function addBreak($options = array('type' => 'line', 'rawWordML' => false)) {
        $break = CreatePage::getInstance();
        $break->generatePageBreak($options ['type']);
//PhpdocxLogger::logger ( 'Add break to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $break;
        } else {
            $this->_wordDocumentC .= (string) $break;
        }
    }

    public function addChart($options = array('rawWordML' => false)) {
//PhpdocxLogger::logger ( 'Create chart.', 'debug' );
        try {
            if (isset($options ['data']) && isset($options ['type'])) {
                self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Chart.', 'debug' );
                $type = $options ['type'];
                if (strpos($type, 'Chart') === false)
                    $type .= 'Chart';
                $graphic = CreateChartFactory::createObject($type);
                if ($graphic->createGraphic(self::$intIdWord, $options) != false) {
//PhpdocxLogger::logger ( 'Add chart word/charts/chart' . self::$intIdWord . '.xml to DOCX.', 'info' );
                    $this->_zipDocx->addFromString('word/charts/chart' . self::$intIdWord . '.xml', $graphic->getXmlChart());
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'chart', 'charts/chart' . self::$intIdWord . '.xml');
                    $this->generateDEFAULT('xlsx', 'application/octet-stream');
                    $this->generateOVERRIDE('/word/charts/chart' . self::$intIdWord . '.xml', 'application/vnd.openxmlformats-officedocument.' . 'drawingml.chart+xml');
                } else {
                    throw new Exception('There was an error related to the chart.');
                }
                $excel = $graphic->getXlsxType();
                $this->_tempFileXLSX [self::$intIdWord] = tempnam($this->_tempDir, 'documentxlsx');
                if ($excel->createXlsx($this->_tempFileXLSX [self::$intIdWord], $options ['data']) != false) {
                    $this->_zipDocx->addFile($this->_tempFileXLSX [self::$intIdWord], 'word/embeddings/datos' . self::$intIdWord . '.xlsx');
                    $chartRels = CreateChartRels::getInstance();
                    $chartRels->createRelationship(self::$intIdWord);
                    $this->_zipDocx->addFromString('word/charts/_rels/chart' . self::$intIdWord . '.xml.rels', (string) $chartRels);
                }
                if (isset($options ['rawWordML']) && $options ['rawWordML']) {
                    return (string) $graphic;
                } else {
                    $this->_wordDocumentC .= (string) $graphic;
                }
            } else {
                throw new Exception('Images must have "data" and "type" values.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addComment($options = array('rawWordML' => false)) {
        $id = rand(9999, 32766);
        $idBookmark = uniqid(true);
        if ($options ['textComment'] instanceof WordMLFragment) {
            $commentBase = '<w:comment w:id="' . $id . '"';
            if (isset($options ['initials'])) {
                $commentBase .= ' w:initials="' . $options ['initials'] . '"';
            }
            if (isset($options ['author'])) {
                $commentBase .= ' w:author="' . $options ['author'] . '"';
            }
            if (isset($options ['date'])) {
                $commentBase .= ' w:date="' . date("Y-m-d\TH:i:s\Z", strtotime($options ['date'])) . '"';
            }
            $commentBase .= ' xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                xmlns:v="urn:schemas-microsoft-com:vml"
                xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                xmlns:w10="urn:schemas-microsoft-com:office:word"
                xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                >';
            $commentBase .= $this->parseWordMLNote('comment', $options ['textComment'], array(), array());
            $commentbase .= '<w:bookmarkStart w:id="' . $idBookmark . '" w:name="_GoBack"/><w:bookmarkEnd w:id="' . $idBookmark . '"/>';
            $commentBase .= '</w:comment>';
        } else {
            $commentBase = '<w:comment w:id="' . $id . '" ';
            $commentBase = '<w:comment w:id="' . $id . '"';
            if (isset($options ['initials'])) {
                $commentBase .= ' w:initials="' . $options ['initials'] . '"';
            }
            if (isset($options ['author'])) {
                $commentBase .= ' w:author="' . $options ['author'] . '"';
            }
            if (isset($options ['date']) && ($options ['date'] instanceof date)) {
                $commentBase .= ' w:date="' . date("Y-m-d\TH:i:s\Z", strtotime($options ['date'])) . '"';
            }
            $commentBase .= ' xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" ><w:p>
                <w:pPr><w:pStyle w:val="commentTextPHPDOCX"/></w:pPr>
                <w:r><w:rPr><w:rStyle w:val="commentReferencePHPDOCX"/></w:rPr><w:annotationRef/></w:r>';
            $commentBase .= '<w:r><w:t xml:space="preserve">' . $options ['textComment'] . '</w:t></w:r></w:p>';
            $commentbase .= '<w:bookmarkStart w:id="' . $idBookmark . '" w:name="_GoBack"/><w:bookmarkEnd w:id="' . $idBookmark . '"/>';
            $commentBase .= '</w:comment>';
        }
        if (!is_array($options ['textDocument'])) {
            $options ['textDocument'] = array(
                'text' => $options ['textDocument']
            );
        }
        $textOptions = $options ['textDocument'];
        $textOptions ['rawWordML'] = true;
        $text = $textOptions ['text'];
        $commentDocument = $this->addText($text, $textOptions);
        $commentStart = '</w:pPr><w:commentRangeStart w:id="' . $id . '"/>';
        $commentEnd .= '<w:commentRangeEnd w:id="' . $id . '"/>
                         <w:r>
                            <w:rPr>
                                <w:rStyle w:val="CommentReference"/>
                            </w:rPr>
                        <w:commentReference w:id="' . $id . '"/></w:r></w:p>';
        $commentDocument = preg_replace('/__[A-Z]+__/', '', $commentDocument);
        $commentDocument = str_replace('</w:pPr>', $commentStart, $commentDocument);
        $commentDocument = str_replace('</w:p>', $commentEnd, $commentDocument);
        $filename = $this->_baseTemplateFilesPath . '/word/comments.xml';
        $handle = fopen($filename, "r");
        $contents = fread($handle, 999999);
        fclose($handle);
        $this->_wordCommentsT = new DOMDocument ();
        $this->_wordCommentsT->loadXML($contents);
        $tempNode = $this->_wordCommentsT->createDocumentFragment();
        $tempNode->appendXML($commentBase);
        $this->_wordCommentsT->documentElement->appendChild($tempNode);
        $commentHandler = fopen($this->_baseTemplateFilesPath . '/word/comments.xml', "w+");
        fwrite($commentHandler, $this->_wordCommentsT->saveXML());
        fclose($commentHandler);
//PhpdocxLogger::logger ( 'Add comment to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $commentDocument;
        } else {
            $this->_wordDocumentC .= (string) $commentDocument;
        }
    }

    public function addDateAndHour($options = array('dateFormat' => 'dd/MM/yyyy H:mm:ss', 'rawWordML' => false)) {
        if (!isset($options ['dateFormat'])) {
            $options ['dateFormat'] = 'dd/MM/yyyy H:mm:ss';
        }
        $textOptions = $options;
        $textOptions ['rawWordML'] = true;
        $date = $this->addText('date', $textOptions);
        $date = preg_replace('/__[A-Z]+__/', '', $date);
        $dateRef = '<?xml version="1.0" encoding="UTF-8" ?>' . $date;
        $dateRef = str_replace('<w:p>', '<w:p xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">', $dateRef);
        $dateDOM = new DOMDocument ();
        $dateDOM->loadXML($dateRef);
        $pPrNodes = $dateDOM->getElementsByTagName('pPr');
        if ($pPrNodes->length > 0) {
            $pPrContent = $dateDOM->saveXML($pPrNodes->item(0));
        } else {
            $pPrContent = '';
        }
        $rPrNodes = $dateDOM->getElementsByTagName('rPr');
        if ($rPrNodes->length > 0) {
            $rPrContent = $dateDOM->saveXML($rPrNodes->item(0));
        } else {
            $rPrContent = '';
        }
        if ($pPrContent != '') {
            $pPrContent = str_replace('</w:pPr>', $rPrContent . '</w:pPr>', $pPrContent);
        } else {
            $pPrContent = '<w:pPr>' . $rPrContent . '</w:pPr>';
        }
        $runs = '<w:r>' . $rPrContent . '<w:fldChar w:fldCharType="begin" /></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:instrText xml:space="preserve">TIME \@ &quot;' . $options ['dateFormat'] . '&quot;</w:instrText></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:fldChar w:fldCharType="separate" /></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:t>date</w:t></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:fldChar w:fldCharType="end" /></w:r>';
        $date = '<w:p>' . $pPrContent . $runs . '</w:p>';
//PhpdocxLogger::logger ( 'Add a date to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $date;
        } else {
            $this->_wordDocumentC .= (string) $date;
        }
    }

    public function addDOCX($options = array('matchSource' => true, 'rawWordML' => false, 'preprocess' => false)) {
        try {
            if ($this->_compatibilityMode) {
                throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            if (file_exists($options ['pathDOCX'])) {
                if ($options ['preprocess']) {
                    $this->preprocessDocx($options ['pathDOCX']);
                }
                $wordDOCX = EmbedDOCX::getInstance();
                if (isset($options ['matchSource']) && $options ['matchSource'] === false) {
                    $wordDOCX->embed(false);
                } else {
                    $wordDOCX->embed(true);
                }
//PhpdocxLogger::logger ( 'Add DOCX file to word document.', 'info' );
                $this->_zipDocx->addFile($options ['pathDOCX'], 'word/docx' . $wordDOCX->getId() . '.zip');
                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rDOCXId' . $wordDOCX->getId(), 'aFChunk', 'docx' . $wordDOCX->getId() . '.zip', 'TargetMode="Internal"');
                $this->generateDEFAULT('zip', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
                if (isset($options ['rawWordML']) && $options ['rawWordML']) {
                    return (string) $wordDOCX . '<w:p />';
                } else {
                    $this->_wordDocumentC .= (string) $wordDOCX;
                }
            } else {
                throw new Exception('File does not exist.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addElement($type, $params = '') {
        $type = str_replace('Chart', 'Graphic', $type);
        $type = str_replace('add', 'Create', $type);
        if ($type == 'CreateGraphic') {
            if (strpos($params ['type'], 'Chart') === false)
                $params ['type'] .= 'Chart';
            $element = CreateChartFactory::createObject($params ['type']);
        } else {
            $element = CreateFactory::createObject($type);
        }
        $type = str_replace('Create', 'init', $type);
        $element->$type($params);
        if ($type == 'initImage') {
//PhpdocxLogger::logger ( 'Create embedded image.', 'debug' );
            try {
                if (isset($params ['name']) && file_exists($params ['name'])) {
                    $attrImages = getimagesize($params ['name']);
                    try {
                        if ($attrImages ['mime'] == 'image/jpg' || $attrImages ['mime'] == 'image/jpeg' || $attrImages ['mime'] == 'image/png' || $attrImages ['mime'] == 'image/gif') {
                            self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Embedded image.', 'debug' );
                            $type = str_replace('add', 'Create', $type);
                            $dir = $this->parsePath($params ['name']);
                            $element->setRId(self::$intIdWord);
//PhpdocxLogger::logger ( 'Add image ' . $params ['name'] . ' to DOCX.', 'info' );
                            $this->_zipDocx->addFile($params ['name'], 'word/media/image' . self::$intIdWord . '.' . $dir ['extension']);
                            $this->generateDEFAULT($dir ['extension'], $attrImages ['mime']);
//PhpdocxLogger::logger ( 'Add a new relationship related to image ' . $params ['name'] . ' .', 'debug' );
                            $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'image', 'media/image' . self::$intIdWord . '.' . $dir ['extension']);
                        } else {
                            throw new Exception('Image format is not supported.');
                        }
                    } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                    }
                } else {
                    throw new Exception('Image does not exist.');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
        } elseif ($type == 'initGraphic' || $type == 'initChart') {
//PhpdocxLogger::logger ( 'Create embedded chart.', 'debug' );
            try {
                if (isset($params ['data']) && isset($params ['type'])) {
                    self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Embedded chart.', 'debug' );
                    $element->setRId(self::$intIdWord);
//PhpdocxLogger::logger ( 'Add chart ' . 'word/charts/chart' . self::$intIdWord . '.xml to DOCX.', 'info' );
                    $this->_zipDocx->addFromString('word/charts/chart' . self::$intIdWord . '.xml', $element->createEmbeddedXmlChart());
//PhpdocxLogger::logger ( 'Add a new relationship related to chart.', 'debug' );
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'chart', 'charts/chart' . self::$intIdWord . '.xml');
                    $this->generateDEFAULT('xlsx', 'application/octet-stream');
                    $this->generateOVERRIDE('/word/charts/chart' . self::$intIdWord . '.xml', 'application/vnd.openxmlformats-officedocument' . '.drawingml.chart+xml');
                    $excel = $element->getXlsxType();
                    $this->_tempFileXLSX [self::$intIdWord] = tempnam($this->_tempDir, 'documentxlsx');
                    if ($excel->createXlsx($this->_tempFileXLSX [self::$intIdWord], $params ['data'], $params ['type']) != false) {
                        $this->_zipDocx->addFile($this->_tempFileXLSX [self::$intIdWord], 'word/embeddings/datos' . self::$intIdWord . '.xlsx');
                        $chartRels = CreateChartRels::getInstance();
                        $chartRels->createRelationship(self::$intIdWord);
//PhpdocxLogger::logger ( 'Add chart ' . 'word/charts/_rels/chart' . self::$intIdWord . '.xml.rels to DOCX.', 'info' );
                        $this->_zipDocx->addFromString('word/charts/_rels/chart' . self::$intIdWord . '.xml.rels', (string) $chartRels);
                    }
                } else {
                    throw new Exception('Charts must have "data" and "type" values.');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
        }
        return $element;
    }

    public function addEndnote($options = array('rawWordML' => false)) {
        $id = rand(9999, 32766);
        if ($options ['textEndnote'] instanceof WordMLFragment) {
            $endnoteBase = '<w:endnote w:id="' . $id . '"
                xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                xmlns:v="urn:schemas-microsoft-com:vml"
                xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                xmlns:w10="urn:schemas-microsoft-com:office:word"
                xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                >';
            $endnoteBase .= $this->parseWordMLNote('endnote', $options ['textEndnote'], $options ['endnoteMark'], $options ['referenceMark']);
            $endnoteBase .= '</w:endnote>';
        } else {
            $endnoteBase = '<w:endnote w:id="' . $id . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:p>
                <w:pPr><w:pStyle w:val="endnoteTextPHPDOCX"/></w:pPr>
                <w:r><w:rPr><w:rStyle w:val="endnoteReferencePHPDOCX"/>';
            if (isset($options ['referenceMark'] ['font'])) {
                $endnoteBase .= '<w:rFonts w:ascii="' . $options ['referenceMark'] ['font'] . '" w:hAnsi="' . $options ['referenceMark'] ['font'] . '" w:cs="' . $options ['referenceMark'] ['font'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['b'])) {
                $endnoteBase .= '<w:b w:val="' . $options ['referenceMark'] ['b'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['i'])) {
                $endnoteBase .= '<w:i w:val="' . $options ['referenceMark'] ['i'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['color'])) {
                $endnoteBase .= '<w:color w:val="' . $options ['referenceMark'] ['color'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['sz'])) {
                $endnoteBase .= '<w:sz w:val="' . (2 * $options ['referenceMark'] ['sz']) . '"/>';
                $endnoteBase .= '<w:szCs w:val="' . (2 * $options ['referenceMark'] ['sz']) . '"/>';
            }
            $endnoteBase .= '</w:rPr>';
            if (isset($options ['endnoteMark'] ['customMark'])) {
                $endnoteBase .= '<w:t>' . $options ['endnoteMark'] ['customMark'] . '</w:t>';
            } else {
                $endnoteBase .= '<w:endnoteRef/>';
            }
            $endnoteBase .= '</w:r>
                <w:r><w:t xml:space="preserve">' . $options ['textEndnote'] . '</w:t></w:r></w:p>
                </w:endnote>';
        }
        if (!is_array($options ['textDocument'])) {
            $options ['textDocument'] = array(
                'text' => $options ['textDocument']
            );
        }
        $textOptions = $options ['textDocument'];
        $textOptions ['rawWordML'] = true;
        $text = $textOptions ['text'];
        $endnoteDocument = $this->addText($text, $textOptions);
        $endnoteMark = '<w:r><w:rPr><w:rStyle w:val="endnoteReferencePHPDOCX" />';
        if (isset($options ['endnoteMark'] ['font'])) {
            $endnoteMark .= '<w:rFonts w:ascii="' . $options ['endnoteMark'] ['font'] . '" w:hAnsi="' . $options ['endnoteMark'] ['font'] . '" w:cs="' . $options ['endnoteMark'] ['font'] . '"/>';
        }
        if (isset($options ['endnoteMark'] ['b'])) {
            $endnoteMark .= '<w:b w:val="' . $options ['endnoteMark'] ['b'] . '"/>';
        }
        if (isset($options ['endnoteMark'] ['i'])) {
            $endnoteMark .= '<w:i w:val="' . $options ['endnoteMark'] ['i'] . '"/>';
        }
        if (isset($options ['endnoteMark'] ['color'])) {
            $endnoteMark .= '<w:color w:val="' . $options ['endnoteMark'] ['color'] . '"/>';
        }
        if (isset($options ['endnoteMark'] ['sz'])) {
            $endnoteMark .= '<w:sz w:val="' . (2 * $options ['endnoteMark'] ['sz']) . '"/>';
            $endnoteMark .= '<w:szCs w:val="' . (2 * $options ['endnoteMark'] ['sz']) . '"/>';
        }
        $endnoteMark .= '</w:rPr><w:endnoteReference w:id="' . $id . '" ';
        if (isset($options ['endnoteMark'] ['customMark'])) {
            $endnoteMark .= 'w:customMarkFollows="1"/><w:t>' . $options ['endnoteMark'] ['customMark'] . '</w:t>';
        } else {
            $endnoteMark .= '/>';
        }
        $endnoteMark .= '</w:r></w:p>';
        $endnoteDocument = str_replace('</w:p>', $endnoteMark, $endnoteDocument);
        $endnoteDocument = preg_replace('/__[A-Z]+__/', '', $endnoteDocument);
        $filename = $this->_baseTemplateFilesPath . '/word/endnotes.xml';
        $handle = fopen($filename, "r");
        $contents = fread($handle, 999999);
        fclose($handle);
        $this->_wordEndnotesT = new DOMDocument ();
        $this->_wordEndnotesT->loadXML($contents);
        $tempNode = $this->_wordEndnotesT->createDocumentFragment();
        $tempNode->appendXML($endnoteBase);
        $this->_wordEndnotesT->documentElement->appendChild($tempNode);
        $endnoteHandler = fopen($this->_baseTemplateFilesPath . '/word/endnotes.xml', "w+");
        fwrite($endnoteHandler, $this->_wordEndnotesT->saveXML());
        fclose($endnoteHandler);
//PhpdocxLogger::logger ( 'Add endnote to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $endnoteDocument;
        } else {
            $this->_wordDocumentC .= (string) $endnoteDocument;
        }
    }

    public function addFont($fonts) {
        $font = CreateFontTable::getInstance();
        $font->createFont($fonts);
//PhpdocxLogger::logger ( 'Add font to fontable document.', 'info' );
        $this->_wordFontTableC .= (string) $font;
    }

    public function addFooter($footers) {
        $this->footerAdded = true;
        $this->removeFooters();
        foreach ($footers as $key => $value) {
            if ($value instanceof WordMLFragment) {
                $this->_wordFooterT [$key] = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
                                            <w:ftr
                                                xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006"
                                                xmlns:o="urn:schemas-microsoft-com:office:office"
                                                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                                                xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                                                xmlns:v="urn:schemas-microsoft-com:vml"
                                                xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                                                xmlns:w10="urn:schemas-microsoft-com:office:word"
                                                xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                                                xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml">';
                $this->_wordFooterT [$key] .= (string) $value;
                $this->_wordFooterT [$key] .= '</w:ftr>';
                $this->_wordFooterT [$key] = preg_replace('/__[A-Z]+__/', '', $this->_wordFooterT [$key]);
                $relationships = '';
                if (isset($this->_relsHeaderFooterImage [$key . 'Footer'])) {
                    foreach ($this->_relsHeaderFooterImage [$key . 'Footer'] as $key2 => $value2) {
                        $relationships .= '<Relationship Id="' . $value2 ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/img' . $value2 ['rId'] . '.' . $value2 ['extension'] . '" />';
                    }
                }
                if (isset($this->_relsHeaderFooterExternalImage [$key . 'Footer'])) {
                    foreach ($this->_relsHeaderFooterExternalImage [$key . 'Footer'] as $key2 => $value2) {
                        $relationships .= '<Relationship Id="' . $value2 ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="' . $value2 ['url'] . '" TargetMode="External" />';
                    }
                }
                if (isset($this->_relsHeaderFooterLink [$key . 'Footer'])) {
                    foreach ($this->_relsHeaderFooterLink [$key . 'Footer'] as $key2 => $value2) {
                        $relationships .= '<Relationship Id="' . $value2 ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink" Target="' . $value2 ['url'] . '" TargetMode="External" />';
                    }
                }
                if ($relationships != '') {
                    $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
                    $rels .= $relationships;
                    $rels .= '</Relationships>';
                }
                $footerHandler = fopen($this->_baseTemplateFilesPath . '/word/' . $key . 'Footer.xml', 'w+');
                fwrite($footerHandler, $this->_wordFooterT [$key]);
                fclose($footerHandler);
                if (isset($rels)) {
                    $footerRelsHandler = fopen($this->_baseTemplateFilesPath . '/word/_rels/' . $key . 'Footer.xml.rels', 'w+');
                    fwrite($footerRelsHandler, $rels);
                    fclose($footerRelsHandler);
                }
                $newId = uniqid(true);
                $newFooterNode = '<Relationship Id="rId';
                $newFooterNode .= $newId . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer"';
                $newFooterNode .= ' Target="' . $key . 'Footer.xml" />';
                $newNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                $newNode->appendXML($newFooterNode);
                $baseNode = $this->_wordRelsDocumentRelsT->documentElement;
                $baseNode->appendChild($newNode);
                $newSectNode = '<w:footerReference w:type="' . $key . '" r:id="rId' . $newId . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>';
                $sectNode = $this->_sectPr->createDocumentFragment();
                $sectNode->appendXML($newSectNode);
                $refNode = $this->_sectPr->documentElement->childNodes->item(0);
                $refNode->parentNode->insertBefore($sectNode, $refNode);
                if ($key == 'first') {
                    $this->generateTitlePg();
                } else if ($key == 'even') {
                    $this->generateSetting('w:evenAndOddHeaders');
                }
                $this->generateOVERRIDE('/word/' . $key . 'Footer.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.' . 'footer+xml');
                $this->_relsFooter [] = $key . 'Footer.xml';
                $this->_relsHeaderFooterImage [$key . 'Footer'] = array();
                $this->_relsHeaderFooterExternalImage [$key . 'Footer'] = array();
                $this->_relsHeaderFooterLink [$key . 'Footer'] = array();
            } else {
//PhpdocxLogger::logger ( 'The footer contents must be WordML fragments', 'fatal' );
            }
        }
    }

    public function addFootnote($options = array('rawWordML' => false)) {
        $id = rand(9999, 32766);
        if ($options ['textFootnote'] instanceof WordMLFragment) {
            $footnoteBase = '<w:footnote w:id="' . $id . '"
                xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                xmlns:v="urn:schemas-microsoft-com:vml"
                xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                xmlns:w10="urn:schemas-microsoft-com:office:word"
                xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                >';
            $footnoteBase .= $this->parseWordMLNote('footnote', $options ['textFootnote'], $options ['footnoteMark'], $options ['referenceMark']);
            $footnoteBase .= '</w:footnote>';
        } else {
            $footnoteBase = '<w:footnote w:id="' . $id . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" ><w:p>
                <w:pPr><w:pStyle w:val="footnoteTextPHPDOCX"/></w:pPr>
                <w:r><w:rPr><w:rStyle w:val="footnoteReferencePHPDOCX"/>';
            if (isset($options ['referenceMark'] ['font'])) {
                $footnoteBase .= '<w:rFonts w:ascii="' . $options ['referenceMark'] ['font'] . '" w:hAnsi="' . $options ['referenceMark'] ['font'] . '" w:cs="' . $options ['referenceMark'] ['font'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['b'])) {
                $footnoteBase .= '<w:b w:val="' . $options ['referenceMark'] ['b'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['i'])) {
                $footnoteBase .= '<w:i w:val="' . $options ['referenceMark'] ['i'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['color'])) {
                $footnoteBase .= '<w:color w:val="' . $options ['referenceMark'] ['color'] . '"/>';
            }
            if (isset($options ['referenceMark'] ['sz'])) {
                $footnoteBase .= '<w:sz w:val="' . (2 * $options ['referenceMark'] ['sz']) . '"/>';
                $footnoteBase .= '<w:szCs w:val="' . (2 * $options ['referenceMark'] ['sz']) . '"/>';
            }
            $footnoteBase .= '</w:rPr>';
            if (isset($options ['footnoteMark'] ['customMark'])) {
                $footnoteBase .= '<w:t>' . $options ['footnoteMark'] ['customMark'] . '</w:t>';
            } else {
                $footnoteBase .= '<w:footnoteRef/>';
            }
            $footnoteBase .= '</w:r>
                <w:r><w:t xml:space="preserve">' . $options ['textFootnote'] . '</w:t></w:r></w:p>
                </w:footnote>';
        }
        if (!is_array($options ['textDocument'])) {
            $options ['textDocument'] = array(
                'text' => $options ['textDocument']
            );
        }
        $textOptions = $options ['textDocument'];
        $textOptions ['rawWordML'] = true;
        $text = $textOptions ['text'];
        $footnoteDocument = $this->addText($text, $textOptions);
        $footnoteMark = '<w:r><w:rPr><w:rStyle w:val="footnoteReferencePHPDOCX" />';
        if (isset($options ['footnoteMark'] ['font'])) {
            $footnoteMark .= '<w:rFonts w:ascii="' . $options ['footnoteMark'] ['font'] . '" w:hAnsi="' . $options ['footnoteMark'] ['font'] . '" w:cs="' . $options ['footnoteMark'] ['font'] . '"/>';
        }
        if (isset($options ['footnoteMark'] ['b'])) {
            $footnoteMark .= '<w:b w:val="' . $options ['footnoteMark'] ['b'] . '"/>';
        }
        if (isset($options ['footnoteMark'] ['i'])) {
            $footnoteMark .= '<w:i w:val="' . $options ['footnoteMark'] ['i'] . '"/>';
        }
        if (isset($options ['footnoteMark'] ['color'])) {
            $footnoteMark .= '<w:color w:val="' . $options ['footnoteMark'] ['color'] . '"/>';
        }
        if (isset($options ['footnoteMark'] ['sz'])) {
            $footnoteMark .= '<w:sz w:val="' . (2 * $options ['footnoteMark'] ['sz']) . '"/>';
            $footnoteMark .= '<w:szCs w:val="' . (2 * $options ['footnoteMark'] ['sz']) . '"/>';
        }
        $footnoteMark .= '</w:rPr><w:footnoteReference w:id="' . $id . '" ';
        if (isset($options ['footnoteMark'] ['customMark'])) {
            $footnoteMark .= 'w:customMarkFollows="1"/><w:t>' . $options ['footnoteMark'] ['customMark'] . '</w:t>';
        } else {
            $footnoteMark .= '/>';
        }
        $footnoteMark .= '</w:r></w:p>';
        $footnoteDocument = str_replace('</w:p>', $footnoteMark, $footnoteDocument);
        $footnoteDocument = preg_replace('/__[A-Z]+__/', '', $footnoteDocument);
        $filename = $this->_baseTemplateFilesPath . '/word/footnotes.xml';
        $handle = fopen($filename, "r");
        $contents = fread($handle, 999999);
        fclose($handle);
        $this->_wordFootnotesT = new DOMDocument ();
        $this->_wordFootnotesT->loadXML($contents);
        $tempNode = $this->_wordFootnotesT->createDocumentFragment();
        $tempNode->appendXML($footnoteBase);
        $this->_wordFootnotesT->documentElement->appendChild($tempNode);
        $footnoteHandler = fopen($this->_baseTemplateFilesPath . '/word/footnotes.xml', "w+");
        fwrite($footnoteHandler, $this->_wordFootnotesT->saveXML());
        fclose($footnoteHandler);
//PhpdocxLogger::logger ( 'Add footnote to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $footnoteDocument;
        } else {
            $this->_wordDocumentC .= (string) $footnoteDocument;
        }
    }

    public function addFormElement($type, $options = array('rawWordML' => false)) {
        $formElementTypes = array(
            'textfield',
            'checkbox',
            'select'
        );
        if (!in_array($type, $formElementTypes)) {
//PhpdocxLogger::logger ( 'The chosen form element type is not available', 'fatal' );
        }
        $formElementBase = CreateText::getInstance();
        $ParagraphOptions = $options;
        $ParagraphOptions ['rawWordML'] = true;
        $formElementBase->createText(array(
            array(
                'text' => '__formElement__'
            )
                ), $ParagraphOptions);
        $formElement = CreateFormElement::getInstance();
        $formElement->createFormElement($type, $options, (string) $formElementBase);
//PhpdocxLogger::logger ( 'Add form element to Word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $formElement;
        } else {
            $this->_wordDocumentC .= (string) $formElement;
        }
    }

    public function addGraphic($dats) {
//PhpdocxLogger::logger ( 'Create chart.', 'debug' );
        try {
            if (isset($dats ['data']) && isset($dats ['type'])) {
                self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Chart.', 'debug' );
                $graphic = CreateGraphic::getInstance();
                if ($graphic->createGraphic(self::$intIdWord, $dats) != false) {
//PhpdocxLogger::logger ( 'Add chart word/charts/chart' . self::$intIdWord . '.xml to DOCX.', 'info' );
                    $this->_zipDocx->addFromString('word/charts/chart' . self::$intIdWord . '.xml', $graphic->getXmlChart());
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'chart', 'charts/chart' . self::$intIdWord . '.xml');
                    $this->_wordDocumentC .= (string) $graphic;
                    $this->generateDEFAULT('xlsx', 'application/octet-stream');
                    $this->generateOVERRIDE('/word/charts/chart' . self::$intIdWord . '.xml', 'application/vnd.openxmlformats-officedocument.' . 'drawingml.chart+xml');
                } else {
                    throw new Exception('There was an error related to the chart.');
                }
                $excel = CreateXlsx::getInstance();
                $this->_tempFileXLSX [self::$intIdWord] = tempnam($this->_tempDir, 'documentxlsx');
                if ($excel->createXlsx($this->_tempFileXLSX [self::$intIdWord], $dats ['data'], $dats ['type']) != false) {
                    $this->_zipDocx->addFile($this->_tempFileXLSX [self::$intIdWord], 'word/embeddings/datos' . self::$intIdWord . '.xlsx');
                    $chartRels = CreateChartRels::getInstance();
                    $chartRels->createRelationship(self::$intIdWord);
                    $this->_zipDocx->addFromString('word/charts/_rels/chart' . self::$intIdWord . '.xml.rels', (string) $chartRels);
                }
            } else {
                throw new Exception('Images must have "data" and "type" values.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addGraphicImg($options = array('rawWordML' => false)) {
//PhpdocxLogger::logger ( 'Create image chart.', 'debug' );
        try {
            if (isset($options ['data']) && isset($options ['type'])) {
                $graphic = CreateGraphicImg::getInstance();
                if ($graphic->createGraphicImg($options)) {
                    
                } else {
                    throw new Exception('Unsupported chart type.');
                }
            } else {
                throw new Exception('Images must have "data" and "type" values.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addGraphicTemplate($options) {
        try {
            if (isset($options ['data']) && isset($options ['type'])) {
                self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Image template.', 'debug' );
                $type = $options ['type'];
                if (strpos($type, 'Chart') === false)
                    $type .= 'Chart';
                $graphic = CreateChartFactory::createObject($type);
                if ($graphic->createGraphic(self::$intIdWord, $options) != false) {
                    $this->graphicTemplate ['arrChartXML'] [self::$intIdWord] = $graphic->getXmlChart();
                    $this->graphicTemplate ['arrRelationships'] [self::$intIdWord] = $this->generateRELATIONSHIPTemplate('rId' . self::$intIdWord, 'chart', 'charts/chart' . self::$intIdWord . '.xml');
                    $this->graphicTemplate ['arrGraphics'] [self::$intIdWord] = (string) $graphic;
                    $this->graphicTemplate ['arrGenerateDEFAULT'] [self::$intIdWord] = '<Default Extension="' . 'xlsx' . '" ContentType="' . 'application/octet-stream' . '"> </Default>';
                    $this->graphicTemplate ['arrGenerateOVERRIDE'] [self::$intIdWord] = '<Override PartName="' . '/word/charts/chart' . self::$intIdWord . '.xml' . '" ContentType="' . 'application/vnd.openxmlformats-officedocument.' . 'drawingml.chart+xml' . '"> </Override>';
                } else {
                    throw new Exception('There was an error related to the chart.');
                }
                $excel = $graphic->getXlsxType();
                $this->_tempFileXLSX [self::$intIdWord] = tempnam($this->_tempDir, 'documentxlsx');
                if ($excel->createXlsx($this->_tempFileXLSX [self::$intIdWord], $options ['data'], $options ['type']) != false) {
                    $this->fileGraphicTemplate ['datos' . self::$intIdWord . '.xlsx'] = 'word/embeddings/datos' . self::$intIdWord . '.xlsx';
                    $objChartRels = CreateChartRels::getInstance();
                    $objChartRels->createRelationship(self::$intIdWord);
                    $this->fileGraphicTemplate ['word/charts/_rels/chart' . self::$intIdWord . '.xml.rels'] = (string) $objChartRels;
                }
            } else {
                throw new Exception('Charts must have "data" and "type" values.
                ');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addHeader($headers) {
        $this->headerAdded = true;
        $this->removeHeaders();
        foreach ($headers as $key => $value) {
            if ($value instanceof WordMLFragment) {
                $this->_wordHeaderT [$key] = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
                                            <w:hdr
                                                xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006"
                                                xmlns:o="urn:schemas-microsoft-com:office:office"
                                                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                                                xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                                                xmlns:v="urn:schemas-microsoft-com:vml"
                                                xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                                                xmlns:w10="urn:schemas-microsoft-com:office:word"
                                                xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                                                xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml">';
                $this->_wordHeaderT [$key] .= (string) $value;
                $this->_wordHeaderT [$key] .= '</w:hdr>';
                $this->_wordHeaderT [$key] = preg_replace('/__[A-Z]+__/', '', $this->_wordHeaderT [$key]);
                $relationships = '';
                if (isset($this->_relsHeaderFooterImage [$key . 'Header'])) {
                    foreach ($this->_relsHeaderFooterImage [$key . 'Header'] as $key2 => $value2) {
                        $relationships .= '<Relationship Id="' . $value2 ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/img' . $value2 ['rId'] . '.' . $value2 ['extension'] . '" />';
                    }
                }
                if (isset($this->_relsHeaderFooterExternalImage [$key . 'Header'])) {
                    foreach ($this->_relsHeaderFooterExternalImage [$key . 'Header'] as $key2 => $value2) {
                        $relationships .= '<Relationship Id="' . $value2 ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="' . $value2 ['url'] . '" TargetMode="External" />';
                    }
                }
                if (isset($this->_relsHeaderFooterLink [$key . 'Header'])) {
                    foreach ($this->_relsHeaderFooterLink [$key . 'Header'] as $key2 => $value2) {
                        $relationships .= '<Relationship Id="' . $value2 ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink" Target="' . $value2 ['url'] . '" TargetMode="External" />';
                    }
                }
                if ($relationships != '') {
                    $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
                    $rels .= $relationships;
                    $rels .= '</Relationships>';
                }
                $headerHandler = fopen($this->_baseTemplateFilesPath . '/word/' . $key . 'Header.xml', 'w+');
                fwrite($headerHandler, $this->_wordHeaderT [$key]);
                fclose($headerHandler);
                if (isset($rels)) {
                    $headerRelsHandler = fopen($this->_baseTemplateFilesPath . '/word/_rels/' . $key . 'Header.xml.rels', 'w+');
                    fwrite($headerRelsHandler, $rels);
                    fclose($headerRelsHandler);
                }
                $newId = uniqid(true);
                $newHeaderNode = '<Relationship Id="rId';
                $newHeaderNode .= $newId . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header"';
                $newHeaderNode .= ' Target="' . $key . 'Header.xml" />';
                $newNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                $newNode->appendXML($newHeaderNode);
                $baseNode = $this->_wordRelsDocumentRelsT->documentElement;
                $baseNode->appendChild($newNode);
                $newSectNode = '<w:headerReference w:type="' . $key . '" r:id="rId' . $newId . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>';
                $sectNode = $this->_sectPr->createDocumentFragment();
                $sectNode->appendXML($newSectNode);
                $refNode = $this->_sectPr->documentElement->childNodes->item(0);
                $refNode->parentNode->insertBefore($sectNode, $refNode);
                if ($key == 'first') {
                    $this->generateTitlePg();
                } else if ($key == 'even') {
                    $this->generateSetting('w:evenAndOddHeaders');
                }
                $this->generateOVERRIDE('/word/' . $key . 'Header.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.' . 'header+xml');
                $this->_relsHeader [] = $key . 'Header.xml';
                $this->_relsHeaderFooterImage [$key . 'Header'] = array();
                $this->_relsHeaderFooterExternalImage [$key . 'Header'] = array();
                $this->_relsHeaderFooterLink [$key . 'Header'] = array();
            } else {
//PhpdocxLogger::logger ( 'The header contents must be WordML fragments', 'fatal' );
            }
        }
    }

    public function addHeading($text, $level = 1, $options = array('rawWordML' => false)) {
        if (!isset($options ['b'])) {
            $options ['b'] = 'on';
        }
        if (!isset($options ['keepLines'])) {
            $options ['keepLines'] = 'on';
        }
        if (!isset($options ['keepNext'])) {
            $options ['keepNext'] = 'on';
        }
        if (!isset($options ['widowControl'])) {
            $options ['widowControl'] = 'on';
        }
        if (!isset($options ['sz'])) {
            $options ['sz'] = max(15 - $level, 10);
        }
        if (!isset($options ['font'])) {
            $options ['font'] = 'Cambria';
        }
        if (!isset($options ['rawWordML'])) {
            $options ['rawWordML'] = false;
        }
        $options ['headingLevel'] = $level;
        $heading = CreateText::getInstance();
        $heading->createText($text, $options);
//PhpdocxLogger::logger ( 'Adds a heading of level ' . $level . 'to teh Word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $heading;
        } else {
            $this->_wordDocumentC .= (string) $heading;
        }
    }

    public function addHTML($options = array('html' => '', 'rawWordML' => false)) {
        try {
            if ($this->_compatibilityMode) {
                throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            $wordHTML = EmbedHTML::getInstance();
            $wordHTML->embed();
//PhpdocxLogger::logger ( 'Embed HTML to word document.', 'info' );
            $this->_zipDocx->addFromString('word/html' . $wordHTML->getId() . '.htm', '<html>' . $options ['html'] . '</html>');
            $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rHTMLId' . $wordHTML->getId(), 'aFChunk', 'html' . $wordHTML->getId() . '.htm', 'TargetMode="Internal"');
            $this->generateDEFAULT('htm', 'application/xhtml+xml');
            if (isset($options ['rawWordML']) && $options ['rawWordML']) {
                return (string) $wordHTML . '<w:p/>';
            } else {
                $this->_wordDocumentC .= (string) $wordHTML;
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addImage($data = '') {
        if (!isset($data ['target'])) {
            $data ['target'] = 'document';
        }
//PhpdocxLogger::logger ( 'Create image.', 'debug' );
        try {
            if (isset($data ['name']) && file_exists($data ['name']) == 'true') {
                $attrImage = getimagesize($data ['name']);
                try {
                    if ($attrImage ['mime'] == 'image/jpg' || $attrImage ['mime'] == 'image/jpeg' || $attrImage ['mime'] == 'image/png' || $attrImage ['mime'] == 'image/gif') {
                        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID rId' . self::$intIdWord . ' . Image.', 'debug' );
                        $image = CreateImage::getInstance();
                        $data ['rId'] = self::$intIdWord;
                        $image->createImage($data);
                        $dir = $this->parsePath($data ['name']);
//PhpdocxLogger::logger ( 'Add image word/media/imgrId' . self::$intIdWord . '.' . $dir ['extension'] . '.xml to DOCX.', 'info' );
                        $this->_zipDocx->addFile($data ['name'], 'word/media/imgrId' . self::$intIdWord . '.' . $dir ['extension']);
                        $this->generateDEFAULT($dir ['extension'], $attrImage ['mime']);
                        if ((string) $image != '') {
                            if ($data ['target'] == 'defaultHeader' || $data ['target'] == 'firstHeader' || $data ['target'] == 'evenHeader' || $data ['target'] == 'defaultFooter' || $data ['target'] == 'firstFooter' || $data ['target'] == 'evenFooter') {
                                $this->_relsHeaderFooterImage [$data ['target']] [] = array(
                                    'rId' => 'rId' . self::$intIdWord,
                                    'extension' => $dir ['extension']
                                );
                            } else if ($data ['target'] == 'footnote' || $data ['target'] == 'endnote' || $data ['target'] == 'comment') {
                                $this->_relsNotesImage [$data ['target']] [] = array(
                                    'rId' => 'rId' . self::$intIdWord,
                                    'extension' => $dir ['extension']
                                );
                            } else {
                                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'image', 'media/imgrId' . self::$intIdWord . '.' . $dir ['extension']);
                            }
                        }
                        if (isset($data ['rawWordML']) && $data ['rawWordML']) {
                            return (string) $image;
                        } else {
                            $this->_wordDocumentC .= (string) $image;
                        }
                    } else {
                        throw new Exception('Image format is not supported.');
                    }
                } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                }
            } else {
                throw new Exception('Image does not exist.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addLineNumbering($options = array()) {
        $restart_types = array(
            'continuous',
            'newPage',
            'newSection'
        );
        if (isset($options ['countBy']) && is_int($options ['countBy'])) {
            $increment = $options ['countBy'];
        } else {
            $increment = 1;
        }
        if (isset($options ['start']) && is_int($options ['start'])) {
            $start = $options ['start'];
        } else {
            $start = 1;
        }
        if (isset($options ['distance']) && is_int($options ['distance'])) {
            $distance = $options ['distance'];
        }
        if (isset($options ['restart']) && in_array($options ['restart'], $restart_types)) {
            $condition = $options ['restart'];
        } else {
            $condition = 'continuous';
        }
        if ($this->_sectPr->getElementsByTagName('lnNumType')->length > 0) {
            $this->_sectPr->getElementsByTagName('lnNumType')->item(0)->setAttribute('w:countBy', $increment);
            $this->_sectPr->getElementsByTagName('lnNumType')->item(0)->setAttribute('w:start', $start);
            $this->_sectPr->getElementsByTagName('lnNumType')->item(0)->setAttribute('w:distance', $distance);
            $this->_sectPr->getElementsByTagName('lnNumType')->item(0)->setAttribute('w:restart', $condition);
        } else {
            $lnNumNode = $this->_sectPr->createDocumentFragment();
            $strNode = '<w:lnNumType w:countBy="' . $increment . '" w:start="' . $start . '" ';
            if (isset($distance)) {
                $strNode .= 'w:distance="' . $distance . '" ';
            }
            $strNode .= 'w:restart="' . $condition . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" />';
            $lnNumNode->appendXML($strNode);
            $propIndex = array_search('w:lnNumType', self::$sectionProperties);
            $childNodes = $this->_sectPr->documentElement->childNodes;
            $index = false;
            foreach ($childNodes as $node) {
                $name = $node->nodeName;
                $index = array_search($node->nodeName, self::$sectionProperties);
                if ($index > $propIndex) {
                    $node->parentNode->insertBefore($lnNumNode, $node);
                    break;
                }
            }
            if (!$index) {
                $this->_sectPr->documentElement->appendChild($lnNumNode);
            }
        }
    }

    public function addLink($text, $options = array('url' => '', 'font' => '', 'sz' => '', 'color' => '0000ff', 'u' => 'single', 'rawWordML' => false)) {
        if (substr($options ['url'], 0, 1) == '#') {
            $url = 'HYPERLINK \l "' . substr($options ['url'], 1) . '"';
        } else {
            $url = 'HYPERLINK "' . $options ['url'] . '"';
        }
        if ($text == '') {
//PhpdocxLogger::logger ( 'The linked text is missing', 'fatal' );
        } else if ($options ['url'] == '') {
//PhpdocxLogger::logger ( 'The URL is missing', 'fatal' );
        }
        if (isset($options ['color'])) {
            $color = $options ['color'];
        } else {
            $color = '0000ff';
        }
        if (isset($options ['u'])) {
            $u = $options ['u'];
        } else {
            $u = 'single';
        }
        $textOptions = $options;
        $textOptions ['rawWordML'] = true;
        $link = $this->addText($text, $textOptions);
        $link = preg_replace('/__[A-Z]+__/', '', $link);
        $startNodes = '<w:r><w:fldChar w:fldCharType="begin" /></w:r><w:r>
        <w:instrText xml:space="preserve">' . $url . '</w:instrText>
        </w:r><w:r><w:fldChar w:fldCharType="separate" /></w:r>';
        if (strstr($link, '</w:pPr>')) {
            $link = preg_replace('/<\/w:pPr>/', '</w:pPr>' . $startNodes, $link);
        } else {
            $link = preg_replace('/<w:p>/', '<w:p>' . $startNodes, $link);
        }
        $endNode = '<w:r><w:fldChar w:fldCharType="end" /></w:r>';
        $link = preg_replace('/<\/w:p>/', $endNode . '</w:p>', $link);
//PhpdocxLogger::logger ( 'Add link to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $link;
        } else {
            $this->_wordDocumentC .= (string) $link;
        }
    }

    public function addList($data, $options = array('rawWordML' => false)) {
        $list = CreateList::getInstance();
        if ($options ['val'] == 2) {
            self::$numOL ++;
            $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$orderedListStyle, self::$numOL);
        }
        if (is_string($options ['val'])) {
            $options ['val'] = self::$customLists [$options ['val']] ['id'];
        }
        $list->createList($data, $options);
//PhpdocxLogger::logger ( 'Add list to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $list;
        } else {
            $this->_wordDocumentC .= (string) $list;
        }
    }

    public function addMacroFromDoc($path) {
        if (!$this->_docm) {
//PhpdocxLogger::logger ( 'The base template should be a docm to include a macro in your document', 'fatal' );
            exit();
        } else if (!file_exists($this->_baseTemplateFilesPath . '/word/vbaData.xml')) {
//PhpdocxLogger::logger ( 'The base template should be a docm and it should include at list a macro', 'fatal' );
        }
        $package = new ZipArchive ();
//PhpdocxLogger::logger ( 'Open document with a macro.', 'info' );
//PhpdocxLogger::logger ( 'Add macro files to DOCX file.', 'info' );
        $vbaDataHandler = fopen($this->_baseTemplateFilesPath . '/word/vbaData.xml', "w+");
        fwrite($vbaDataHandler, $package->getFromName('word/vbaData.xml'));
        fclose($vbaDataHandler);
        $vbaProjectHandler = fopen($this->_baseTemplateFilesPath . '/word/vbaProject.bin', "w+");
        fwrite($vbaProjectHandler, $package->getFromName('word/vbaProject.bin'));
        fclose($vbaProjectHandler);
        $package->close();
    }

    public function addMathDocx($path) {
        $package = new ZipArchive ();
//PhpdocxLogger::logger ( 'Open document with an existing math eq.', 'info' );
        $package->open($path);
        $document = $package->getFromName('word/document.xml');
        $eqs = preg_split('/<[\/]*m:oMathPara>/', $document);
//PhpdocxLogger::logger ( 'Add math eq to word document.', 'info' );
        $this->addMathEq('<m:oMathPara>' . $eqs [1] . '</m:oMathPara>');
        $package->close();
    }

    public function addMathEq($eq, $rawWordML = false) {
//PhpdocxLogger::logger ( 'Add existing math eq to word document.', 'info' );
        if ($rawWordML) {
            return $eq;
        } else {
            $this->_wordDocumentC .= '<' . CreateDocx::NAMESPACEWORD . ':p>' . (string) $eq . '</' . CreateDocx::NAMESPACEWORD . ':p>';
        }
    }

    public function addMathMML($eq, $rawWordML = false) {
        $math = CreateMath::getInstance();
//PhpdocxLogger::logger ( 'Convert MathMML eq.', 'debug' );
        $math->createMath($eq);
//PhpdocxLogger::logger ( 'Add converted MathMML eq to word document.', 'info' );
        $equation = $this->addMathEq('<m:oMathPara>' . (string) $math . '</m:oMathPara>', $rawWordML);
        if ($rawWordML) {
            return $equation;
        }
    }

    public function addMergeField($name, $mergeParameters = array(), $options = array('rawWordML' => false)) {
        if (!isset($mergeParameters ['preserveFormat'])) {
            $mergeParameters ['preserveFormat'] = true;
        }
        $textOptions = $options;
        $textOptions ['rawWordML'] = true;
        $fieldName = '';
        if (isset($mergeParameters ['textBefore'])) {
            $fieldName .= $mergeParameters ['textBefore'];
        }
        $fieldName .= '«' . $name . '»';
        if (isset($mergeParameters ['textAfter'])) {
            $fieldName .= $mergeParameters ['textAfter'];
        }
        $simpleField = $this->addText($fieldName, $textOptions);
        $data = 'MERGEFIELD &quot;' . $name . '&quot; ';
        foreach ($mergeParameters as $key => $value) {
            switch ($key) {
                case 'textBefore' :
                    $data .= '\b &quot;' . $value . '&quot; ';
                    break;
                case 'textAfter' :
                    $data .= '\f &quot;' . $value . '&quot; ';
                    break;
                case 'mappedField' :
                    if ($value) {
                        $data .= '\m ';
                    }
                    break;
                case 'verticalFormat' :
                    if ($value) {
                        $data .= '\v ';
                    }
                    break;
                case 'preserveFormat' :
                    if ($value) {
                        $data .= '\* MERGEFORMAT';
                    }
                    break;
            }
        }
        $beguin = '<w:fldSimple w:instr=" ' . $data . ' ">';
        $end = '</w:fldSimple>';
        $simpleField = str_replace('<w:r>', $beguin . '<w:r>', $simpleField);
        $simpleField = str_replace('</w:r>', '</w:r>' . $end, $simpleField);
//PhpdocxLogger::logger ( 'Adding a merge field to the Word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $simpleField;
        } else {
            $this->_wordDocumentC .= (string) $simpleField;
        }
    }

    public function addMHT($options = array('rawWordML' => false)) {
        try {
            if ($this->_compatibilityMode) {
                throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            if (file_exists($options ['pathMHT'])) {
                $wordMHT = EmbedMHT::getInstance();
                $wordMHT->embed();
//PhpdocxLogger::logger ( 'Add MHT file to word document.', 'info' );
                $this->_zipDocx->addFile($options ['pathMHT'], 'word/mht' . $wordMHT->getId() . '.mht');
                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rMHTId' . $wordMHT->getId(), 'aFChunk', 'mht' . $wordMHT->getId() . '.mht', 'TargetMode="Internal"');
                $this->generateDEFAULT('mht', 'message/rfc822');
                if (isset($options ['rawWordML']) && $options ['rawWordML']) {
                    return (string) $wordMHT . '<w:p />';
                } else {
                    $this->_wordDocumentC .= (string) $wordMHT;
                }
            } else {
                throw new Exception('File does not exist.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addNewBaseTemplate($path, $name, $overwrite = false) {
        if (!$overwrite) {
            try {
                $exists = file_exists(PHPDOCX_BASE_FOLDER . '/' . $name);
                if ($exists == true) {
                    throw new Exception('There is a base template by that name. If you want to overwrite it set the overwrite parameter to true');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
        }
        try {
            $newTemplate = copy($path, PHPDOCX_BASE_FOLDER . '/' . $name);
            if ($newTemplate !== true) {
                throw new Exception('Error while trying to copy the new template: please, check the path or the permission rights of the template folder');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addObject($options = array('rawWordML' => false)) {
        try {
            if (file_exists($options ['path']) && ($options ['type'] == 'xls' || $options ['type'] == 'pptx')) {
                self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Object.', 'debug' );
                if ($options ['type'] == 'xls') {
                    $this->generateDEFAULT('xls', 'application/vnd.ms-excel');
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'oleObject', 'embeddings/Microsoft_Office_Excel_97-2003_' + 'Worksheet' + self::$intIdWord + '.xls');
                } elseif ($options ['type'] == 'pptx') {
                    $this->generateDEFAULT('pptx', 'application/vnd.openxmlformats-officedocument' + '.presentationml.presentation');
                    $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'package', 'embeddings/Microsoft_Office_PowerPoint_' + 'PresentationWorksheet' + self::$intIdWord + '.pptx');
                }
                $this->generateDEFAULT('emf', 'image/x-emf');
                $object = CreateObject::getInstance();
                $object->createObject($options ['path'], $options ['type']);
//PhpdocxLogger::logger ( 'Add object to word document.', 'info' );
                if (isset($options ['rawWordML']) && $options ['rawWordML']) {
                    return (string) $object;
                } else {
                    $this->_wordDocumentC .= (string) $object;
                }
            } else {
                throw new Exception('File does not exist or format is not supported.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'warn' );
            throw new Exception('File does not exist or format is not supported.');
        }
    }

    public function addPageBorders($options = array()) {
        $display_types = array(
            'allPages',
            'firstPage',
            'notFirstPage'
        );
        $offset_types = array(
            'page',
            'text'
        );
        $sides = array(
            'top',
            'left',
            'bottom',
            'right'
        );
        $type = array(
            'width' => 4,
            'color' => '000000',
            'style' => 'single',
            'space' => 24
        );
        if (isset($options ['zOrder'])) {
            $zOrder = $options ['zOrder'];
        } else {
            $zOrder = 1000;
        }
        if (isset($options ['display']) && in_array($options ['display'], $display_types)) {
            $display = $options ['display'];
        } else {
            $display = 'allPages';
        }
        if (isset($options ['offsetFrom']) && in_array($options ['offsetFrom'], $offset_types)) {
            $offsetFrom = $options ['offsetFrom'];
        } else {
            $offsetFrom = 'page';
        }
        foreach ($type as $key => $value) {
            foreach ($sides as $side) {
                if (isset($options ['border-' . $side . '-' . $key])) {
                    $opt ['border-' . $side . '-' . $key] = $options ['border-' . $side . '-' . $key];
                } else if (isset($options ['border-' . $key])) {
                    $opt ['border-' . $side . '-' . $key] = $options ['border-' . $key];
                } else {
                    $opt ['border-' . $side . '-' . $key] = $value;
                }
            }
        }
        if ($this->_sectPr->getElementsByTagName('pgBorders')->length > 0) {
            $pgBorder = $this->_sectPr->getElementsByTagName('pgBorders')->item(0);
            $pgBorder->parentNode->removeChild($pgBorder);
        }
        $pgBordersNode = $this->_sectPr->createDocumentFragment();
        $strNode = '<w:pgBorders ';
        $strNode .= 'xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" ';
        $strNode .= 'w:zOrder="' . $zOrder . '" w:display="' . $display . '" w:offsetFrom="' . $offsetFrom . '" >';
        foreach ($sides as $side) {
            $strNode .= '<w:' . $side . ' w:val="' . $opt ['border-' . $side . '-style'] . '" ';
            $strNode .= 'w:color="' . $opt ['border-' . $side . '-color'] . '" ';
            $strNode .= 'w:sz="' . $opt ['border-' . $side . '-width'] . '" ';
            $strNode .= 'w:space="' . $opt ['border-' . $side . '-space'] . '" />';
        }
        $strNode .= '</w:pgBorders>';
        $pgBordersNode->appendXML($strNode);
        $propIndex = array_search('w:pgBorders', self::$sectionProperties);
        $childNodes = $this->_sectPr->documentElement->childNodes;
        $index = false;
        foreach ($childNodes as $node) {
            $name = $node->nodeName;
            $index = array_search($node->nodeName, self::$sectionProperties);
            if ($index > $propIndex) {
                $node->parentNode->insertBefore($pgBordersNode, $node);
                break;
            }
        }
        if (!$index) {
            $this->_sectPr->documentElement->appendChild($pgBordersNode);
        }
    }

    public function addPageNumber($type = 'numerical', $options = array('defaultValue' => 1, 'rawWordML' => false)) {
        if (!isset($options ['defaultValue'])) {
            if ($type == 'numerical') {
                $options ['defaultValue'] = '1';
            } else if ($type == 'alphabetical') {
                $options ['defaultValue'] = 'a';
            }
        }
        $textOptions = $options;
        $textOptions ['rawWordML'] = true;
        $pageNumber = $this->addText($options ['defaultValue'], $textOptions);
        if ($type == 'alphabetical') {
            $beguin = '<w:fldSimple w:instr="PAGE \* alphabetic \* MERGEFORMAT">';
        } else {
            $beguin = '<w:fldSimple w:instr="PAGE \* MERGEFORMAT">';
        }
        $end = '</w:fldSimple>';
        $pageNumber = str_replace('<w:r>', $beguin . '<w:r>', $pageNumber);
        $pageNumber = str_replace('</w:r>', '</w:r>' . $end, $pageNumber);
//PhpdocxLogger::logger ( 'Add page number to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $pageNumber;
        } else {
            $this->_wordDocumentC .= (string) $pageNumber;
        }
    }

    public function addParagraph($parameters, $options) {
        if (!isset($options ['target'])) {
            $options ['target'] = 'document';
        }
        $rawParagraph = '';
        try {
            $customHTML = CustomHTML::getInstance();
            $pHTML = $customHTML->paragraphHTML($parameters, $options);
            if (isset($options ['rawWordML']) && $options ['rawWordML'] === true) {
                $rawParagraph = $this->embedHTML($pHTML [0], array(
                    'target' => $options ['target'],
                    'downloadImages' => true,
                    'parseAnchors' => true,
                    'rawWordML' => true
                        ));
            } else {
                $this->embedHTML($pHTML [0], array(
                    'downloadImages' => true,
                    'parseAnchors' => true
                ));
            }
            if (count($pHTML [1]) > 0) {
                foreach ($pHTML [1] as $key => $value) {
                    $footnoteWordML = new HTML2WordML($this->_baseTemplateFilesPath);
                    $dataML = $footnoteWordML->render($value, array());
                    $footnoteML = (string) $dataML [0];
                    $footnoteBase = '<w:footnote w:id="' . $key . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">' . $footnoteML . '</w:footnote>';
                    $tempSubstring = explode('<w:rPr>', $footnoteBase);
                    $content = explode('</w:rPr>', $tempSubstring [1]);
                    $rStyle = str_replace('<w:vertAlign w:val="superscript" />', '', $content [0]);
                    $aux = '<w:r><w:rPr>' . $rStyle . '<w:vertAlign w:val="superscript" /></w:rPr><w:footnoteRef/></w:r>';
                    $footnoteBase = str_replace('</w:pPr><w:r>', '</w:pPr>' . $aux . '<w:r>', $footnoteBase);
                    $filename = $this->_baseTemplateFilesPath . '/word/footnotes.xml';
                    $handle = fopen($filename, "r");
                    $contents = fread($handle, 999999);
                    fclose($handle);
                    $this->_wordFootnotesT = new DOMDocument ();
                    $this->_wordFootnotesT->loadXML($contents);
                    $tempNode = $this->_wordFootnotesT->createDocumentFragment();
                    $tempNode->appendXML($footnoteBase);
                    $this->_wordFootnotesT->documentElement->appendChild($tempNode);
                    $footnoteHandler = fopen($this->_baseTemplateFilesPath . '/word/footnotes.xml', "w+");
                    fwrite($footnoteHandler, $this->_wordFootnotesT->saveXML());
                    fclose($footnoteHandler);
                }
            }
            if (count($pHTML [2]) > 0) {
                foreach ($pHTML [2] as $key => $value) {
                    $endnoteWordML = new HTML2WordML($this->_baseTemplateFilesPath);
                    $dataML = $endnoteWordML->render($value, array());
                    $endnoteML = (string) $dataML [0];
                    $endnoteBase = '<w:endnote w:id="' . $key . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">' . $endnoteML . '</w:endnote>';
                    $tempSubstring = explode('<w:rPr>', $endnoteBase);
                    $content = explode('</w:rPr>', $tempSubstring [1]);
                    $rStyle = str_replace('<w:vertAlign w:val="superscript" />', '', $content [0]);
                    $aux = '<w:r><w:rPr>' . $rStyle . '<w:vertAlign w:val="superscript" /></w:rPr><w:endnoteRef/></w:r>';
                    $endnoteBase = str_replace('</w:pPr><w:r>', '</w:pPr>' . $aux . '<w:r>', $endnoteBase);
                    $filename = $this->_baseTemplateFilesPath . '/word/endnotes.xml';
                    $handle = fopen($filename, "r");
                    $contents = fread($handle, 999999);
                    fclose($handle);
                    $this->_wordEndnotesT = new DOMDocument ();
                    $this->_wordEndnotesT->loadXML($contents);
                    $tempNode = $this->_wordEndnotesT->createDocumentFragment();
                    $tempNode->appendXML($endnoteBase);
                    $this->_wordEndnotesT->documentElement->appendChild($tempNode);
                    $endnoteHandler = fopen($this->_baseTemplateFilesPath . '/word/endnotes.xml', "w+");
                    fwrite($endnoteHandler, $this->_wordEndnotesT->saveXML());
                    fclose($endnoteHandler);
                }
            }
            if (count($pHTML [3]) > 0) {
                for ($j = 0; $j < count($pHTML [3]); $j ++) {
                    $type = $pHTML [3] [$j] ['type'];
                    $pHTML [3] [$j] ['rawWordML'] = true;
                    $shapeData = $this->addShape($type, $pHTML [3] [$j]);
                    if (isset($options ['rawWordML']) && $options ['rawWordML'] === true) {
                        $rawParagraph = str_replace('<w:t xml:space="preserve">PHPDOCX_shape_' . $j . '</w:t>', $shapeData, $rawParagraph);
                    } else {
                        $this->_wordDocumentC = str_replace('<w:t xml:space="preserve">PHPDOCX_shape_' . $j . '</w:t>', $shapeData, $this->_wordDocumentC);
                    }
                }
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        if (isset($options ['rawWordML']) && $options ['rawWordML'] === true) {
            $paragraphObj = new WordML ();
            $paragraphObj->createRawWordML($rawParagraph);
            return $paragraphObj;
        }
    }

    public function addProperties($values) {
        $prop = CreateProperties::getInstance();
        if (!empty($values ['title']) || !empty($values ['subject']) || !empty($values ['creator']) || !empty($values ['keywords']) || !empty($values ['description']) || !empty($values ['category']) || !empty($values ['contentStatus'])) {
            $prop->createProperties($values, $this->_baseTemplateFilesPath);
        }
        if ($values ['contentStatus'] == 'Final') {
            $prop->createPropertiesCustom(array(
                '_MarkAsFinal' => array(
                    'boolean' => 'true'
                )
                    ), $this->_baseTemplateFilesPath);
            $this->generateOVERRIDE('/docProps/custom.xml', 'application/vnd.openxmlformats-officedocument.' . 'custom-properties+xml');
        }
        if (!empty($values ['Manager']) || !empty($values ['Company'])) {
            $prop->createPropertiesApp($values, $this->_baseTemplateFilesPath);
        }
        if (!empty($values ['custom']) && is_array($values ['custom'])) {
            $prop->createPropertiesCustom($values ['custom'], $this->_baseTemplateFilesPath);
            $this->generateOVERRIDE('/docProps/custom.xml', 'application/vnd.openxmlformats-officedocument.' . 'custom-properties+xml');
        }
//PhpdocxLogger::logger ( 'Adding properties to word document.', 'info' );
    }

    public function addSimpleField($fieldName, $type = 'general', $format = '', $options = array('rawWordML' => false)) {
        $availableTypes = array(
            'date' => '\@',
            'numeric' => '\#',
            'general' => '\*'
        );
        $fieldOptions = array();
        if (isset($options ['doNotShadeFormData']) && $options ['doNotShadeFormData']) {
            $fieldOptions ['doNotShadeFormData'] = true;
        }
        if (isset($options ['updateFields']) && $options ['updateFields']) {
            $fieldOptions ['updateFields'] = true;
        }
        if (count($fieldOptions) > 0) {
            $this->docxSettings($fieldOptions);
        }
        $textOptions = $options;
        $textOptions ['rawWordML'] = true;
        $simpleField = $this->addText($fieldName, $textOptions);
        $data = $fieldName . ' ';
        if (!empty($format)) {
            $data .= $availableTypes [$type] . ' ' . $format . ' ';
        }
        $data .= '\* MERGEFORMAT';
        $beguin = '<w:fldSimple w:instr=" ' . $data . ' ">';
        $end = '</w:fldSimple>';
        $simpleField = str_replace('<w:r>', $beguin . '<w:r>', $simpleField);
        $simpleField = str_replace('</w:r>', '</w:r>' . $end, $simpleField);
//PhpdocxLogger::logger ( 'Adding a simple field to the Word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $simpleField;
        } else {
            $this->_wordDocumentC .= (string) $simpleField;
        }
    }

    public function addRawWordML($wml) {
//PhpdocxLogger::logger ( 'Add raw WordML.', 'info' );
        $this->_wordDocumentC .= $wml;
    }

    public function addRTF($options = array('rawWordML' => false)) {
        try {
            if ($this->_compatibilityMode) {
                throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            if (file_exists($options ['pathRTF'])) {
                $wordRTF = EmbedRTF::getInstance();
                $wordRTF->embed();
//PhpdocxLogger::logger ( 'Add RTF file to word document.', 'info' );
                $this->_zipDocx->addFile($options ['pathRTF'], 'word/rtf' . $wordRTF->getId() . '.rtf');
                $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rRTFId' . $wordRTF->getId(), 'aFChunk', 'rtf' . $wordRTF->getId() . '.rtf', 'TargetMode="Internal"');
                $this->generateDEFAULT('rtf', 'application/rtf');
                if (isset($options ['rawWordML']) && $options ['rawWordML']) {
                    return (string) $wordRTF . '<w:p/>';
                } else {
                    $this->_wordDocumentC .= (string) $wordRTF;
                }
            } else {
                throw new Exception('File does not exist.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addSection($sectionType = 'nextPage', $paperType = null, $options = array()) {
        if (!$paperType) {
            $paperType = $this->_phpdocxconfig ['settings'] ['paper_size'];
        }
        $previousSectionPr = '<w:p><w:pPr>' . $this->_sectPr->saveXML() . '</w:pPr></w:p>';
        $previousSectionPr = str_replace('<?xml version="1.0"?>', '', $previousSectionPr);
        $this->_wordDocumentC .= (string) $previousSectionPr;
        $this->modifyPageLayout($paperType, $options);
        $nodeSz = $this->_sectPr->getElementsByTagName('pgSz')->item(0);
        $typeNode = $this->_sectPr->createDocumentFragment();
        $typeNode->appendXML('<w:type xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" w:val="' . $sectionType . '" />');
        $nodeSz->parentNode->insertBefore($typeNode, $nodeSz);
    }

    public function addShape($type, $options = array('rawWordML' => false)) {
        $shape = new CreateShape ();
        $shapeData = $shape->createShape($type, $options);
//PhpdocxLogger::logger ( 'Add a ' . $type . 'to the Word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return '<w:p><w:r>' . $shapeData . '</w:r></w:p>';
        } else {
            $paragraphShape = '<w:p><w:r>' . $shapeData . '</w:r></w:p>';
            $this->_wordDocumentC .= $paragraphShape;
        }
    }

    public function addStructuredDocumentTag($type, $options = array('rawWordML' => false)) {
        $sdtTypes = array(
            'comboBox',
            'date',
            'dropDownList',
            'richText'
        );
        if (!in_array($type, $sdtTypes)) {
//PhpdocxLogger::logger ( 'The chosen Structured Document Tag type is not available', 'fatal' );
            exit();
        }
        $sdtBase = CreateText::getInstance();
        $ParagraphOptions = $options;
        $ParagraphOptions ['rawWordML'] = true;
        $ParagraphOptions ['text'] = $options ['placeholderText'];
        $sdtBase->createText(array(
            $ParagraphOptions
                ), $ParagraphOptions);
        $sdt = CreateStructuredDocumentTag::getInstance();
        $sdt->createStructuredDocumentTag($type, $options, (string) $sdtBase);
//PhpdocxLogger::logger ( 'Add Structured Document Tag to Word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $sdt;
        } else {
            $this->_wordDocumentC .= (string) $sdt;
        }
    }

    public function addSubDocument($originalPath, $destination = '') {
        $rId = 'rId' . uniqid(true);
        $this->_wordDocumentC .= '<w:p><w:pPr><w:pStyle w:val="Heading2PHPDOCX" /></w:pPr><w:subDoc r:id ="' . $rId . '" /></w:p>';
        $this->generateRELATIONSHIP($rId, 'subDocument', $originalPath, 'TargetMode="External"');
    }

    public function addTable($tableData, $tableProperties = array('rawWordML' => false), $rowProperties = array()) {
        $table = CreateTable::getInstance();
        $table->createTable($tableData, $tableProperties, $rowProperties);
//PhpdocxLogger::logger ( 'Add table to Word document.', 'info' );
        if (isset($tableProperties ['rawWordML']) && $tableProperties ['rawWordML']) {
            return (string) $table;
        } else {
            $this->_wordDocumentC .= (string) $table;
        }
    }

    public function addTableContents($options = array(), $legend = array(), $stylesTOC = '') {
        if (!empty($stylesTOC)) {
            $this->importStyles($stylesTOC, 'merge', array(
                'TDC1',
                'TDC2',
                'TDC3',
                'TDC4',
                'TDC5',
                'TDC6',
                'TDC7',
                'TDC8',
                'TDC9'
                    ), 'styleID');
        }
        if (empty($legend ['text'])) {
            $legend ['text'] = 'Click here to update the Table of Contents';
        }
        $legendOptions = $legend;
        unset($legendOptions ['text']);
        $legendOptions ['rawWordML'] = true;
        $legendData = $this->addText(array(
            $legend
                ), $legendOptions);
        $tableContents = CreateTableContents::getInstance();
        $tableContents->createTableContents($options, $legendData);
        if ($options ['autoUpdate']) {
            $this->generateSetting('w:updateFields');
        }
//PhpdocxLogger::logger ( 'Add table of contents to word document.', 'info' );
        $this->_wordDocumentC .= (string) $tableContents;
    }

    public function addTemplate($path) {
        $numRef = rand(9999999, 99999999);
        CreateDocx::$numUL = $numRef;
        CreateDocx::$numOL = $numRef + 1;
        $template = CreateTemplate::getInstance();
        try {
            if (file_exists($path)) {
//PhpdocxLogger::logger ( 'Open template ' . $path . '.', 'info' );
                $template->openTemplate($path);
            } else {
                throw new Exception('File ' . $path . ' not exists');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function addTemplateChart($var, $chart) {
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Assign as chart variable ' . $var . ' in template.', 'info' );
        $this->addGraphicTemplate($chart);
//PhpdocxLogger::logger ( 'Replace chart variable ' . $var . ' in template.', 'info' );
        $template->replaceChart($var);
    }

    public function addTemplateCheckBox($var, $value = false) {
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Replace text variable ' . $var . ' with a checkbox.', 'info' );
        $template->replaceCheckBox($var, $value);
    }

    public function addTemplateImage($var, $pathImage, $options = array()) {
        if (!file_exists($pathImage)) {
            throw new Exception('File ' . $path . ' not exists');
        }
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Assign and replace image variable ' . $var . ' in template.', 'info' );
        $template->replaceImage($var, $pathImage, $options);
    }

    public function addTemplateVariable($var, $value = '', $settings = '') {
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Assign and replace text variable ' . $var . ' in template.', 'info' );
        $template->replaceVariable($var, $value, $settings);
    }

    public function addText($textParams, $paragraphParams = array('rawWordML' => false)) {
        $text = CreateText::getInstance();
        $text->createText($textParams, $paragraphParams);
//PhpdocxLogger::logger ( 'Add text to word document.', 'info' );
        if (isset($paragraphParams ['rawWordML']) && $paragraphParams ['rawWordML']) {
            return (string) $text;
        } else {
            $this->_wordDocumentC .= (string) $text;
        }
    }

    public function addTextBox($text, $options = array('rawWordML' => false)) {
        $textBox = CreateTextBox::getInstance();
        $textBox->createTextBox($text, $options);
//PhpdocxLogger::logger ( 'Add textbox to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $textBox;
        } else {
            $this->_wordDocumentC .= (string) $textBox;
        }
    }

    public function addTitle($text, $options = array('rawWordML' => false)) {
        $title = CreateText::getInstance();
        $title->createTitle($text, $options);
//PhpdocxLogger::logger ( 'Add title to word document.', 'info' );
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            return (string) $title;
        } else {
            $this->_wordDocumentC .= (string) $title;
        }
    }

    public function addWordML($options = array('rawWordML' => false)) {
        if (isset($options ['rawWordML']) && $options ['rawWordML']) {
            $wordXML = new WordML ();
            $wordXML->CreateRawWordML($options ['wordML']);
            return $wordXML;
        } else {
            $this->_wordDocumentC .= (string) $options ['wordML'];
        }
    }

    public function cleanTemplateVariable($variableName, $type = 'block') {
        if ($type == 'inline') {
            $this->addTemplateVariable($variableName, '');
        } else {
            $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Remove a template variable', 'info' );
            $template->removeVariable($variableName, $type);
        }
    }

    public function cleanWordMLBlockElements($wordML) {
        $wordMLChunk = new DOMDocument ();
        $namespaces = 'xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" ';
        $wordML = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><w:root ' . $namespaces . '>' . $wordML;
        $wordML = $wordML . '</w:root>';
        $wordMLChunk->loadXML($wordML);
        $wordMLXpath = new DOMXPath($wordMLChunk);
        $wordMLXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $wordMLXpath->registerNamespace('m', 'http://schemas.openxmlformats.org/wordprocessingml/2006/math');
        $query = '//w:r[not(ancestor::w:hyperlink)] | //w:hyperlink | //w:bookmarkStart | //w:bookmarkEnd | //w:commentRangeStart | //w:commentRangeEnd | //m:oMath';
        $wrNodes = $wordMLXpath->query($query);
        $blockCleaned = '';
        foreach ($wrNodes as $node) {
            $nodeR = $node->ownerDocument->saveXML($node);
            $blockCleaned .= $nodeR;
        }
        return $blockCleaned;
    }

    public function clearBlocks() {
//PhpdocxLogger::logger ( 'Clear all blocks.', 'info' );
        CreateTemplate::deleteAllBlocks();
    }

    public function createDocx() {
        $args = func_get_args();        
        if (!empty($args [0])) {
            $fileName = $args [0];
        } else {
            $fileName = 'document';
        }
//PhpdocxLogger::logger ( 'Set DOCX name to: ' . $fileName . '.', 'info' );
        if (!CreateTemplate::getBlnTemplate()) {
            $message = '<w:p xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
                            <w:r>
                                <w:t>' . str_rot13('Guvf qbphzrag unf orra trarengrq jvgu n') . '</w:t>
                            </w:r>
                            <w:r>
                                <w:rPr>
                                    <w:b/>
                                </w:rPr>
                                <w:t xml:space="preserve"> ' . str_rot13('gevny') . '</w:t>
                            </w:r>
                            <w:r>
                                <w:t xml:space="preserve"> ' . str_rot13('pbcl bs') . ' </w:t>
                            </w:r>
                            <w:r>
                                <w:rPr>
                                    <w:b/>
                                </w:rPr>
                                <w:t>' . str_rot13('CUCQbpK') . '</w:t>
                            </w:r>
                            <w:r>
                                <w:t>. ' . str_rot13('Cyrnfr') . '</w:t>
                            </w:r>
                            <w:r>
                                <w:t>, ' . str_rot13('ivfvg') . '</w:t>
                            </w:r>
                            <w:r>
                                <w:t xml:space="preserve"> ' . str_rot13('gur') . ' </w:t>
                            </w:r>
                            <w:r>
                                <w:fldChar w:fldCharType="begin" />
                            </w:r>
                            <w:r>
                                <w:instrText xml:space="preserve">HYPERLINK "' . str_rot13('uggc://jjj.cucqbpk.pbz') . '"</w:instrText>
                            </w:r>
                            <w:r>
                                <w:fldChar w:fldCharType="separate" />
                            </w:r>
                            <w:r>
                                <w:rPr>
                                    <w:b/>
                                    <w:color w:val="3333EE"/>
                                    <w:u/>
                                </w:rPr>
                                <w:t xml:space="preserve">' . str_rot13('CUCQbpK jrofvgr') . '</w:t>
                            </w:r>
                            <w:r>
                                <w:fldChar w:fldCharType="end" />
                            </w:r>
                            <w:r>
                                <w:t xml:space="preserve"> ' . str_rot13('gb ohl gur yvprafr gung orfg nqncgf gb lbhe arrqf') . '.</w:t>
                            </w:r>
                        </w:p>';
            $this->addRawWordML($message);
            //$this->addBackgroundImage(dirname(__FILE__) . '/../files/img/imagebg.jpg');
            $this->addProperties(array(
                'creator' => trim($phpdocxconfig ['license'] ['email']),
                'description' => str_rot13('Trarengrq ol CUCQbpK gevny irefvba')
            ));
//PhpdocxLogger::logger ( 'DOCX is a new file, not a template.', 'debug' );
            try {
                GenerateDocx::beginDocx();
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            $relsHandler = fopen($this->_baseTemplateFilesPath . '/word/_rels/document.xml.rels', "w+");
            fwrite($relsHandler, $this->_wordRelsDocumentRelsT->saveXML());
            fclose($relsHandler);
            $contentTypesHandler = fopen($this->_baseTemplateFilesPath . '/[Content_Types].xml', "w+");
            fwrite($contentTypesHandler, $this->_contentTypeT->saveXML());
            fclose($contentTypesHandler);
            $arrArgsPage = array();
            if (isset($args [1])) {
//PhpdocxLogger::logger ( 'Modify page layout.', 'debug' );
                $this->modifyPageLayout('custom', $args [1]);
            }
            $this->generateTemplateWordDocument($arrArgsPage);
            if ($this->_debug->getActive() == 1) {
//PhpdocxLogger::logger ( 'Debug is active, add messages to objDebug.', 'debug' );
                libxml_use_internal_errors(true);
                simplexml_load_string($this->_wordDocumentT, 'SimpleXMLElement', LIBXML_NOWARNING);
                $xmlErrors = libxml_get_errors();
                if (is_array($xmlErrors)) {
                    $this->_debug->addMessage($xmlErrors);
                    libxml_clear_errors();
                }
            }
//PhpdocxLogger::logger ( 'Add word/document.xml content to DOCX file.', 'info' );
            $documentHandler = fopen($this->_baseTemplateFilesPath . '/word/document.xml', "w+");
            if (self::$_encodeUTF) {
                $contentDocumentXML = utf8_encode($this->_wordDocumentT);
                fwrite($documentHandler, utf8_encode($this->_wordDocumentT));
            } else {
                if ($this->_phpdocxconfig ['settings'] ['encode_to_UTF8'] == 'true' && !PhpdocxUtilities::isUtf8($this->_wordDocumentT)) {
                    $contentDocumentXML = utf8_encode($this->_wordDocumentT);
                } else {
                    $contentDocumentXML = $this->_wordDocumentT;
                }
                fwrite($documentHandler, $this->_wordDocumentT);
            }
            fclose($documentHandler);
            if (file_exists(dirname(__FILE__) . '/RepairPDF.inc')) {
                if ($this->_compatibilityMode) {
                    $contentRepairPDF = RepairPDF::repairTablesPDFConversion($contentDocumentXML);
                    $documentHandler = fopen($this->_baseTemplateFilesPath . '/word/document.xml', "w+");
                    fwrite($documentHandler, (string) $contentRepairPDF);
                    fclose($documentHandler);
                }
            }
            $repair = Repair::getInstance();
            $repair->setXML($contentDocumentXML);
            $repair->addParapraphEmptyTablesTags();
            $documentHandler = fopen($this->_baseTemplateFilesPath . '/word/document.xml', "w+");
            fwrite($documentHandler, (string) $repair);
            fclose($documentHandler);
            $numberingHandler = fopen($this->_baseTemplateFilesPath . '/word/numbering.xml', "w+");
            fwrite($numberingHandler, $this->_wordNumberingT);
            fclose($numberingHandler);
//PhpdocxLogger::logger ( 'Close ZIP file', 'info' );
            if (!empty($this->_relsNotesImage ['footnote']) || !empty($this->_relsNotesExternalImage ['footnote']) || !empty($this->_relsNotesLink ['footnote'])) {
                $this->generateRelsNotes('footnote');
            }
            if (!empty($this->_relsNotesImage ['endnote']) || !empty($this->_relsNotesExternalImage ['endnote']) || !empty($this->_relsNotesLink ['endnote'])) {
                $this->generateRelsNotes('endnote');
            }
            if (!empty($this->_relsNotesImage ['comment']) || !empty($this->_relsNotesExternalImage ['comment']) || !empty($this->_relsNotesLink ['comment'])) {
                $this->generateRelsNotes('comment');
            }
            $this->recursiveInsert($this->_zipDocx, $this->_baseTemplateFilesPath, $this->_baseTemplateFilesPath);
            if (is_dir($this->_baseTemplateFilesPath . '/word/mediaTemplate')) {
                $contentsDir = scandir($this->_baseTemplateFilesPath . '/word/mediaTemplate');
                $predefinedExtensions = explode(',', PHPDOCX_ALLOWED_IMAGE_EXT);
                foreach ($contentsDir as $element) {
                    $arrayExtension = explode('.', $element);
                    $extension = strtolower(array_pop($arrayExtension));
                    if (in_array($extension, $predefinedExtensions)) {
                        $this->_zipDocx->addFile($this->_baseTemplateFilesPath . '/word/mediaTemplate/' . $element, 'word/media/' . $element);
                    }
                    $this->_zipDocx->deleteName('word/mediaTemplate/' . $element);
                }
                $deleteMediaTemplate = $this->_zipDocx->deleteName('word/mediaTemplate/');
            }
            if (count($this->_bookmarksIds) > 0) {
//PhpdocxLogger::logger ( 'There are unclosed bookmarks. Please, check that all open bookmarks tags are properly closed.', 'fatal' );
            }
            $this->_zipDocx->close();
//$arrpathFile = pathinfo ( $fileName );
//PhpdocxLogger::logger ( 'Copy DOCX file using a new name.', 'info' );
            header('Content-Description: File Transfer');
            header('Content-Type: application/ms-word');
            header('Content-Disposition: attachment; filename=' . $fileName . '.docx');
//header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($this->_tempFile));
            ob_clean();
            flush();
            readfile($this->_tempFile);
            unlink($this->_tempFile);
//copy ( $this->_tempFile, $fileName . '.' . $this->_extension );
            if ($this->_debug->getActive() == 1) {
//PhpdocxLogger::logger ( 'Debug is active, show messages.', 'debug' );
                echo $this->_debug;
            }
        } else {
//PhpdocxLogger::logger ( 'DOCX is a template.', 'debug' );
//PhpdocxLogger::logger ( 'Create a temp copy of the file, to generate a new DOCX.', 'info' );
            $finalFileName = $fileName . '.' . $this->_extension;
            $fileName = tempnam($this->_tempDir, $fileName);
            copy(CreateTemplate::$path, $fileName);
            $zipDocxTemplate = new ZipArchive ();
            try {
                if ($zipDocxTemplate->open($fileName)) {
                    if (count(CreateTemplate::getFooter()) > 0) {
                        $footers = CreateTemplate::getFooter();
                        foreach ($footers as $key => $footer) {
//PhpdocxLogger::logger ( 'Footer exists, replace ' . $key . ' with a new footer.', 'info' );
                            $zipDocxTemplate->addFromString($key, $footer);
                        }
                    }
                    if (count(CreateTemplate::getHeader()) > 0) {
                        $headers = CreateTemplate::getHeader();
                        foreach ($headers as $key => $header) {
//PhpdocxLogger::logger ( 'Footer exists, replace ' . $key . ' with a new header.', 'info' );
                            $zipDocxTemplate->addFromString($key, $header);
                        }
                    }
                    if (CreateTemplate::getRelsDocumentXMLRels() != '') {
//PhpdocxLogger::logger ( 'Document.xml.rels exists, replace word/_rels/' . 'document.xml.rels with a new document.', 'info' );
                        $zipDocxTemplate->addFromString('word/_rels/document.xml.rels', CreateTemplate::getRelsDocumentXMLRels());
                    }
                    if (count(CreateTemplate::$placeholderImages) > 0) {
//PhpdocxLogger::logger ( 'There is one or more images as placeholders, . ' . 'replace them.', 'info' );
                        CreateTemplate::replaceImages($zipDocxTemplate);
                    }
                    if (count(CreateTemplate::$placeholderHeaderImages) > 0) {
//PhpdocxLogger::logger ( 'There is one or more images in the header as placeholders, . ' . 'replace them.', 'info' );
                        CreateTemplate::replaceHeaderMediaImages($zipDocxTemplate);
                    }
                    if (CreateTemplate::$totalTemplateCharts > 0) {
//PhpdocxLogger::logger ( 'There is one or more charts as placeholders, . ' . 'replace them.', 'info' );
                        for ($i = CreateTemplate::$ridInitTemplateCharts + 1; $i <= self::$intIdWord; $i ++) {
                            $zipDocxTemplate->addFromString('word/charts/chart' . $i . '.xml', $this->graphicTemplate ['arrChartXML'] [$i]);
                            CreateTemplate::replaceVariableChart($this->graphicTemplate ['arrGraphics'] [$i], $i);
                            $zipDocxTemplate->addFile($this->_tempFileXLSX [$i], $this->fileGraphicTemplate ['datos' . $i . '.xlsx']);
                            $zipDocxTemplate->addFromString('word/charts/_rels/chart' . $i . '.xml.rels', $this->fileGraphicTemplate ['word/charts/_rels/chart' . $i . '.xml.rels']);
                            CreateTemplate::addContentTypes($this->graphicTemplate ['arrGenerateOVERRIDE'] [$i]);
                            CreateTemplate::addContentTypes($this->graphicTemplate ['arrGenerateDEFAULT'] [$i]);
                            CreateTemplate::addRelationship($this->graphicTemplate ['arrRelationships'] [$i]);
                        }
                    }
//PhpdocxLogger::logger ( 'Replace [Content_Types].xml with a new document.', 'info' );
                    $zipDocxTemplate->addFromString('[Content_Types].xml', CreateTemplate::getContentTypes());
//PhpdocxLogger::logger ( 'Replace word/_rels/document.xml.rels with a new ' . 'document.', 'info' );
                    $zipDocxTemplate->addFromString('word/_rels/document.xml.rels', CreateTemplate::getRelsDocumentXMLRels());
                    $this->_wordNumberingT = CreateTemplate::getNumbering();
                    if ($this->_wordNumberingT == '') {
                        $this->_wordNumberingT = $this->generateBaseWordNumbering();
                    }
                    $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$unorderedListStyle, self::$numUL);
                    if (is_array($this->_templateNumberings)) {
                        foreach ($this->_templateNumberings as $value) {
                            $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$orderedListStyle, $value);
                        }
                    }
                    if (is_array($this->_templateCustomNumberings)) {
                        foreach ($this->_templateCustomNumberings as $value) {
                            $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$customLists [$value ['name']] ['wordML'], $value ['id']);
                        }
                    }
                    $zipDocxTemplate->addFromString('word/numbering.xml', $this->_wordNumberingT);
                    $this->_wordStylesT = CreateTemplate::getStyles();
                    $importingStyles = new DOMDocument ();
                    $importingStyles->loadXML(self::$PHPDOCXStyles);
                    $this->_wordStylesT = $this->addStylesTemplate($this->_wordStylesT, $importingStyles);
                    $zipDocxTemplate->addFromString('word/styles.xml', $this->_wordStylesT);
//PhpdocxLogger::logger ( 'Replace word/document.xml with a new document.', 'info' );
                    if (self::$_encodeUTF) {
                        $contentDocumentXML = utf8_encode(CreateTemplate::getDocument());
                        $zipDocxTemplate->addFromString('word/document.xml', utf8_encode(CreateTemplate::getDocument()));
                    } else {
                        if ($this->_phpdocxconfig ['settings'] ['encode_to_UTF8'] == 'true' && !PhpdocxUtilities::isUtf8(CreateTemplate::getDocument())) {
                            $contentDocumentXML = utf8_encode(CreateTemplate::getDocument());
                            $zipDocxTemplate->addFromString('word/document.xml', utf8_encode(CreateTemplate::getDocument()));
                        } else {
                            $contentDocumentXML = CreateTemplate::getDocument();
                            $zipDocxTemplate->addFromString('word/document.xml', CreateTemplate::getDocument());
                        }
                    }
                    $zipDocxTemplate->addFromString('word/settings.xml', CreateTemplate::$settings);
                    $zipDocxTemplate->addFromString('docProps/core.xml', CreateTemplate::$properties);
//PhpdocxLogger::logger ( 'Add embedded files.', 'info' );
                    foreach (CreateTemplate::$embedFiles as $files) {
                        if (isset($files ['src_string'])) {
                            $zipDocxTemplate->addFromString('word/' . $files ['dest_file'], $files ['src_string']);
                        } elseif (isset($files ['src_file'])) {
                            $zipDocxTemplate->addFile($files ['src_file'], 'word/' . $files ['dest_file']);
                        }
                    }
                    if (is_dir($this->_baseTemplateFilesPath . '/word/mediaTemplate')) {
                        $contentsDir = scandir($this->_baseTemplateFilesPath . '/word/mediaTemplate');
                        $predefinedExtensions = array(
                            'gif',
                            'png',
                            'jpg',
                            'jpeg',
                            'bmp'
                        );
                        foreach ($contentsDir as $element) {
                            $arrayExtension = explode('.', $element);
                            $extension = strtolower(array_pop($arrayExtension));
                            if (in_array($extension, $predefinedExtensions)) {
                                $zipDocxTemplate->addFile($this->_baseTemplateFilesPath . '/word/mediaTemplate/' . $element, 'word/media/' . $element);
                            }
                        }
                    }
//PhpdocxLogger::logger ( 'End of file, close it.', 'info' );
                    $repair = Repair::getInstance();
                    $repair->setXML($contentDocumentXML);
                    $repair->addParapraphEmptyTablesTags();
                    $zipDocxTemplate->addFromString('word/document.xml', (string) $repair);
                    $zipDocxTemplate->close();
                    copy($fileName, $finalFileName);
                    CleanTemp::clean($fileName);
                    CleanTemp::clean($this->_baseTemplateFilesPath);
                } else {
                    throw new Exception('Unable to create DOCX file.');
                }
                CreateTemplate::reset();
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
        }
        foreach ($this->_tempFileXLSX as $xlsxFile) {
            CleanTemp::clean($xlsxFile);
        }
        CleanTemp::clean($this->_tempFile);
        CleanTemp::clean($this->_baseTemplateFilesPath);
    }

    public function createDocxAndDownload() {
        $args = func_get_args();
        try {
            if (isset($args [1])) {
                $this->createDocx($args [0], $args [1]);
            } else {
                $this->createDocx($args [0]);
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        if (!empty($args [0])) {
            $fileName = $args [0];
            $completeName = explode("/", $args [0]);
            $fileNameDownload = array_pop($completeName);
        } else {
            $fileName = 'document';
            $fileNameDownload = 'document';
        }
//PhpdocxLogger::logger ( 'Download file ' . $fileNameDownload . '.' . $this->_extension . '.', 'info' );
        header('Content-Type: application/vnd.openxmlformats-officedocument.' . 'wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $fileNameDownload . '.' . $this->_extension . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($fileName . '.' . $this->_extension));
        readfile($fileName . '.' . $this->_extension);
    }

    public function deleteTemplateBlock($blockName) {
//PhpdocxLogger::logger ( 'Delete block ' . $blockName . '.', 'info' );
        CreateTemplate::deleteBlock($blockName);
    }

    public function disableDebug() {
//PhpdocxLogger::logger ( 'Disable debug', 'info' );
        $this->_debug->disableDebug();
    }

    public function docxSettings($settingParameters) {
        $settingParams = array(
            'view',
            'zoom',
            'bordersDoNotSurroundHeader',
            'bordersDoNotSurroundFooter',
            'gutterAtTop',
            'hideSpellingErrors',
            'hideGrammaticalErrors',
            'documentType',
            'trackRevisions',
            'defaultTabStop',
            'autoHyphenation',
            'consecutiveHyphenLimit',
            'hyphenationZone',
            'doNotHyphenateCaps',
            'defaultTableStyle',
            'bookFoldRevPrinting',
            'bookFoldPrinting',
            'bookFoldPrintingSheets',
            'doNotShadeFormData',
            'noPunctuationKerning',
            'printTwoOnOne',
            'savePreviewPicture',
            'updateFields'
        );
        try {
            $settings = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "r");
            $baseTemplateSettingsT = fread($settings, 1000000);
            fclose($settings);
            if ($baseTemplateSettingsT == '') {
                throw new Exception('Error while extracting settings.xml file from the base template to insert the selected element');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $this->_wordSettingsT = new DOMDocument ();
        $this->_wordSettingsT->loadXML($baseTemplateSettingsT);
        foreach ($settingParameters as $tag => $value) {
            if ((!in_array($tag, $settingParams))) {
//PhpdocxLogger::logger ( 'That setting tag is not supported.', 'info' );
            } else {
                $settingIndex = array_search('w:' . $tag, self::$settings);
                $selectedElements = $this->_wordSettingsT->documentElement->getElementsByTagName($tag);
                if ($selectedElements->length == 0) {
                    $settingsElement = $this->_wordSettingsT->createDocumentFragment();
                    if ($tag == 'zoom') {
                        if (is_integer($value)) {
                            $settingsElement->appendXML('<w:' . $tag . ' xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" w:percent = "' . $value . '"/>');
                        } else {
                            $settingsElement->appendXML('<w:' . $tag . ' xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" w:val = "' . $value . '"/>');
                        }
                    } else {
                        $settingsElement->appendXML('<w:' . $tag . ' xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" w:val = "' . $value . '"/>');
                    }
                    $childNodes = $this->_wordSettingsT->documentElement->childNodes;
                    $index = false;
                    foreach ($childNodes as $node) {
                        $name = $node->nodeName;
                        $index = array_search($node->nodeName, self::$settings);
                        if ($index > $settingIndex) {
                            $node->parentNode->insertBefore($settingsElement, $node);
                            break;
                        }
                    }
                    if (!$index) {
                        $this->_wordSettingsT->documentElement->appendChild($settingsElement);
                    }
                } else {
                    if ($tag == 'zoom') {
                        $selectedElements->item(0)->removeAttribute('w:val');
                        $selectedElements->item(0)->removeAttribute('w:percent');
                        if (is_integer($value)) {
                            $selectedElements->item(0)->setAttribute('w:percent', $value);
                        } else {
                            $selectedElements->item(0)->setAttribute('w:val', $value);
                        }
                    } else {
                        $selectedElements->item(0)->setAttribute('w:val', $value);
                    }
                }
            }
        }
        $newSettings = $this->_wordSettingsT->saveXML();
        $settingsHandle = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "w+");
        $contents = fwrite($settingsHandle, $newSettings);
        fclose($settingsHandle);
    }

    public static function docx2txt($from, $to, $options = array()) {
        $text = new Docx2Text($options);
        $text->setDocx($from);
        $text->extract($to);
    }

    public function embedHTML($html = '<html><body></body></html>', $options = array()) {
        if (!isset($options ['target'])) {
            $options ['target'] = 'document';
        }
        if (!class_exists('Tidy')) {
//PhpdocxLogger::logger ( 'Tidy is not installed. Htmlawed library will be used to clean the HTML.', 'warning' );
        }
        $htmlDOCX = new HTML2WordML($this->_baseTemplateFilesPath);
        $sFinalDocX = $htmlDOCX->render($html, $options);
//PhpdocxLogger::logger ( 'Add converted HTML to word document.', 'info' );
        $this->HTMLRels($sFinalDocX, $options);
        if (is_array($sFinalDocX [3])) {
            foreach ($sFinalDocX [3] as $value) {
                $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$orderedListStyle, $value);
            }
        }
        if (is_array($sFinalDocX [4])) {
            foreach ($sFinalDocX [4] as $value) {
                $realNameArray = explode('_', $value ['name']);
                $value ['name'] = $realNameArray [0];
                $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, self::$customLists [$value ['name']] ['wordML'], $value ['id']);
            }
        }
        if (isset($options ['rawWordML']) && $options ['rawWordML'] === true) {
            return (string) $sFinalDocX [0];
        } else {
            $this->_wordDocumentC .= (string) $sFinalDocX [0];
        }
    }

    public function enableCompatibilityMode() {
//PhpdocxLogger::logger ( 'Enable compatibility mode.', 'info' );
        $this->_compatibilityMode = true;
    }

    public function enableDebug() {
//PhpdocxLogger::logger ( 'Enable debug.', 'info' );
        $this->_debug->enableDebug();
    }

    public function getTemplateVariables() {
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Return template variables.', 'info' );
        return $template->returnAllVariables();
    }

    public function getTemplateXML() {
        $templateXML = CreateXML::getInstance();
//PhpdocxLogger::logger ( 'Return the XML of the given Template.', 'info' );
        self::getTemplateVariables();
        $templateXML->XML();
        return $templateXML->getXML();
    }

    public function importStyles($path, $type = 'replace', $myStyles = array(), $styleIdentifier = 'styleName') {
        $zipStyles = new ZipArchive ();
        try {
            $openStyle = $zipStyles->open($path);
            if ($openStyle !== true) {
                throw new Exception('Error while opening the Style Template: please, check the path');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        if ($type == 'replace') {
            try {
                $extractingStyleFile = $zipStyles->extractTo($this->_baseTemplateFilesPath . '/', 'word/styles.xml');
                if (!$extractingStyleFile) {
                    throw new Exception('Error while trying to overwrite the styles.xml of the base template');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            $this->importStyles(PHPDOCX_BASE_TEMPLATE, 'merge', $this->_defaultPHPDOCXStyles);
        } else {
            try {
                $newStyles = $zipStyles->getFromName('word/styles.xml');
                if ($newStyles == '') {
                    throw new Exception('Error while extracting the styles from the external docx');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            $newStylesDoc = new DOMDocument ();
            $newStylesDoc->loadXML($newStyles);
            $stylesXpath = new DOMXPath($newStylesDoc);
            $stylesXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
            $queryStyle = '//w:style';
            $styleNodes = $stylesXpath->query($queryStyle);
            try {
                $styleHandler = fopen($this->_baseTemplateFilesPath . '/word/styles.xml', 'r');
                $styleXML = fread($styleHandler, filesize($this->_baseTemplateFilesPath . '/word/styles.xml'));
                fclose($styleHandler);
                $this->_wordStylesT = $styleXML;
                if ($styleXML == '') {
                    throw new Exception('Error while extracting the style file from the base template');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            $stylesDocument = new DomDocument ();
            $stylesDocument->loadXML($this->_wordStylesT);
            $baseNode = $stylesDocument->documentElement;
            $stylesDocumentXPath = new DOMXPath($stylesDocument);
            $stylesDocumentXPath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
            $query = '//w:style';
            $originalNodes = $stylesDocumentXPath->query($query);
            foreach ($styleNodes as $node) {
                foreach ($originalNodes as $oldNode) {
                    if ($styleIdentifier == 'styleID') {
                        if ($oldNode->getAttribute('w:styleId') == $node->getAttribute('w:styleId') && in_array($oldNode->getAttribute('w:styleId'), $myStyles)) {
                            $oldNode->parentNode->removeChild($oldNode);
                        }
                    } else {
                        $oldName = $oldNode->getElementsByTagName('w:name');
                        if ($oldNode->getAttribute('w:styleId') == $node->getAttribute('w:styleId') && in_array($oldName, $myStyles)) {
                            $oldNode->parentNode->removeChild($oldNode);
                        }
                    }
                }
                if (count($myStyles) > 0) {
                    if ($styleIdentifier == 'styleID') {
                        if (in_array($node->getAttribute('w:styleId'), $myStyles)) {
                            $insertNode = $stylesDocument->importNode($node, true);
                            $baseNode->appendChild($insertNode);
                        }
                    } else {
                        $nodeChilds = $node->childNodes;
                        foreach ($nodeChilds as $child) {
                            if ($child->nodeName == 'w:name') {
                                $styleName = $child->getAttribute('w:val');
                                if (in_array($styleName, $myStyles)) {
                                    $insertNode = $stylesDocument->importNode($node, true);
                                    $baseNode->appendChild($insertNode);
                                }
                            }
                        }
                    }
                } else {
                    $insertNode = $stylesDocument->importNode($node, true);
                    $baseNode->appendChild($insertNode);
                }
            }
            $this->_wordStylesT = $stylesDocument->saveXML();
            try {
                $stylesFile = fopen($this->_baseTemplateFilesPath . '/word/styles.xml', 'w');
                if ($stylesFile == false) {
                    throw new Exception('Error while opening the base template styles.xml file');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            try {
                $writeStyles = fwrite($stylesFile, $this->_wordStylesT);
                if ($writeStyles == 0) {
                    throw new Exception('There were no new styles written');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
        }
//PhpdocxLogger::logger ( 'Importing styles from an external docx.', 'info' );
    }

    public function importThemeXML($path) {
        try {
            $zipTheme = new ZipArchive ();
            $extractingThemeFile = $zipTheme->extractTo($this->_baseTemplateFilesPath . '/', 'word/theme/theme1.xml');
            if (!$extractingThemeFile) {
                throw new Exception('Error while trying to overwrite the theme1.xml of the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function importWebSettingsXML($path) {
        try {
            $zipWebSettings = new ZipArchive ();
            $extractingWebSettingsFile = $zipTheme->extractTo($this->_baseTemplateFilesPath . '/', 'word/webSettings.xml');
            if (!$extractingWebSettingsFile) {
                throw new Exception('Error while trying to overwrite the webSettings.xml of the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function importSettingsXML($path) {
        try {
            $zipSettings = new ZipArchive ();
            $extractingSettingsFile = $zipTheme->extractTo($this->_baseTemplateFilesPath . '/', 'word/settings.xml');
            if (!$extractingSettingsFile) {
                throw new Exception('Error while trying to overwrite the settings.xml of the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function importFontTableXML($path) {
        try {
            $zipFontTable = new ZipArchive ();
            $extractingFontTableFile = $zipTheme->extractTo($this->_baseTemplateFilesPath . '/', 'word/fontTable.xml');
            if (!$extractingFontTableFile) {
                throw new Exception('Error while trying to overwrite the fontTable.xml of the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function modifyInputFields($data) {
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Modify input field', 'info' );
        $template->modifyInputText($data);
    }

    public function modifyPageLayout($paperType = 'letter', $options = array()) {
        $paperTypes = array(
            'A4',
            'A3',
            'letter',
            'legal',
            'A4-landscape',
            'A3-landscape',
            'letter-landscape',
            'legal-landscape',
            'custom'
        );
        $layoutOptions = array(
            'width',
            'height',
            'numberCols',
            'orient',
            'code',
            'marginTop',
            'marginRight',
            'marginBottom',
            'marginLeft',
            'marginHeader',
            'marginFooter',
            'gutter'
        );
        // ducdm hack this
        $referenceSizes = array(
            'A4' => array(
                'width' => '11906',
                'height' => '16838',
                'numberCols' => '1',
                'orient' => 'portrait',
                'code' => '9',
                'marginTop' => '200',
//                'marginTop' => '1417',
                'marginRight' => '200',
//                'marginRight' => '1701',
                'marginBottom' => '200',
//                'marginBottom' => '1417',
                'marginLeft' => '200',
//                'marginLeft' => '1701',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            ),
            'A4-landscape' => array(
                'width' => '16838',
                'height' => '11906',
                'numberCols' => '1',
                'orient' => 'landscape',
                'code' => '9',
                'marginTop' => '1701',
                'marginRight' => '1417',
                'marginBottom' => '1701',
                'marginLeft' => '1417',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            ),
            'A3' => array(
                'width' => '16839',
                'height' => '23814',
                'numberCols' => '1',
                'orient' => 'portrait',
                'code' => '8',
                'marginTop' => '1417',
                'marginRight' => '1701',
                'marginBottom' => '1417',
                'marginLeft' => '1701',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            ),
            'A3-landscape' => array(
                'width' => '23814',
                'height' => '16839',
                'numberCols' => '1',
                'orient' => 'landscape',
                'code' => '8',
                'marginTop' => '1701',
                'marginRight' => '1417',
                'marginBottom' => '1701',
                'marginLeft' => '1417',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            ),
            'letter' => array(
                'width' => '12240',
                'height' => '15840',
                'numberCols' => '1',
                'orient' => 'portrait',
                'code' => '1',
                'marginTop' => '1417',
                'marginRight' => '1701',
                'marginBottom' => '1417',
                'marginLeft' => '1701',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            ),
            'letter-landscape' => array(
                'width' => '15840',
                'height' => '12240',
                'numberCols' => '1',
                'orient' => 'landscape',
                'code' => '1',
                'marginTop' => '1701',
                'marginRight' => '1417',
                'marginBottom' => '1701',
                'marginLeft' => '1417',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            ),
            'legal' => array(
                'width' => '12240',
                'height' => '20160',
                'numberCols' => '1',
                'orient' => 'portrait',
                'code' => '5',
                'marginTop' => '1417',
                'marginRight' => '1701',
                'marginBottom' => '1417',
                'marginLeft' => '1701',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            ),
            'legal-landscape' => array(
                'width' => '20160',
                'height' => '12240',
                'numberCols' => '1',
                'orient' => 'landscape',
                'code' => '5',
                'marginTop' => '1701',
                'marginRight' => '1417',
                'marginBottom' => '1701',
                'marginLeft' => '1417',
                'marginHeader' => '708',
                'marginFooter' => '708',
                'gutter' => '0'
            )
        );
        try {
            if (!in_array($paperType, $paperTypes)) {
                throw new Exception('You have used an invalid paper size');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $layout = array();
        foreach ($layoutOptions as $opt) {
            if (isset($referenceSizes [$paperType] [$opt])) {
                $layout [$opt] = $referenceSizes [$paperType] [$opt];
            }
        }
        foreach ($layoutOptions as $opt) {
            if (isset($options [$opt])) {
                $layout [$opt] = $options [$opt];
            }
        }
        if (isset($layout ['width'])) {
            $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:w', $layout ['width']);
        }
        if (isset($layout ['height'])) {
            $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:h', $layout ['height']);
        }
        if (isset($layout ['orient'])) {
            $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:orient', $layout ['orient']);
        }
        if (isset($layout ['code'])) {
            $this->_sectPr->getElementsByTagName('pgSz')->item(0)->setAttribute('w:code', $layout ['code']);
        }
        if (isset($layout ['marginTop'])) {
            $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:top', $layout ['marginTop']);
        }
        if (isset($layout ['marginRight'])) {
            $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:right', $layout ['marginRight']);
        }
        if (isset($layout ['marginBottom'])) {
            $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:bottom', $layout ['marginBottom']);
        }
        if (isset($layout ['marginLeft'])) {
            $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:left', $layout ['marginLeft']);
        }
        if (isset($layout ['marginHeader'])) {
            $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:header', $layout ['marginHeader']);
        }
        if (isset($layout [$paperType] ['marginFooter'])) {
            $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:footer', $layout ['marginFooter']);
        }
        if (isset($layout ['gutter'])) {
            $this->_sectPr->getElementsByTagName('pgMar')->item(0)->setAttribute('w:gutter', $layout ['gutter']);
        }
        if (isset($layout ['numberCols'])) {
            if ($this->_sectPr->getElementsByTagName('cols')->length > 0) {
                $this->_sectPr->getElementsByTagName('cols')->item(0)->setAttribute('w:num', $layout ['numberCols']);
            } else {
                $colsNode = $this->_sectPr->createDocumentFragment();
                $colsNode->appendXML('<w:cols w:num="' . $layout ['numberCols'] . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" />');
                $this->_sectPr->documentElement->appendChild($colsNode);
            }
        }
    }

    private function parseWordMLNote($type, $wordMLFragment, $markOptions = array(), $referenceOptions = array()) {
        $strFrag = (string) $wordMLFragment;
        $basePIni = '<w:p><w:pPr><w:pStyle w:val="' . $type . 'TextPHPDOCX"/></w:pPr>';
        $run .= '<w:r><w:rPr><w:rStyle w:val="' . $type . 'ReferencePHPDOCX"/>';
        if (isset($referenceOptions ['font'])) {
            $run .= '<w:rFonts w:ascii="' . $referenceOptions ['font'] . '" w:hAnsi="' . $referenceOptions ['font'] . '" w:cs="' . $referenceOptions ['font'] . '"/>';
        }
        if (isset($referenceOptions ['b'])) {
            $run .= '<w:b w:val="' . $referenceOptions ['b'] . '"/>';
        }
        if (isset($referenceOptions ['i'])) {
            $run .= '<w:i w:val="' . $referenceOptions ['i'] . '"/>';
        }
        if (isset($referenceOptions ['color'])) {
            $run .= '<w:color w:val="' . $referenceOptions ['color'] . '"/>';
        }
        if (isset($referenceOptions ['sz'])) {
            $run .= '<w:sz w:val="' . (2 * $referenceOptions ['sz']) . '"/>';
            $run .= '<w:szCs w:val="' . (2 * $referenceOptions ['sz']) . '"/>';
        }
        $run .= '</w:rPr>';
        if (isset($markOptions ['customMark'])) {
            $run .= '<w:t>' . $markOptions ['customMark'] . '</w:t>';
        } else {
            if ($type != 'comment') {
                $run .= '<w:' . $type . 'Ref/>';
            }
        }
        $run .= '</w:r>';
        $basePEnd = '</w:p>';
        $startFrag = substr($strFrag, 0, 5);
        if ($startFrag == '<w:p>') {
            $strFrag = preg_replace('/<\/w:pPr>/', '</w:pPr>' . $run, $strFrag, 1);
        } else {
            $strFrag = $basePIni . $run . $basePEnd . $strFrag;
        }
        return $strFrag;
    }

    public function parseStyles($path = '') {
        if ($path != '') {
            $tempTitle = explode('/', $path);
            $title = array_pop($tempTitle);
            $parseStyles = new ZipArchive ();
            try {
                $openParseStyle = $parseStyles->open($path);
                if ($openParseStyle !== true) {
                    throw new Exception('Error while opening the Style sheet to be tested: please, check the path');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            try {
                $parsedStyles = $parseStyles->getFromName('word/styles.xml');
                if ($parsedStyles == '') {
                    throw new Exception('Error while extracting the styles to be parsed from the external docx');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            try {
                $parsedNumberings = $parseStyles->getFromName('word/numbering.xml');
                if ($parsedNumberings == '') {
                    throw new Exception('Error while extracting the numberings to be parsed from the external docx');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
        } else {
            $tempTitle = explode('/', PHPDOCX_BASE_TEMPLATE);
            $title = array_pop($tempTitle);
            $parsedStyles = $this->_baseTemplateZip->getFromName('word/styles.xml');
            $parsedNumberings = $this->_baseTemplateZip->getFromName('word/numbering.xml');
        }
        $myParagraph = 'This is some sample paragraph test';
        $myList = array(
            'item 1',
            'item 2',
            array(
                'subitem 2_1',
                'subitem 2_2'
            ),
            'item 3',
            array(
                'subitem 3_1',
                'subitem 3_2',
                array(
                    'sub_subitem 3_2_1',
                    'sub_subitem 3_2_1'
                )
            ),
            'item 4'
        );
        $myTable = array(
            array(
                'Title A',
                'Title B',
                'Title C'
            ),
            array(
                'First row A',
                'First row B',
                'First row C'
            ),
            array(
                'Second row A',
                'Second row B',
                'Second row C'
            )
        );
        $this->addText('List styles: ' . $title, array(
            'jc' => 'center',
            'color' => 'b90000',
            'b' => 'single',
            'sz' => '18',
            'u' => 'double'
        ));
        $wordListChunk = '<w:p><w:pPr><w:rPr><w:b/></w:rPr></w:pPr>
        <w:r><w:rPr><w:b/></w:rPr><w:t>SAMPLE CODE:</w:t></w:r>
        </w:p><w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/>
        <w:shd w:val="clear" w:color="auto" w:fill="DDD9C3"/>
        <w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid>
        <w:gridCol w:w="8644"/></w:tblGrid><w:tr><w:tc>
        <w:tcPr><w:tcW w:w="8644" w:type="dxa"/>
        <w:shd w:val="clear" w:color="auto" w:fill="DCDAC4"/>
        </w:tcPr><w:p><w:pPr><w:spacing w:before="200"/></w:pPr>
        <w:r><w:t>$</w:t></w:r><w:r>
        <w:t>myList</w:t></w:r><w:r>
        <w:t xml:space="preserve"> = array(\'item 1\', </w:t>
        </w:r><w:r>
        <w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r>
        <w:t xml:space="preserve">\'item 2\', </w:t>
        </w:r><w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t>array(\'</w:t></w:r><w:r><w:t>subitem</w:t>
        </w:r><w:r>
        <w:t xml:space="preserve"> 2_1\', </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                                        </w:t>
        </w:r><w:r><w:t>\'</w:t>
        </w:r><w:r><w:t>subitem</w:t></w:r><w:r>
        <w:t xml:space="preserve"> 2_2\'), </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t xml:space="preserve">\'item 3\', </w:t></w:r>
        <w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t>array(\'</w:t></w:r><w:r><w:t>subitem</w:t>
        </w:r><w:r><w:t xml:space="preserve"> 3_1\', </w:t></w:r>
        <w:r><w:br/>
        <w:t xml:space="preserve">                                        </w:t>
        </w:r><w:r><w:t>\'</w:t></w:r><w:r><w:t>subitem</w:t></w:r>
        <w:r><w:t xml:space="preserve"> 3_2\', </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                                        </w:t>
        </w:r><w:r><w:t>array(\'</w:t></w:r><w:r><w:t>sub_subitem</w:t></w:r><w:r>
        <w:t xml:space="preserve"> 3_2_1\', </w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                                                   </w:t>
        </w:r><w:r><w:t>\'</w:t></w:r><w:r><w:t>sub_subitem</w:t></w:r><w:r>
        <w:t xml:space="preserve"> 3_2_1\')),</w:t></w:r><w:r><w:br/>
        <w:t xml:space="preserve">                             </w:t>
        </w:r><w:r><w:t xml:space="preserve"> \'item 4\');</w:t></w:r></w:p>
        <w:p><w:pPr><w:spacing w:before="200"/></w:pPr>
        <w:r><w:t>addList</w:t></w:r><w:r><w:t>($</w:t></w:r>
        <w:r><w:t>myList</w:t></w:r><w:r><w:t>, array(\'</w:t></w:r>
        <w:r><w:t>val</w:t></w:r><w:r>
        <w:t>\' =</w:t></w:r><w:r><w:t>&gt; NUMID</w:t></w:r>
        <w:r><w:t>))</w:t></w:r></w:p></w:tc></w:tr></w:tbl><w:p><w:pPr></w:pPr>
        </w:p>
        <w:p><w:pPr><w:rPr><w:b/></w:rPr></w:pPr>
        <w:r><w:rPr><w:b/></w:rPr><w:t>SAMPLE RESULT:</w:t></w:r>
        </w:p>';
        if ($parsedNumberings) {
            $NumberingsDoc = new DOMDocument ();
            $NumberingsDoc->loadXML($parsedNumberings);
            $numberXpath = new DOMXPath($NumberingsDoc);
            $numberXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
            $queryNumber = '//w:num';
            $numberingsNodes = $numberXpath->query($queryNumber);
            foreach ($numberingsNodes as $node) {
                $wordListChunkTemp = str_replace('NUMID', $node->getAttribute('w:numId'), $wordListChunk);
                $this->_wordDocumentC .= $wordListChunkTemp;
                $this->addList($myList, array(
                    'val' => (int) $node->getAttribute('w:numId')
                ));
                $this->addBreak(array(
                    'type' => 'page'
                ));
            }
        }
        $this->addText('Paragraph and Table styles: ' . $title, array(
            'jc' => 'center',
            'color' => 'b90000',
            'b' => 'single',
            'sz' => '18',
            'u' => 'double'
        ));
        $StylesDoc = new DOMDocument ();
        $StylesDoc->loadXML($parsedStyles);
        $parseStylesXpath = new DOMXPath($StylesDoc);
        $parseStylesXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $query = '//w:style';
        $parsedNodes = $parseStylesXpath->query($query);
        $count = 1;
        foreach ($parsedNodes as $node) {
            $styleId = $node->getAttribute('w:styleId');
            $styleType = $node->getAttribute('w:type');
            $styleDefault = $node->getAttribute('w:default');
            $styleCustom = $node->getAttribute('w:custom');
            $nodeChilds = $node->childNodes;
            foreach ($nodeChilds as $child) {
                if ($child->nodeName == 'w:name') {
                    $styleName = $child->getAttribute('w:val');
                }
            }
            $this->parsedStyles [$count] = array(
                'id' => $styleId,
                'name' => $styleName,
                'type' => $styleType,
                'default' => $styleDefault,
                'custom' => $styleCustom
            );
            $default = ($styleDefault == 1) ? 'true' : 'false';
            $custom = ($styleCustom == 1) ? 'true' : 'false';
            $wordMLChunk = '<w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/>
                </w:tblPr><w:tblGrid><w:gridCol w:w="4322"/><w:gridCol w:w="4322"/>
                </w:tblGrid><w:tr><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="BD1503"/>
                </w:tcPr><w:p><w:pPr><w:spacing w:after="0"/><w:rPr>
                <w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r><w:rPr>
                <w:color w:val="FFFFFF"/></w:rPr><w:t>NAME:</w:t></w:r></w:p>
                </w:tc><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="BD1503"/></w:tcPr>
                <w:p><w:pPr><w:spacing w:after="0"/><w:rPr><w:color w:val="FFFFFF"/>
                </w:rPr></w:pPr><w:r><w:rPr><w:color w:val="FFFFFF"/>
                </w:rPr><w:t>' . $styleName . '</w:t></w:r></w:p></w:tc>
                </w:tr><w:tr><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>Type</w:t>
                </w:r><w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>:</w:t></w:r></w:p></w:tc><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>' . $styleType . '</w:t></w:r></w:p></w:tc></w:tr>
                <w:tr><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>ID:</w:t></w:r></w:p></w:tc><w:tc>
                <w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>' . $styleId . '</w:t></w:r></w:p></w:tc></w:tr><w:tr><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/></w:tcPr>
                <w:p><w:pPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>Default:</w:t></w:r>
                </w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr>
                <w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>' . $default . '</w:t></w:r></w:p></w:tc></w:tr><w:tr>
                <w:tc><w:tcPr><w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr>
                <w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>Custom</w:t>
                </w:r><w:r><w:rPr><w:color w:val="FFFFFF"/></w:rPr>
                <w:t>:</w:t></w:r></w:p></w:tc><w:tc><w:tcPr>
                <w:tcW w:w="4322" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="3E3E42"/>
                </w:tcPr><w:p><w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr></w:pPr><w:r>
                <w:rPr><w:color w:val="FFFFFF"/></w:rPr><w:t>' . $custom . '</w:t>
                </w:r></w:p></w:tc></w:tr></w:tbl>
                <w:p w:rsidR="000F6147" w:rsidRDefault="000F6147" w:rsidP="00B42E7D">
                <w:pPr><w:spacing w:after="0"/></w:pPr></w:p>
                <w:p w:rsidR="00DC3ACE" w:rsidRDefault="00DC3ACE">
                <w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:r>
                <w:rPr><w:b/></w:rPr><w:t>SAMPLE CODE:</w:t></w:r></w:p>
                <w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/>
                <w:shd w:val="clear" w:color="auto" w:fill="DDD9C3"/>
                </w:tblPr><w:tblGrid><w:gridCol w:w="8644"/>
                </w:tblGrid><w:tr><w:tc><w:tcPr><w:tcW w:w="8644" w:type="dxa"/>
                <w:shd w:val="clear" w:color="auto" w:fill="DCDAC4"/></w:tcPr>
                <w:p w:rsidR="00DC3ACE" w:rsidRDefault="00DC3ACE">
                <w:pPr><w:spacing w:before="200" /></w:pPr><w:r>
                <w:t>CODEX</w:t></w:r></w:p></w:tc></w:tr></w:tbl><w:p/><w:p>
                <w:pPr><w:rPr><w:b/></w:rPr></w:pPr><w:r><w:rPr><w:b/>
                </w:rPr><w:t>SAMPLE RESULT:</w:t></w:r></w:p>
                ';
            switch ($styleType) {
                case 'table' :
                    $wordMLChunk = str_replace('CODEX', "addTable(array(array('Title A','Title B','Title C'),array('First row A','First row B','First row C'),array('Second row A','Second row B','Second row C')), array('TBLSTYLEval'=> '$styleId'))", $wordMLChunk);
                    $this->_wordDocumentC .= $wordMLChunk;
                    $params = array(
                        'TBLSTYLEval' => $styleId
                    );
                    $this->addTable($myTable, $params);
                    if ($count % 2 == 0) {
                        $this->_wordDocumentC .= '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
                    } else {
                        $this->_wordDocumentC .= '<w:p /><w:p />';
                    }
                    $count ++;
                    break;
                case 'paragraph' :
                    $myPCode = "addText('This is some sample paragraph test', array('pStyle' => '" . $styleId . "'))";
                    $wordMLChunk = str_replace('CODEX', $myPCode, $wordMLChunk);
                    $this->_wordDocumentC .= $wordMLChunk;
                    $params = array(
                        'pStyle' => $styleId
                    );
                    $this->addText($myParagraph, $params);
                    if ($count % 2 == 0) {
                        $this->_wordDocumentC .= '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
                    } else {
                        $this->_wordDocumentC .= '<w:p /><w:p />';
                    }
                    $count ++;
                    break;
            }
        }
    }

    public function readDOCX($path) {
        $parser = Parser::getInstance();
        try {
            $parser->readFile($path);
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
//PhpdocxLogger::logger ( 'Read DOCX file.', 'info' );
    }

    public function replaceHeaderImage($var, $pathImage) {
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Assign and replace image variable ' . $var . ' in header.', 'info' );
        $template->replaceHeaderImage($var, $pathImage);
    }

    public function replaceTemplateVariableByHTML($var, $type, $html = '<html><body></body></html>', $options = array()) {
        $htmlDOCX = new HTML2WordML($this->_baseTemplateFilesPath);
        $wordHTML = $htmlDOCX->render($html, $options);
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Assign and replace  variable ' . $var . ' in template by HTML.', 'info' );
        if ($type == 'block') {
            $wordHTMLString = $wordHTML [0];
        } else {
            $wordHTMLString = $this->cleanWordMLBlockElements($wordHTML [0]);
        }
        $template->replaceVariableByHTML($var, $type, $wordHTMLString);
        $template->TemplateHTMLRels($wordHTML, $options, $this->_baseTemplateFilesPath);
        if (is_array($this->_templateNumberings)) {
            $this->_templateNumberings = array_merge($this->_templateNumberings, $wordHTML [3]);
        } else {
            $this->_templateNumberings = $wordHTML [3];
        }
        if (is_array($this->_templateCustomNumberings)) {
            $this->_templateCustomNumberings = array_merge($this->_templateCustomNumberings, $wordHTML [4]);
        } else {
            $this->_templateCustomNumberings = $wordHTML [4];
        }
    }

    public function setBackgroundColor($color) {
        $this->_backgroundColor = $color;
        if ($this->_background == '') {
            $this->_background = '<w:background w:color="' . $color . '" />';
            try {
                $settings = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "r");
                $baseTemplateSettingsT = fread($settings, 1000000);
                fclose($settings);
                if ($baseTemplateSettingsT == '') {
                    throw new Exception('Error while extracting settings.xml file from the base template to insert the background image');
                }
            } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
            }
            $this->_wordSettingsT = new DOMDocument ();
            $this->_wordSettingsT->loadXML($baseTemplateSettingsT);
            $settingsImage = $this->_wordSettingsT->createDocumentFragment();
            $settingsImage->appendXML('<w:displayBackgroundShape xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" />');
            $this->_wordSettingsT->documentElement->appendChild($settingsImage);
            $newSettings = $this->_wordSettingsT->saveXML();
            $settingsHandle = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "w+");
            $contents = fwrite($settingsHandle, $newSettings);
            fclose($settingsHandle);
        } else {
            $this->_background = str_replace('w:color="FFFFFF"', 'w:color="' . $color . '"', $this->_background);
        }
    }

    public function setDecimalSymbol($symbol) {
        try {
            $settingsHandler = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', 'r');
            $settingsXML = fread($settingsHandler, filesize($this->_baseTemplateFilesPath . '/word/settings.xml'));
            fclose($settingsHandler);
            if ($settingsXML == '') {
                throw new Exception('Error while extracting the settings file from the base template to stablish default decimal symbol');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $settingsDocument = new DomDocument ();
        $settingsDocument->loadXML($settingsXML);
        $decimalNodes = $settingsDocument->getElementsByTagName('decimalSymbol');
        $decimalNode = $decimalNodes->item(0);
        $newDecimalNode = $settingsDocument->createElement('w:decimalSymbol', '');
        $newDecimalNode->setAttribute('w:val', $symbol);
        $decimalNode->parentNode->replaceChild($newDecimalNode, $decimalNode);
        $settingsXML = $settingsDocument->saveXML();
        try {
            $settingsFile = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', 'w');
            if ($settingsFile == false) {
                throw new Exception('Error while opening the base template settings.xml file');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $writeSettings = fwrite($settingsFile, $settingsXML);
            if ($writeSettings == 0) {
                throw new Exception('There was an error while trying to set the decimal Symbol');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
//PhpdocxLogger::logger ( 'Change decimal symbol.', 'info' );
    }

    public function setDefaultFont($font) {
        $this->_defaultFont = $font;
        try {
            $themeHandler = fopen($this->_baseTemplateFilesPath . '/word/theme/theme1.xml', 'r');
            $themeXML = fread($themeHandler, filesize($this->_baseTemplateFilesPath . '/word/theme/theme1.xml'));
            fclose($themeHandler);
            if ($themeXML == '') {
                throw new Exception('Error while extracting the theme file from the base template to stablish default font');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $themeDocument = new DomDocument ();
        $themeDocument->loadXML($themeXML);
        $latinNode = $themeDocument->getElementsByTagName('latin');
        $latinNode->item(0)->setAttribute('typeface', $font);
        $latinNode->item(1)->setAttribute('typeface', $font);
        $themeXML = $themeDocument->saveXML();
        try {
            $themeFile = fopen($this->_baseTemplateFilesPath . '/word/theme/theme1.xml', 'w');
            if ($themeFile == false) {
                throw new Exception('Error while opening the base template theme1.xml file');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $writeTheme = fwrite($themeFile, $themeXML);
            if ($writeTheme == 0) {
                throw new Exception('There was an error while trying to set the default font');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
//PhpdocxLogger::logger ( 'Change default font.', 'info' );
    }

    public function setEncodeUTF8() {
        self::$_encodeUTF = 1;
    }

    public function setLanguage($lang = null) {
        if (!$lang) {
            $lang = 'en-US';
        }
        try {
            $styleHandler = fopen($this->_baseTemplateFilesPath . '/word/styles.xml', 'r');
            $styleXML = fread($styleHandler, 10000000);
            fclose($styleHandler);
            $this->_wordStylesT = $styleXML;
            if ($styleXML == '') {
                throw new Exception('Error while extracting the style file from the base template to stablish default language');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $stylesDocument = new DomDocument ();
        $stylesDocument->loadXML($this->_wordStylesT);
        $langNode = $stylesDocument->getElementsByTagName('lang');
        $langNode->item(0)->setAttribute('w:val', $lang);
        $langNode->item(0)->setAttribute('w:eastAsia', $lang);
        $this->_wordStylesT = $stylesDocument->saveXML();
        try {
            $stylesFile = fopen($this->_baseTemplateFilesPath . '/word/styles.xml', 'w');
            if ($stylesFile == false) {
                throw new Exception('Error while opening the base template styles.xml file');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $writeStyles = fwrite($stylesFile, $this->_wordStylesT);
            if ($writeStyles == 0) {
                throw new Exception('There was an error while trying to set the default language');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
//PhpdocxLogger::logger ( 'Set language.', 'info' );
    }

    public function setMarkAsFinal() {
        $this->_markAsFinal = 1;
        $this->addProperties(array(
            'contentStatus' => 'Final'
        ));
        $this->generateOVERRIDE('/docProps/custom.xml', 'application/vnd.openxmlformats-officedocument.' . 'custom-properties+xml');
    }

    public function setTemplateSymbol($symbol = '$') {
//PhpdocxLogger::logger ( 'Change template symbol.', 'info' );
        CreateTemplate::setTemplateSymbol($symbol);
    }

    public function tickCheckbox($var, $value = 1) {
        if ($value != 0 && $value != 1) {
//PhpdocxLogger::logger ( 'The value maust be 0 or 1', 'fatal' );
        }
        $template = CreateTemplate::getInstance();
//PhpdocxLogger::logger ( 'Tick or not a checkbox', 'info' );
        $template->checkCheckbox($var, $value);
    }

    public function transformDocx($docSource, $docDestination, $tempDir = null, $options = array(), $version = null) {
        try {
            if (!$this->_compatibilityMode) {
                throw new Exception('Running in compatibility mode. Unsupported method.');
            }
            $convert = new TransformDocAdv ();
            $convert->transformDocument($docSource, $docDestination, $tempDir, $options, $version);
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function transformDocxUsingMSWord($docSource, $docDestination) {
        $convert = new MSWordInterface ();
        $convert->transformFormat($docSource, $docDestination);
    }

    public function txt2docx($text_filename, $options = array()) {
        $text = new Text2Docx($text_filename, $options);
//PhpdocxLogger::logger ( 'Add text from text file.', 'info' );
        $this->_wordDocumentC .= (string) $text;
    }

    public function createListStyle($name, $listOptions = array()) {
        $newStyle = new CreateListStyle ();
        $style = $newStyle->addListStyle($name, $listOptions);
        $listId = rand(9999, 999999999);
        $this->_wordNumberingT = $this->importSingleNumbering($this->_wordNumberingT, $style, $listId);
        self::$customLists [$name] ['id'] = $listId;
        self::$customLists [$name] ['wordML'] = $style;
    }

    public function createParagraphStyle($name, $styleOptions = array()) {
        $newStyle = new CreateParagraphStyle ();
        $style = $newStyle->addParagraphStyle($name, $styleOptions);
        try {
            $styleHandler = fopen($this->_baseTemplateFilesPath . '/word/styles.xml', 'r');
            $styleXML = fread($styleHandler, 1000000);
            fclose($styleHandler);
            if ($styleXML == '') {
                throw new Exception('Error while extracting the style file from the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $styleXML = str_replace('</w:styles>', $style [0] . '</w:styles>', $styleXML);
        $styleXML = str_replace('</w:styles>', $style [1] . '</w:styles>', $styleXML);
        try {
            $stylesFile = fopen($this->_baseTemplateFilesPath . '/word/styles.xml', 'w');
            if ($stylesFile == false) {
                throw new Exception('Error while opening the base template styles.xml file');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $writeStyles = fwrite($stylesFile, $styleXML);
            if ($writeStyles == 0) {
                throw new Exception('There was an error while inserting the new style');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    public function createWordMLFragment($data) {
        $wordMLFragment = new WordMLFragment ();
        if (!empty($data) && !is_array($data)) {
            $wordMLFragment->addRawWordML($data);
        } else if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                $wordMLFragment->addRawWordML($value);
            }
        }
        return $wordMLFragment;
    }

    private function addDefaultEndnote() {
        $endnote = CreateEndnote::getInstance();
        $endnote->createInitEndnote(array(
            'type' => 'separator'
        ));
        $this->_wordEndnotesC .= (string) $endnote;
        $endnote = CreateEndnote::getInstance();
        $endnote->createInitEndnote(array(
            'type' => 'continuationSeparator'
        ));
        $this->_wordEndnotesC .= (string) $endnote;
    }

    private function addDefaultFootnote() {
        $footnote = CreateFootnote::getInstance();
        $footnote->createInitFootnote(array(
            'type' => 'separator'
        ));
        $this->_wordFootnotesC .= (string) $footnote;
        $footnote = CreateFootnote::getInstance();
        $footnote->createInitFootnote(array(
            'type' => 'continuationSeparator'
        ));
        $this->_wordFootnotesC .= (string) $footnote;
    }

    private function addImageFooter($dats = '') {
        try {
            if (isset($dats ['name']) && file_exists($dats ['name'])) {
                $arrAtributos = getimagesize($dats ['name']);
                try {
                    if ($arrAtributos ['mime'] == 'image/jpg' || $arrAtributos ['mime'] == 'image/jpeg' || $arrAtributos ['mime'] == 'image/png' || $arrAtributos ['mime'] == 'image/gif') {
                        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Image footer.', 'debug' );
                        $img = CreateImage::getInstance();
                        $dats ['rId'] = self::$intIdWord;
                        $img->createImage($dats);
                        $xmlHeaderImg = (string) $img;
                        $dir = $this->parsePath($dats ['name']);
//PhpdocxLogger::logger ( 'Add image header word/media/image' . self::$intIdWord . '.' . $dir ['extension'] . '.xml to DOCX.', 'info' );
                        $this->_zipDocx->addFile($dats ['name'], 'word/media/image' . self::$intIdWord . '.' . $dir ['extension']);
                        $this->generateDEFAULT($dir ['extension'], $arrAtributos ['mime']);
                        if ((string) $img != '') {
                            $this->_wordRelsFooterRelsC .= $this->generateRELATIONSHIPTemplate('rId' . self::$intIdWord, 'image', 'media/image' . self::$intIdWord . '.' . $dir ['extension']);
                            return $xmlHeaderImg;
                        } else {
                            throw new Exception('Image format is not supported.');
                        }
                    } else {
                        throw new Exception('Image does not exist.');
                    }
                } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                }
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    private function addImageHeader($dats = '') {
        try {
            if (isset($dats ['name']) && file_exists($dats ['name'])) {
                $attrImage = getimagesize($dats ['name']);
                try {
                    if ($attrImage ['mime'] == 'image/jpg' || $attrImage ['mime'] == 'image/jpeg' || $attrImage ['mime'] == 'image/png' || $attrImage ['mime'] == 'image/gif') {
                        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Image header.', 'debug' );
                        $img = CreateImage::getInstance();
                        $dats ['rId'] = self::$intIdWord;
                        $img->createImage($dats);
                        $xmlHeaderImg = (string) $img;
                        $dir = $this->parsePath($dats ['name']);
//PhpdocxLogger::logger ( 'Add image header word/media/image' . self::$intIdWord . '.' . $dir ['extension'] . '.xml to DOCX.', 'info' );
                        $this->_zipDocx->addFile($dats ['name'], 'word/media/image' . self::$intIdWord . '.' . $dir ['extension']);
                        $this->generateDEFAULT($dir ['extension'], $attrImage ['mime']);
                        if ((string) $img != '') {
                            $this->_wordRelsHeaderRelsC .= $this->generateRELATIONSHIPTemplate('rId' . self::$intIdWord, 'image', 'media/image' . self::$intIdWord . '.' . $dir ['extension']);
                            return $xmlHeaderImg;
                        } else {
                            throw new Exception('Image format is not supported.');
                        }
                    } else {
                        throw new Exception('Image does not exist.');
                    }
                } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                }
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    private function addSettings() {
        $settings = CreateSettings::getInstance();
        $settings->generateSettings();
//PhpdocxLogger::logger ( 'Add settings to settings document.', 'info' );
        $this->_wordSettingsC .= (string) $settings;
    }

    private function addStyle($lang = 'en-US') {
        $style = CreateStyle::getInstance();
        $style->createStyle($lang);
//PhpdocxLogger::logger ( 'Add styles to styles document.', 'info' );
        $this->_wordStylesC .= (string) $style;
    }

    private function addStylesTemplate($templateStyles, $importedStylesheet) {
        $templateStylesheet = new DomDocument ();
        $templateStylesheet->loadXML($templateStyles);
        $stylesXpath = new DOMXPath($importedStylesheet);
        $stylesXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $queryStyle = '//w:style';
        $styleNodes = $stylesXpath->query($queryStyle);
        $stylesDocument = new DomDocument ();
        $stylesDocument->loadXML($templateStyles);
        $baseNode = $stylesDocument->documentElement;
        foreach ($styleNodes as $node) {
            $originalNodes = $stylesDocument->childNodes;
            foreach ($originalNodes as $oldNode) {
                if ($oldNode->getAttribute('w:styleId') == $node->getAttribute('w:styleId')) {
                    $oldNode->parent->removeChild($oldNode);
                }
            }
            $insertNode = $stylesDocument->importNode($node, true);
            $baseNode->appendChild($insertNode);
        }
//PhpdocxLogger::logger ( 'Importing styles into the template stylesheet.', 'info' );
        return $stylesDocument->saveXML();
    }

    private function addWebSettings() {
        $webSettings = CreateWebSettings::getInstance();
        $webSettings->generateWebSettings();
//PhpdocxLogger::logger ( 'Add web settings to web settings document.', 'info' );
        $this->_wordWebSettingsC .= (string) $webSettings;
    }

    private function cleanTemplate() {
//PhpdocxLogger::logger ( 'Remove existing template tags.', 'debug' );
        $this->_wordDocumentT = preg_replace('/__[A-Z]+__/', '', $this->_wordDocumentT);
    }

    private function generateContentType() {
        try {
            GenerateDocx::beginDocx();
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $this->generateDEFAULT('rels', 'application/vnd.openxmlformats-package.relationships+xml');
        $this->generateDEFAULT('xml', 'application/xml');
        $this->generateDEFAULT('htm', 'application/xhtml+xml');
        $this->generateDEFAULT('rtf', 'application/rtf');
        $this->generateDEFAULT('zip', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
        $this->generateDEFAULT('mht', 'message/rfc822');
        $this->generateDEFAULT('wml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
        $this->generateOVERRIDE('/word/numbering.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.' . 'numbering+xml');
        $this->generateOVERRIDE('/word/styles.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml' . '.styles+xml');
        $this->generateOVERRIDE('/docProps/app.xml', 'application/vnd.openxmlformats-officedocument.extended-' . 'properties+xml');
        $this->generateOVERRIDE('/docProps/custom.xml', 'application/vnd.openxmlformats-officedocument.' . 'custom-properties+xml');
        $this->generateOVERRIDE('/word/settings.xml', 'application/' . 'vnd.openxmlformats-officedocument.wordprocessingml.settings+xml');
        $this->generateOVERRIDE('/word/theme/theme1.xml', 'application/vnd.openxmlformats-officedocument.theme+xml');
        $this->generateOVERRIDE('/word/fontTable.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.' . 'fontTable+xml');
        $this->generateOVERRIDE('/word/webSettings.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml' . '.webSettings+xml');
        if ($this->_wordFooterC != '' || $this->_wordHeaderC != '') {
            $this->generateOVERRIDE('/word/header.xml', 'application/vnd.openxmlformats-officedocument.' . 'wordprocessingml.header+xml');
            $this->generateOVERRIDE('/word/footer.xml', 'application/vnd.openxmlformats-officedocument.' . 'wordprocessingml.footer+xml');
            $this->generateOVERRIDE('/word/footnotes.xml', 'application/vnd.openxmlformats-officedocument.' . 'wordprocessingml.footnotes+xml');
            $this->generateOVERRIDE('/word/endnotes.xml', 'application/vnd.openxmlformats-officedocument.' . 'wordprocessingml.endnotes+xml');
        }
        $this->generateOVERRIDE('/docProps/core.xml', 'application/vnd.openxmlformats-package.core-properties+xml');
    }

    private function generateDEFAULT($extension, $contentType) {
        $strContent = $this->_contentTypeT->saveXML();
        if (strpos($strContent, 'Extension="' . $extension) === false) {
            $strContentTypes = '<Default Extension="' . $extension . '" ContentType="' . $contentType . '"> </Default>';
            $tempNode = $this->_contentTypeT->createDocumentFragment();
            $tempNode->appendXML($strContentTypes);
            $this->_contentTypeT->documentElement->appendChild($tempNode);
        }
    }

    private function generateDefaultFonts() {
        $font = array(
            'name' => 'Calibri',
            'pitch' => 'variable',
            'usb0' => 'A00002EF',
            'usb1' => '4000207B',
            'usb2' => '00000000',
            'usb3' => '00000000',
            'csb0' => '0000009F',
            'csb1' => '00000000',
            'family' => 'swiss',
            'charset' => '00',
            'panose1' => '020F0502020204030204'
        );
        $this->addFont($font);
        $font = array(
            'name' => 'Times New Roman',
            'pitch' => 'variable',
            'usb0' => 'E0002AEF',
            'usb1' => 'C0007841',
            'usb2' => '00000009',
            'usb3' => '00000000',
            'csb0' => '000001FF',
            'csb1' => '00000000',
            'family' => 'roman',
            'charset' => '00',
            'panose1' => '02020603050405020304'
        );
        $this->addFont($font);
        $font = array(
            'name' => 'Cambria',
            'pitch' => 'variable',
            'usb0' => 'A00002EF',
            'usb1' => '4000004B',
            'usb2' => '00000000',
            'usb3' => '00000000',
            'csb0' => '0000009F',
            'csb1' => '00000000',
            'family' => 'roman',
            'charset' => '00',
            'panose1' => '02040503050406030204'
        );
        $this->addFont($font);
    }

    private function generateDefaultWordRels() {
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . numbering.xml.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'numbering', 'numbering.xml');
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . theme/theme1.xml.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'theme', 'theme/theme1.xml');
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . numbering.xml.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'webSettings', 'webSettings.xml');
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . webSettings.xml.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'fontTable', 'fontTable.xml');
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . fontTable.xml.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'settings', 'settings.xml');
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . settings.xml.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'styles', 'styles.xml');
    }

    private function generateOVERRIDE($partName, $contentType) {
        $strContent = $this->_contentTypeT->saveXML();
        if (strpos($strContent, 'PartName="' . $partName . '"') === false) {
            $strContentTypes = '<Override PartName="' . $partName . '" ContentType="' . $contentType . '" />';
            $tempNode = $this->_contentTypeT->createDocumentFragment();
            $tempNode->appendXML($strContentTypes);
            $this->_contentTypeT->documentElement->appendChild($tempNode);
        }
    }

    private function generateRELATIONSHIP() {
        $arrArgs = func_get_args();
        if ($arrArgs [1] == 'vbaProject') {
            $type = 'http://schemas.microsoft.com/office/2006/relationships/vbaProject';
        } else {
            $type = 'http://schemas.openxmlformats.org/officeDocument/2006/' . 'relationships/' . $arrArgs [1];
        }
        if (!isset($arrArgs [3])) {
            $nodeWML = '<Relationship Id="' . $arrArgs [0] . '" Type="' . $type . '" Target="' . $arrArgs [2] . '"></Relationship>';
        } else {
            $nodeWML = '<Relationship Id="' . $arrArgs [0] . '" Type="' . $type . '" Target="' . $arrArgs [2] . '" ' . $arrArgs [3] . '></Relationship>';
        }
        $relsNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
        $relsNode->appendXML($nodeWML);
        $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNode);
    }

    private function generateRELATIONSHIPTemplate() {
        $arrArgs = func_get_args();
        if ($arrArgs [1] == 'vbaProject') {
            $type = 'http://schemas.microsoft.com/office/2006/relationships/vbaProject';
        } else {
            $type = 'http://schemas.openxmlformats.org/officeDocument/2006/' . 'relationships/' . $arrArgs [1];
        }
        if (!isset($arrArgs [3])) {
            $nodeWML = '<Relationship Id="' . $arrArgs [0] . '" Type="' . $type . '" Target="' . $arrArgs [2] . '"></Relationship>';
        } else {
            $nodeWML = '<Relationship Id="' . $arrArgs [0] . '" Type="' . $type . '" Target="' . $arrArgs [2] . '" ' . $arrArgs [3] . '></Relationship>';
        }
        return $nodeWML;
    }

    private function generateRelsNotes($type) {
        try {
            $relsFile = $this->_baseTemplateZip->getFromName('word/_rels/' . $type . 's.xml.rels');
            if (!$relsFile) {
                $relsFile = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"></Relationships>';
                throw new Exception('The ' . $type . '.xml.rels file from the base template was not found. PHPDocX generated it.');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'info' );
        }
        $relsDOM = new DOMDocument ();
        $relsDOM->loadXML($relsFile);
        if (!empty($this->_relsNotesImage [$type])) {
            foreach ($this->_relsNotesImage [$type] as $key => $value) {
                $nodeWML = '<Relationship Id="' . $value ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/img' . $value ['rId'] . '.' . $value ['extension'] . '" ></Relationship>';
                $relsNode = $relsDOM->createDocumentFragment();
                $relsNode->appendXML($nodeWML);
                $relsDOM->documentElement->appendChild($relsNode);
            }
        }
        if (!empty($this->_relsNotesExternalImage [$type])) {
            foreach ($this->_relsNotesExternalImage [$type] as $key => $value) {
                $nodeWML = '<Relationship Id="' . $value ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="' . $value ['url'] . '" TargetMode="External" ></Relationship>';
                $relsNode = $relsDOM->createDocumentFragment();
                $relsNode->appendXML($nodeWML);
                $relsDOM->documentElement->appendChild($relsNode);
            }
        }
        if (!empty($this->_relsNotesLink [$type])) {
            foreach ($this->_relsNotesLink [$type] as $key => $value) {
                $nodeWML = '<Relationship Id="' . $value ['rId'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink" Target="' . $value ['url'] . '" TargetMode="External" ></Relationship>';
                $relsNode = $relsDOM->createDocumentFragment();
                $relsNode->appendXML($nodeWML);
                $relsDOM->documentElement->appendChild($relsNode);
            }
        }
        $noteHandler = fopen($this->_baseTemplateFilesPath . '/word/_rels/' . $type . 's.xml.rels', 'w+');
        fwrite($noteHandler, $relsDOM->saveXML());
        fclose($noteHandler);
    }

    private function generateSECTPR($args = '') {
        $page = CreatePage::getInstance();
        $page->createSECTPR($args);
        $this->_wordDocumentC .= (string) $page;
    }

    private function generateSetting($tag) {
        if ((!in_array($tag, self::$settings))) {
//PhpdocxLogger::logger ( 'Incorrect setting tag', 'fatal' );
        }
        $settingIndex = array_search($tag, self::$settings);
        try {
            $settings = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "r");
            $baseTemplateSettingsT = fread($settings, 1000000);
            fclose($settings);
            if ($baseTemplateSettingsT == '') {
                throw new Exception('Error while extracting settings.xml file from the base template to insert the selected element');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $this->_wordSettingsT = new DOMDocument ();
        $this->_wordSettingsT->loadXML($baseTemplateSettingsT);
        $selectedElements = $this->_wordSettingsT->documentElement->getElementsByTagName($tag);
        if ($selectedElements->length == 0) {
            $settingsElement = $this->_wordSettingsT->createDocumentFragment();
            $settingsElement->appendXML('<' . $tag . ' xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" />');
            $childNodes = $this->_wordSettingsT->documentElement->childNodes;
            $index = false;
            foreach ($childNodes as $node) {
                $name = $node->nodeName;
                $index = array_search($node->nodeName, self::$settings);
                if ($index > $settingIndex) {
                    $node->parentNode->insertBefore($settingsElement, $node);
                    break;
                }
            }
            if (!$index) {
                $this->_wordSettingsT->documentElement->appendChild($settingsElement);
            }
            $newSettings = $this->_wordSettingsT->saveXML();
            $settingsHandle = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "w+");
            $contents = fwrite($settingsHandle, $newSettings);
            fclose($settingsHandle);
        }
    }

    private function generateTemplateContentType() {
        $this->_wordContentTypeT = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/' . 'content-types">' . $this->_contentTypeC . '</Types>';
    }

    private function generateTemplateDocPropsApp() {
        $this->_docPropsAppT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<Properties xmlns="http://schemas.openxmlformats.org/' . 'officeDocument/2006/extended-properties" xmlns:vt="' . 'http://schemas.openxmlformats.org/officeDocument/2006/' . 'docPropsVTypes"><Template>Normal.dotm</Template><TotalTime>' . '0</TotalTime><Pages>1</Pages><Words>1</Words><Characters>1' . '</Characters><Application>Microsoft Office Word</Application>' . '<DocSecurity>4</DocSecurity><Lines>1</Lines><Paragraphs>1' . '</Paragraphs><ScaleCrop>false</ScaleCrop>';
        if ($this->_docPropsAppC) {
            $this->_docPropsAppT .= $this->_docPropsAppC;
        } else {
            $this->_docPropsAppT .= '<Company>Company</Company>';
        }
        $this->_docPropsAppT .= '<LinksUpToDate>false</LinksUpToDate>' . '<CharactersWithSpaces>1</CharactersWithSpaces><SharedDoc>' . 'false</SharedDoc><HyperlinksChanged>false</HyperlinksChanged>' . '<AppVersion>12.0000</AppVersion></Properties>';
    }

    private function generateTemplateDocPropsCore() {
        date_default_timezone_set('UTC');
        if ($this->_markAsFinal) {
            $this->_docPropsCoreT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats' . '.org/package/2006/metadata/core-properties" ' . 'xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms' . '="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl' . '.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XML' . 'Schema-instance"><dc:title>Title</dc:title><dc:subject>' . 'Subject</dc:subject><dc:creator>2mdc</dc:creator>' . '<dc:description>Description</dc:description>' . '<cp:lastModifiedBy>user</cp:lastModifiedBy><cp:revision>1' . '</cp:revision><dcterms:created xsi:type="dcterms:W3CDTF">' . date('c') . '</dcterms:created><dcterms:modified ' . 'xsi:type="dcterms:W3CDTF">' . date('c') . '</dcterms:modified><cp:contentStatus>Final' . '</cp:contentStatus></cp:coreProperties>';
        } else {
            $this->_docPropsCoreT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?> ' . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats' . '.org/package/2006/metadata/core-properties" ' . 'xmlns:dc="http://purl.org/dc/elements/1.1/" ' . 'xmlns:dcterms="http://purl.org/dc/terms/" ' . 'xmlns:dcmitype="http://purl.org/dc/dcmitype/" ' . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . '<dc:title>Title</dc:title><dc:subject>Subject</dc:subject>' . '<dc:creator>2mdc</dc:creator><dc:description>Description' . '</dc:description><cp:lastModifiedBy>user' . '</cp:lastModifiedBy><cp:revision>1</cp:revision>' . '<dcterms:created xsi:type="dcterms:W3CDTF">' . date('c') . '</dcterms:created><dcterms:modified xsi:type="dcterms:W3CDTF' . '">' . date('c') . '</dcterms:modified></cp:coreProperties>';
        }
    }

    private function generateTemplateDocPropsCustom() {
        $this->_docPropsCustomT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<Properties xmlns="http://schemas.openxmlformats.org/' . 'officeDocument/2006/custom-properties" xmlns:vt="http://' . 'schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">' . '<property fmtid="{D5CDD505-2E9C-101B-9397-08002B2CF9AE}" ' . 'pid="2" name="_MarkAsFinal"><vt:bool>true</vt:bool></property>' . '</Properties>';
    }

    private function generateTemplateRelsRels() {
        $this->_relsRelsT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<Relationships xmlns="http://schemas.openxmlformats.org/package/' . '2006/relationships">' . $this->generateRELATIONSHIP('rId3', 'extended-properties', 'docProps/app.xml') . '<Relationship Id="rId2" Type="http://schemas.openxmlformats' . '.org/package/2006/relationships/metadata/core-properties"' . ' Target="docProps/core.xml"/>' . $this->generateRELATIONSHIP('rId1', 'officeDocument', 'word/document.xml');
        if ($this->_markAsFinal) {
            $this->_relsRelsT .= '<Relationship Id="rId4" Type="http://schemas' . '.openxmlformats.org/officeDocument/2006/relationships/' . 'custom-properties" Target="docProps/custom.xml"/>';
        }
        $this->_relsRelsT .= '</Relationships>';
    }

    private function generateTemplateWordDocument() {
        $arrArgs = func_get_args();
        $this->_wordDocumentC .= $this->_sectPr->saveXML($this->_sectPr->documentElement);
        if (!empty($this->_wordHeaderC)) {
            $this->_wordDocumentC = str_replace('__GENERATEHEADERREFERENCE__', '<' . CreateDocx::NAMESPACEWORD . ':headerReference ' . CreateDocx::NAMESPACEWORD . ':type="default" r:id="rId' . $this->_idWords ['header'] . '"></' . CreateDocx::NAMESPACEWORD . ':headerReference>', $this->_wordDocumentC);
        }
        if (!empty($this->_wordFooterC)) {
            $this->_wordDocumentC = str_replace('__GENERATEFOOTERREFERENCE__', '<' . CreateDocx::NAMESPACEWORD . ':footerReference ' . CreateDocx::NAMESPACEWORD . ':type="default" r:id="rId' . $this->_idWords ['footer'] . '"></' . CreateDocx::NAMESPACEWORD . ':footerReference>', $this->_wordDocumentC);
        }
        $this->_wordDocumentT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<' . CreateDocx::NAMESPACEWORD . ':document xmlns:ve=' . '"http://schemas.openxmlformats.org/markup-compatibility/2006" ' . 'xmlns:o="urn:schemas-microsoft-com:office:office"' . ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006' . '/relationships" xmlns:m="http://schemas.openxmlformats.org/' . 'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml"' . ' xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/' . 'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' . 'office:word" xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" xmlns:wne="http://schemas' . '.microsoft.com/office/word/2006/wordml">' . $this->_background . '<' . CreateDocx::NAMESPACEWORD . ':body>' . $this->_wordDocumentC . '</' . CreateDocx::NAMESPACEWORD . ':body>' . '</' . CreateDocx::NAMESPACEWORD . ':document>';
        $this->cleanTemplate();
    }

    private function generateTemplateWordEndnotes() {
        $this->_wordEndnotesT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<' . CreateDocx::NAMESPACEWORD . ':endnotes xmlns:ve' . '="http://schemas.openxmlformats.org/markup-compatibility/2006" ' . 'xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="' . 'http://schemas.openxmlformats.org/officeDocument/2006/' . 'relationships" xmlns:m="http://schemas.openxmlformats.org/' . 'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:' . 'vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006' . '/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' . 'office:word" xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" xmlns:wne="http://schemas' . '.microsoft.com/office/word/2006/wordml">' . $this->_wordEndnotesC . '</' . CreateDocx::NAMESPACEWORD . ':endnotes>';
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Endnotes.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'endnotes', 'endnotes.xml');
        $this->generateOVERRIDE('/word/endnotes.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml' . '.endnotes+xml');
    }

    private function generateTemplateWordFontTable() {
        $this->_wordFontTableT = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' . '<' . CreateDocx::NAMESPACEWORD . ':fonts xmlns:r="http://' . 'schemas.openxmlformats.org/officeDocument/2006/' . 'relationships" xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main">' . $this->_wordFontTableC . '</' . CreateDocx::NAMESPACEWORD . ':fonts>';
    }

    private function generateTemplateWordFooter() {
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Footer.', 'debug' );
        $this->_idWords ['footer'] = self::$intIdWord;
        $this->_wordFooterT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <' . CreateDocx::NAMESPACEWORD . ':ftr xmlns:ve' . '="http://schemas.openxmlformats.org/markup-compatibility/' . '2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns' . ':r="http://schemas.openxmlformats.org/officeDocument/2006/' . 'relationships" xmlns:m="http://schemas.openxmlformats.org/' . 'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml' . '" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/' . 'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' . 'office:word" xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" xmlns:wne="http://schemas' . '.microsoft.com/office/word/2006/wordml">' . $this->_wordFooterC . '</' . CreateDocx::NAMESPACEWORD . ':ftr>';
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'footer', 'footer.xml');
        return 'rId' . self::$intIdWord;
    }

    private function generateTemplateWordFootnotes() {
        $this->_wordFootnotesT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<' . CreateDocx::NAMESPACEWORD . ':footnotes xmlns:ve="' . 'http://schemas.openxmlformats.org/markup-compatibility/2006" ' . 'xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="' . 'http://schemas.openxmlformats.org/officeDocument/2006/' . 'relationships" xmlns:m="http://schemas.openxmlformats.org/' . 'officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:' . 'vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006' . '/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:' . 'office:word" xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" xmlns:wne="http://schemas.microsoft' . '.com/office/word/2006/wordml">' . $this->_wordFootnotesC . '</' . CreateDocx::NAMESPACEWORD . ':footnotes>';
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Footnotes.', 'debug' );
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'footnotes', 'footnotes.xml');
        $this->generateOVERRIDE('/word/footnotes.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml' . '.footnotes+xml');
    }

    private function generateTemplateWordHeader() {
        self::$intIdWord ++;
//PhpdocxLogger::logger ( 'New ID ' . self::$intIdWord . ' . Header.', 'debug' );
        $this->_idWords ['header'] = self::$intIdWord;
        $this->_wordHeaderT = '<?xml version="1.0" encoding="UTF-8" ' . 'standalone="yes"?>' . '<' . CreateDocx::NAMESPACEWORD . ':hdr xmlns:ve="http://schemas.openxmlformats.org/markup' . '-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:' . 'office:office" xmlns:r="http://schemas.openxmlformats.org/' . 'officeDocument/2006/relationships" xmlns:m="http://schemas' . '.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:' . 'schemas-microsoft-com:vml" xmlns:wp="http://schemas' . '.openxmlformats.org/drawingml/2006/wordprocessingDrawing" ' . 'xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="' . 'http://schemas.openxmlformats.org/wordprocessingml/2006/' . 'main" xmlns:wne="http://schemas.microsoft.com/office/word/' . '2006/wordml"> ' . $this->_wordHeaderC . '</' . CreateDocx::NAMESPACEWORD . ':hdr>';
        $this->_wordRelsDocumentRelsC .= $this->generateRELATIONSHIP('rId' . self::$intIdWord, 'header', 'header.xml');
        return 'rId' . self::$intIdWord;
    }

    private function generateTemplateWordNumbering() {
        $this->_wordNumberingT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<w:numbering xmlns:ve="http://schemas.openxmlformats' . '.org/markup-compatibility/2006" xmlns:o="urn:schemas-' . 'microsoft-com:office:office" xmlns:r="http://schemas' . '.openxmlformats.org/officeDocument/2006/relationships" ' . 'xmlns:m="http://schemas.openxmlformats.org/officeDocument/' . '2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:' . 'wp="http://schemas.openxmlformats.org/drawingml/2006/' . 'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com' . ':office:word" xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" xmlns:wne="http://schemas.' . 'microsoft.com/office/word/2006/wordml"><w:abstractNum w:' . 'abstractNumId="0"><w:nsid w:val="713727AE"/><w:multiLevelType' . ' w:val="hybridMultilevel"/><w:tmpl w:val="F0B4B6B8"/>' . '<w:lvl w:ilvl="0" w:tplc="0C0A0001"><w:start w:val="1"/>' . '<w:numFmt w:val="bullet"/><w:lvlText w:val=""/><w:lvlJc ' . 'w:val="left"/><w:pPr><w:ind w:left="720" w:hanging="360"/>' . '</w:pPr><w:rPr><w:rFonts w:ascii="Symbol" w:hAnsi="Symbol" ' . 'w:hint="default"/></w:rPr></w:lvl><w:lvl w:ilvl="1" ' . 'w:tplc="0C0A0003" w:tentative="1"><w:start w:val="1"/>' . '<w:numFmt w:val="bullet"/><w:lvlText w:val="o"/><w:lvlJc ' . 'w:val="left"/><w:pPr><w:ind w:left="1440" w:hanging="360"/>' . '
                </w:pPr><w:rPr><w:rFonts w:ascii="Courier New" w:hAnsi=' . '"Courier New" w:cs="Courier New" w:hint="default"/></w:rPr>' . '</w:lvl><w:lvl w:ilvl="2" w:tplc="0C0A0005" w:tentative="1">' . '<w:start w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText ' . 'w:val=""/><w:lvlJc w:val="left"/><w:pPr><w:ind w:left="2160" ' . 'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Wingdings" ' . 'w:hAnsi="Wingdings" w:hint="default"/></w:rPr></w:lvl><w:lvl ' . 'w:ilvl="3" w:tplc="0C0A0001" w:tentative="1"><w:start ' . 'w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText w:val=""/>' . '<w:lvlJc w:val="left"/><w:pPr><w:ind w:left="2880" w:hanging=' . '"360"/></w:pPr><w:rPr><w:rFonts w:ascii="Symbol" w:hAnsi=' . '"Symbol" w:hint="default"/></w:rPr></w:lvl><w:lvl w:ilvl="4" ' . 'w:tplc="0C0A0003" w:tentative="1"><w:start w:val="1"/>' . '<w:numFmt w:val="bullet"/><w:lvlText w:val="o"/><w:lvlJc ' . 'w:val="left"/><w:pPr><w:ind w:left="3600" w:hanging="360"/>' . '</w:pPr><w:rPr><w:rFonts w:ascii="Courier New" w:hAnsi=' . '"Courier New" w:cs="Courier New" w:hint="default"/></w:rPr>' . '</w:lvl><w:lvl w:ilvl="5" w:tplc="0C0A0005" w:tentative="1">' . '<w:start w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText ' . 'w:val=""/><w:lvlJc w:val="left"/><w:pPr><w:ind w:left="4320" ' . 'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Wingdings" ' . 'w:hAnsi="Wingdings" w:hint="default"/></w:rPr></w:lvl><w:lvl ' . 'w:ilvl="6" w:tplc="0C0A0001" w:tentative="1"><w:start ' . 'w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText w:val=""/>' . '<w:lvlJc w:val="left"/><w:pPr><w:ind w:left="5040" ' . 'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Symbol" ' . 'w:hAnsi="Symbol" w:hint="default"/></w:rPr></w:lvl><w:lvl ' . 'w:ilvl="7" w:tplc="0C0A0003" w:tentative="1"><w:start ' . 'w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText w:val="o"/>' . '<w:lvlJc w:val="left"/><w:pPr><w:ind w:left="5760" ' . 'w:hanging="360"/></w:pPr><w:rPr><w:rFonts w:ascii="Courier New" ' . 'w:hAnsi="Courier New" w:cs="Courier New" w:hint="default"/>' . '</w:rPr></w:lvl><w:lvl w:ilvl="8" w:tplc="0C0A0005" ' . 'w:tentative="1"><w:start w:val="1"/><w:numFmt w:val="bullet"' . '/><w:lvlText w:val=""/><w:lvlJc w:val="left"/><w:pPr><w:ind ' . 'w:left="6480" w:hanging="360"/></w:pPr><w:rPr><w:rFonts ' . 'w:ascii="Wingdings" w:hAnsi="Wingdings" w:hint="default"/>' . '</w:rPr></w:lvl></w:abstractNum><w:num w:numId="1">' . '<w:abstractNumId w:val="0"/></w:num></w:numbering>';
    }

    private function generateTemplateWordNumberingStyles() {
        $this->_wordNumberingT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<w:numbering xmlns:ve="http://schemas.openxmlformats' . '.org/markup-compatibility/2006" xmlns:o="urn:schemas-' . 'microsoft-com:office:office" xmlns:r="http://schemas' . '.openxmlformats.org/officeDocument/2006/relationships" ' . 'xmlns:m="http://schemas.openxmlformats.org/officeDocument/' . '2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:' . 'wp="http://schemas.openxmlformats.org/drawingml/2006/' . 'wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com' . ':office:word" xmlns:w="http://schemas.openxmlformats.org/' . 'wordprocessingml/2006/main" xmlns:wne="http://schemas.' . 'microsoft.com/office/word/2006/wordml"><w:abstractNum w:' . 'abstractNumId="0"><w:nsid w:val="713727AE"/><w:multiLevelType' . ' w:val="hybridMultilevel"/><w:tmpl w:val="F0B4B6B8"/>' . $this->_wordDocumentStyles . '</w:abstractNum><w:num w:numId="1">' . '<w:abstractNumId w:val="0"/></w:num></w:numbering>';
    }

    private function generateTemplateWordRelsDocumentRels() {
        $this->_wordRelsDocumentRelsT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<Relationships xmlns="http://schemas.openxmlformats.org/' . 'package/2006/relationships">' . $this->_wordRelsDocumentRelsC . '</Relationships>';
    }

    private function generateTemplateWordRelsFooterRels() {
        $this->_wordRelsFooterRelsT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<Relationships xmlns="http://schemas.openxmlformats.org/' . 'package/2006/relationships">' . $this->_wordRelsFooterRelsC . '</Relationships>';
    }

    private function generateTemplateWordRelsHeaderRels() {
        $this->_wordRelsHeaderRelsT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<Relationships xmlns="http://schemas.openxmlformats.org/' . 'package/2006/relationships">' . $this->_wordRelsHeaderRelsC . '</Relationships>';
    }

    private function generateTemplateWordSettings() {
        $this->_wordSettingsT = $this->_wordSettingsC;
    }

    private function generateTemplateWordStyles() {
        $this->_wordStylesT = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><' . CreateDocx::NAMESPACEWORD . ':styles xmlns:r="http://' . 'schemas.openxmlformats.org/officeDocument/2006/relationships' . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/' . '2006/main">' . $this->_wordStylesC . '</' . CreateDocx::NAMESPACEWORD . ':styles>';
    }

    private function generateTemplateWordThemeTheme1() {
        $this->addTheme($this->_defaultFont);
        $this->_wordThemeThemeT = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><' . CreateTheme1::NAMESPACEWORD . ':theme xmlns:a="http://' . 'schemas.openxmlformats.org/drawingml/2006/main" name="' . 'Tema de Office">' . $this->_wordThemeThemeC . '</' . CreateTheme1::NAMESPACEWORD . ':theme>';
    }

    private function generateTemplateWordWebSettings() {
        $this->_wordWebSettingsT = $this->_wordWebSettingsC;
    }

    private function generateTitlePg() {
        $foundNodes = $this->_sectPr->documentElement->getElementsByTagName('w:TitlePg');
        if ($foundNodes->length == 0) {
            $newSectNode = '<w:titlePg xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" />';
            $sectNode = $this->_sectPr->createDocumentFragment();
            $sectNode->appendXML($newSectNode);
            $refNode = $this->_sectPr->documentElement->appendChild($sectNode);
        }
    }

    public static function getTempDir() {
        if (!function_exists('sys_get_temp_dir')) {

            function sys_get_temp_dir() {
                if ($temp = getenv('TMP')) {
                    return $temp;
                }
                if ($temp = getenv('TEMP')) {
                    return $temp;
                }
                if ($temp = getenv('TMPDIR')) {
                    return $temp;
                }
                $temp = tempnam(__FILE__, '');
                if (file_exists($temp)) {
                    unlink($temp);
                    return dirname($temp);
                }
                return null;
            }

        } else {
            return sys_get_temp_dir();
        }
    }

    private function HTMLRels($sFinalDocX, $options) {
        $relsLinks = '';
        if ($options ['target'] == 'defaultHeader' || $options ['target'] == 'firstHeader' || $options ['target'] == 'evenHeader' || $options ['target'] == 'defaultFooter' || $options ['target'] == 'firstFooter' || $options ['target'] == 'evenFooter') {
            foreach ($sFinalDocX [1] as $key => $value) {
                $this->_relsHeaderFooterLink [$options ['target']] [] = array(
                    'rId' => $key,
                    'url' => $value
                );
            }
        } else if ($options ['target'] == 'footnote' || $options ['target'] == 'endnote' || $options ['target'] == 'comment') {
            foreach ($sFinalDocX [1] as $key => $value) {
                $this->_relsNotesLink [$options ['target']] [] = array(
                    'rId' => $key,
                    'url' => $value
                );
            }
        } else {
            foreach ($sFinalDocX [1] as $key => $value) {
                $relsLinks .= '<Relationship Id="' . $key . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink" Target="' . $value . '" TargetMode="External" />';
            }
            if ($relsLinks != '') {
                $relsNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                $relsNode->appendXML($relsLinks);
                $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNode);
            }
        }
        $relsImages = '';
        if ($options ['target'] == 'defaultHeader' || $options ['target'] == 'firstHeader' || $options ['target'] == 'evenHeader' || $options ['target'] == 'defaultFooter' || $options ['target'] == 'firstFooter' || $options ['target'] == 'evenFooter') {
            foreach ($sFinalDocX [2] as $key => $value) {
                $value = array_shift(explode('?', $value));
                if (isset($options ['downloadImages']) && $options ['downloadImages']) {
                    $arrayExtension = explode('.', $value);
                    $extension = strtolower(array_pop($arrayExtension));
                    $predefinedExtensions = array(
                        'gif',
                        'png',
                        'jpg',
                        'jpeg',
                        'bmp'
                    );
                    if (!in_array($extension, $predefinedExtensions)) {
                        $this->generateDEFAULT($extension, 'image/' . $extension);
                    }
                    $this->_relsHeaderFooterImage [$options ['target']] [] = array(
                        'rId' => $key,
                        'extension' => $extension
                    );
                } else {
                    $this->_relsHeaderFooterExternalImage [$options ['target']] [] = array(
                        'rId' => $key,
                        'url' => $value
                    );
                }
            }
        } else if ($options ['target'] == 'footnote' || $options ['target'] == 'endnote' || $options ['target'] == 'comment') {
            foreach ($sFinalDocX [2] as $key => $value) {
                $value = array_shift(explode('?', $value));
                if (isset($options ['downloadImages']) && $options ['downloadImages']) {
                    $arrayExtension = explode('.', $value);
                    $extension = strtolower(array_pop($arrayExtension));
                    $predefinedExtensions = array(
                        'gif',
                        'png',
                        'jpg',
                        'jpeg',
                        'bmp'
                    );
                    if (!in_array($extension, $predefinedExtensions)) {
                        $this->generateDEFAULT($extension, 'image/' . $extension);
                    }
                    $this->_relsNotesImage [$options ['target']] [] = array(
                        'rId' => $key,
                        'extension' => $extension
                    );
                } else {
                    $this->_relsNotesExternalImage [$options ['target']] [] = array(
                        'rId' => $key,
                        'url' => $value
                    );
                }
            }
        } else {
            foreach ($sFinalDocX [2] as $key => $value) {
                $valueArray = explode('?', $value);
                $value = array_shift($valueArray);
                if (isset($options ['downloadImages']) && $options ['downloadImages']) {
                    $arrayExtension = explode('.', $value);
                    $extension = strtolower(array_pop($arrayExtension));
                    $predefinedExtensions = array(
                        'gif',
                        'png',
                        'jpg',
                        'jpeg',
                        'bmp'
                    );
                    if (!in_array($extension, $predefinedExtensions)) {
                        $this->generateDEFAULT($extension, 'image/' . $extension);
                    }
                    $relsImages .= '<Relationship Id="' . $key . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/img' . $key . '.' . $extension . '" />';
                } else {
                    $relsImages .= '<Relationship Id="' . $key . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="' . $value . '" TargetMode="External" />';
                }
            }
            if ($relsImages != '') {
                $relsNodeImages = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                $relsNodeImages->appendXML($relsImages);
                $this->_wordRelsDocumentRelsT->documentElement->appendChild($relsNodeImages);
            }
        }
    }

    private function parsePath($dir) {
        $slash = 0;
        $path = '';
        if (($slash = strrpos($dir, '/')) !== false) {
            $slash += 1;
            $path = substr($dir, 0, $slash);
        }
        $punto = strpos(substr($dir, $slash), '.');
        $nombre = substr($dir, $slash, $punto);
        $extension = substr($dir, $punto + $slash + 1);
        return array(
            'path' => $path,
            'nombre' => $nombre,
            'extension' => $extension
        );
    }

    private function preprocessDocx($pathDOCX) {
//PhpdocxLogger::logger ( 'Preprocess a docx for embeding with the addDOCX method.', 'debug' );
        try {
            $embedZip = new ZipArchive ();
            if ($embedZip->open($pathDOCX) === true) {
                
            } else {
                throw new Exception('it was not posible to unzip the docx file.');
            }
            $numberingXML = $embedZip->getFromName('word/numbering.xml');
            $numberingDOM = new DOMDocument ();
            $numberingDOM->loadXML($numberingXML);
            $numberingXPath = new DOMXPath($numberingDOM);
            $numberingXPath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
            $nsidQuery = '//w:nsid | //w:tmpl';
            $nsidNodes = $numberingXPath->query($nsidQuery);
            foreach ($nsidNodes as $node) {
                $node->parentNode->removeChild($node);
            }
            $newNumbering = $numberingDOM->saveXML();
            $embedZip->addFromString('word/numbering.xml', $newNumbering);
            $embedZip->close();
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
    }

    private function recursiveDelete($str) {
        if (is_file($str)) {
            return @unlink($str);
        } elseif (is_dir($str)) {
            $scan = glob(rtrim($str, '/') . '/*');
            foreach ($scan as $index => $path) {
                $this->recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }

    private function recursiveInsert($myZip, $fileName, $basePath) {
        $length = strlen($basePath);
        if (is_dir($fileName)) {
            $contentsDir = scandir($fileName);
            foreach ($contentsDir as $element) {
                if ($element != "." && $element != "..") {
                    $this->recursiveInsert($myZip, $fileName . "/" . $element, $basePath);
                }
            }
        } else {
            $newName = substr($fileName, $length + 1);
            $myZip->addFile($fileName, $newName);
        }
    }

    private function includeSettings($data) {
        try {
            $baseSettings = $this->_baseTemplateZip->getFromName('word/settings.xml');
            if ($baseSettings == '') {
                throw new Exception('Error while extracting the settings.xml file from the base template');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $settingsDoc = new DOMDocument ();
        $settingsDoc->loadXML($baseSettings);
        $settings = $settingsDoc->documentElement;
        foreach ($data as $key => $value) {
            $newNode = $settingsDoc->createDocumentFragment();
            $newNode->appendXML($value);
            $settings->appendChild($newNode);
        }
        $settingsHandler = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "w+");
        fwrite($settingsHandler, $settingsDoc->saveXML());
        fclose($documentHandler);
    }

    public function removeHeadersAndFooters() {
        $this->removeHeaders();
        $this->removeFooters();
    }

    public function removeHeaders() {
        foreach ($this->_relsHeader as $key => $value) {
            $this->recursiveDelete($this->_baseTemplateFilesPath . '/word/' . $value);
            $this->recursiveDelete($this->_baseTemplateFilesPath . '/word/_rels/' . $value . '.rels');
            $relationships = $this->_wordRelsDocumentRelsT->getElementsByTagName('Relationship');
            $counter = $relationships->length - 1;
            for ($j = $counter; $j > - 1; $j --) {
                $target = $relationships->item($j)->getAttribute('Target');
                if ($target == $value) {
                    $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                }
            }
            $overrides = $this->_contentTypeT->getElementsByTagName('Override');
            $counter = $overrides->length - 1;
            for ($j = $counter; $j > - 1; $j --) {
                $target = $overrides->item($j)->getAttribute('PartName');
                if ($target == '/word/' . $value) {
                    $this->_contentTypeT->documentElement->removeChild($overrides->item($j));
                }
            }
        }
        $headers = $this->_sectPr->getElementsByTagName('headerReference');
        $counter = $headers->length - 1;
        for ($j = $counter; $j > - 1; $j --) {
            $this->_sectPr->documentElement->removeChild($headers->item($j));
        }
        $titlePage = $this->_sectPr->getElementsByTagName('titlePg');
        $counter = $titlePage->length - 1;
        for ($j = $counter; $j > - 1; $j --) {
            $this->_sectPr->documentElement->removeChild($titlePage->item($j));
        }
        $this->removeSetting('w:evenAndOddHeaders');
    }

    public function removeFooters() {
        foreach ($this->_relsFooter as $key => $value) {
            $this->recursiveDelete($this->_baseTemplateFilesPath . '/word/' . $value);
            $this->recursiveDelete($this->_baseTemplateFilesPath . '/word/_rels/' . $value . '.rels');
            $relationships = $this->_wordRelsDocumentRelsT->getElementsByTagName('Relationship');
            $counter = $relationships->length - 1;
            for ($j = $counter; $j > - 1; $j --) {
                $target = $relationships->item($j)->getAttribute('Target');
                if ($target == $value) {
                    $this->_wordRelsDocumentRelsT->documentElement->removeChild($relationships->item($j));
                }
            }
            $overrides = $this->_contentTypeT->getElementsByTagName('Override');
            $counter = $overrides->length - 1;
            for ($j = $counter; $j > - 1; $j --) {
                $target = $overrides->item($j)->getAttribute('PartName');
                if ($target == '/word/' . $value) {
                    $this->_contentTypeT->documentElement->removeChild($overrides->item($j));
                }
            }
        }
        $footers = $this->_sectPr->getElementsByTagName('footerReference');
        $counter = $footers->length - 1;
        for ($j = $counter; $j > - 1; $j --) {
            $this->_sectPr->documentElement->removeChild($footers->item($j));
        }
        $titlePage = $this->_sectPr->getElementsByTagName('titlePg');
        $counter = $titlePage->length - 1;
        for ($j = $counter; $j > - 1; $j --) {
            $this->_sectPr->documentElement->removeChild($titlePage->item($j));
        }
        $this->removeSetting('w:evenAndOddHeaders');
    }

    private function removeSetting($tag) {
        try {
            $settings = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "r");
            $baseTemplateSettingsT = fread($settings, 1000000);
            fclose($settings);
            if ($baseTemplateSettingsT == '') {
                throw new Exception('Error while extracting settings.xml file from the base template to remove the tag element');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $this->_wordSettingsT = new DOMDocument ();
        $this->_wordSettingsT->loadXML($baseTemplateSettingsT);
        $settingsHeader = $this->_wordSettingsT->documentElement->getElementsByTagName($tag);
        if ($settingsHeader->length > 0) {
            $this->_wordSettingsT->documentElement->removeChild($settingsHeader->item(0));
            $newSettings = $this->_wordSettingsT->saveXML();
            $settingsHandle = fopen($this->_baseTemplateFilesPath . '/word/settings.xml', "w+");
            $contents = fwrite($settingsHandle, $newSettings);
            fclose($settingsHandle);
        }
    }

    public function importSingleNumbering($numberingsXML, $newNumbering, $numberId, $originalAbstractNumId = '') {
        $myNumbering = new DOMDocument ();
        $myNumbering->loadXML($numberingsXML);
        $newNumbering = str_replace('<w:abstractNum w:abstractNumId="' . $originalAbstractNumId . '"', '<w:abstractNum w:abstractNumId="' . $numberId . '"', $newNumbering);
        $newNumbering = str_replace('w:tplc=""', 'w:tplc="' . rand(10000000, 99999999) . '"', $newNumbering);
        $new = $myNumbering->createDocumentFragment();
        $new->appendXML($newNumbering);
        $base = $myNumbering->documentElement->firstChild;
        $base->parentNode->insertBefore($new, $base);
        $numberingsXML = $myNumbering->saveXML();
        $newNum = '<w:num w:numId="' . $numberId . '"><w:abstractNumId w:val="' . $numberId . '" /></w:num>';
        $numberingsXML = str_replace('</w:numbering>', $newNum . '</w:numbering>', $numberingsXML);
        return $numberingsXML;
    }

    public function generateBaseWordNumbering() {
        $numZip = new ZipArchive ();
        try {
            $openNumZip = $numZip->open(PHPDOCX_BASE_TEMPLATE);
            if ($openNumZip !== true) {
                throw new Exception('Error while opening the standard base template to extract the word/numbering.xml file');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $baseWordNumbering = $numZip->getFromName('word/numbering.xml');
        return $baseWordNumbering;
    }

    public function importHeadersAndFooters($path, $type = 'headerAndFooter') {
        switch ($type) {
            case 'headerAndFooter' :
                $this->removeHeadersAndFooters();
                break;
            case 'header' :
                $this->removeHeaders();
                break;
            case 'footer' :
                $this->removeFooters();
                break;
        }
        try {
            $baseHeadersFooters = new ZipArchive ();
            $openHeadersFooters = $baseHeadersFooters->open($path);
            if ($openHeadersFooters !== true) {
                throw new Exception('Error while opening the docx to extract the header and/or footer');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $baseHeadersFootersFilesPath = $this->_tempDir . '/' . uniqid(true);
            $extractHeadersFooters = $baseHeadersFooters->extractTo($baseHeadersFootersFilesPath);
            if ($extractHeadersFooters !== true) {
                throw new Exception('Error while extracting the contents of the docx file that containes the header and/or footer: there may be problems writing in the default tmp folder');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        try {
            $docHeadersFooters = $baseHeadersFooters->getFromName('word/document.xml');
            if ($docHeadersFooters == '') {
                throw new Exception('Error while extracting the document.xml file from the docx from which we want to get the feaders and/or footers');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $docHeadersFootersContent = new DOMDocument ();
        $docHeadersFootersContent->loadXML($docHeadersFooters);
        $docSectPr = $docHeadersFootersContent->getElementsByTagName('sectPr')->item(0);
        $headerTypes = array();
        $footerTypes = array();
        $titlePg = false;
        foreach ($docSectPr->childNodes as $value) {
            if ($value->nodeName == 'w:headerReference') {
                $headerTypes [$value->getAttribute('r:id')] = $value->getAttribute('w:type');
            } else if ($value->nodeName == 'w:footerReference') {
                $footerTypes [$value->getAttribute('r:id')] = $value->getAttribute('w:type');
            }
        }
        $titlePg = false;
        if ($docHeadersFootersContent->getElementsByTagName('titlePg')->length > 0) {
            $titlePg = true;
        }
        try {
            $settingsHeadersFooters = $baseHeadersFooters->getFromName('word/settings.xml');
            if ($settingsHeadersFooters == '') {
                throw new Exception('Error while extracting the settings.xml file from the docx from which we want to get the headers and/or footers');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $settingsHeadersFootersContent = new DOMDocument ();
        $settingsHeadersFootersContent->loadXML($settingsHeadersFooters);
        if ($settingsHeadersFootersContent->getElementsByTagName('evenAndOddHeaders')->length > 0) {
            $this->generateSetting('w:evenAndOddHeaders');
        }
        try {
            $baseHeadersFootersRelsT = $baseHeadersFooters->getFromName('word/_rels/document.xml.rels');
            if ($baseHeadersFootersRelsT == '') {
                throw new Exception('Error while extracting the document.xml.rels file from the docx from which we want to get the feaders and/or footers');
            }
        } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
        }
        $wordHeadersFootersRelsT = new DOMDocument ();
        $wordHeadersFootersRelsT->loadXML($baseHeadersFootersRelsT);
        $relationships = $wordHeadersFootersRelsT->getElementsByTagName('Relationship');
        $counter = $relationships->length - 1;
        for ($j = $counter; $j > - 1; $j --) {
            $rId = $relationships->item($j)->getAttribute('Id');
            $completeType = $relationships->item($j)->getAttribute('Type');
            $target = $relationships->item($j)->getAttribute('Target');
            $myType = array_pop(explode('/', $completeType));
            switch ($myType) {
                case 'header' :
                    $relsHeader [$rId] = $target;
                    break;
                case 'footer' :
                    $relsFooter [$rId] = $target;
                    break;
            }
        }
        if ($type == 'headerAndFooter' || $type == 'header') {
            foreach ($relsHeader as $key => $value) {
                if (file_exists($baseHeadersFootersFilesPath . '/word/_rels/' . $value . '.rels')) {
                    try {
                        $headersRelsT = $baseHeadersFooters->getFromName('word/_rels/' . $value . '.rels');
                        if ($headersRelsT == '') {
                            throw new Exception('Error while extracting the rels file for the header');
                        }
                    } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                    }
                    $wordHeadersRelsT = new DOMDocument ();
                    $wordHeadersRelsT->loadXML($headersRelsT);
                    $relations = $wordHeadersRelsT->getElementsByTagName('Relationship');
                    $countrels = $relations->length - 1;
                    for ($j = $countrels; $j > - 1; $j --) {
                        $completeType = $relations->item($j)->getAttribute('Type');
                        $target = $relations->item($j)->getAttribute('Target');
                        $myType = array_pop(explode('/', $completeType));
                        switch ($myType) {
                            case 'image' :
                                $refExtension = array_pop(explode('.', $target));
                                $refImage = 'media/image' . uniqid(true) . '.' . $refExtension;
                                $relations->item($j)->setAttribute('Target', $refImage);
                                if (!file_exists($this->_baseTemplateFilesPath . '/word/media')) {
                                    $test2 = mkdir($this->_baseTemplateFilesPath . '/word/media');
                                }
                                copy($baseHeadersFootersFilesPath . '/word/' . $target, $this->_baseTemplateFilesPath . '/word/' . $refImage);
                                $newRels = fopen($this->_baseTemplateFilesPath . '/word/_rels/' . $value . '.rels', 'w+');
                                fwrite($newRels, $wordHeadersRelsT->saveXML());
                                $imageTypeFound = false;
                                foreach ($this->_contentTypeT->documentElement->childNodes as $node) {
                                    if ($node->nodeName == 'Default' && $node->getAttribute('Extension') == $refExtension) {
                                        $imageTypeFound = true;
                                    }
                                }
                                if (!$imageTypeFound) {
                                    $newDefaultNode = '<Default Extension="' . $refExtension . '" ContentType="image/' . $refExtension . '" />';
                                    $newDefault = $this->_contentTypeT->createDocumentFragment();
                                    $newDefault->appendXML($newDefaultNode);
                                    $baseDefaultNode = $this->_contentTypeT->documentElement;
                                    $baseDefaultNode->appendChild($newDefault);
                                }
                                break;
                        }
                    }
                }
                copy($baseHeadersFootersFilesPath . '/word/' . $value, $this->_baseTemplateFilesPath . '/word/' . $value);
                $newId = uniqid(true);
                $newHeaderNode = '<Relationship Id="rId';
                $newHeaderNode .= $newId . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header"';
                $newHeaderNode .= ' Target="' . $value . '" />';
                $newNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                $newNode->appendXML($newHeaderNode);
                $baseNode = $this->_wordRelsDocumentRelsT->documentElement;
                $baseNode->appendChild($newNode);
                $newSectNode = '<w:headerReference w:type="' . $headerTypes [$key] . '" r:id="rId' . $newId . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>';
                $sectNode = $this->_sectPr->createDocumentFragment();
                $sectNode->appendXML($newSectNode);
                $refNode = $this->_sectPr->documentElement->childNodes->item(0);
                $refNode->parentNode->insertBefore($sectNode, $refNode);
                $newOverrideNode = '<Override PartName="/word/' . $value . '" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml" />';
                $newOverride = $this->_contentTypeT->createDocumentFragment();
                $newOverride->appendXML($newOverrideNode);
                $baseOverrideNode = $this->_contentTypeT->documentElement;
                $baseOverrideNode->appendChild($newOverride);
            }
        }
        if ($type == 'headerAndFooter' || $type == 'footer') {
            foreach ($relsFooter as $key => $value) {
                if (file_exists($baseHeadersFootersFilesPath . '/word/_rels/' . $value . '.rels')) {
                    try {
                        $footersRelsT = $baseHeadersFooters->getFromName('word/_rels/' . $value . '.rels');
                        if ($footersRelsT == '') {
                            throw new Exception('Error while extracting the rels file for the footer');
                        }
                    } catch (Exception $e) {
//PhpdocxLogger::logger ( $e->getMessage (), 'fatal' );
                    }
                    $wordFootersRelsT = new DOMDocument ();
                    $wordFootersRelsT->loadXML($footersRelsT);
                    $relations = $wordFootersRelsT->getElementsByTagName('Relationship');
                    $countrels = $relations->length - 1;
                    for ($j = $countrels; $j > - 1; $j --) {
                        $completeType = $relations->item($j)->getAttribute('Type');
                        $target = $relations->item($j)->getAttribute('Target');
                        $myType = array_pop(explode('/', $completeType));
                        switch ($myType) {
                            case 'image' :
                                $refExtension = array_pop(explode('.', $target));
                                $refImage = 'media/image' . uniqid(true) . '.' . $refExtension;
                                $relations->item($j)->setAttribute('Target', $refImage);
                                if (!file_exists($this->_baseTemplateFilesPath . '/word/media')) {
                                    $test2 = mkdir($this->_baseTemplateFilesPath . '/word/media');
                                }
                                copy($baseHeadersFootersFilesPath . '/word/' . $target, $this->_baseTemplateFilesPath . '/word/' . $refImage);
                                $newRels = fopen($this->_baseTemplateFilesPath . '/word/_rels/' . $value . '.rels', 'w+');
                                fwrite($newRels, $wordFootersRelsT->saveXML());
                                $imageTypeFound = false;
                                foreach ($this->_contentTypeT->documentElement->childNodes as $node) {
                                    if ($node->nodeName == 'Default' && $node->getAttribute('Extension') == $refExtension) {
                                        $imageTypeFound = true;
                                    }
                                }
                                if (!$imageTypeFound) {
                                    $newDefaultNode = '<Default Extension="' . $refExtension . '" ContentType="image/' . $refExtension . '" />';
                                    $newDefault = $this->_contentTypeT->createDocumentFragment();
                                    $newDefault->appendXML($newDefaultNode);
                                    $baseDefaultNode = $this->_contentTypeT->documentElement;
                                    $baseDefaultNode->appendChild($newDefault);
                                }
                                break;
                        }
                    }
                }
                copy($baseHeadersFootersFilesPath . '/word/' . $value, $this->_baseTemplateFilesPath . '/word/' . $value);
                $newId = uniqid(true);
                $newFooterNode = '<Relationship Id="rId';
                $newFooterNode .= $newId . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer"';
                $newFooterNode .= ' Target="' . $value . '" />';
                $newNode = $this->_wordRelsDocumentRelsT->createDocumentFragment();
                $newNode->appendXML($newFooterNode);
                $baseNode = $this->_wordRelsDocumentRelsT->documentElement;
                $baseNode->appendChild($newNode);
                $newSectNode = '<w:footerReference w:type="' . $footerTypes [$key] . '" r:id="rId' . $newId . '" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>';
                $sectNode = $this->_sectPr->createDocumentFragment();
                $sectNode->appendXML($newSectNode);
                $refNode = $this->_sectPr->documentElement->childNodes->item(0);
                $refNode->parentNode->insertBefore($sectNode, $refNode);
                $newOverrideNode = '<Override PartName="/word/' . $value . '" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml" />';
                $newOverride = $this->_contentTypeT->createDocumentFragment();
                $newOverride->appendXML($newOverrideNode);
                $baseOverrideNode = $this->_contentTypeT->documentElement;
                $baseOverrideNode->appendChild($newOverride);
            }
        }
        if ($titlePg) {
            $this->generateTitlePg();
        }
    }

}

<?php
/*
ED150525 

ods-php a library to read and write ods files from php.

This library has been forked from eyeOS project and licended under the LGPL3
terms available at: http://www.gnu.org/licenses/lgpl-3.0.txt (relicenced
with permission of the copyright holders)

Copyright: Juan Lao Tebar (juanlao@eyeos.org) and Jose Carlos Norte (jose@eyeos.org) - 2008 

https://sourceforge.net/projects/ods-php/

*/

class ods {
	var $fonts;
	var $styles;
	var $sheets;
	var $columns;
	var $names;
	var $lastElement;
	var $fods;
	var $firstSheet;
	var $currentSheet;
	var $currentRow;
	var $currentCell;
	var $currentColumn;
	var $lastRowAtt;
	var $repeat;
	var $sheetsFilter;
	var $skipRows;
	var $rowsAttrs;
	
	function ods() {
		$this->styles = array();
		$this->fonts = array();
		$this->sheets = array();
		$this->columns = array();
		$this->names = array();
		$this->sheetsFilter = array();
		$this->skipRows = array();
		$this->rowsAttrs = array();
		$this->currentRow = 0;
		$this->firstSheet = FALSE;
		$this->currentSheet = 0;
		$this->currentColumn = 0;
		$this->currentCell = 0;
		$this->repeat = 0;
	}
	
	function parse($data) {
		$xml_parser = xml_parser_create(); 
		xml_parser_set_option($xml_parser,XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_set_object ( $xml_parser, $this );
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "characterData");
		xml_parse($xml_parser, $data, strlen($data));

		xml_parser_free($xml_parser);
		
		$this->currentSheet = $this->firstSheet;
	}
	
	function setSheetsFilters($sheetsFilters){
		if(is_numeric($sheetsFilters))
			$this->sheetsFilters = array($sheetsFilters);
		elseif(is_string($sheetsFilters))
			$this->sheetsFilters = explode(','.$sheetsFilters);
		elseif(is_array($sheetsFilters))
			$this->sheetsFilters = $sheetsFilters;
	}
	
	function array2ods() {
		$fontArray = $this->fonts;
		$styleArray = $this->styles;
		$sheetArray = $this->sheets;
		// Header
		$string = '<?xml version="1.0" encoding="UTF-8"?><office:document-content xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" office:version="1.0">';
		
		// ToDo: scripts
		$string .= '<office:scripts/>';
		
		// Fonts
		$string .= '<office:font-face-decls>';
		foreach ($fontArray as $fontName => $fontAttribs) {
			$string .= '<style:font-face ';
			foreach ($fontAttribs as $attrName => $attrValue) {
				$string .= strtolower($attrName) . '="' . $attrValue . '" ';
			}
			$string .= '/>';
		}
		$string .= '</office:font-face-decls>';
		
		// Styles
		$string .= '<office:automatic-styles>';
		foreach ($styleArray as $styleName => $styleAttribs) {
			$string .= '<style:style ';
			foreach ($styleAttribs['attrs'] as $attrName => $attrValue) {
				$string .= strtolower($attrName) . '="' . $attrValue . '" ';
			}
			$string .= '>';
			
			// Subnodes
			if($styleAttribs['styles'])
			foreach ($styleAttribs['styles'] as $nodeName => $nodeTree) {
				$string .= '<' . $nodeName . ' ';
				foreach ($nodeTree as $attrName => $attrValue) {
					$string .= strtolower($attrName) . '="' . $attrValue . '" ';
				}
				$string .= '/>';
			}
			
			$string .= '</style:style>';
		}
		$string .= '</office:automatic-styles>';
		
		// Body
		$string .= '<office:body>';
		$string .= '<office:spreadsheet>';
		foreach ($sheetArray as $tableIndex => $tableContent) {
			$string .= '<table:table table:name="' . $tableIndex . '" table:print="false">';
			//$string .= '<office:forms form:automatic-focus="false" form:apply-design-mode="false"/>';
			
			foreach ($tableContent['rows'] as $rowIndex => $rowContent) {
				$string .= '<table:table-row>';
				
				foreach($rowContent as $cellIndex => $cellContent) {
					$string .= '<table:table-cell ';
					foreach ($cellContent['attrs'] as $attrName => $attrValue) {
						$string .= strtolower($attrName) . '="' . $attrValue . '" ';
					}
					$string .= '>';
					
					if (isset($cellContent['value'])) {
						$string .= '<text:p>' . $cellContent['value'] . '</text:p>';
					}
					
					$string .= '</table:table-cell>';
				}
				
				$string .= '</table:table-row>';
			}
			
			$string .= '</table:table>';
		}
		
		$string .= '</office:spreadsheet>';
		$string .= '</office:body>';
		
		// Footer
		$string .= '</office:document-content>';
		
		return $string;
	}
	
	function startElement($parser, $tagName, $attrs) {
		$cTagName = strtolower($tagName);
		if($cTagName == 'style:font-face') {
			$this->fonts[$attrs['STYLE:NAME']] = $attrs;
		} elseif($cTagName == 'style:style') {
			$this->lastElement = $attrs['STYLE:NAME'];
			$this->styles[$this->lastElement]['attrs'] = $attrs;
			
		} elseif(strpos($cTagName, 'style:') === 0 && $attrs) {
			//var_dump($cTagName, $attrs);
			$this->styles[$this->lastElement]['styles'][$cTagName] = $attrs;
			
		} elseif($cTagName == 'table:table-cell') {
			if($this->sheetSkipped($this->currentSheet))
				return;
			if($this->firstSheet === FALSE)
				$this->firstSheet = $this->currentSheet;
			$this->lastElement = $cTagName;
			$this->sheets[$this->currentSheet]['rows'][$this->currentRow][$this->currentCell]['attrs'] = $attrs;
			if(isset($attrs['TABLE:NUMBER-COLUMNS-REPEATED'])) {
				$times = intval($attrs['TABLE:NUMBER-COLUMNS-REPEATED']);
				if($times < 256){
					$times--;
					for($i=1;$i<=$times;$i++) {
						$cnum = $this->currentCell+$i;
						$this->sheets[$this->currentSheet]['rows'][$this->currentRow][$cnum]['attrs'] = $attrs;
					}
					$this->currentCell += $times;
					$this->repeat = $times;
				}
			}
			if(isset($this->lastRowAtt['TABLE:NUMBER-ROWS-REPEATED'])) {
				$times = intval($this->lastRowAtt['TABLE:NUMBER-ROWS-REPEATED']);
				if($times < 32000){
					$times--;
					for($i=1;$i<=$times;$i++) {
						$cnum = $this->currentRow+$i;
						$this->sheets[$this->currentSheet]['rows'][$cnum][$i-1]['attrs'] = $attrs;
					}
					$this->currentRow += $times;
				}
			}
		} elseif($cTagName == 'table:table') {
			$this->names['tables'][$this->currentSheet] = $attrs['TABLE:NAME'];
			if($this->sheetSkipped($this->currentSheet))
				return false;
			
		} elseif($cTagName == 'table:table-row') {
			if($this->sheetSkipped($this->currentSheet))
				return;
			if(array_key_exists('TABLE:VISIBILITY', $attrs))
				$this->skipRows[$this->currentSheet][$this->currentRow] = true;
			if($attrs)
				$this->rowsAttrs[$this->currentSheet][$this->currentRow] = $attrs;
			$this->lastRowAtt = $attrs;
			
		} elseif($cTagName == 'table:table-column') {
			if($this->sheetSkipped($this->currentSheet))
				return;
			$this->columns[$this->currentSheet][$this->currentColumn] = $attrs;
			if(isset($attrs['TABLE:NUMBER-COLUMNS-REPEATED'])) {
				$times = intval($attrs['TABLE:NUMBER-COLUMNS-REPEATED']);
				if($times < 256){
					$times--;
					for($i=1;$i<=$times;$i++) {
						$cnum = $this->currentColumn+$i;
						$this->columns[$this->currentSheet][$cnum] = $attrs;
					}
					$this->currentColumn += $times;
				}
			}
			
		}
	}
	
	function sheetSkipped($sheetIndex){
		return $this->sheetsFilters && !in_array($sheetIndex, $this->sheetsFilters);
	}
	
	function endElement($parser, $tagName) {
		switch( strtolower($tagName) ){
		case 'table:table' :
			$this->currentSheet++;
			$this->currentRow = 0;
			$this->currentColumn = 0;
			$this->repeat = 0;
			break;
		case 'table:table-column':
			$this->currentColumn++;
			break;
		case 'table:table-row':
			$this->currentRow++;
			$this->currentCell = 0;
			break;
		case 'table:table-cell':
			$this->currentCell++;
			$this->repeat = 0;
			break;
		}
	}
	
	function characterData($parser, $data) {
		if($this->lastElement == 'table:table-cell') {
			//en cas d'accent, la valeur est passée en plusieurs fois
			if($this->sheets[$this->currentSheet]['rows'][$this->currentRow][$this->currentCell]['value'])
				$this->sheets[$this->currentSheet]['rows'][$this->currentRow][$this->currentCell]['value'] .= $data;
			else
				$this->sheets[$this->currentSheet]['rows'][$this->currentRow][$this->currentCell]['value'] = $data;
			if($this->repeat > 0) {
				$data = $this->sheets[$this->currentSheet]['rows'][$this->currentRow][$this->currentCell]['value'];
				for($i=0;$i<$this->repeat;$i++) {
					$cnum = $this->currentCell - ($i+1);
					$this->sheets[$this->currentSheet]['rows'][$this->currentRow][$cnum]['value'] = $data;
				}
			}
		}
	}
	
	function getMeta($lang) {
		$myDate = date('Y-m-j\TH:i:s');
		$meta = '<?xml version="1.0" encoding="UTF-8"?>
		<office:document-meta xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:ooo="http://openoffice.org/2004/office" office:version="1.0">
			<office:meta>
				<meta:generator>ods-php</meta:generator>
				<meta:creation-date>'.$myDate.'</meta:creation-date>
				<dc:date>'.$myDate.'</dc:date>
				<dc:language>'.$lang.'</dc:language>
				<meta:editing-cycles>2</meta:editing-cycles>
				<meta:editing-duration>PT15S</meta:editing-duration>
				<meta:user-defined meta:name="Info 1"/>
				<meta:user-defined meta:name="Info 2"/>
				<meta:user-defined meta:name="Info 3"/>
				<meta:user-defined meta:name="Info 4"/>
			</office:meta>
		</office:document-meta>';
		return $meta;
	}
	
	function getStyle() {
		return '<?xml version="1.0" encoding="UTF-8"?>
			<office:document-styles xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" office:version="1.0"><office:font-face-decls><style:font-face style:name="Liberation Sans" svg:font-family="&apos;Liberation Sans&apos;" style:font-family-generic="swiss" style:font-pitch="variable"/><style:font-face style:name="DejaVu Sans" svg:font-family="&apos;DejaVu Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/></office:font-face-decls><office:styles><style:default-style style:family="table-cell"><style:table-cell-properties style:decimal-places="2"/><style:paragraph-properties style:tab-stop-distance="1.25cm"/><style:text-properties style:font-name="Liberation Sans" fo:language="es" fo:country="ES" style:font-name-asian="DejaVu Sans" style:language-asian="zxx" style:country-asian="none" style:font-name-complex="DejaVu Sans" style:language-complex="zxx" style:country-complex="none"/></style:default-style><number:number-style style:name="N0"><number:number number:min-integer-digits="1"/>
			</number:number-style><number:currency-style style:name="N103P0" style:volatile="true"><number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/><number:text> </number:text><number:currency-symbol number:language="es" number:country="ES">€</number:currency-symbol></number:currency-style><number:currency-style style:name="N103"><style:text-properties fo:color="#ff0000"/><number:text>-</number:text><number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/><number:text> </number:text><number:currency-symbol number:language="es" number:country="ES">€</number:currency-symbol><style:map style:condition="value()&gt;=0" style:apply-style-name="N103P0"/></number:currency-style><style:style style:name="Default" style:family="table-cell"/><style:style style:name="Result" style:family="table-cell" style:parent-style-name="Default"><style:text-properties fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold"/></style:style><style:style style:name="Result2" style:family="table-cell" style:parent-style-name="Result" style:data-style-name="N103"/><style:style style:name="Heading" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties style:text-align-source="fix" style:repeat-content="false"/><style:paragraph-properties fo:text-align="center"/><style:text-properties fo:font-size="16pt" fo:font-style="italic" fo:font-weight="bold"/></style:style><style:style style:name="Heading1" style:family="table-cell" style:parent-style-name="Heading"><style:table-cell-properties style:rotation-angle="90"/></style:style></office:styles><office:automatic-styles><style:page-layout style:name="pm1"><style:page-layout-properties style:writing-mode="lr-tb"/><style:header-style><style:header-footer-properties fo:min-height="0.751cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0.25cm"/></style:header-style><style:footer-style><style:header-footer-properties fo:min-height="0.751cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-top="0.25cm"/>
			</style:footer-style></style:page-layout><style:page-layout style:name="pm2"><style:page-layout-properties style:writing-mode="lr-tb"/><style:header-style><style:header-footer-properties fo:min-height="0.751cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0.25cm" fo:border="0.088cm solid #000000" fo:padding="0.018cm" fo:background-color="#c0c0c0"><style:background-image/></style:header-footer-properties></style:header-style><style:footer-style><style:header-footer-properties fo:min-height="0.751cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-top="0.25cm" fo:border="0.088cm solid #000000" fo:padding="0.018cm" fo:background-color="#c0c0c0"><style:background-image/></style:header-footer-properties></style:footer-style></style:page-layout></office:automatic-styles><office:master-styles><style:master-page style:name="Default" style:page-layout-name="pm1"><style:header><text:p><text:sheet-name>???</text:sheet-name></text:p></style:header><style:header-left style:display="false"/><style:footer><text:p>Página <text:page-number>1</text:page-number></text:p></style:footer><style:footer-left style:display="false"/></style:master-page><style:master-page style:name="Report" style:page-layout-name="pm2"><style:header><style:region-left><text:p><text:sheet-name>???</text:sheet-name> (<text:title>???</text:title>)</text:p></style:region-left><style:region-right><text:p><text:date style:data-style-name="N2" text:date-value="2008-02-18">18/02/2008</text:date>, <text:time>00:17:06</text:time></text:p></style:region-right></style:header><style:header-left style:display="false"/><style:footer><text:p>Página <text:page-number>1</text:page-number> / <text:page-count>99</text:page-count></text:p></style:footer><style:footer-left style:display="false"/></style:master-page></office:master-styles></office:document-styles>';
	}
	
	function getSettings() {
		return '<?xml version="1.0" encoding="UTF-8"?>
		<office:document-settings xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:ooo="http://openoffice.org/2004/office" office:version="1.0"><office:settings><config:config-item-set config:name="ooo:view-settings"><config:config-item config:name="VisibleAreaTop" config:type="int">0</config:config-item><config:config-item config:name="VisibleAreaLeft" config:type="int">0</config:config-item><config:config-item config:name="VisibleAreaWidth" config:type="int">2258</config:config-item><config:config-item config:name="VisibleAreaHeight" config:type="int">903</config:config-item><config:config-item-map-indexed config:name="Views"><config:config-item-map-entry><config:config-item config:name="ViewId" config:type="string">View1</config:config-item><config:config-item-map-named config:name="Tables"><config:config-item-map-entry config:name="Hoja1"><config:config-item config:name="CursorPositionX" config:type="int">0</config:config-item><config:config-item config:name="CursorPositionY" config:type="int">1</config:config-item><config:config-item config:name="HorizontalSplitMode" config:type="short">0</config:config-item><config:config-item config:name="VerticalSplitMode" config:type="short">0</config:config-item><config:config-item config:name="HorizontalSplitPosition" config:type="int">0</config:config-item><config:config-item config:name="VerticalSplitPosition" config:type="int">0</config:config-item><config:config-item config:name="ActiveSplitRange" config:type="short">2</config:config-item><config:config-item config:name="PositionLeft" config:type="int">0</config:config-item><config:config-item config:name="PositionRight" config:type="int">0</config:config-item><config:config-item config:name="PositionTop" config:type="int">0</config:config-item><config:config-item config:name="PositionBottom" config:type="int">0</config:config-item></config:config-item-map-entry></config:config-item-map-named><config:config-item config:name="ActiveTable" config:type="string">Hoja1</config:config-item><config:config-item config:name="HorizontalScrollbarWidth" config:type="int">270</config:config-item><config:config-item config:name="ZoomType" config:type="short">0</config:config-item><config:config-item config:name="ZoomValue" config:type="int">100</config:config-item><config:config-item config:name="PageViewZoomValue" config:type="int">60</config:config-item><config:config-item config:name="ShowPageBreakPreview" config:type="boolean">false</config:config-item><config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item><config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item><config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item><config:config-item config:name="GridColor" config:type="long">12632256</config:config-item><config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item><config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item><config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item><config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item><config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item><config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item><config:config-item config:name="RasterResolutionX" config:type="int">1000</config:config-item><config:config-item config:name="RasterResolutionY" config:type="int">1000</config:config-item><config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item>
		<config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item><config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item></config:config-item-map-entry></config:config-item-map-indexed></config:config-item-set><config:config-item-set config:name="ooo:configuration-settings"><config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item><config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item><config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item><config:config-item config:name="GridColor" config:type="long">12632256</config:config-item><config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item><config:config-item config:name="LinkUpdateMode" config:type="short">3</config:config-item><config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item><config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item><config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item><config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item><config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item><config:config-item config:name="RasterResolutionX" config:type="int">1000</config:config-item><config:config-item config:name="RasterResolutionY" config:type="int">1000</config:config-item><config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item><config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item><config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item><config:config-item config:name="AutoCalculate" config:type="boolean">true</config:config-item><config:config-item config:name="PrinterName" config:type="string">Generic Printer</config:config-item><config:config-item config:name="PrinterSetup" config:type="base64Binary">WAH+/0dlbmVyaWMgUHJpbnRlcgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU0dFTlBSVAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWAAMAngAAAAAAAAAFAFZUAAAkbQAASm9iRGF0YSAxCnByaW50ZXI9R2VuZXJpYyBQcmludGVyCm9yaWVudGF0aW9uPVBvcnRyYWl0CmNvcGllcz0xCm1hcmdpbmRhanVzdG1lbnQ9MCwwLDAsMApjb2xvcmRlcHRoPTI0CnBzbGV2ZWw9MApjb2xvcmRldmljZT0wClBQRENvbnRleERhdGEKUGFnZVNpemU6TGV0dGVyAAA=</config:config-item><config:config-item config:name="ApplyUserData" config:type="boolean">true</config:config-item><config:config-item config:name="CharacterCompressionType" config:type="short">0</config:config-item><config:config-item config:name="IsKernAsianPunctuation" config:type="boolean">false</config:config-item><config:config-item config:name="SaveVersionOnClose" config:type="boolean">false</config:config-item><config:config-item config:name="UpdateFromTemplate" config:type="boolean">false</config:config-item><config:config-item config:name="AllowPrintJobCancel" config:type="boolean">true</config:config-item><config:config-item config:name="LoadReadonly" config:type="boolean">false</config:config-item></config:config-item-set></office:settings></office:document-settings>';
	}
	
	function getManifest() {
		return '<?xml version="1.0" encoding="UTF-8"?>
<manifest:manifest xmlns:manifest="urn:oasis:names:tc:opendocument:xmlns:manifest:1.0">
 <manifest:file-entry manifest:media-type="application/vnd.oasis.opendocument.spreadsheet" manifest:full-path="/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/statusbar/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/accelerator/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/floater/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/popupmenu/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/progressbar/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/menubar/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/toolbar/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/images/Bitmaps/"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Configurations2/images/"/>
 <manifest:file-entry manifest:media-type="application/vnd.sun.xml.ui.configuration" manifest:full-path="Configurations2/"/>
 <manifest:file-entry manifest:media-type="text/xml" manifest:full-path="content.xml"/>
 <manifest:file-entry manifest:media-type="text/xml" manifest:full-path="styles.xml"/>
 <manifest:file-entry manifest:media-type="text/xml" manifest:full-path="meta.xml"/>
 <manifest:file-entry manifest:media-type="" manifest:full-path="Thumbnails/"/>
 <manifest:file-entry manifest:media-type="text/xml" manifest:full-path="settings.xml"/>
</manifest:manifest>';
	}
	
	function addCell($sheet,$row,$cell,$value,$type) {
		$this->sheets[$sheet]['rows'][$row][$cell]['attrs'] = array('OFFICE:VALUE-TYPE'=>$type,'OFFICE:VALUE'=>$value);
		$this->sheets[$sheet]['rows'][$row][$cell]['value'] = $value;
	}
	
	function editCell($sheet,$row,$cell,$value) {
		$this->sheets[$sheet]['rows'][$row][$cell]['attrs']['OFFICE:VALUE'] = $value;
		$this->sheets[$sheet]['rows'][$row][$cell]['value'] = $value;
	}
	
	function parseToHtml($uid = FALSE, $getSheetNode = FALSE, $sourceFile = FALSE){
		if(!$uid)
			$uid = uniqid('ods');
		echo '<div id="'.$uid.'">';
		echo '<ul>';
		foreach($this->sheets as $sheetIndex => $sheet)
			if($this->sheetSkipped($sheetIndex)){
				echo '<li>';
				echo '<a href="view.php'
					. '?id=' . $getSheetNode
					. '&vw=file.call'
					. '&file=' . $sourceFile
					. '&sheet=' . $sheetIndex
					.'#'. $uid.'-'. $sheetIndex.'">'.htmlentities($this->names['tables'][$sheetIndex]) .'</a>';
				echo '</li>';
			}
			else {
				echo '<li>';
				echo '<a href="#'. $uid.'-'. $sheetIndex.'">'.htmlentities($this->names['tables'][$sheetIndex]) .'</a>';
				echo '</li>';
			}
		echo '</ul>';
		$firstSheet = false;
		foreach($this->sheets as $sheetIndex => $sheet)
			if(!$this->sheetSkipped($sheetIndex)){
				if($firstSheet === false)
					$firstSheet = $sheetIndex;
				$this->currentSheet = $sheetIndex;
				echo '<div id="'.$uid.'-'.$sheetIndex.'">';
				$this->parseSheetToHtml($uid, $sheetIndex, $sheet);
				echo '</div>';
			}
		$this->parseStylesToHtml($uid);
		if($firstSheet !== false)
			$this->currentSheet = $firstSheet;
		$this->echoScript($uid, $firstSheet);
	}
	function parseSheetToAllHtml($sheetIndex){
		$uid = uniqid('odsheet');
		if($sheetIndex === FALSE){
			var_dump($this->sheetsFilter);
			$sheetIndex = $this->sheetsFilter ? $this->sheetsFilter[0] : 0;
		}
		if($this->sheetSkipped($sheetIndex)){
			echo print_r($sheetIndex, true) . ' is skipped. ' . print_r($this->sheetsFilter , true);
			return;
		}
		$sheet = $this->sheets[$sheetIndex];
		echo '<div id="'.$uid.'">';
				
			echo '<div id="'.$uid.'-'.$sheetIndex.'">';
				$this->parseSheetToHtml($uid, $sheetIndex, $sheet);
			echo '</div>';
			$this->parseStylesToHtml($uid);
		echo '</div>';
	}
	function parseSheetToHtml($uid = FALSE, $sheetIndex = FALSE, $sheet = FALSE){
		if($sheetIndex === FALSE){
			$sheetIndex = $this->sheetsFilter ? $this->sheetsFilter[0] : 0;
			$echoStyles = true;
		}
		if($this->sheetSkipped($sheetIndex))
			return;
		if($sheet === FALSE)
			$sheet = $this->sheets[$sheetIndex];
		echo '<table sheet="'.$sheetIndex.'" colspan=>';
		echo '<caption>' . htmlentities($this->names['tables'][$sheetIndex]) . '</caption>';
		echo '<thead><tr>';
		echo '<th/>';
		foreach($sheet['rows'] as $rowIndex => $row){
			foreach($row as $colIndex => $col){
				echo '<th>';			
				echo self::columnName($colIndex+1);
				echo '</th>';
			}
			break;
		}
		echo '</tr></thead>';
		
		echo '<tbody>';
		foreach($sheet['rows'] as $rowIndex => $row){
			$this->parseRowToHtml($uid, $rowIndex, $row);
		}
		echo '</tbody>';
		echo '</table>';
		
		if($echoStyles)
			$this->parseStylesToHtml($uid);
	}
	function parseRowToHtml($uid, $rowIndex, $row){
		$skip = $this->skipRows[$this->currentSheet][$rowIndex];
		echo '<tr row="'.$rowIndex.'"'
			. ($skip ? ' style="display: none;"' : '')
			.'>';
		if($skip){
			echo '</tr>';
			return;
		}
		echo '<th>' . ($rowIndex+1) . '</th>';
		foreach($row as $colIndex => $col){
			echo '<td col="'.$colIndex.'"';
			$classNames = array();
			foreach($col['attrs'] as $attr => $value){
				switch($attr){
				case 'TABLE:STYLE-NAME':
					/*if($value == 'Default'){
						$attr = '';
						break;
					}*/
					$attr = '';
					$classNames[] = 'ods-'.$value;
					break;
				case 'TABLE:NUMBER-COLUMNS-REPEATED':
				case 'OFFICE:VALUE':
				case 'OFFICE:DATE-VALUE':
				case 'OFFICE:STRING-VALUE':
				case 'OFFICE:CURRENCY':
				case 'TABLE:FORMULA':
					$attr = '';
					break;
				case 'OFFICE:VALUE-TYPE':
					$attr = '';
					switch($value){
					case 'currency':
					case 'float':
					case 'percentage':
						$classNames[] = 'odsvt-'.$value;
						$cellValue = $col['attrs']['OFFICE:VALUE'];
						if(is_numeric($cellValue) && (float)$cellValue < 0)
							$classNames[] = 'ods-negative';
						break;
					case 'date':
					case 'string':
						break;
					default:
						//$classNames[] = 'odsvt-'.$value;
						break;
					}
					break;
				default:
					break;
				}
				if($attr)
					echo ' '.$attr.'="'. htmlentities($value) . '"';
			}
			if($classNames)
				echo ' class="'.implode(' ', $classNames).'"';
			echo '>';
			//echo '<pre>'.print_r($col['attrs'], true).'</pre>';
			if(array_key_exists('value', $col)){
				echo htmlentities($col['value']);
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	
	function parseStylesToHtml($uid){
		echo '<style>';
		$this->echoDefaultCSS($uid);
		$this->parseStylesToCss($uid);
		echo '</style>';
	}
	function echoDefaultCSS($uid){
		echo "\n#".$uid.' {
			list-style: none;
		}';
		
		echo "\n#".$uid.' table[sheet] {
			border-collapse: collapse;
			border: 1px solid black;
			background-color: white;
		}';
		
		echo "\n#".$uid.' table[sheet] th {
			display: none;
			text-align: center;
			background-color: lightgray;
			border: 1px outset black;
			font-style: inherit;
			font-family: inherit;
			text-decoration: inherit;
		}';
		
		echo "\n#".$uid.' table[sheet] td {
			/*border: 1px solid lightgray;*/
			/*color: inherit;
			background: inherit;
			font-style: inherit;
			font-family: inherit;
			text-decoration: inherit;*/
		}';
		echo "\n#".$uid.' table[sheet] td.odsvt-currency
		    , #'.$uid.' table[sheet] td.odsvt-float
		    , #'.$uid.' table[sheet] td.odsvt-percentage {
			text-align: right;
			white-space: nowrap;
		}';
		echo "\n#".$uid.' table[sheet] td.ods-negative {
			color: red;
		}';
		echo "\n#".$uid.' table[sheet] td.ods-error {
			color: #FFAAAA;
		}';
		
		
		
	}
	
	function parseStylesToCss($uid){
		//styles par défaut par colonne
		foreach($this->columns as $sheetIndex => $columns){
			if($this->sheetSkipped($sheetIndex))
				continue;
			foreach($columns as $colIndex => $attrs){
				$visibility = $attrs['TABLE:VISIBILITY'];
				if($visibility == 'collapse'){
					echo "\n#".$uid . ' table[sheet="'.$sheetIndex.'"] > * > tr > *:nth-child('. ($colIndex+2). ') {'
						.'display: none;'
					.'}';
					continue;
				}
				foreach(array('TABLE:STYLE-NAME', 'TABLE:DEFAULT-CELL-STYLE-NAME') as $attr){
					if(array_key_exists($attr, $attrs)){
						$styleName = $attrs[$attr];
						$style = $this->styles[$styleName];
						$this->parseStyleToCss($uid, $styleName, $style, 'table[sheet="'.$sheetIndex.'"] > tbody > tr > td:nth-of-type('. ($colIndex+1) . ')');
						//echo "\n#".$uid . '[sheet="'.$sheetIndex."] td:nth-of-type('. $nColIndex . ') {';
					}
				}
			}
		}
		
		//foreach($this->rowsAttrs as $sheetIndex => $rows){
		//	if($this->sheetSkipped($sheetIndex))
		//		continue;
		//	foreach($rows as $rowIndex => $attrs){
		//		foreach(array('TABLE:STYLE-NAME') as $attr){
		//			if(array_key_exists($attr, $attrs)){
		//				$styleName = $attrs[$attr];
		//				$style = $this->styles[$styleName];
		//				$this->parseStyleToCss($uid, $styleName, $style, 'table[sheet="'.$sheetIndex.'"] > tbody > tr:nth-of-type('. ($rowIndex+1) . ')');
		//				echo "\n#".$uid . '[sheet="'.$sheetIndex.'"] tr:nth-of-type('. $rowIndex . ') {';
		//			}
		//		}
		//	}
		//}
		//styles définis par nom de class
		foreach($this->styles as $styleIndex => $style){
			$this->parseStyleToCss($uid, $styleIndex, $style);
		}
	}
	function parseStyleToCss($uid, $styleIndex, $style, $styleName = false){
		if(!$style['styles'])
			return;
		if(!$styleName)
			$styleName = 'table[sheet] > tbody > tr > td.ods-'.$style['attrs']['STYLE:NAME'];
		echo "\n#".$uid . ' ' . $styleName . '{';
			//echo '/*' . print_r(array_keys($style['styles']), true) . '*/';
		//$keys = array('style:table-cell-properties', 'style:table-column-properties', 'style:text-properties', 'style:paragraph-properties');
		$properties = array();
		foreach($style['styles'] as $key=>$data)
			if(strpos($key, 'style:') == 0)
				$properties = array_merge($properties, $style['styles'][$key]);
		if($properties){
			//if($style['attrs']['STYLE:NAME'] == 'ce2')
			//	echo '/*' . print_r(array_keys($properties), true) . '*/';
			foreach($properties as $propertyName => $value){
				switch($propertyName){
				case 'STYLE:FONT-NAME':
					$propertyName = 'font-family';
					break;
				case 'FO:FONT-SIZE':
					$propertyName = 'font-size';
					break;
				case 'FO:FONT-WEIGHT':
					$propertyName = 'font-weight';
					break;
				case 'STYLE:TEXT-UNDERLINE-STYLE':
					$propertyName = 'text-decoration';
					switch ($value){
					case 'none':
						$propertyName = '';
						break;
					default:	
						$value = 'underline';
						break;
					}
					break;
				case 'STYLE:TEXT-UNDERLINE-WIDTH':
				case 'STYLE:TEXT-UNDERLINE-COLOR':
					$propertyName = '';
					break;
				case 'STYLE:TEXT-LINE-THROUGH-STYLE':
					$propertyName = 'text-decoration';
					switch ($value){
					case 'none':
						$propertyName = '';
						break;
					default:	
						$value = 'line-through';
						break;
					}
					break;
				case 'STYLE:TEXT-OUTLINE':
					$propertyName = 'text-outline';
					switch ($value){
					case 'false':
						$propertyName = '';
						break;
					default:	
						//$value = 'default';
						break;
					}
					break;
				case 'STYLE:TEXT-OVERLINE-STYLE':
					$propertyName = 'text-decoration';
					switch ($value){
					case 'none':
						$propertyName = '';
						break;
					default:	
						$value = 'overline';
						break;
					}
					break;
				case 'STYLE:TEXT-OVERLINE-COLOR':
					$propertyName = '';
					break;
				case 'FO:TEXT-SHADOW':
					$propertyName = '';
					break;
				case 'FO:BORDER':
				case 'FO:BORDER-TOP':
				case 'FO:BORDER-BOTTOM':
				case 'FO:BORDER-LEFT':
				case 'FO:BORDER-RIGHT':
					$propertyName = strtolower(substr($propertyName, 3));
					$value = str_replace('0.002cm', '1px', $value);
					break;
				case 'FO:COLOR':
					$propertyName = 'color';
					break;
				case 'FO:BACKGROUND-COLOR':
					$propertyName = 'background-color';
					break;
				case 'FO:FONT-STYLE':
					$propertyName = 'font-style';
					switch ($value){
					case 'none':
						$propertyName = '';
						break;
					default:	
						break;
					}
					break;
				case 'FO:WRAP-OPTION':
					$propertyName = 'white-space';
					break;
				case 'FO:PADDING':
				case 'FO:PADDING-TOP':
				case 'FO:PADDING-BOTTOM':
				case 'FO:PADDING-LEFT':
				case 'FO:PADDING-RIGHT':
					$propertyName = strtolower(substr($propertyName, 3));
					break;
				//STYLE:TEXT-ALIGN-SOURCE: value-type
				case 'STYLE:TEXT-ALIGN-SOURCE':
					if(array_key_exists('FO:TEXT-ALIGN', $properties)){
						$propertyName = '';
						break;
					}
				case 'FO:TEXT-ALIGN':
					$propertyName = 'text-align';
					switch($value){
					case 'value-type':
						$propertyName = '';
					case 'fix':
						$value = 'center';
					case 'end':
						$value = 'right';
					default:
						break;
					}
					break;
				
				case 'FO:MARGIN-LEFT':
					if($value == '0cm')
						$propertyName = '';
					else
						$propertyName = 'padding-left';
					break;
				case 'STYLE:ROW-HEIGHT':
					$propertyName = 'height';
					break;
				//STYLE:COLUMN-WIDTH: 1.919cm
				case 'STYLE:COLUMN-WIDTH':
					$propertyName = 'width';
					break;
				
				case 'STYLE:REPEAT-CONTENT':
				case 'FO:BREAK-BEFORE':
				case 'STYLE:DIAGONAL-BL-TR':
				case 'STYLE:DIAGONAL-TL-BR':
				case 'STYLE:REPEAT-CONTENT':
				case 'STYLE:ROTATION-ANGLE':
				case 'STYLE:ROTATION-ALIGN':
				case 'STYLE:VERTICAL-ALIGN':
				case 'STYLE:USE-OPTIMAL-ROW-HEIGHT':
					$propertyName = '';
					break;
				default:
					
					if(preg_match('/^STYLE\:.*-(ASIAN|COMPLEX)$/', $propertyName))
						$propertyName = '';
					else {
						echo "\n/*\t" . $propertyName . ': ' . $value .';';
						$propertyName = false;
						echo '*/';
					}
					break;
				}
				if($propertyName){
					echo "\n\t" . $propertyName . ': ' . $value .';';
					
				}
			}
		}
		echo '}';
		
	}
	
	function echoScript($uid, $defaultViewIndex = 0){
		echo '<script> $().ready(function(){
	$("#' . $uid . '")
	  .tabs({
		active: ' . $defaultViewIndex . ',
	  })
	;
 });</script>';
	}

	public static function columnName($colIndex){
		$str = '';
		while ($colIndex > 0) {
			$str = chr(ord('A') - 1 + $colIndex % 26) . $str;
			$colIndex = (int) ($colIndex / 26);
		}
		return $str;
	}
}

function parseOds($file, $sheetsFilters = FALSE) {
	$tmp = get_tmp_dir();
	copy($file,$tmp. DIRECTORY_SEPARATOR .basename($file));
	$path = $tmp. DIRECTORY_SEPARATOR .basename($file);
	$uid = uniqid();
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid);
	shell_exec('unzip '.escapeshellarg($path).' -d '.escapeshellarg($tmp. DIRECTORY_SEPARATOR .$uid));
	$obj = new ods();
	$obj->setSheetsFilters( $sheetsFilters );
	$obj->parse(file_get_contents($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'content.xml'));
	helpers::rrmdir($tmp.DIRECTORY_SEPARATOR.$uid);
	//echo('sheets<pre>'); print_r($obj->sheets); echo '</pre>';
	//echo('styles<pre>'); print_r($obj->styles); echo '</pre>';
	//echo('names<pre>'); print_r($obj->names); echo '</pre>';
	//echo('skipRows<pre>'); print_r($obj->skipRows); echo '</pre>';
	//echo('rowsAttrs<pre>'); print_r($obj->rowsAttrs); echo '</pre>';
	return $obj;
}

function saveOds($obj,$file) {
	
	$charset = ini_get('default_charset');
	ini_set('default_charset', 'UTF-8');
	$tmp = get_tmp_dir();
	$uid = uniqid();
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid);
	file_put_contents($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'content.xml',$obj->array2ods());
	file_put_contents($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'mimetype','application/vnd.oasis.opendocument.spreadsheet');
	file_put_contents($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'meta.xml',$obj->getMeta('fr-FR'));
	file_put_contents($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'styles.xml',$obj->getStyle());
	file_put_contents($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'settings.xml',$obj->getSettings());
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'META-INF'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'acceleator'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'popupmenu'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'statusbar'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'floater'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'menubar'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'progressbar'.DIRECTORY_SEPARATOR.'');
	mkdir($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'Configurations2'.DIRECTORY_SEPARATOR.'toolbar'.DIRECTORY_SEPARATOR.'');
	file_put_contents($tmp. DIRECTORY_SEPARATOR .$uid.DIRECTORY_SEPARATOR.'META-INF'.DIRECTORY_SEPARATOR.'manifest.xml',$obj->getManifest());
	//shell_exec('cd '.$tmp.DIRECTORY_SEPARATOR.$uid.';zip -r '.escapeshellarg($file).' .'.DIRECTORY_SEPARATOR.'');
	shell_exec('zip -r '.escapeshellarg($file).' '.$tmp.DIRECTORY_SEPARATOR.$uid.DIRECTORY_SEPARATOR.';');
	helpers::rrmdir($tmp.DIRECTORY_SEPARATOR.$uid);
	ini_set('default_charset',$charset);
}

function newOds() {
	$content = '<?xml version="1.0" encoding="UTF-8"?>
	<office:document-content xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" office:version="1.0"><office:scripts/><office:font-face-decls><style:font-face style:name="Liberation Sans" svg:font-family="&apos;Liberation Sans&apos;" style:font-family-generic="swiss" style:font-pitch="variable"/><style:font-face style:name="DejaVu Sans" svg:font-family="&apos;DejaVu Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/></office:font-face-decls><office:automatic-styles><style:style style:name="co1" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width="2.267cm"/></style:style><style:style style:name="ro1" style:family="table-row"><style:table-row-properties style:row-height="0.453cm" fo:break-before="auto" style:use-optimal-row-height="true"/></style:style><style:style style:name="ta1" style:family="table" style:master-page-name="Default"><style:table-properties table:display="true" style:writing-mode="lr-tb"/></style:style></office:automatic-styles><office:body><office:spreadsheet><table:table table:name="Hoja1" table:style-name="ta1" table:print="false"><office:forms form:automatic-focus="false" form:apply-design-mode="false"/><table:table-column table:style-name="co1" table:default-cell-style-name="Default"/><table:table-row table:style-name="ro1"><table:table-cell/></table:table-row></table:table><table:table table:name="Hoja2" table:style-name="ta1" table:print="false"><table:table-column table:style-name="co1" table:default-cell-style-name="Default"/><table:table-row table:style-name="ro1"><table:table-cell/></table:table-row></table:table><table:table table:name="Hoja3" table:style-name="ta1" table:print="false"><table:table-column table:style-name="co1" table:default-cell-style-name="Default"/><table:table-row table:style-name="ro1"><table:table-cell/></table:table-row></table:table></office:spreadsheet></office:body></office:document-content>';
	$obj = new ods();
	$obj->parse($content);	
	return $obj;
}

function get_tmp_dir() {
	$path = '';		
	if(!function_exists('sys_get_temp_dir')){	    
		$path = try_get_temp_dir();
	}else{				
		$path = sys_get_temp_dir();
		if(is_dir($path)){
			return $path;	
		}else{			
			$path = try_get_temp_dir();
		}
	}
	return $path;
}

function try_get_temp_dir() {	        
    // Try to get from environment variable
	if(!empty($_ENV['TMP'])){
		$path = realpath($_ENV['TMP']);
	}else if(!empty($_ENV['TMPDIR'])){
		$path = realpath( $_ENV['TMPDIR'] );
	}else if(!empty($_ENV['TEMP'])){
		$path = realpath($_ENV['TEMP']);
	}
	// Detect by creating a temporary file
	else{
		// Try to use system's temporary directory
		// as random name shouldn't exist
		$temp_file = tempnam(md5(uniqid(rand(),TRUE)),'');
		if ($temp_file){
			$temp_dir = realpath(dirname($temp_file));
			unlink($temp_file);
			$path = $temp_dir;
		}else{
			return "/tmp";
		}
	}	
	return $path;
}

?>
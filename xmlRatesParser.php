<?php

// Парсинг иксэмэль файла

class xmlTerroristsParser {
	
	
	public static function getSingleData ($file,$name,$attr=null) {	
		$FILE_DATA = "";
		$dom_xml= new DomDocument();
		$dom_xml = DomDocument::load($file);
		$mod=$dom_xml->getElementsByTagName($name);
		Foreach ($mod as $element){		
			$FILE_DATA = $element->nodeValue;
			if($attr!=null){ $FILE_DATA = $element->getAttribute($attr); }		
		}
		unset($dom_xml);
		return $FILE_DATA;
	}
	
	public static function convertDate($text){
	$sign = "-";
	$format = array(4,6,11,13);
	$signs = array("-","-",":",":");
	$naewDate = "";
	$text_rows = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
	for($i=1;$i<=count($text_rows);$i++) {
		$naewDate .= $text_rows[$i-1];
		for($a=1;$a<=count($format);$a++){
			if($i==$format[$a-1]) $naewDate .= $signs[$a-1];
		}
	}
	return $naewDate;
}
	
	public static function getXml($file,$filial){   
		$reader = new XMLReader();
		$reader->open($file);
		while ($reader->read()) {
			switch ($reader->nodeType) {
			case (XMLREADER::ELEMENT):
				if ($reader->localName == "entity") {						
					while ($reader->read()) {
						if ($reader->localName == "name") {
                            if ($reader->nodeType == XMLREADER::END_ELEMENT) break;
                        } else{
                                if ($reader->nodeType == XMLREADER::ELEMENT) {	
                                    $talibanName = null;
                                    
                                    $talibanName = $reader->readInnerXML("name");
                                }												
                            }							
					}					
				} else break;			
			}			
		}
		$reader->close($file);
		return $RATES;
	}	
				

}

?>
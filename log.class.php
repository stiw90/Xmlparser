<?php 

class LOG {
	
	const FILE = "log.txt";
	const EMPTYLOG = "";
		
	public static function write ( $class, $func, $text ) {
		$LOG_TEXT = "[".date('d-m-Y H:i:s')."][".$class."][".$func."] ".$text."\n";
		self::write_log ( $LOG_TEXT );
	}	
    
    public static function write_txt ($func, $text ) {
		$LOG_TEXT = $text."|".$func."|\n";
		self::write_log ( $LOG_TEXT );
	}	
	
	public static function write_array ( $class, $func, $array ) {
		$LOG_TEXT = "[".date('d-m-Y H:i:s')."][".$class."][".$func."] ARRAY(".count($array).")\n";
		foreach($array as $key=>$value){
			$info = self::var_info( $value );			
			$LOG_TEXT.= "\t[".$key."]=> ".$info."\n";
			if(is_array($value)) {
				foreach($value as $key2=>$value2){
					$info2 = self::var_info( $value2 );
					$LOG_TEXT.= "\t\t[".$key2."]=> ".$info2."\n";
					if(is_array($value2)) {
						foreach($value2 as $key3=>$value3){
							$info3 = self::var_info( $value3 );
							$LOG_TEXT.= "\t\t\t[".$key3."]=> ".$info3."\n";
						}
					}
				}
			}
		}		
		self::write_log ( $LOG_TEXT );
	}

	private static function var_info ( $var ) {
		$type = gettype($var);
		if($type=='string') return $type."(".strlen($var).") ".$var;
		else if($type=='array') return $type."(".count($var).") ";
		else if($type=='integer') return $type."(".$var.") ";
		else if($type=='boolean') {
			if($var)$info = 'true'; else $info = 'false';
			return $type."(".$info.")";
		} else return $type." ".$var;
	}
	
	private static function write_log ( $log ) {
		$f = fopen( self::FILE, "a+" );
		fwrite( $f, $log );
		fclose( $f );
	}	
	
	public static function clear ( ) {				
		$f = fopen( self::FILE, 'a');
		ftruncate($f, 0);
	}
	
}

?>
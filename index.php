<html> 
  <head> 
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Xml & excel Parser</title>
  </head>
  <body>
  
  <?php 
  
  include('log.class.php');
  
  //CREATE LOG
  $LOG = new LOG();
  $LOG->clear();
  $time_begin = date('d-m-Y H:i:s');
  $LOG_EY = $LOG->write('TIME','BEGIN',$time_begin);   
  
  ini_set('display_errors', 'On');
  error_reporting(E_ALL);

    $start = microtime(true);
    $filespath = array();
    $xlsFilePath = array();               
    $reader = new XMLReader();
    set_time_limit(0); 
    $count = 0;
    $opts = array(  'user'    	=> 'root',
                    'pass'    	=> 'root',
                    'db'      	=> 'tableList',
                    'charset' 	=> 'utf8');
                    
    require_once('ocidb.class.php');
    $db = oci_connect($opts['user'], $opts['pass'], $opts['db'], $opts['charset']);
    
    if ( !$db )	{			
		echo oci_error();
		}
    foreach($filespath as $path){
        $reader->open($path);
        $orgname = null;
        if($path== 'y:\organizations.xml' ){
            $orgname = "Org";
        }
        else{ 
            if($path== 'y:\test.XML'){
                $orgname = "test";
            }
             else { 
                   $orgname = "boo";    
            }
        }   
    while($reader->read()) {
        if($reader->nodeType == XMLReader::ELEMENT) {
              if($reader->localName == 'nativeCharName' && $reader->getAttribute("charSet") == 'Cyrillic' ) {
                $reader->read();
                if($reader->nodeType == XMLReader::TEXT) {
                $talan = null;
                    $talan = $reader->value;
                    setToDB($talan,$orgname);       
                    $count++;
                    }
                }                
            }
        }
   $LOG_EY = $LOG->write('talan','in base:',$count);    
   $reader->close(); 
    } 
   
    //truncate db
    function truncateDB(){
        global $db;
        $trunc_query = "TRUNCATE TABLE list";
        $stid=OCIParse($db, $trunc_query);
        oci_execute($stid);
        OCICommit($db);
    }
    
    //save from xml to db
    function setToDB($taliban,$orgname){
        global $db;
        $query = "INSERT INTO list (Name1,Name2) values (q'(".$talan.")',q'(".$orgname.")' )";
        $stid=OCIParse($db, $query);
        oci_execute($stid);
        OCICommit($db);
    }
    
    // excel reader
    function readExelFile($filepath){
        require_once "PHPExcel.php"; 
        $ar=array(); 
        $inputFileType = PHPExcel_IOFactory::identify($filepath);  
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $objPHPExcel = $objReader->load($filepath);
        $ar = $objPHPExcel->getActiveSheet()->toArray(); 
        return $ar; 
    }
    
     foreach($xlsFilePath as $filepath){
     $nameBadCompany = null;
     $ar = array();
     $ar=readExelFile($filepath);
     $companyName = null;
         if($filepath == 'y:\bool.xls'){
             $companyName = "bool";
             foreach($ar as $key=>$ar_colls){
                if($key>1){
                     $nameBadCompany = $ar_colls[0];
                     $text = $nameBadCompany." - ".$companyName;
                     $LOG_EY = $LOG->write('BadCompany',$companyName,$text);
                     excelToDB($nameBadCompany,$companyName);
                 }
             }
        }
        else {
            $companyName = "num";
            foreach($ar as $key=>$ar_colls){
                if($key>1){
                $UNPBadCompany = $ar_colls[0];
                $nameBadCompany = $ar_colls[1];
                $text = $nameBadCompany." - ".$companyName;
                $LOG_EY = $LOG->write('BadCompany',$companyName,$text);
                excelToDBUNP($nameBadCompany,$companyName,$UNPBadCompany);
                }
            }   
        }
     }
     
     //save from excel to db
     function excelToDB($nameBadCompany,$companyName){ 
         global $db;
         $query = "INSERT INTO list (Name1, Name2) values (q'(".$nameBadCompany.")',q'(".$companyName.")' )";
         $stid=OCIParse($db, $query);
         oci_execute($stid);
         OCICommit($db);
    } 
    //save from excel to db with arg
    function excelToDBUNP($nameBadCompany,$companyName,$UNPBadCompany){
        global $db;
        $query = "INSERT INTO list (Name1, Name2, Name3) values (q'(".$nameBadCompany.")',q'(".$companyName.")',q'(".$UNPBadCompany.")' )";
        $stid=OCIParse($db, $query);
        oci_execute($stid);
        OCICommit($db);
    }  

    $time = microtime(true) - $start;
    $time_min = ($time/60);
    
    $LOG_EY = $LOG->write('TIME','END',$time_min);    
  ?>

  </body>
</html>
  

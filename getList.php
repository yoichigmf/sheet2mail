<?php

require_once __DIR__ . '/vendor/autoload.php';

require 'functions.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$log = new Logger('name');
$log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));

date_default_timezone_set('Asia/Tokyo');

header("Content-Type: application/json; charset=UTF-8"); //ヘッダー情報の明記。必須。



 $sheetname = filter_input(INPUT_POST,"sheetname"); //変数の出力。jQueryで指定したキー値optを用いる

 $sheetid= filter_input(INPUT_POST,"sheetid"); //変数の出力。jQueryで指定したキー値optを用いる



$envname  = getenv('SHEET_NAME');
$envid= getenv('SPREADSHEET_ID');
 //$sheetname = 'シート1';
 $spreadsheetId = getenv('SPREADSHEET_ID');

 if ( ! empty($sheetid)  ){
     $spreadsheetId = $sheetid;
 }

$client = getGoogleSheetClient();
 if( empty($sheetname)  ) {
     $sheetname  = getenv('SHEET_NAME');
     if( empty($sheetname)  ) {
          //$sheetname = 'シート1';
          $sheetname = GetFirstSheetName(  $spreadsheetId, $client );
     }
 }




$sheetd = GetSheet( $spreadsheetId, $sheetname, $client );



$isdone = false;


$listAr = array(
   
);


$output_ar = array();    // array of output data

foreach ($sheetd as $index => $cols) {

//echo "\nindex ${index}  ";  //////

     $dated = $cols[0];
     $userd = $cols[1];

     $kind = $cols[2];
     $url  = $cols[3];

     $stext = $cols[4];


 if ( strcmp( $kind ,'location' ) == 0 ) {   //  if record is location data

   //  echo "\nkind ${kind}  ";  sample



        $xcod = (double)$cols[6];    //  coordinate
        $ycod = (double)$cols[5];

     
         $attr = array();

         $attr['日付'] = $dated;
         $attr['ユーザ'] = $userd;
         $attr['種別'] = $kind;
         $attr['TEXT'] = $stext;
         $attr['url'] = $url;
         
         $attr['経度'] = $xcod ;
         $attr['緯度'] = $ycod;
                  
         array_push($output_ar, $attr);

       }    // location
       else  {



       if ( $index > 0 ){


      
                  $attr = array();

                  $attr['日付'] = $dated;
                  $attr['ユーザ'] = $userd;
                  $attr['種別'] = $kind;
                  $attr['TEXT'] = $stext;
                  $attr['url'] = $url;


           

                array_push($output_ar, $attr);  


             }
       }

     }  //  foreach

     $retjson = json_encode( $output_ar  );      // make json
     echo $retjson;

?>

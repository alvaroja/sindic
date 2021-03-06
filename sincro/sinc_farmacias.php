<?
/**
* ----------------------------------------------------------------
*			Resinc Farmacias
*			Actualizacion de los datos en MYSql desde DBF	
* 
*  Developer        : Alvaro J. Vera
*  released at      : Nov 2005, Actual 2012
*  last modified by : Alvaro
* 
* --------------------------------------------------------------
*
*
*CREATE TABLE FARMACIAS (
*	ID_FARMACI Integer,
*	CUIT varchar(13),
*	RAZON_SOCI varchar(25),
*	NOMBRE_TIT varchar(30),
*	DOMICILIO varchar(25),
*	TELEFONO varchar(12),
*	FAX varchar(12),
*	EMAIL varchar(40),
*	LOCALIDAD varchar(30),
*	COD_POSTAL Integer,
*	IVA varchar(1),
*	OBSERVACIO mediumtext
*	);
*
* en SQL   id_farmacia	id_parentesco	id_afiliado	nombre	fecha_nacimiento	tipo_documento	nro_documento	sexo	fecha_alta	incapacidad
**/


	/* load the required classes */
	error_reporting(0);

function existeItem($busca) {
	global $arr_sql;

	foreach ($arr_sql as $key => $value) {
		//echo $value[0]."_";
		if ($value[0]==$busca){
			$ret=true;
			break;
		}else{
			$ret=false;			
		}
	}

	return($ret);
}


require_once "Column.class.php";
require_once "Record.class.php";
require_once "Table.class.php";
include_once("../library/adodb_lite/adodb.inc.php"); 
//	include_once("../localconf.php");

$dbuname="sindicatofarm";
$dbpass="";
$dbhost="localhost";
$dbname="sindicatofarm";

$db = ADONewConnection();

$db->debug = false ;
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");


$commit = ADONewConnection();
$result = $commit->Connect("$dbhost", "$dbuname", "", "$dbname");
$commit->debug = false ;

/*Setear los parametros de las tablas a resincronizar*/
//*****************************************************
$dbf='../../unidadc/sindfarm/Data/farmacias.dbf';
//*****************************************************

//$consulta = "SELECT * FROM categorias";
//$rs=$db->Execute($consulta);
//$arr_cat=$rs->GetArray();  //Obtengo el arreglo con las categorias
//var_dump($arr_cat);

/*
* Alta DB
*			Si es la primera vez que se ejecuta el script se genera la tabla farmaciasmd5 donde se realizará el control del md5
*/

$varios = "";  //Variable para resumen de actividades.
$consulta = "SELECT id_farmacia FROM farmaciasmd5 Order By id_farmacia limit 0,10";
$rs=$db->Execute($consulta);
if (!$rs) {  //No existe tabla --> la creamos
		$add="CREATE TABLE IF NOT EXISTS `farmaciasmd5` (`id_farmacia` int(5) NOT NULL, `md5` char(32) COLLATE latin1_spanish_ci NOT NULL, PRIMARY KEY (`id_farmacia`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;";
		$rs=$db->Execute($add);
		$varios='-Se crea la tabla para control MD5-';
}
/*
* Buscamos los elementos si ya existia la DB que se calcularon la ultima vez que se corrio el control de md5
*/
$consulta = "SELECT * FROM farmacias Order By id_farmacia";
$rs=$db->Execute($consulta);

/* create a table object and open it */
$table = new XBaseTable($dbf);
$table->open();

	/* print some header info 
    echo "Datos de la tabla DBF:$dbf<br/>";
    echo "version: ".$table->version."<br />";
    echo "foxpro: ".($table->foxpro?"yes":"no")."<br />";
    echo "modifyDate: ".date("r",$table->modifyDate)."<br />";
    echo "recordCount: ".$table->recordCount."<br />";   //cantidad de registros para controlar la cantidad existentes. Si cambio recargar.
    echo "headerLength: ".$table->headerLength."<br />";
    echo "recordByteLength: ".$table->recordByteLength."<br />";
    echo "inTransaction: ".($table->inTransaction?"yes":"no")."<br />";
    echo "encrypted: ".($table->encrypted?"yes":"no")."<br />";
    echo "mdxFlag: ".ord($table->mdxFlag)."<br />";
    echo "languageCode: ".ord($table->languageCode)."<br />";

	echo $rs->FieldCount()."<br/>";*/
	$cantidad_registros_sql = $rs->RecordCount();//."<br/>";

	$arr_sql=$rs->GetArray();

	$reg_found=0;
	$reg_no_encontrado="";	
	
	$md5creados=0;
	$md5actualizados=0;
	$altas=0;
	$sincambios=0;
	$registro_fila=0;
    while ($record=$table->nextRecord()) {
	    
	// -------   Iteracion entre las filas    -------

	    $col=0; //Zero to columna DBF
	    $cadenamd5=""; //Reseteamos la variable para el calculo del md5
	    $toInsertar="";
	    
	    foreach ($table->getColumns() as $i=>$c) {

	    	// ---------  Iteracion entre las columnas    -------

			$columna_dbf=$record->getString($c);			
			//echo "-".$columna_dbf."-";
			$cadenamd5.=$columna_dbf; //acumulamos la cadena completa para convertir en md5

			if ($col==0) {  //tomo el primer campo id_farmacia del DBF y lo comparo con todos de SQL
				$id_farmacia=$columna_dbf;
				if (existeItem($columna_dbf)) {
					//Existe en la DB buscar en tabla de md5 y comparar - se hace fuera de este bloque por que se necesita la cadena md5 completa
				    //echo "El elemento se encuentra ".$columna_dbf." buscar MD5 y actualizar si corresponde ";
				    $existe=true;
				} else {
					//No existe en DB levantar los datos y crear Md5
					//echo "NO SE ENCUENTRA ".$columna_dbf." dar de alta y crear MD5 ";
					$existe=false;
				}
			}
			// Navego en las otras columnas para generar un array que luego nos servira para actualizar o insertar elementos en la MySQL
			/*if  (($col==4)||($col==8))  {   //conversion fechas a fmt DB
				        $toInsertar[$id_farmacia][$col]=date("Y-m-d", strtotime($columna_dbf));
				        
			} else {*/
				        $toInsertar[$id_farmacia][$col]=addslashes(htmlentities($columna_dbf));
			//}	
		$col++;
		//echo "<br>";
		}
		

		$md5=md5($cadenamd5);
	    //echo $md5."->".$cadenamd5."<br/>";

	    //Existe el afiliado que importamos del DBF en nuestra MySQL -> entonces consulta MD5, si existe MD5 a)son iguales? no pasa nada b) difiere - actualizar datos. No existe MD5? -> Crearlo. 
		if ($existe) {
			
				$db2 = ADONewConnection();
				$db2->debug = false ;
				$res = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");
				// Buscamos el md5 del afiliado en cuestion
				$consulta = "SELECT * FROM farmaciasmd5 where id_farmacia=".$id_farmacia;
				$res=$db2->Execute($consulta);

				if ($db2->Affected_Rows()<1) { //No existe md5 asociado, crear
					echo "Creando MD5 para Farmacias ".$id_farmacia;
					$consulta="INSERT INTO `sindicatofarm`.`farmaciasmd5` (`id_farmacia`, `md5`) VALUES ('".$id_farmacia."', '".$md5."')";
					$sale=$commit->Execute($consulta);
					$md5creados++;
				} else {  //Existe obtener Md5 y comparar
					echo "Farmacia ".$id_farmacia." SQLMD5=".$res->fields['md5']." MD5Calculado=".$md5."<br/>\n\n";
					if ($res->fields['md5']!=$md5) {
						$md5actualizados++;

						$updatemd5="UPDATE  `sindicatofarm`.`farmaciasmd5` SET  `md5` =  '".$md5."' WHERE  `farmaciasmd5`.`id_farmacia` =".$id_farmacia;
// 	id_parentesco	id_afiliado	nombre	fecha_nacimiento	tipo_documento	nro_documento	sexo	fecha_alta	incapacidad

						$updateafiliado="UPDATE  `sindicatofarm`.`farmacias` SET  
						`id_farmacia`=	'".$toInsertar[$id_farmacia][1]."',
						`cuit`=	'".$toInsertar[$id_farmacia][2]."',
						`razon_social`= '".$toInsertar[$id_farmacia][3]."',	
						`nombre_titular`= '".$toInsertar[$id_farmacia][4]."',	
						`domicilio`= '".$toInsertar[$id_farmacia][5]."',	
						`telefono`= '".$toInsertar[$id_farmacia][6]."',	
						`fax`=	'".$toInsertar[$id_farmacia][7]."',
						`email`=	'".$toInsertar[$id_farmacia][8]."',
						`localidad`=	'".$toInsertar[$id_farmacia][9]."',
						`cod_postal`=	'".$toInsertar[$id_farmacia][10]."',
						`iva`=	'".$toInsertar[$id_farmacia][11]."',
						`observaciones`=	'".$toInsertar[$id_farmacia][12]."'
						 WHERE  `farmacias`.`id_farmacia` =".$id_farmacia;
						 //echo $updateafiliado."-".$updatemd5."<br/>";
						 $sale=$commit->Execute($updateafiliado);
						 $sale=$commit->Execute($updatemd5);
						 //var_dump($toInsertar);
					} else {
						$sincambios++;
					}
				}


		}else{ //No exste farmacia, es necesario darlo de alta en la MySQL y generar su MD5

			if ($toInsertar[$id_farmacia][3]!=""){
			$insert="INSERT INTO farmacias  values ( ".$id_farmacia.",
							'".$toInsertar[$id_farmacia][1]."',
							'".$toInsertar[$id_farmacia][2]."',
						 '".$toInsertar[$id_farmacia][3]."',	
						 '".$toInsertar[$id_farmacia][4]."',	
						 '".$toInsertar[$id_farmacia][5]."',	
						 '".$toInsertar[$id_farmacia][6]."',	
							'".$toInsertar[$id_farmacia][7]."',
							'".$toInsertar[$id_farmacia][8]."',
							'".$toInsertar[$id_farmacia][9]."',
							'".$toInsertar[$id_farmacia][10]."',
							'".$toInsertar[$id_farmacia][11]."',
						'".$toInsertar[$id_farmacia][12]."')";
						 $insertmd5="INSERT INTO  `sindicatofarm`.`farmaciasmd5` values ('".$id_farmacia."', '".$md5."')";
						 $sale=$commit->Execute($insert);
						 $sale=$commit->Execute($insertmd5);
			$altas++;
			$md5creados++;
			}
			//var_dump($toInsertar);
		}
		$registro_fila++;

	}
				//if $c==

	//var_dump($arr_sql);
	echo "\n --- RESUMEN de OPERACIONES ---";
	echo "\n".$varios;
	echo "\n Se realizaron altas: ".$altas;
	echo "\n Se realizaron sin cambios: ".$sincambios;
	echo "\n Se realizaron altas en md5: ".$md5creados;
	echo "\n Se realizaron updates segun md5: ".$md5actualizados;
	$table->close();
	$consulta="UPDATE `sindicatofarm`.`parametros` SET `valor`= '".date("Y-m-d")."' WHERE `variable`='sincronizacion'";
	$sale=$commit->Execute($consulta);
?>

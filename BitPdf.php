<?php
/**
* Pdf system class for outputing pdf file images
*
* @author   
* @version  $Revision: 1.7 $
* @package  pdf
*/

/**
* required setup
*/
//include ('lib/pdflib/class.ezpdf.php');
require_once( PDF_PKG_PATH.'class.ezpdf.php' );
require_once( UTIL_PKG_PATH.'htmlparser/html_parser_inc.php' );

/**
* @package  pdf
* @subpackage  BitPdf
*/
class BitPdf extends Cezpdf
{
	var $mSettings;
	
	function BitPdf() 
	{
		Cezpdf::Cezpdf( 'TRADEPAPERBACK' );
		
		$this->loadSettings();
		$this->selectFont( $this->mSettings['font'] );
		
		$grammarfile=UTIL_PKG_PATH.'htmlparser/htmlgrammar.cmp';
		if( $fp=fopen($grammarfile,'r')) {
			$this->html_grammar=unserialize(fread($fp,filesize($grammarfile)));
		}

		fclose($fp);
	}

	function loadSettings() {
		global $gBitSystem;
		$this->mSettings = $this->getDefaultSettings();
		foreach( array_keys( $this->mSettings ) as $key ) {
			$keyPref = $gBitSystem->getConfig( $key, NULL );
			if( !empty( $keyPref ) ) {
				$this->mSettings[$key] = $keyPref;
			}
		}
	}

	function verifySettings( &$pParamHash ) {
		$defaults = $this->getDefaultSettings();
		
		// let's trim out all whitespace
		foreach( array_keys( $pParamHash ) as $key ) {
			$pParamHash[$key] = trim( $pParamHash[$key] );
		}

		if( isset( $pParamHash['font'] ) && ( $pParamHash['font'] != $defaults['font'] ) ) {
			$pParamHash['setting_store']['font'] = $pParamHash['font'];
		}

		$numericKeys = array( 'textheight', 'h1height', 'h2height', 'h3height', 'tbheight', 'imagescale' );

		foreach( $numericKeys as $key ) {
			if( !is_numeric( $pParamHash[$key] ) ) {
				$this->mErrors[$key] = "$key must be a number";
			} elseif( isset( $pParamHash[$key] ) && ( $pParamHash[$key] != $defaults[$key] ) ) {
				$pParamHash['setting_store'][$key] = $pParamHash[$key];
			}
		}
		
		if( isset( $pParamHash['autobreak'] ) ) {
			$pParamHash['setting_store']['autobreak'] = $pParamHash['autobreak'];
		}

		return( !empty( $pParamHash['setting_store'] ) );
	}

	// get packages that are capable of pdf output
	// for some reason `package` is returned as empty every time - XING
	function getPDFable() {
		global $gBitSystem;
		$ret = NULL;
		$query = "SELECT `name`,`package`,`pref_value` FROM `" . BIT_DB_PREFIX . "kernel_prefs` WHERE `name` LIKE ?";
		$result = $gBitSystem->mDb->query($query,array('feature_%_generate_pdf'));
		while( $res = $result->fetchRow() ) {
			$ret[] = $res;
		}
		
		return $ret;
	}

	function storeSettings( &$pParamHash ) {
		global $gBitSystem;
		$gBitSystem->expungePackagePreferences( PDF_PKG_NAME );
		if( $this->verifySettings( $pParamHash ) ) {
			foreach( array_keys( $pParamHash['setting_store'] ) as $key ) {
				$gBitSystem->storeConfig( $key, $pParamHash['setting_store'][$key],  PDF_PKG_NAME );
				$this->mSettings[$key] = $pParamHash['setting_store'][$key];
			}
		}
	}


	function getDefaultSettings() {
		return( array(	"font" => PDF_PKG_PATH."fonts/LuxiSerif.afm",
						"textheight" => 10,
						"h1height" => 16,
						"h2height" => 14,
						"h3height" => 12,
						"tbheight" => 14,
						"imagescale" => 0.5,
						"autobreak" => 'off'
			) );
	}


	function insert_html(&$data)
	{
	  // new code starts here
	  // read grammar
	//  $grammarfile='lib/htmlparser/htmlgrammar.cmp';
	//  if(!$fp=@fopen($grammarfile,'r')) die();
	//  $grammar=unserialize(fread($fp,filesize($grammarfile)));
	//  fclose($fp);
//vd( $data );
	  // create parser object and insert html code
	  $htmlparser=new HtmlParser($data,$this->html_grammar,'',0);
	  // parse it
	  $htmlparser->Parse();
	  //debug output
//vd( $htmlparser->content );
	
	  // now set it together
	  $src='';
	  $dummy=array();
	  $this->WalkParsedArray($htmlparser->content,$src,$dummy);
	  /*
	  echo "<pre>";
	  echo "Walk array:\n\n";
	  echo $src;
	  echo "</pre>";
	  die();
	  */
	  $this->flush($src);
	  // new code ends here
	  
	
	  /* old code starts here
	  //$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"data before parsing:\n$data\n");fclose($fpd);
	  //parse data
	
	  //replace <br/>
	  $data=preg_replace("#<br/>#","\n",$data);
	  // titlebar
	  $data=preg_replace("#<div class=['\"]titlebar['\"]>(.+)</div>#","<C:titlebar:\$1>",$data);
	  //$data=preg_replace("#<div class='titlebar'>(.+)</div>#e","'<C:titlebar:\$1>'.$this->add_linkdestination('$1')",$data);
	  //line
	  $data=preg_replace("#<hr/>#","<C:hr:>",$data);
	  //headings
	  $data=preg_replace("#<h1>(.+)</h1>#","<C:h1:\$1>",$data);
	  $data=preg_replace("#<h2>(.+)</h2>#","<C:h2:\$1>",$data);
	  $data=preg_replace("#<h3>(.+)</h3>#","<C:h3:\$1>",$data);
	  //images
	  $data=preg_replace("#<img(.+)src=[\"\']([^\"|^\']+)[\"\'].*\\>#","<C:img:\$2>",$data);
	  //links
	  $data=preg_replace("#<a.+href=[\"\']([^\"|^\']+)[\"\'].*>(.*)</a>#e","\$this->whatlink('$1','$2')",$data);
	
	  //$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"before adding text\n");fclose($fpd);
	  //$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"data:\n$data\n");fclose($fpd);
	  $this->ezText($data,$this->mSettings['textheight']);
	  //$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"after adding text\n");fclose($fpd);
	  iold code ends here */
	}
	
	function flush($src) {
	  $this->ezText($src,$this->mSettings['textheight']);
	}
	
	function concatData( $pData, &$src, &$parms ) {
		if( !empty( $pData ) ) {
			if( array_key_exists( "tabrow", $parms ) ) {
				// make sure we concat and not overwrite
				if( empty( $parms["tabdata"][$parms["tabrow"]][$parms["tabcol"]] ) ) {
					$parms["tabdata"][$parms["tabrow"]][$parms["tabcol"]] = $pData;
				} else {
					$parms["tabdata"][$parms["tabrow"]][$parms["tabcol"]] .= $pData;
				}
			} else {
				$src.=$pData;
			}
		}
	}
	
	function WalkParsedArray(&$c,&$src,&$parms) { // stolen from common.inc of the htmlparser lib
		$parms["descend"]=isset($parms["descend"])?$parms["descend"]:true;
		$parms["listlevel"]=isset($parms["listlevel"])?$parms["listlevel"]:0;
		$parms["orderedlist"]=isset($parms["orderedlist"])?$parms["orderedlist"]:array();
		//$descend=true; //recusively descend the array
		//$listlevel=0; //level for lists
		if (!is_array($c)) return;
		for ($i=0;$i<=$c["contentpos"];$i++) { // loop though elements
				switch ($c[$i]["type"]) { // switch type of element (text, tag, ...)
				case "comment":
				case "text":
					$this->concatData( trim( $c[$i]["data"] ), $src, $parms );
					break;
				case "tag":
//vd( $c[$i]["data"]["type"].' - '.$c[$i]["data"]["name"]  );
					switch($c[$i]["data"]["type"]) { // switch open or close a tag
					  case "open":
						switch($c[$i]["data"]["name"]) { //switch tagname
case "br":
	$this->concatData( "\n", $src, $parms );
	break;
case "hr":
	$this->concatData( "<C:hr:>\n", $src, $parms );
	break;
case "h1":
	$this->concatData( "<C:h1:", $src, $parms );
	break;
case "h2":
	$this->concatData( "<C:h2:", $src, $parms );
	break;
case "h3":
	$this->concatData( "<C:h3:", $src, $parms );
	break;
case "img":
	$this->concatData( "<C:img:".$c[$i]["pars"]["src"]["value"].">", $src, $parms );
	break;
case "b":
	$this->concatData( "<b>", $src, $parms );
	break;
case "i":
	$this->concatData( "<i>", $src, $parms );
	break;
case "ul":
	$parms["listlevel"]=$parms["listlevel"]+1;
	break;
case "ol":
	$parms["listlevel"]=$parms["listlevel"]+1;
	$parms["orderedlist"][$parms["listlevel"]]=0;
	break;
case "li":
	if (array_key_exists($parms["listlevel"],$parms["orderedlist"])) {
		$parms["orderedlist"][$parms["listlevel"]]=$parms["orderedlist"][$parms["listlevel"]]+1;
		for ($j=1; $j<count($parms["orderedlist"])+1;$j++) {
			$this->concatData( $parms["orderedlist"][$j].".", $src, $parms );
		}
	} else {
	  $this->concatData( str_repeat(" ",$parms["listlevel"])."* ", $src, $parms );
	}
	break;
case "div":
	$keys=array_keys($c[$i]["pars"]);
	if (array_key_exists("class",$c[$i]["pars"])) {
	  $classval=$c[$i]["pars"]["class"]["value"];
	  switch($classval) {
				case "titlebar":
		  $this->concatData( "<C:titlebar:", $src, $parms );
		  break;
		case "underline":
		  $this->concatData( "<c:uline>", $src, $parms );
		  $parms["closetag"]="</c:uline>";
		  break;
	  } // end switch classname
	} // end if
	break;
case "span":
	$keys=array_keys($c[$i]["pars"]);
	if (array_key_exists("style",$c[$i]["pars"])) {
	  $styleval=$c[$i]["pars"]["style"]["value"];
	  switch($styleval) {
		case "text-decoration:underline;":
		$this->concatData( "<c:uline>", $src, $parms );
		$parms["closetag"]='</c:uline>';
		break;
	  } // end switch styleval
	} // end if
	break;
case "a":
	$hrefsrc=$c[$i]["pars"]["href"]["value"];
	$hreftext=$c[$i]["content"]["0"]["data"]; //always ["0"] ?
	if( is_array( $hreftext ) ) {
		$this->WalkParsedArray( $c[$i]["content"], $src, $parms );
	} else {
		$this->concatData( $this->whatlink($hrefsrc,$hreftext), $src, $parms );
	}
	$parms["descend"]=false;
	break;
//tables
case "table":
	$parms["tabdata"]=array();
	$parms["tabrow"]=-1;
	$parms["tabcol"]=-1;
	$parms["tabmaxcol"]=1;
	break;
case "tr":
	$parms["tabrow"]++;
	$parms["tabdata"][$parms["tabrow"]]=array();
	break;
case "td":
	$parms["tabcol"]++;
// next case in tags
} // end switch tagname
break;
				  case "close":
					switch($c[$i]["data"]["name"]) { // switch tagname on close
case "br":
case "img":
case "hr":
case "a":
	break;
case "b":
	$this->concatData( "</b>", $src, $parms );
	break;
case "i":
	$this->concatData( "</i>", $src, $parms );
	break;
case "ul":
	$parms["listlevel"]=$parms["listlevel"]-1;
	break;
case "ol":
	unset($parms["orderedlist"][$parms["listlevel"]]);
	$parms["listlevel"]=$parms["listlevel"]-1;
	break;
case "li":
	$this->concatData( "\n", $src, $parms );
	break;
case "div":
case "span":
	if(array_key_exists("closetag",$parms)) {
		$this->concatData( $parms["closetag"], $src, $parms );
		unset($parms["closetag"]);
	} else {
//		$this->concatData( ">\n", $src, $parms );
	}
	break;
//tables:
case "table":
	$this->flush($src);
	$src="";
	// fill array
	for( $j=0; $j < count($parms["tabdata"]); $j++ ) {
		for( $k=0; $k < $parms["tabmaxcol"] + 1; $k++ ) {
			if( !isset( $parms["tabdata"][$j][$k] ) ) {
				$parms["tabdata"][$j][$k]="";
			}
		}
	}
	// add table
	$this->ezTable($parms["tabdata"],null,null,array("showHeadings" => 0, "width" => (int)($this->ez['pageWidth'] * .85) ));
	unset ($parms["tabdata"]);
	unset ($parms["tabrow"]);
	unset ($parms["tabcol"]);
	break;
case "tr":
	if( $parms["tabmaxcol"]<$parms["tabcol"] ) {
		$parms["tabmaxcol"]=$parms["tabcol"];
	}
	$parms["tabcol"]=-1;
	break;
case "td":
	break;
case "h1":
case "h2":
case "h3":
	$this->concatData( ">\n", $src, $parms );
	break;
default:
	$this->concatData( ">", $src, $parms );
	break;
} // end switch tagname on close
						break;
					} // end switch tag open or close
					break;
			} // switch type of element (text, tag, ...)
			if ($parms["descend"]) {
				if (isset($c[$i]["content"])) {
//print "DESCENDING: ".$c[$i]['data']['name']."<br />";
					$this->WalkParsedArray($c[$i]["content"],$src,$parms); // recursion
				}
			} else {
			  $parms["descend"]=true; //reset mode
			}
		} // end loop though elements
		return ($parms);
	}
	
	
	function completeLink($link)
	{
		if (strpos($link,"http") === 0) {
			return($link);
		} else {
			return BIT_ROOT_PATH.$link;
		}
	}
	
	function whatlink($link,$text)
	{
		//$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"whatlink: link: $link text: $text\n");fclose($fpd);
		// for building non-relative links
		$https_mode = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
		$port=":".$_SERVER['SERVER_PORT'];
		$http_path=preg_replace("#".PDF_PKG_URL."export_pdf.php#","",$_SERVER["SCRIPT_NAME"]);
		
		if ($https_mode) {
			$site_http_prefix="https://" ; 
		} else {
			$site_http_prefix="http://";
			if ($port == ":80") {
				$port="";
			}
		}
		
		
		//wiki link?
		if (strpos($link, (WIKI_PKG_URL."index.php") ) === 0) {
			//internal link?
			$linkpage=preg_replace("#tiki-index.php\?page=#","",$link);
			if (array_search($linkpage,$this->linkdest)!== FALSE) {
			  //$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"<c:ilink:$linkpage>$text</c:ilink>\n");fclose($fpd);
			  return("<c:ilink:$linkpage>$text</c:ilink>");
			} else {
	//$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"<c:alink:".$site_http_prefix.$_SERVER["SERVER_NAME"].$port.$http_path.$link.">$text</c:alink>\n");fclose($fpd);
			  return("<c:alink:".$site_http_prefix.$_SERVER["SERVER_NAME"].$port.$http_path.$link.">$text</c:alink>");
			}
		}
		
		if (strpos($link,"http") === FALSE)	{
			$link=$site_http_prefix.$_SERVER["SERVER_NAME"].$port.$http_path.$link;
		}
		//$fpd=fopen("/tmp/tikidebug",'a');fwrite($fpd,"<c:alink:$link>$text</c:alink>\n");fclose($fpd);
		return ("<c:alink:$link>$text</c:alink>");
	}
	
	function insert_linkdestinations($convertpages)
	{
	  $this->linkdest=$convertpages;
	}
	
	function add_linkdestination($ld)
	{
	  $this->linkdest[]=$ld;
	}
	
	function page($info)
	{
	  $this->currentpage=$info["p"];
	  $this->addDestination($info["p"],"Fit");
	  $this->ezText($info["p"],$this->mSettings['h1height']);
	}
	
	function hr($info)
	{
	  $this->line($this->ez['leftMargin'], $this->y,
			  $this->ez['pageWidth']-$this->ez['rightMargin'],$this->y);
	}
	
	
	function img($info)
	{
		$info["p"]=$this->completeLink($info["p"]);
		$info["p"]=str_replace("&amp;","&",$info["p"]);
		//hope GD is installed properly and the image is a jpg!
		$ext = substr( $info["p"], strrpos( $info["p"], '.' ) );
		if( preg_match( '/\.jp[e]?g$/i', $info["p"] ) ) {
			$data=imagecreatefromjpeg( $info["p"] );
		} elseif( preg_match( '/\.gif$/i', $info["p"] ) ) {
			$data=imagecreatefromgif( $info["p"] );
		} elseif( preg_match( '/\.png$/i', $info["p"] ) ) {
			$data=imagecreatefrompng( $info["p"] );
		} elseif( preg_match( '/bmp$/i', $info["p"] ) ) {
			$data=imagecreatefromwbmp( $info["p"] );
		} else {
			$fp = fopen( $info["p"], "rb" );
			$data = fread($fp, 1000000);
			fclose($fp);
			$data=imagecreatefromstring($data);
		}
		
		if( !empty( $data ) ) {
			$x=round(imagesx($data)*$this->mSettings['imagescale']);
			$y=round(imagesy($data)*$this->mSettings['imagescale']);
			//add some space for the image
			$this->ezSetDy(-$y,'makeSpace');
			//insert image
//vd( $info );
//print "this->addImage(,$info[x],$info[y]-$y,$x,$y)";
			$this->addImage($data,$info["x"],$info["y"]-$y,$x,$y);
		} else {
			$this->ezText($info["p"]." could not be uploaded.\n",$this->mSettings['textheight']);
			return;
		}
	}
	
	function h1($info)
	{
	  $this->ezText($info["p"],$this->mSettings['h1height']);
	}
	
	function h2($info)
	{
	  $this->ezText($info["p"],$this->mSettings['h2height']);
	}
	
	function h3($info)
	{
	  $this->ezText($info["p"],$this->mSettings['h3height']);
	}
	
	//Callback functions. See ezpdf manual
	
	function titlebar($info)
	{
		$this->transaction('start');
		$ok=0;
		while (!$ok){
			// not working and not yet useful:
			//$this->addDestination($this->currentpage."-".$info["p"],"left");
			$thisPageNum = $this->ezPageCount;
			$this->saveState();
			$this->setColor(0.9,0.9,0.9);
			$this->filledRectangle($this->ez['leftMargin'],
			$this->y-$this->getFontHeight($this->mSettings['tbheight'])+
			$this->getFontDecender($this->mSettings['tbheight']),
			$this->ez['pageWidth']-$this->ez['leftMargin']-
			$this->ez['rightMargin'],
			$this->getFontHeight($this->mSettings['tbheight']));
			$this->restoreState();
			$this->ezText($info["p"],$this->mSettings['tbheight'],
			array('justification'=>'center'));
			if ($this->ezPageCount==$thisPageNum){
				$this->transaction('commit');
				$ok=1;
			} else {
				// then we have moved onto a new page, bad bad, as the background colour will be on the old one
				$this->transaction('rewind');
				$this->ezNewPage();
			}
		}
	}


}


?>

<?php
class CBasePlugin 
{
	const JAVASCRIPT_DOCUMENT_READY	= 'js_doc_ready';
	const PARAM_DISABLE_COMMENTS	= 'disable_comments';
	
    protected static $buffer;
    /**
	* @var Smarty
     */
    public $smarty;
	protected $cfg;
	/**
	 * @var Chrono
	 */
	protected $clock;
	protected $tpl_name			= '';
	protected $tpl_ext			= 'html';
    protected $cancel_output	= false;
    protected $time_msgs;
    protected $time_last;
	protected $params;
	protected $pluginTimeTable = array();
	protected $post_request;
	
	/**
	 * disables | enables init for module language 
	 * @var bool 
	 */
	protected $initLangModule		= true;
	
	/**
	 * @var array
	 */
	//protected $langModule			= null;
	/**
	 * @var array
	 */
	//protected $flatLangModule		= null;
	
	/**
	 * @var array
	 */
	protected static $langGlobal	= null;
	
	 /**
     * constructor
     * Tworzy zmienne dla smartiego $lang,$images
     * przypisuje do $this->cfg referencje do globalnego configa
     * tworzy obiekt $this->smarty
     *
     */
    public function __construct() 
	{
		global $cfg_main, $language, $aq;
		        
        $this->post_request	= strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post';
        $this->cfg			= &$cfg_main;

        $this->smarty				= new Smarty();
        $this->smarty->template_dir = 'app' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $this->smarty->compile_dir  = 'data' . DIRECTORY_SEPARATOR . 'templates_c' . DIRECTORY_SEPARATOR ;
        $this->smarty->use_sub_dirs	= true;
    	
		if(!isset($GLOBALS['cfg_main']['http_host']) || empty($GLOBALS['cfg_main']['http_host'])) {
			$GLOBALS['cfg_main']['http_host'] = $_SERVER['HTTP_HOST'];
		}
		if(!isset($GLOBALS['cfg_main']['base_href']) || empty($GLOBALS['cfg_main']['base_href'])) {
			$GLOBALS['cfg_main']['base_href'] = $GLOBALS['cfg_main']['http_host'];
		}		
		//common assignments
		$this->smarty->assign_by_ref( 'lang', $this->getLangModule() );
		//$this->smarty->assignByRef( 'lang_global', $this->getLangGlobal() );
		$this->smarty->assign_by_ref( 'aq', $aq );
		$this->smarty->register_function( 'lang' , array( $this , 'smarty_lang' ) , false );
		
		$this->smarty->assign( array (
			'images'			=> '/pics',
			'http_host'			=> $GLOBALS['cfg_main']['http_host'],
			'base_href'			=> $GLOBALS['cfg_main']['base_href'],
			'view_name'			=> $GLOBALS['aq']['view'],
			//'google_map_key'	=> Config::get( 'gmap_key' ),
		) );
		
		$this->smarty->assign_by_ref('__plugin', $this);		
    }

    /**
     * ustawienie globalnej zmiennej, widocznej w każdym plugnie
     * nalezy uważac na kolejność wywołyania pluginów ( addmodule w klasach widoków )
     * 
     * @param string $name
     * @param mixed $value
     */
	function setBufferVar( $name, $value ) {
		self::$buffer[ $name ] = $value;
	}

    /**
     * pobranie globalnej zmiennej, widocznej w każdym plugnie
     * nalezy uważac na kolejność wywołyania pluginów ( addmodule w klasach widoków )
     * 
     * @param unknown_type $name
     * @return unknown
     */
    function getBufferVar( $name ) {
		return self::$buffer[ $name ];
	}
	
	function clearBufferVar( $name ) {
		unset( self::$buffer[ $name ] );
	}
	
    /**
     * private
     * funkcja pokazuje czas jaki uplynal od utworzenia obiektu
     *
     * @param string $txt
     */
    function showTime( $txt = "" ) {
        global $cfg_main;
        if( $cfg_main['show_plugin_times'] ) {
            $current			= $this->clock->lap();
            $time_diff			= $current - $this->time_last;
            $this->time_last	= $current;

            // wersja wyswietlajaca czasy na ekran
            $this->time_msgs .= " &nbsp; &nbsp; &nbsp; &nbsp; place:{$txt}</b>, time: <b>{$current}</b> sec., diff: {$time_diff}<br>";

            // wersja ladujaca czasy do tablicy i na koncu sa wszystkie wyswietlane
			$_tmp_time['name']	= $txt;
			$_tmp_time['time']	= $time_diff;
			$this->pluginTimeTable[] = $_tmp_time;
		}
	}


    /**
     * public
     * Zwraca tresc html wygenerowana przez plugin
     * @param string $frame_tpl_name
     * @return string
     */
    function OutputPage( $frame_tpl_name = "" ) {
		global $cfg_main;
		$c = get_class( $this );
		
        //uruchomienie szablonu w smarty i zwr�cenie html'a poprzez return
        $this->DoActions();
		
		if( $c != 'CBasePlugin' && !file_exists($this->smarty->template_dir.$this->GetTemplateName())) {
			return false;
		}
		if( $this->cfg['show_plugin_comments'] && !$this->params[self::PARAM_DISABLE_COMMENTS] ) {
			$tpl_begin	= "\n\n<!-- BEGIN " . strtoupper( $c ) . " -->\n\n";
			$tpl_end	= "\n\n<!-- END " . strtoupper( $c ) . " -->\n\n";
			}
        if( $cfg_main['show_plugin_times'] ) {
            global $timeTable;
            // info dla kazdego plugina
            $tmp = array (
            	'name'	=> $c,
            	'time'	=> $this->clock->stop(),
            );
            if( is_array( $this->pluginTimeTable ) ) {
            	$tmp['sub_time'] = $this->pluginTimeTable;
            }
			$timeTable[] = $tmp;
        }
		if( $this->cancel_output ){
			return $tpl_begin . "\n" . $tpl_end;
		}
		if( $cfg_main['strip_output'] ) {
			$this->smarty->register_outputfilter( "smarty_filter_strip" );
		}
		if(!file_exists( $frame_tpl_name ) )  {
			$frame_tpl_name = "";
		}
		if( $frame_tpl_name && $this->tpl_ext != "xml" ) {
			$frame_tpl = array(
				'title'				=> $this->Lang_GetText( 'frame_title' ),
				'content_tpl_file'	=> $this->GetTemplateName(),
			);
			$tpl_name = $frame_tpl_name . "." . $this->tpl_ext;
			$this->smarty->assign("frame_tpl",$frame_tpl);
		} else {
			$tpl_name = $this->GetTemplateName();
        }
        //render the template
		if( file_exists( $this->smarty->template_dir . $tpl_name ) ) {
			$out = $tpl_begin . $this->smarty->fetch( $tpl_name ) . $tpl_end;
		} else {
			$this->logError( 'CBasePlugin', $this->smarty->templates_dir . $tpl_name );
        }
        return $out;
    }
    /**
     * public
     * anuluje wyj�cie z plugina, po wywo�aniu tej funkcji plugin wykona wszystkie akcje ale nie wypisze nic na ekran
     */
    function CancelOutput() {
		$this->cancel_output = true;
    }
    
    /**
     * private
     * zwraca nazwe pliku szablonu dla aktualnego pluginu
     *
     * @return unknown
     */
	public function GetTemplateName() {
        $tplf = $this->tpl_name != '' ? $this->tpl_name : get_class( $this );		
        return strtolower( $tplf ) . "." . $this->tpl_ext;
    }

    /**
     * public
     * zmienia domy�lny szablon na inny
     * nazwa pliku musi sie zaczynac od nazwy pluginu przedrostek ,
     * np:
     *   jezeli zmieniasz szablon dla pluginu plg_center_xyz to
     *   nazwa nowego szablonu musi sie zaczynac od plg_center_xyz
     *      np plg_center_xyz_nowytemplate
     *
     * @param string $tpl
     * nazwa szablonu
     * w nazwie szablonu nie podajmy sciezki ani rozszerzenia, sama nazwa
     */
    function SetTemplate( $tpl, $free_tplname = false ) {
        $curr=get_class($this);
        {$this->tpl_name=$tpl;}

		if($free_tplname==false)
		{
			if((strstr($tpl,$curr)!==false))
			{
				$this->tpl_name=$tpl;
			}
			else
			{
			   $this->LogError("template","bad tpl name in SetTemplate(), so let see the manual {$tpl} , {$curr}");
			}
        }else
        {
				$this->tpl_name=$tpl;				
        }
    }

    /**
     * public
     * funkcja do wyswietlania i logowania bledow
     *
     * @param string $type
     * typ bledu, kazdy typ bledu jest zapisywany w innym pliku
     * @param mixed $txt
     * tekst bledu
     *
     */
    function LogError($type,$txt)
    {
        $t=date("Y-m-d_H:i:s ")."class: ".get_class($this).",  ".$txt;

        if($this->cfg["logs_show"])
        {
            echo  "<br><b>Blad: </b>".$t."<br>\n";
        }
        $t.="\n";
        $t.="HTTP_REFERER    : ".$_SERVER['HTTP_REFERER']."\n";
        $t.="SERVER_NAME     : ".$_SERVER['SERVER_NAME']."\n";
        $t.="REQUEST_URI     : ".$_SERVER['REQUEST_URI']."\n";
        $t.="HTTP_USER_AGENT : ".$_SERVER['HTTP_USER_AGENT']."\n";
        $t.="\n";
        $t.=debugGetBackTrace();
        $t.="\n";

        if(!$this->cfg["logs_dir"]) {return ;}
        $fn=$this->cfg["logs_dir"]."/".date("Y-m-d_")."log_".$type;
        $t.="---------------------------------------------------------\n";
        if(!$this->cfg['write_logs']) {return ;}
        error_log($t."\n", 3,$fn );
    }



    //
    /**
     * public
     * funkcja do wyswietlania zmiennych
     *
     * @param unknown_type $text
     * nazwa lub opis zmiennej
     * @param unknown_type $variable
     * zmienna do wyswietlenia
     */
    function ShowVar($text,$variable)
    {
        if(!$this->cfg[variables_show]) {return;}

        echo "<div style='text-align:left;' align='left'><br><b>"."class: ".get_class($this).", var: $text:</b> <pre>";
        print_r($variable);
        //var_export($variable);
        echo '</pre><br></div>'."\n";
//        debugGetBackTrace();
    }




    //OVERLOAD IT!
    //
    /**
     *
     * funkcja ktora wykonuje w pluginie wszystkie czynnosci zwiazane z przygotowaniem htmla
     * np pobranie danych i przypisanie ich do zmiennych smartyego
     *
     */
    function DoActions()
    {

    }
    /**
     * funkcja zarejestrowana w smarty jako  {lang ...}
     *
     * @param mixed $params
     * @return mixed
     */
    function smarty_lang($params){
    	
    	if($params['module']){
			$module =	$params['module'];	
    	}else{
    		$module = get_class($this);
    	}
    	$lables	= Language::getLangForModule($module, true);
    	//show($lables);
    	if($params['key']){
    		$key = $params['key'];
	    	$ret = $lables[$module][$key];
	    	return $ret?$ret:Language::inlineTranslationPrepareLabel($module, $key);
    	}else{
    		return $lables[$module];
    	}
    }
     
    /**
     * public
     * zwraca z pliku jezyka tekst dla podanego identyfikatora
     *
     * @param unknown_type $label
     * @return unknown
     */
    function Lang_GetText($label)
    {
		$module = $this->getLangModule();
		if( is_array( $module ) ){
			return $module[ $label ];
		}
		return null;
    }

    /**
     * public
     * zwraca z pliku jezyka tekst dla podanego identyfikatora oraz plugina
     *
     * @param unknown_type $label
     * @return unknown
     */
    function Lang_GetGlobalText($plugin, $label)
    {

		$module = &Language::getLangForModule( $plugin );
        if( is_array( $module ) ){	
        	$ret = $module[ $plugin ][ $label ];
        	return $ret?$ret:$label;
        }
        return null;
    }
    
    /**
     * gets translations for global module
     * @return mixed
     */
    public function getLangGlobal(){
    	$result = &Language::getLangForModule( 'global' );
    	return $result['global'];
    }
    
    /**
     * gets translations for this module
     * @return array
     */
	public function getLangModule(){
    	$class = get_class( $this );
    	
    	// skip CBasePlugin and views like 'homepage_view', 'search_view'
    	if( !$this->initLangModule || 'CBasePlugin' == $class || preg_match( '/[^_]+(_view)$/', $class ) ){
			$result = array();
	    	return $result;
		}else{
			$result = false; /*&Language::getLangForModule( $class );*/
			return $result[$class];
		}
    }

    /**
     * Przekazuje parametry do pluginu
     * mo�na je potem odczyta� wewnatrz pluginu za pomoca zmiennej $this->paramsa
     *
     * $plg_center_tipps=new plg_center_tipps();
     * $plg_center_tipps->SetParams(array("param1"=>"costam"));
     * $this->smarty->assign("center_tipps", $plg_center_tipps->OutputPage());
     *
     * @param mixed $params
     */
    function SetParams($params)
    {
        $this->params=$params;
    }

    function GetParams()
    {
        return $this->params;
    }

    function run_plugin($plgname,$params=NULL,$plugins=array())
    {
        $plugin=new $plgname();
        $plugin->SetParams($params);
        $plugin->SetPluginTable($plugins);
        $this->smarty->assign($plgname  , $plugin->OutputPage());
    }

    function ReturnTimeTable()
    {
     global $timeTable;
     return $timeTable;   
    }
    
	function readfile( $file ) {
		return file_exists( $file ) && is_readable( $file ) ? @file_get_contents( $file ) : '';
	}

}

?>
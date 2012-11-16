<?php

class CView extends CBasePlugin 
{
    public $modules				= array();
	public $output_buffer 		= '';
    protected $CacheLite		= null;
	static $cache_key_params	= null;
    
	public function __construct() 
	{
	   parent::__construct();
        $cname = get_class( $this );
        $this->SetParams( CBasePlugin::PARAM_DISABLE_COMMENTS );
		
		$this->Actions();
		$views_as_questions = array (
			'questions_search_view',
			'my_questions_view',
			'questions_search_results_view',
		);
		if( in_array( $cname, $views_as_questions ) ) {
			$cname = 'questions_view';          
		}
		//$views2tabs = $this->Lang_GetGlobalText( 'global', 'views2tabs' );
        $this->smarty->assign( array (
        	'view_name'			=> $cname,
        	//'active_top_tab'	=> $views2tabs[ $cname ],
        ) );
	}

	function AddModule( $name, $frame_tpl = "", $params = array() ) {
		$this->modules []= new CViewModule( $name, $frame_tpl, $params );	    
	}

	function SetTemplateName( $tplname ) {
		$this->SetTemplate( $tplname, true );
    }

    function tplAssign( $var, $value ) {
    	$this->smarty->assign( $var, $value );
    }

	function RunModules() {
		$output_buffer = array ();
	    foreach ( $this->modules as $module ) {
			$plg	= new $module->name;			
            $plg->SetParams( $module->params );
            $out	= $plg->OutputPage( $module->frame_tpl );
            if( $out ) {
            	$output_buffer[ $module->name ] = $out;
            }
		}

		if( is_array( self::$buffer[ CBasePlugin::JAVASCRIPT_DOCUMENT_READY ] ) ) {
			$doc_ready = new plg_document_ready();
			$output_buffer[ 'plg_document_ready' ] = $doc_ready->OutputPage();
		}
		
		$this->smarty->assign( $output_buffer );
        return $this->OutputPage();
	}

	function GenerateSite() {
	
		$out = $this->RunModules();
	
		return $out;
	}
	
	function CacheCreateKey( $place = null ) {
		$params = array (
			'cache_key_params'	=>	self::$cache_key_params,
			'class_name'		=>	get_class( $this ),
			'place'				=>	$place,
		);
		return md5( serialize( $params ) );
	}

	function PutToCache( $data ) {
		
		return Cache_View::save( $this, $data );
	}
		
	function GetFromCache() {
		return Cache_View::get( $this );
	}

}
?>
<?php
class plg_meta_tags extends CBasePlugin {

    	public function DoActions() 
		{
			global $aq;
			
			//jesli jesteśmy na głównej stronie to takie mamy ustawiena dla facebooka
			$facebook_title = 'gl05';
			$facebook_image = 'pics/thumbnails/gl05.jpg';
			
			if( !empty( $aq[ 'params' ][ 'subpage' ] ) )
			{
				$subpage = $aq[ 'params' ][ 'subpage' ];	
				
				switch( $subpage )
				{
				
					case 'cer06':
						$facebook_url =   'cer06';
						$facebook_title = 'Cersanitech 2005';
						$facebook_image = 'pics/thumbnails/cer06.jpg';
					break;
				
					case 'marten':
						$facebook_url =   'marten';
						$facebook_title = 'Marten Biurowiec';
						$facebook_image = 'pics/thumbnails/marten.jpg';
					break;
					
					case 'liab':
						$facebook_url =   'liab';
						$facebook_title = 'Living in a Box';
						$facebook_image = 'pics/thumbnails/liab.jpg';
					break;
					
					case 'lagi':
						$facebook_url =   'lagi';
						$facebook_title = 'Land Art Generator';
						$facebook_image = 'pics/thumbnails/lagi.jpg';
					break;

					case 'dnw':
						$facebook_url =   'dnw';
						$facebook_title = 'Dom na Wodzie';
						$facebook_image = 'pics/thumbnails/dnw.jpg';
					break;

					case 'vydrica':
						$facebook_url =   'vydrica';
						$facebook_title = 'Vydrica';
						$facebook_image = 'pics/thumbnails/vydrica.jpg';
					break;				

					case 'nvc':
						$facebook_url =   'nvc';
						$facebook_title = 'Newark Visitors Center';
						$facebook_image = 'pics/thumbnails/nvc.jpg';
					break;	

					case 'ltb':
						$facebook_url =   'ltb';
						$facebook_title = 'Live the Box';
						$facebook_image = 'pics/thumbnails/ltb.jpg';
					break;	

					case 'europan09':
						$facebook_url =   'europan09';
						$facebook_title = 'Europan 2009';
						$facebook_image = 'pics/thumbnails/europan09.jpg';
					break;
					
					case 'szuflada':
						$facebook_url =   'szuflada';
						$facebook_title = 'Klub Muzyczny \'Szuflada\'';
						$facebook_image = 'pics/thumbnails/szuflada.jpg';
					break;									
					
					case 'cer07':
						$facebook_url =   'cer07';
						$facebook_title = 'Cersanitech 2007';
						$facebook_image = 'pics/thumbnails/cer07.jpg';
					break;	

					case 'jobpl':
						$facebook_url =   'jobpl';
						$facebook_title = 'Job Polska Pulpit';
						$facebook_image = 'pics/thumbnails/jobpl.jpg';
					break;	

					case 'korty':
						$facebook_url =   'korty';
						$facebook_title = 'Zespół Kortów Tenisowych';
						$facebook_image = 'pics/thumbnails/korty.jpg';
					break;

					case 'domsl':
						$facebook_url =   'domsl';
						$facebook_title = 'Dom Gościnny w Smołdzińskim Lesie';
						$facebook_image = 'pics/thumbnails/szuflada.jpg';
					break;				

					case 'europan11':
						$facebook_url =   'europan11';
						$facebook_title = 'Europan 2011';
						$facebook_image = 'pics/thumbnails/europan11.jpg';
					break;	

					case 'anonymousa':
						$facebook_url =   'anonymousa';
						$facebook_title = 'Carpet Assembly';
						$facebook_image = 'pics/thumbnails/anonymousa.jpg';
					break;	

					case 'dj06':
						$facebook_url =   'dj06';
						$facebook_title = 'dj06';
						$facebook_image = 'pics/thumbnails/dj06.jpg';
					break;	
	
					case 'dj07':
						$facebook_url =   'dj07';
						$facebook_title = 'dj07';
						$facebook_image = 'pics/thumbnails/dj07.jpg';
					break;								

					case 'dj08':
						$facebook_url =   'dj08';
						$facebook_title = 'dj08';
						$facebook_image = 'pics/thumbnails/dj08.jpg';
					break;	

					case 'dj09':
						$facebook_url =   'dj09';
						$facebook_title = 'dj09';
						$facebook_image = 'pics/thumbnails/dj09.jpg';
					break;	
										
					
					
				}
			}
			
			$this->smarty->assign( array( 'facebook_title' => $facebook_title,
                                          'facebook_image' => $facebook_image	) );
			
 		}
    
}   
?>

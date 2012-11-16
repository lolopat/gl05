<?php

	class Menu
	{
		public static $menu = array(
				'level1' =>
					array
					(
						array(
							'white_black_picture' 	=> null,
							'color_picture'			=> null,
							'load_content'			=> false,
							'item'					=> null
						),
						array(
							'white_black_picture' 	=> 'menuGL05g.png',
							'color_picture'			=> 'menuGL05g100.png',
							'load_content'			=> true,
							'item'					=> 'gl05'
						),
						array(
							'white_black_picture' 	=> 'menuNEWSg.png',
							'color_picture'			=> 'menuNEWSg100.png',
							'load_content'			=> true,
							'item'					=> 'news'
						),
						array(
							'white_black_picture' 	=> 'menuCLIENTSg.png',
							'color_picture'			=> 'menuCLIENTSg100.png',
							'load_content'			=> true,
							'item'					=> 'clients'
						),
					),
				'level2' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuCER06g.png',
							'color_picture'			=> 'menuCER06g.png',
							'load_content'			=> true,
							'item'					=> 'cer06'
						),
						array(
							'white_black_picture' 	=> 'menuMARTENg.png',
							'color_picture'			=> 'menuMARTEN.jpg',
							'load_content'			=> true,
							'item'					=> 'marten'
						),
						array(
							'white_black_picture' 	=> 'menuLIABg.png',
							'color_picture'			=> 'menuLIAB.jpg',
							'load_content'			=> true,
							'item'					=> 'liab'
						),
						array(
							'white_black_picture' 	=> 'menuLAGIg.png',
							'color_picture'			=> 'menuLAGI.jpg',
							'load_content'			=> true,
							'item'					=> 'lagi'
						),
					),
				'level3' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuDNWg.png',
							'color_picture'			=> 'menuDNW.jpg',
							'load_content'			=> true,
							'item'					=> 'dnw'
						),
						array(
							'white_black_picture' 	=> 'menuVYDRICAg.png',
							'color_picture'			=> 'menuVYDRICA.jpg',
							'load_content'			=> true,
							'item'					=> 'vydrica'
						),
						array(
							'white_black_picture' 	=> 'menuNVCg.png',
							'color_picture'			=> 'menuNVC.jpg',
							'load_content'			=> true,
							'item'					=> 'nvc'
						),
						array(
							'white_black_picture' 	=> 'menuLTBg.png',
							'color_picture'			=> 'menuLTB.jpg',
							'load_content'			=> true,
							'item'					=> 'ltb'
						),
					),	
				'level4' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuEUROPAN09g.png',
							'color_picture'			=> 'menuEUROPAN09.jpg',
							'load_content'			=> true,
							'item'					=> 'europan09'
						),
						array(
							'white_black_picture' 	=> 'menuSZUFLADAg.png',
							'color_picture'			=> 'menuSZUFLADA.jpg',
							'load_content'			=> true,
							'item'					=> 'szuflada'
						),
						array(
							'white_black_picture' 	=> 'menuCER07g.png',
							'color_picture'			=> 'menuCER07.jpg',
							'load_content'			=> true,
							'item'					=> 'cer07'
						),
						array(
							'white_black_picture' 	=> 'menuJOBPLg.png',
							'color_picture'			=> 'menuJOBPL.jpg',
							'load_content'			=> true,
							'item'					=> 'jobpl'
						),
					),
				'level5' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuKORTYg.png',
							'color_picture'			=> 'menuKORTY.jpg',
							'load_content'			=> true,
							'item'					=> 'korty'
						),
						array(
							'white_black_picture' 	=> 'menuDOMSLg.png',
							'color_picture'			=> 'menuDOMSL.jpg',
							'load_content'			=> true,
							'item'					=> 'domsl'
						),
						array(
							'white_black_picture' 	=> 'menuEUROPAN11g.png',
							'color_picture'			=> 'menuEUROPAN11.jpg',
							'load_content'			=> true,
							'item'					=> 'europan11'
						),
						array(
							'white_black_picture' 	=> 'menuANONYMOUSAg.png',
							'color_picture'			=> 'menuANONYMOUSA.jpg',
							'load_content'			=> true,
							'item'					=> 'anonymousa'
						),
					),
				'level6' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuDJ06g.png',
							'color_picture'			=> 'menuDJ06.jpg',
							'load_content'			=> true,
							'item'					=> 'dj06'
						),
						array(
							'white_black_picture' 	=> 'menuDJ07g.png',
							'color_picture'			=> 'menuDJ07.jpg',
							'load_content'			=> true,
							'item'					=> 'dj07'
						),
						array(
							'white_black_picture' 	=> 'menuDJ08g.png',
							'color_picture'			=> 'menuDJ08.jpg',
							'load_content'			=> true,
							'item'					=> 'dj08'
						),
						array(
							'white_black_picture' 	=> 'menuDJ09g.png',
							'color_picture'			=> 'menuDJ09.jpg',
							'load_content'			=> true,
							'item'					=> 'dj09'
						),
					),
			);
		
		public function DoActions()
		{
		}
		
		public static function getMenu()
		{
			return self::$menu;
		}
	}
	
?>
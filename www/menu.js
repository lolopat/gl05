Menu = {
		
			CLASS_IMAGE_MENU_ELEMENT	: 'img.image-menu-element',
			CLASS_CLOSE_CONTENT_ELEMENT	: '.close-content-element',
			CLASS_CONTENT_ELEMENT		: 'div.content-element',
			ID_CONTENT_ELEMENT_LEVEL	: 'content-element-',
			ID_CONTENT					: false,
			
			//class to mouseover and mouseout
			CLASS_BACKGROUND_GREY		: '.cell-element',
			
			CLICKED_ELEMENT_ID			: false,
			
			AJAX_POST_URL				: 'app/ajax/gate.php',  
      
      OPEN_ELEMENT        : null,                      
			
			
			init						: function()
			{
        //bind click event
				$( Menu.CLASS_BACKGROUND_GREY ).click( Menu.eventShowHideContent );
                    
        //get hash param            
        //var hash_param = window.location.hash.substring(1);
		
			
        
        //if hash param exists. for example #marten the we call function eventShowHideContent
        //then we open content of marten
        //if( hash_param )
		if( subpage )
        {
          Menu.OPEN_ELEMENT = $( 'img[name=' + subpage + ']' ).parent();  //$( 'img[name=' + hash_param + ']' ).parent() ? $( 'img[name=' + hash_param + ']' ).parent() : false;
        
          if( Menu.OPEN_ELEMENT.length )
          {
            Menu.eventShowHideContent();          
          }
        }
        	
			},
			
			//show content after layer slides Up
			eventShowHideContent	:function( param )
			{ 
        if( Menu.OPEN_ELEMENT )
        {
          var _this = $( Menu.OPEN_ELEMENT );
          Menu.OPEN_ELEMENT = false;
        }
        else
        {
          var _this = $( this );
        }   
                
        if( $( Menu.CLASS_CONTENT_ELEMENT + ':visible' ).length == 0 )
				{
					Menu.loadContent( $( _this ) );
				}
				else
				{
					$( Menu.CLASS_CONTENT_ELEMENT + ':visible' ).slideUp( 'slow' , function(){ Menu.loadContent( $( _this ) ) } );					
				}
				
				return false;
			},
			
			loadContent		:function( _this )		
			{		
				var element_menu_id	= $( _this ).attr( 'id' );	
				
				var level_array_data = element_menu_id.split( "|" );
				
				var level	=	level_array_data[ 0 ];
				
				var item 	=	level_array_data[ 1 ]; 
						
				var ID_content_element = '#' + Menu.ID_CONTENT_ELEMENT_LEVEL + level;
								
				//if the same element was clicked, close content element
				if( element_menu_id == Menu.CLICKED_ELEMENT_ID )
				{
					Menu.CLICKED_ELEMENT_ID = null;
					return false;
				}
				
				Menu.CLICKED_ELEMENT_ID = element_menu_id;
				
				var container = $( _this ).parent();
				
				Menu.loaderShowHide( container , 'on' );
			
				if( level )
				{
					//Menu.sendAjaxData( item );
					
					$.post( Menu.AJAX_POST_URL, 
					{
						action	: 'showContent',
						item	: item
					}, 
					function ( res )
					{
						if( res.status )
						{
							$( ID_content_element ).html( res.result );	
							
							if( $( ID_content_element ).find('img').length == 0 )
							{
								$( ID_content_element ).find('img').batchImageLoad(
									{
										loadingCompleteCallback: Menu.showContent( $( ID_content_element ) )
										/*imageLoadedCallback: imageLoaded*/
									}
								);
							}
							else
							{
								Menu.showContent( $( ID_content_element ) );
							}							
							
							//var obj2 = $( ID_content_element ).get(0);
				
							//console.log( Menu.findPos( obj2 ) );
				
							//window.scrollTo( 0 , 836/*Menu.findPos( obj2 )*/ ); 
						}
						
						Menu.loaderShowHide( container , 'off' );
					}, 
					'json');
				}
						
				//window.scrollTo(0 , document.body.clientHeight/2); 
				//Menu.findPos( element );
				
				return false;
			},
			
			showContent					:function( layer )
			{
				$( layer ).slideDown( 'slow' );	
				
				return false;
			},
			
			findPos						:function (obj) 
			{
				var curtop = 0;
				
				if (obj.offsetParent) 
				{
					//curleft = obj.offsetLeft
					curtop = obj.offsetTop
					
					while (obj = obj.offsetParent) 
					{
						//curleft += obj.offsetLeft
						curtop += obj.offsetTop
					}
				}
				
				//console.log( curtop );
				
				//window.scrollBy( 0 , curtop );
				
				return curtop; 
				
			},
			
			getScrollHeight	:function () 
			{

			    var y;

			    if (self.pageYOffset) 
				{

			        y = self.pageYOffset;

			    } 
				else if (document.documentElement && document.documentElement.scrollTop) 
				{

			        y = document.documentElement.scrollTop;

			    } 
				else if (document.body)
				{

			        y = document.body.scrollTop;

			    }

			    return parseInt(y);

			},

			
			loaderShowHide				:function( container , loader )
			{
				switch( loader )
				{
					case 'on':
						$( container ).prepend( '<img class="loader" src="pics/gl05 loader.gif" style="position: absolute; top: 48px; left: 65px; z-index: 20;"/>' );
					break;
					
					case 'off':
						$( '.loader' ).remove();
					break;
				}
			},
			
			onClickClose				:function()			
			{
				$( Menu.CLASS_CLOSE_CONTENT_ELEMENT ).click( Menu.eventCloseContentElement );
				
			},
			
			eventCloseContentElement	:function()
			{
				$( Menu.CLASS_CONTENT_ELEMENT + ':visible' ).slideUp( 'slow' );
				
				return false;
			},
			
		}
		
		$( Menu.init() )
		
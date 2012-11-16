<?php /* Smarty version 2.6.25, created on 2012-10-01 16:48:49
         compiled from plg_language_change.html */ ?>
<script language="javascript">
	JSFX.Rollover( "<?php echo $this->_tpl_vars['flag_language_pl']; ?>
" , "pics/language/<?php echo $this->_tpl_vars['flag_language_pl']; ?>
.png");
	JSFX.Rollover( "<?php echo $this->_tpl_vars['flag_language_en']; ?>
" , "pics/language/<?php echo $this->_tpl_vars['flag_language_en']; ?>
.png");
</script>

<div style="position: absolute; top: 0px; right: -20%; color: white; z-index: 2; width: 80px;">
	<table style="width: 80px; height: 0px;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="background-image:url('pics/language/<?php echo $this->_tpl_vars['flag_language_pl']; ?>
_grey.png'); background-repeat:no-repeat;">
				<a 
         <?php if ($this->_tpl_vars['current_lang'] == $this->_tpl_vars['flag_language_en']): ?>
          onMouseOver="JSFX.fadeIn('<?php echo $this->_tpl_vars['flag_language_pl']; ?>
')" 
					onMouseOut="JSFX.fadeOut('<?php echo $this->_tpl_vars['flag_language_pl']; ?>
')"
         <?php endif; ?> 
          href="?language=<?php echo $this->_tpl_vars['flag_language_pl']; ?>
" style="text-decoration: none;"/>
					<img name="<?php echo $this->_tpl_vars['flag_language_pl']; ?>
" style="margin: 0px;  padding: 0px 0px 34px 0px; <?php if ($this->_tpl_vars['current_lang'] == $this->_tpl_vars['flag_language_pl']): ?> opacity: 0.999; <?php else: ?> opacity: 0.0; <?php endif; ?>" src="pics/language/<?php echo $this->_tpl_vars['flag_language_pl']; ?>
_grey.png"/>
				</a> 			
			</td>
			<td style="background-image:url('pics/language/<?php echo $this->_tpl_vars['flag_language_en']; ?>
_grey.png'); background-repeat:no-repeat;">
				<a  
           <?php if ($this->_tpl_vars['current_lang'] == $this->_tpl_vars['flag_language_pl']): ?>
           onMouseOver="JSFX.fadeIn('<?php echo $this->_tpl_vars['flag_language_en']; ?>
')"
					 onMouseOut="JSFX.fadeOut('<?php echo $this->_tpl_vars['flag_language_en']; ?>
')" 
           <?php endif; ?>
           href="?language=<?php echo $this->_tpl_vars['flag_language_en']; ?>
" style="text-decoration: none;"/>
					<img name="<?php echo $this->_tpl_vars['flag_language_en']; ?>
" style="margin: 0px;  padding: 0px 0px 34px 0px; <?php if ($this->_tpl_vars['current_lang'] == $this->_tpl_vars['flag_language_en']): ?> opacity: 0.999; <?php else: ?> opacity: 0.0; <?php endif; ?> " src="pics/language/<?php echo $this->_tpl_vars['flag_language_en']; ?>
_grey.png"/>
				</a> 
			</td>
		</tr>		
	</table>
</div>
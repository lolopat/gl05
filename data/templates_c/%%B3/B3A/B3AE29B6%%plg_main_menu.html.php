<?php /* Smarty version 2.6.25, created on 2012-11-13 15:23:30
         compiled from plg_main_menu.html */ ?>
		
		<script language="javascript">
		var subpage = '<?php echo $this->_tpl_vars['subpage']; ?>
';
		<?php echo '
		<!--	
			$(document).ready(function()
			{
			   $(".latest_img").hover
			   (
					function()
					{
						$(this).fadeTo("slow", 1.0); // This should set the opacity to 100% on hover
					},function()
					{
						$(this).fadeTo("slow", 0.0); // This should set the opacity back to 30% on mouseout
					}
				);
			});
		-->
		'; ?>

		</script>
		<?php echo '<table class="menu_table" cellspacing="0"><tr>'; ?><?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><?php echo ''; ?><?php $_from = $this->_tpl_vars['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu_elements'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu_elements']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key2'] => $this->_tpl_vars['item2']):
        $this->_foreach['menu_elements']['iteration']++;
?><?php echo '<td style="background:url(\'pics/menu/'; ?><?php echo $this->_tpl_vars['item2']['white_black_picture']; ?><?php echo '\') no-repeat;">'; ?><?php if ($this->_tpl_vars['item2']['item']): ?><?php echo '<div class="element_menu_wrapper"><a class="cell-element" id="'; ?><?php echo $this->_tpl_vars['key']; ?><?php echo '|'; ?><?php echo $this->_tpl_vars['item2']['item']; ?><?php echo '" name="'; ?><?php echo $this->_tpl_vars['item2']['item']; ?><?php echo '" href="#"><img style="opacity: 0.0;" name="'; ?><?php echo $this->_tpl_vars['item2']['item']; ?><?php echo '" src="pics/menu/'; ?><?php echo $this->_tpl_vars['item2']['color_picture']; ?><?php echo '" class="imgFader latest_img"></a></div>'; ?><?php endif; ?><?php echo '</td>'; ?><?php if ($this->_foreach['menu_elements']['iteration'] % 4 == 0): ?><?php echo '</tr><tr><td colspan="4" border="0" style="padding: 0px; margin: 0px;"><div id="content-element-'; ?><?php echo $this->_tpl_vars['key']; ?><?php echo '" class="content-element"></div></td></tr><tr>'; ?><?php endif; ?><?php echo ''; ?><?php endforeach; endif; unset($_from); ?><?php echo ''; ?><?php endforeach; endif; unset($_from); ?><?php echo '</tr></table>'; ?>
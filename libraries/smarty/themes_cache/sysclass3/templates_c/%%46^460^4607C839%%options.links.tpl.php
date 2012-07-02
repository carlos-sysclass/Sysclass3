<?php /* Smarty version 2.6.26, created on 2012-06-08 14:14:37
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/includes/options.links.tpl */ ?>
<div class="headerTools">
	<span>
		<img 
			src = "images/others/transparent.png"
			class="imgs_cont sprite16 sprite16-go_into"
			border = "0"
		/>				
		<a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=view_last_paid_invoices"><?php echo @__XPAY_LAST_PAYMENTS; ?>
</a>
	</span>
		
	<span>
		<img 
			src = "images/others/transparent.png"
			class="imgs_cont sprite16 sprite16-go_into"
			border = "0"
		/>				
		<a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=view_to_send_invoices_list"><?php echo @__XPAY_VIEW_TO_SEND_INVOICES_LIST; ?>
</a>
	</span>
	<span>
		<img 
			src = "images/others/transparent.png"
			class="imgs_cont sprite16 sprite16-go_into"
			border = "0"
		/>				
		<a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
_boleto&action=send_return_file">Enviar Arquivo de Retorno</a>
	</span>
</div>
<div class="clear"></div>
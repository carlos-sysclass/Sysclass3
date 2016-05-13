<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				{foreach name="iterator" item="info" from=$T_DATA}
					<div class="span{$info.weight}">
						<a href="{if isset($info.href)}{$info.href}{else}javascript:void(0);{/if}" class="widget-stats">
							<span class="glyphicons {$info.icon}"><i></i></span>
							<span class="txt">{$info.text}</span>
							<div class="clearfix"></div>
							<span class="count label label-success">{$info.count}</span>
						</a>
					</div>
				{/foreach}
				<!--
				<div class="span2">
					<a href="" class="widget-stats">
						<span class="glyphicons shopping_cart"><i></i></span>
						<span class="txt">Franquias</span>
						<div class="clearfix"></div>
						<span class="count label label-important">20</span>
					</a>
				</div>
				<div class="span2">
					<a href="" class="widget-stats">
						<span class="glyphicons coins"><i></i></span>
						<span class="txt">Orçamento Enviados</span>
						<div class="clearfix"></div>
						<span class="count label label-success">&euro;292,039.02</span>
					</a>
				</div>
				<div class="span2">
					<a href="" class="widget-stats">
						<span class="glyphicons message_plus"><i></i></span>
						<span class="txt">Orçamentos Solicitados</span>
						<div class="clearfix"></div>
						<span class="count label">25</span>
					</a>
				</div>

				<div class="span2">
					<a href="" class="widget-stats">
						<span class="glyphicons chat"><i></i></span>
						<span class="txt">Contatos</span>
						<div class="clearfix"></div>
						<span class="count label label-primary">265</span>
					</a>
				</div>
				<div class="span2">
					<a href="" class="widget-stats">
						<span class="glyphicons thumbs_up"><i></i></span>
						<span class="txt">Pesquisas</span>
						<div class="clearfix"></div>
						<span class="count label label-important">13</span>
					</a>
				</div>
				-->
			</div>
		</div>
	</div>
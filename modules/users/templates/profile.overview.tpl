<div class="row">
	<div class="col-md-3">
		<ul class="list-unstyled profile-nav">
			<li>
				<img width="100%" src="{Plico_RelativePath file=$T_BIG_USER_AVATAR.avatar}" class="img-responsive" alt="" /> 
				<a href="#" class="profile-edit">edit</a>
			</li>
			{foreach $T_SUMMARY as $key => $value}
				<li>
					<a href="{$value.link.link}" class="text-{$value.type}">{$value.text} 
					{if $value.count > 0}
						<span>{$value.count}</span>
					{/if}
					</a>
				</li>
			{/foreach}
		</ul>
	</div>

{*
    ["id"]=>
    string(4) "2363"
    ["login"]=>
    string(16) "marcio.cerqueira"
    ["password"]=>
    string(32) "2c87c09d6fcdf69f2061746fb8a9078a"
    ["active"]=>
    string(1) "1"
    ["comments"]=>
    NULL
    ["user_type"]=>
    string(7) "student"
    ["timestamp"]=>
    string(10) "1341869333"
    ["avatar"]=>
    NULL
    ["pending"]=>
    string(1) "0"
    ["user_types_ID"]=>
    string(1) "0"
    ["additional_accounts"]=>
    NULL
    ["viewed_license"]=>
    string(1) "1"
    ["status"]=>
    string(0) ""
    ["short_description"]=>
    NULL
    ["balance"]=>
    string(1) "0"
    ["archive"]=>
    string(1) "0"
    ["dashboard_positions"]=>
    NULL
    ["need_mod_init"]=>
    string(1) "0"
    ["autologin"]=>
    string(32) "fbce43df31675ad86c7ef8a43e20e8b1"
    ["directory"]=>
    string(58) "/srv/www/vhosts/local.sysclass.com/upload/marcio.cerqueira"
  ["polo_id"]=>
  string(1) "1"
  ["ies_id"]=>
  string(1) "2"
  ["data_nascimento"]=>
  NULL
  ["not_18"]=>
  string(1) "0"
  ["rg"]=>
  string(10) "07353912-4"
  ["cpf"]=>
  string(14) "001.957.187-98"
  ["observacoes"]=>
  NULL
  ["cep"]=>
  string(8) "24220300"
  ["endereco"]=>
  string(26) "Rua Ministro Otávio Kelly"
  ["numero"]=>
  string(3) "499"
  ["complemento"]=>
  string(12) "Bl 03 Ap 802"
  ["bairro"]=>
  string(7) "Icaraí"
  ["cidade"]=>
  string(8) "Niterói"
  ["uf"]=>
  string(2) "RJ"
  ["telefone"]=>
  string(14) "(21) 2613-1041"
  ["celular"]=>
  string(14) "(21) 8888-2153"
  ["instituicao_formacao"]=>
  NULL
  ["empregabilidade"]=>
  NULL
  ["escolaridade"]=>
  NULL
*}
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-8 profile-info">
				<h1>{$T_EDIT_USER.name} {$T_EDIT_USER.surname}</h1>
				<p>{$T_EDIT_USER.short_description}</p>
				<p><a href="#">{$T_EDIT_USER.website}</a></p>
				<ul class="list-inline">
					<li class="tooltips" data-original-title="{translateToken value='Your Location'}" data-placement="bottom"><i class="icon-map-marker"></i> {$T_EDIT_USER.uf}, {$T_EDIT_USER.country_code}</li>
					{if $T_EDIT_USER.data_nascimento}
						<li><i class="icon-calendar"></i> {$T_EDIT_USER.data_nascimento}</li>
					{/if}
					{if $T_EDIT_USER.polo_id}
						<li class="tooltips" data-original-title="{translateToken value='Your Proctoring Center'}" data-placement="bottom"><i class="icon-briefcase"></i> {$T_USER_POLO.nome}</li>
					{/if}
					<!--
					<li><i class="icon-star"></i> Top Seller</li>
					<li><i class="icon-heart"></i> BASE Jumping</li>
					-->
				</ul>
			</div>
			<!--end col-md-8-->
			<div class="col-md-4">
				<div class="portlet sale-summary">
					<div class="portlet-title">
						<div class="caption">Sales Summary</div>
						<div class="tools">
							<a class="reload" href="javascript:;"></a>
						</div>
					</div>
					<div class="portlet-body">
						<ul class="list-unstyled">
							<li>
								<span class="sale-info">TODAY SOLD <i class="icon-img-up"></i></span> 
								<span class="sale-num">23</span>
							</li>
							<li>
								<span class="sale-info">WEEKLY SALES <i class="icon-img-down"></i></span> 
								<span class="sale-num">87</span>
							</li>
							<li>
								<span class="sale-info">TOTAL SOLD</span> 
								<span class="sale-num">2377</span>
							</li>
							<li>
								<span class="sale-info">EARNS</span> 
								<span class="sale-num">$37.990</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!--end col-md-4-->
		</div>
		<!--end row-->
		<div class="tabbable tabbable-custom tabbable-custom-profile">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1_11" data-toggle="tab">Latest Customers</a></li>
				<li ><a href="#tab_1_22" data-toggle="tab">Feeds</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1_11">
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-advance table-hover">
							<thead>
								<tr>
									<th><i class="icon-briefcase"></i> Company</th>
									<th class="hidden-xs"><i class="icon-question-sign"></i> Descrition</th>
									<th><i class="icon-bookmark"></i> Amount</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><a href="#">Pixel Ltd</a></td>
									<td class="hidden-xs">Server hardware purchase</td>
									<td>52560.10$ <span class="label label-success label-sm">Paid</span></td>
									<td><a class="btn default btn-xs green-stripe" href="#">View</a></td>
								</tr>
								<tr>
									<td>
										<a href="#">
										Smart House
										</a>  
									</td>
									<td class="hidden-xs">Office furniture purchase</td>
									<td>5760.00$ <span class="label label-warning label-sm">Pending</span></td>
									<td><a class="btn default btn-xs blue-stripe" href="#">View</a></td>
								</tr>
								<tr>
									<td>
										<a href="#">
										FoodMaster Ltd
										</a>
									</td>
									<td class="hidden-xs">Company Anual Dinner Catering</td>
									<td>12400.00$ <span class="label label-success label-sm">Paid</span></td>
									<td><a class="btn default btn-xs blue-stripe" href="#">View</a></td>
								</tr>
								<tr>
									<td>
										<a href="#">
										WaterPure Ltd
										</a>
									</td>
									<td class="hidden-xs">Payment for Jan 2013</td>
									<td>610.50$ <span class="label label-danger label-sm">Overdue</span></td>
									<td><a class="btn default btn-xs red-stripe" href="#">View</a></td>
								</tr>
								<tr>
									<td><a href="#">Pixel Ltd</a></td>
									<td class="hidden-xs">Server hardware purchase</td>
									<td>52560.10$ <span class="label label-success label-sm">Paid</span></td>
									<td><a class="btn default btn-xs green-stripe" href="#">View</a></td>
								</tr>
								<tr>
									<td>
										<a href="#">
										Smart House
										</a>  
									</td>
									<td class="hidden-xs">Office furniture purchase</td>
									<td>5760.00$ <span class="label label-warning label-sm">Pending</span></td>
									<td><a class="btn default btn-xs blue-stripe" href="#">View</a></td>
								</tr>
								<tr>
									<td>
										<a href="#">
										FoodMaster Ltd
										</a>
									</td>
									<td class="hidden-xs">Company Anual Dinner Catering</td>
									<td>12400.00$ <span class="label label-success label-sm">Paid</span></td>
									<td><a class="btn default btn-xs blue-stripe" href="#">View</a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!--tab-pane-->
				<div class="tab-pane" id="tab_1_22">
					<div class="tab-pane active" id="tab_1_1_1">
						<div class="scroller" data-height="290px" data-always-visible="1" data-rail-visible1="1">
							<ul class="feeds">
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-success">                        
													<i class="icon-bell"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													You have 4 pending tasks.
													<span class="label label-danger label-sm">
													Take action 
													<i class="icon-share-alt"></i>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											Just now
										</div>
									</div>
								</li>
								<li>
									<a href="#">
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-success">                        
														<i class="icon-bell"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														New version v1.4 just lunched!   
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												20 mins
											</div>
										</div>
									</a>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-danger">                      
													<i class="icon-bolt"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													Database server #12 overloaded. Please fix the issue.                      
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											24 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-info">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											30 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-success">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											40 mins
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-warning">                        
													<i class="icon-plus"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New user registered.                
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											1.5 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-success">                        
													<i class="icon-bell-alt"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													Web server hardware needs to be upgraded. 
													<span class="label label-inverse label-sm">Overdue</span>             
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											2 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-default">                       
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											3 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-warning">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											5 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-info">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											18 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-default">                       
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											21 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-info">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											22 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-default">                       
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											21 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-info">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											22 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-default">                       
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											21 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-info">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											22 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-default">                       
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											21 hours
										</div>
									</div>
								</li>
								<li>
									<div class="col1">
										<div class="cont">
											<div class="cont-col1">
												<div class="label label-info">                        
													<i class="icon-bullhorn"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">
													New order received. Please take care of it.                 
												</div>
											</div>
										</div>
									</div>
									<div class="col2">
										<div class="date">
											22 hours
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--tab-pane-->
			</div>
		</div>
	</div>
</div>
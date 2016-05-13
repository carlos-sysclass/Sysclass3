<div class="navbar main">
	<a href="#" class="appbrand"><span>{$T_CLIENT_NAME}</span></a>
			
	<button type="button" class="btn btn-navbar">
		<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
	</button>
		
			
	<ul class="topnav pull-right">
		<!--
		<li class="visible-desktop">
			<ul class="notif">
				<li><a href="" class="glyphicons envelope" data-toggle="tooltip" data-placement="bottom" data-original-title="5 new messages"><i></i> 5</a></li>
				<li><a href="" class="glyphicons shopping_cart" data-toggle="tooltip" data-placement="bottom" data-original-title="1 new orders"><i></i> 1</a></li>
				<li><a href="" class="glyphicons log_book" data-toggle="tooltip" data-placement="bottom" data-original-title="3 new activities"><i></i> 3</a></li>
			</ul>
		</li>
	-->
		<li class="account">
			<a data-toggle="dropdown" href="" class="glyphicons logout lock">
				<span class="hidden-phone text">{$T_USER['nome']} {$T_USER['sobrenome']}</span><i></i>
			</a>
			<ul class="dropdown-menu pull-right">
				<!--
				<li><a href="{$T_PROFILE_URL}" class="glyphicons cogwheel">Meu Perfil<i></i></a></li>
				-->
				<!--
				<li><a href="<?php echo getURL(array('my_account')); ?>" class="glyphicons camera">My Photos<i></i></a></li>
				-->
				<li class="highlight profile">
					<span>
						<span class="heading">Profile <!-- <a href="{$T_PROFILE_URL}" class="pull-right">editar</a>--> </span> 
						<span class="img"></span>
						<span class="details">
							<a href="javascript: void(0);">{$T_USER['nome']}</a>
							{$T_USER['email']}
						</span>
						<span class="clearfix"></span>
					</span>
				</li>
				<li>
					<span>
						<a class="btn btn-default btn-small pull-right" style="padding: 2px 10px; background: #fff;" href="{$T_LOGOUT_URL}">Sair</a>
					</span>
				</li>
			</ul>
		</li>
	</ul>
</div>
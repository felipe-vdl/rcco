<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	<div class="menu_section">
		<ul class="nav side-menu">
			@if (Auth::user()->nivel == 'Super-Admin' OR Auth::user()->nivel == 'Admin')
			<li><a href="{{ route('embreve')}}"><i class="fas fa-chart-pie"></i> Gráficos</a></li>
			@endif
			<li>
				<a href="{{ route('resposta.index')}}"><i class="fas fa-list"></i> Formulários</a>
			</li>
			@if (Auth::user()->nivel == 'Super-Admin')
				<li><a href="{{ url("/user") }}">	<i class="fas fa-user-shield"></i>Funcionários</a></li>
				<li><a><i class="fas fa-cogs"></i>Configurações <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu">
						<li><a href="{{ route("setor.index") }}"><i class="fa fa-list"></i> Setores</a></li>
						<li><a href="{{ route("unidade.index") }}"><i class="fa fa-list"></i> Unidades</a></li>
						<li><a href="{{ route("topico.index") }}"><i class="fa fa-list"></i> Tópicos</a></li>
						<li><a href="{{ route("pergunta.index") }}"><i class="fa fa-list"></i> Perguntas</a></li>
					</ul>
				</li>
			@endif
			@if (Auth::user()->nivel == 'Admin')
				<li><a href="{{ url("/user") }}">	<i class="fas fa-user-shield"></i>Funcionários</a></li>
				<li><a><i class="fas fa-cogs"></i> Configurações <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu">
						<li><a href="{{ route("unidade.index") }}"><i class="fa fa-list"></i> Unidades</a></li>
						<li><a href="{{ route("topico.index") }}"><i class="fa fa-list"></i> Tópicos</a></li>
						<li><a href="{{ route("pergunta.index") }}"><i class="fa fa-list"></i> Perguntas</a></li>
					</ul>
				</li>
			@endif
			<li>
				<a href="{{ route('logout')}}"><i class="fa fa-sign-out"></i> Sair do sistema </a>
			</li>
		</ul>	
	</div>
</div>




<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	<div class="menu_section">
		<ul class="nav side-menu">
			<li>
				<a href="{{ route('home')}}"><i class="fas fa-home"></i> Principal </a>
			</li>
			@if (Auth::user()->nivel == 'Super-Admin')
				<li><a href="{{ url("/user") }}">	<i class="fas fa-user-shield"></i>Funcionarios</a></li>	
				<li><a><i class="fas fa-cogs"></i>Configurações <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu">
						<li><a href="{{ route("setor.index") }}"><i class="fa fa-list"></i> Setores</a></li>
						<li><a href="{{ route("unidade.index") }}"><i class="fa fa-list"></i> Unidades</a></li>
						{{-- <li><a href="{{ url("/configforms") }}"><i class="fa fa-list"></i> Formularios</a></li> --}}
					</ul>
				</li>
			@endif
			<li>
				<a href="{{ route('logout')}}"><i class="fa fa-sign-out"></i> Sair do sistema </a>
			</li>
		</ul>	
	</div>
</div>




<div class="container" style="z-index: 999;">

	<ul id="gn-menu" class="gn-menu-main" style="z-index: 999;"> 

		<li class="gn-trigger">
			<a class="gn-icon gn-icon-menu"><span>Menu</span></a>
			<nav class="gn-menu-wrapper">
				<div class="gn-scroller">
					<ul class="gn-menu">
						<li id="menuInicio">
							<a href="/AdmR/Sist" class="gn-icon gn-icon-home"><b>Início</b></a>
						</li>
						<li id="menuUsuario">
							<a href="/AdmR/Sist/Usuarios" class="gn-icon gn-icon-usuario"><b>Usuários</b></a>
						</li>
						<li id="menuDespesa">
							<a href="/AdmR/Sist/Despesas" class="gn-icon gn-icon-desp"><b>Despesas</b></a>
						</li>
						<li id="menuFuncionario">
							<a href="/AdmR/Sist/Funcionarios" class="gn-icon gn-icon-func"><b>Funcionários</b></a>
						</li>
						<li id="menuFornecedor">
							<a href="/AdmR/Sist/Fornecedores" class="gn-icon gn-icon-forn"><b>Fornecedores</b></a>
						</li>
						<li id="menuPrestServ">
							<a href="/AdmR/Sist/PrestServ" class="gn-icon gn-icon-psv"><b>Prest. Serviço</b></a>
						</li>
						<li id="menuPonto">
							<a href="/AdmR/Sist/Ponto" class="gn-icon gn-icon-ponto"><b>Ponto</b></a>
						</li>
						<li id="menuFechtoFunc">
							<a href="/AdmR/Sist/Fechamento" class="gn-icon gn-icon-func-fechto"><b>Fechto Func.</b></a>
						</li>
						<li id="menuContasPagar">
							<a href="/AdmR/Sist/ContasPagar" class="gn-icon gn-icon-pagar"><b>Contas a Pagar</b></a>
						</li>
						<li id="menuSair">
							<a href="#" id="sairSistema" class="gn-icon gn-icon-sair"><b>Sair</b></a>
						</li>
					</ul>
				</div>
			</nav>
		</li>
		<div class="col-row" style="text-align: center;">
			<p id="tituloAplicacao" style="color: #34495e;">( <?php echo $_SESSION['usuario']['usrLogin']; ?> ) </p>
		</div>
		<li style="float: right;"></li>

	</ul>

</div>
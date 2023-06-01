<?php

$umAnoAtras = date( 'Y-m-d', strtotime('-1 year'));
$umAnoFrente = date( 'Y-m-d', strtotime('+1 year'));
?>

<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Your Finances</title>
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="CadastroReceitas.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" type="image/png" href="../Logo/YourFinancesLogo.jpg">
   </head>

   <header>
    <h1>Conta:</h1>
    <h1>Your Finances</h1>
    <h1>Mês:</h1>
   </header>
<body>
  <div class="sidebar close">
    <div class="logo-details">
        <i class='bx bx-wallet' ></i>
      <span class="logo_name">Menu</span>
    </div>
    <ul class="nav-links">
      <li>
        <a href="../HTML/HomePage.html">
          <i class='bx bx-grid-alt' ></i>
          <span class="link_name">Início</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../HTML/HomePage.html">Início</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-collection' ></i>
            <span class="link_name">Receitas e Despesas</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Receitas e Despesas</a></li>
          <li><a href="#">Gestão de Receitas</a></li>
          <li><a href="#">Cadastro de Receitas</a></li>
          <li><a href="#">Gestão de Despesas</a></li>
          <li><a href="../HTML/CadastroDespesas.html">Cadastro de Despesas</a></li>

        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-book-alt' ></i>
            <span class="link_name">Orçamento Mensal</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Orçamento Mensal</a></li>
          <li><a href="#">Definir Novo Orçamento</a></li>
          <li><a href="#">Visualizar Orçamentos</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-pie-chart-alt-2' ></i>
          <span class="link_name">Analytics</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Analytics</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-line-chart' ></i>
          <span class="link_name">Chart</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Chart</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-plug' ></i>
            <span class="link_name">Extratos</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Plugins</a></li>
          <li><a href="#">Ver Extrato</a></li>
          <li><a href="#">Importar Extratos</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-compass' ></i>
          <span class="link_name">Explore</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Explore</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-history'></i>
          <span class="link_name">History</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">History</a></li>
        </ul>
      </li>
      <li>
        <a href="../HTML/configurações.html">
          <i class='bx bx-cog' ></i>
          <span class="link_name">Setting</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../HTML/configurações.html">Setting</a></li>
        </ul>
      </li>
      <li>
    <div class="profile-details">
      <div class="profile-content">
        <!--<img src="image/profile.jpg" alt="profileImg">-->
      </div>
      <!-- <div class="name-job">
        <div class="profile_name">Prem Shahi</div>
        <div class="job">Web Desginer</div>
      </div>
      <i class='bx bx-log-out' ></i>
    </div> -->
  </li>
</ul>
  </div>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
        <div class="CadastroReceita">
            <h1>Cadastro de Receita </h1>
            <div class="cadastro-group">
                <label id="labReceita" for="TipoReceita">Tipo da Receita</label>
                <select name="TipoReceita" id="TipoReceita">
                    <option value="">Selecionar</option>
                </select>
            </div>
            <div class="cadastro-group">
                <label id="labRecebe" for="TipoRecebe">Tipo de Recebimento</label>
                <select name="TipoRecebe" id="TipoRecebe">
                    <option value="">Selecionar</option>
                </select>
            </div>

            <div class="cadastro-group">
                <label id="labValor" for="valorRec">Valor da Receita</label>
                <input type="text" id="valorRec" name="valorRec" class="decimal-input" inputmode="numeric" oninput="mascaraMoeda(event);">
            </div>

            <div class="cadastro-group">
                <label id="labData" for="dataRecebe">Validade do Recebimento</label>
                <input type="date" id="dataRecebe" name="dataRecebe" min="<?php echo $umAnoAtras; ?>" max="<?php echo $umAnoFrente; ?>" required>
            </div>

            <div class="cadastro-group">
                <label id="labRepete" for="repete">Tipo de Repetição</label>
                <select name="repete" id="repete">
                    <option value="0">Valor Unico</option>
                </select>
            </div>

            <div class="cadastro-group">
                <label id="infoComplementares" for="infoComplementares">Informações Complementares</label>
                <textarea name="infoComplementares" id="infoComplementares" cols="73" rows="10"></textarea>
            </div>

            <div class="botao">
                <button type="submit" id="cadastro">Cadastrar Receita</button>
            </div>
            <div class="botao2">
                <button type="submit" id="gestao">Gestao de Receita</button>
            </div>
        </div>
    </div>
  </section>
  <script>
  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
  console.log(sidebarBtn);
  sidebarBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("close");
  });
  </script>
</body>
</html>

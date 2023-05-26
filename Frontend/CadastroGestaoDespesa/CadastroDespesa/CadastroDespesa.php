<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Your Finances</title>
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href= "CadastroDespesas.css">
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
        <a href="#">
          <i class='bx bx-grid-alt' ></i>
          <span class="link_name">Início</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Início</a></li>
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
          <li><a href="CadastroDespesas.html">Cadastro de Receitas</a></li>
          <li><a href="#">Gestão de Despesas</a></li>
          <li><a href="#">Cadastro de Despesas</a></li>

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
        <a href="#">
          <i class='bx bx-cog' ></i>
          <span class="link_name">Setting</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Setting</a></li>
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

      <!-- FORMULARIO-->
      <div class="container">
        <h1>Cadastro de Despesas </h1>
        <form>
          <div class="form-group">
            <label id="nomelabel" for="name">Nome da despesa:</label>
            <input type="text" name="" id="name" cols= "199" rows= "2" placeholder="Nome da Despesa" required>            
            <textarea name="name" id="name" cols="199" rows="2" placeholder="Nome da Despesa" required></textarea>
          </div>
          <div class="form-group">
            <label id="despesalabel" for="valordesp">Valor da despesa:</label>
            <input type="number" id="valordesp" name="valordesp" placeholder="Valor" required>
          </div>
          <div class="form-group">
            <label id="vencimentolabel" for="message">Vencimento:</label>
            <input type="date" id="vencimento" name="message" required>
          </div>
          <div class="form-group">
            <label id="categorialabel" for="message">Categoria:</label>
            <input type="text" id="Categoria" name="Categoria" list="categorialist" placeholder="Categoria" required>
            <datalist id="categorialist">
              <option value="O">0</option>
                <option value="1">
                <option value="2">
                <option value="3">
                <option value="4">
                <option value="ghj5">
              </datalist>
          </div>
    
        </form>

        <div class="Orgoniza">
              <div class="form-group2">
                <h1>Organização </h1>
                <label id="paglabel" for="formapagamento">Forma pagamento:</label>
                <textarea name="formapagamento" id="formapagamento" cols="199" rows="2" placeholder="Forma de Pagamento" required></textarea>
              </div>
              <div class="form-group2">
                <label id="parcelaslabel" for="parcelas">Parcelas:</label>
                <input type="number" id="parcelas" name="parcelas" placeholder="Quantidade de Parcelas" required>
              </div>
              <div class="form-group2">
                <label id="associaçaolabel" for="associaçao">Imovel Associado:</label>
                <input type="text" id="associaçao" name="associaçao"  list="associaçaolist" placeholder="Associação" required>
                <datalist id="associaçaolist">
                    <option value="1">
                    <option value="2">
                    <option value="3">
                    <option value="4">
                    <option value="ghj5">
                  </datalist>
              </div>
              <div class="form-group2">
                <h1 id="h1observaçoes" for="observaçoes" >Dados Complementares</h1>
                <textarea name="observaçoes" id="observaçoes" cols="199" rows="10" placeholder="Observações" required></textarea>
    
              </div>
        </div>
        <!-- BOTOES-->
        <div class="botao">
            <button type="submit">Cadastrar</button>
            <button type="submit">Gestao de Despesas</button>
        </div> 
        <!-- FIM DOS BOTOES-->       
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

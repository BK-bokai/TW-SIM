<header>
   <nav class="nav-extended menu">
      <div class="nav-wrapper">
         <a href="#" class="brand-logo">
            <div>
               <!-- <i class="large material-icons">account_circle</i> -->
               台灣空氣品質模式數據平台
            </div>
         </a>
         <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
         <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="{{route('admin.Evaluate')}}">任務列表</a></li>
            <li><a href="{{route('admin.MetData')}}">資料庫管理系統</a></li>
            <li><a href="{{route('admin.logout')}}">登出</a></li>
         </ul>
         <!-- <a href="#" data-target="sidenav-1" class="right sidenav-trigger hide-on-medium-and-up"><i class="material-icons">menu</i></a> -->

      </div>
      <ul class="sidenav" id="mobile-demo">
         <li><a href="{{route('admin.Evaluate')}}">任務列表</a></li>
         <li><a href="{{route('admin.MetData')}}">資料庫管理系統</a></li>
         <li><a href="{{route('admin.logout')}}">登出</a></li>
      </ul>
   </nav>

   <!-- Right SIDEBAR	
   <ul id="sidenav-1" class="sidenav sidenav-fixed">
      <li><a class="subheader">Always out except on mobile</a></li>
      <li><a href="https://github.com/dogfalo/materialize/" target="_blank">Github</a></li>
      <li><a href="https://twitter.com/MaterializeCSS" target="_blank">Twitter</a></li>
      <li><a href="http://next.materializecss.com/getting-started.html" target="_blank">Docs</a></li>
   </ul> -->
</header>
<header>
   <nav class="nav-extended menu">
      <div class="nav-wrapper">
         <a href="#" class="brand-logo">
            <div>
               <i class="large material-icons">account_circle</i>
               台灣空氣品質模式數據平台
            </div>
         </a>
         <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
         <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="{{route('admin.Evaluate')}}">任務列表</a></li>
            <li><a href="{{route('admin.logout')}}">登出</a></li>
         </ul>

      </div>
      <ul class="sidenav" id="mobile-demo">
         <li><a href="{{route('admin.Evaluate')}}">任務列表</a></li>
         <li><a href="{{route('admin.logout')}}">登出</a></li>
      </ul>
   </nav>
</header>
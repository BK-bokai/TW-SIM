<header>
   <div class="menu_box">
      <div id='menu_icon' class="menu_icon">
         <a href="#"><i class="material-icons">menu</i></a>
      </div>
      <div class="logo">
         台灣空氣品質模式數據平台
      </div>
      <div class="nav_item">
         <ul>
            <li><a href="#">任務列表</a></li>
            <li><a href="#">資料庫管理系統</a></li>
            <li><a href="#">登出</a></li>
         </ul>
      </div>
   </div>
   <div id='left_nav' class="left_nav">
      <div>
         <ul>
            <li><a href="#">任務列表</a></li>
            <li><a href="#">資料庫管理系統</a></li>
            <li><a href="#">登出</a></li>
         </ul>
      </div>
   </div>

   <script>
      $(document).ready(function() {
         $('.sidenav').sidenav();

         $('body').on('touchstart click', function(e) {
            e.stopPropagation();
            if (e.target.id == 'left_nav' || $(e.target).parents("#left_nav").length == 1 || e.target.id == 'menu_icon' || $(e.target).parents("#menu_icon").length == 1) {
               $('.left_nav').show();
            } else {
               $('.left_nav').hide();
            }
         });
      });
   </script>
</header>
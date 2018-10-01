
  	<!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <!--- Sidebar navigation -->
        <!-- If the main navigation has sub navigation, then add the class "has_sub" to "li" of main navigation. -->
        <ul id="nav">
          <!-- Main menu with font awesome icon -->
		  <?php
				$has_sub = '';
				//print_r($user_menu);				
				foreach($user_menu['user_menu'] as $menu_item){										
					if($menu_item['ref_id'] == ''){
						if($has_sub != ''){
							echo '</ul></li>';	//tutup menu jika punya sub menu
						}
						
						//menu utama
						if($menu_item['has_sub'] == 'true'){
							$has_sub = 'has_sub';
							$icon_right = '<span class="pull-right"><i class="fa fa-chevron-right"></i></span>';
						}else{
							$has_sub = '';
							$icon_right = '';
						}
												
						if($menu_item['open'] == 'true'){
							$open = ' open';
						}else{
							$open = '';
						}											
												
						$str_menu = '<li class="'.$has_sub.$open.'">';
						$str_menu = $str_menu.'<a href="'.$menu_item['url'].'" ><i class="'.$menu_item['icon'].'" ></i>'.$menu_item['nama'].$icon_right.'</a>';						
						echo $str_menu;
						
						if($has_sub == ''){
							echo '</li>';	//tutup menu jika tidak punya sub menu
						}else{
							echo '<ul>';
						}
					}else{
						//sub menu									
						$str_menu = '<li><a href="'.$menu_item['url'].'">'.$menu_item['nama'].'</a></li>';
						echo $str_menu;
					}
				}
				
				if($has_sub != ''){
					echo '</ul></li>';	//tutup menu jika punya sub menu
				}
		  ?>		           
		  
        </ul>
    </div>

    <!-- Sidebar ends -->  	 	
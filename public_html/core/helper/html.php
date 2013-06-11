<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2013 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  License details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>
  
 UPGRADE NOTE: 
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.  
------------------------------------------------------------------------------*/
if (! defined ( 'DIR_CORE' )) {
	header ( 'Location: static_pages/' );
}

function renderStoreMenu( $menu, $level = 0 ){
	$menu = (array)$menu;
    $result = '';
    if ( $level ) $result .= "<ul class='dropdown-menu'>\r\n";
	$registry = Registry::getInstance();
	$logged = $registry->get('customer')->isLogged();

    foreach( $menu as $item ) {
		if(($logged && $item['id']=='login')
			||	(!$logged && $item['id']=='logout')){
			continue;
		}

        $id = ( empty($item['id']) ? '' : ' id="menu_'.$item['id'].'" ' ); // li ID

		if($level != 0){
			if(empty($item['children'])){
				$class='';
			}else{
				$class = $item['icon']? ' class="parent" style="background-image:none;" ' : ' class="parent menu_'.$item['id'].'" ';
			}
		}else{
			$class = $item['icon'] ? ' class="top" style="background-image:none;" ' : ' class="top menu_'.$item['id'].'" ';
		}

		$href = empty($item['href']) ? '' : ' href="'.$item['href'].'" '; //a href

        $result .= '<li' . $id . ' class="dropdown hover">';
        $result .= '<a' . $class . $href . '>';
	    $result .= $item['icon'] ? '<img src="'. HTTP_DIR_RESOURCE . $item['icon'].'" alt="" />' : '';
		$result .= '<span>' . $item['text'] . '</span></a>';

        if ( !empty($item['children']) ) $result .= "\r\n" . renderStoreMenu($item['children'], $level+1) ;
        $result .= "</li>\r\n";
    }
    if ( $level ) $result .= "</ul>\r\n";
    return $result;
}

function renderAdminMenu( $menu, $level = 0 ){
    $result = '';
    if ( $level ) $result .= "<ul>\r\n";
    foreach( $menu as $item ) {
        $id = ( empty($item['id']) ? '' : ' id="menu_'.$item['id'].'" ' ); // li ID
        $class = $level != 0 ? empty($item['children']) ? '' : ' class="parent" ' : ' class="top" '; //a class
        $href = empty($item['href']) ? '' : ' href="'.$item['href'].'" '; //a href
        $onclick = empty($item['onclick']) ? '' : ' onclick="'.$item['onclick'].'" '; //a href
       
        $result .= '<li' . $id . '>';
        $result .= '<a' . $class . $href . $onclick . '>' . $item['text'] . '</a>';
        if ( !empty($item['children']) ) $result .= "\r\n" . renderAdminMenu($item['children'], $level+1) ;
        $result .= "</li>\r\n";            
    }
    if ( $level ) $result .= "</ul>\r\n";
    return $result;
}
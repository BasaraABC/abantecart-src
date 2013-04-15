<div class="sidewidt">
<?php if ( $block_framed ) { ?>
	<h2 class="heading2"><span><?php echo $heading_title; ?></span></h2>
<?php } ?>
		<ul class="side_prd_list">
<?php
if ($products) {
    foreach ($products as $product) {
        $item = array();
		if( $item['thumb']['origin']=='internal'){
			$item['image'] = '<img width="50" src="'. $product['thumb']['thumb_url'].'"/>';
		}else{
			$item['image'] = $product['thumb']['thumb_html'];
		}
        $item['title'] = $product['name'];
        $item['description'] = $product['model'];
        $item['rating'] = ($product['rating']) ? "<img src='". $this->templateResource('/image/stars_'.$product['rating'].'.png') ."' alt='".$product['stars']."' />" : '';
                
        $item['info_url'] = $product['href'];
        $item['buy_url'] = $product['add'];
	    if(!$display_price){
		    $item['price'] = '';
	    }
	    
	    $review = $button_write;
	    if ($item['rating']) {
	    	$review = $item['rating'];
	    }
	    
?>      
              <li>
              	<a href="<?php echo $item['info_url']?>"><?php echo $item['image']?></a>
              	<a class="productname" href="<?php echo $item['info_url']?>"><?php echo $item['title']?></a>
                <span class="procategory"><?php echo $item['rating']?></span>
                <span class="price">
				<?php  if ($product['special']) { ?>
					<div class="pricenew"><?php echo $product['special']?></div>
					<div class="priceold"><?php echo $product['price']?></div>
				<?php } else { ?>
					<div class="pricenew"><?php echo $product['price']?></div>
				<?php } ?>
                </span>
              </li>
<?php
	}
}
?>
		</ul>
<?php if ( $block_framed ) { ?>
<?php } ?>
</div>
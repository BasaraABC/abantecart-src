<title><?php echo $title; ?></title>
<meta http-equiv="x-ua-compatible" content="IE=Edge" />
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<base href="<?php echo $base; ?>" />

<?php if ( is_file( DIR_RESOURCE . $icon ) ) {  ?>
<link href="resources/<?php echo $icon; ?>" type="image/png" rel="icon" />
<?php } ?>

<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300italic,400italic,600,600italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Crete+Round' rel='stylesheet' type='text/css'>
<link href="<?php echo $this->templateResource('/stylesheet/bootstrap.css'); ?>" rel="stylesheet">
<link href="<?php echo $this->templateResource('/stylesheet/bootstrap-responsive.css'); ?>" rel="stylesheet">
<link href="<?php echo $this->templateResource('/stylesheet/style.css'); ?>" rel="stylesheet">
<link href="<?php echo $this->templateResource('/stylesheet/flexslider.css'); ?>" type="text/css" media="screen" rel="stylesheet"  />
<link href="<?php echo $this->templateResource('/stylesheet/jquery.fancybox.css'); ?>" rel="stylesheet">
<link href="<?php echo $this->templateResource('/stylesheet/cloud-zoom.css'); ?>" rel="stylesheet">
<link href="<?php echo $this->templateResource('/stylesheet/onebyone.css'); ?>" rel="stylesheet">
<?php if ( $template_debug_mode ) {  ?>
<link href="<?php echo $this->templateResource('/stylesheet/template_debug.css'); ?>" rel="stylesheet">
<?php } ?>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!-- fav -->

<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<script type="text/javascript" src="<?php echo $ssl ? 'https': 'http'?>://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
if (typeof jQuery == 'undefined') {
   var include = '\x3Cscript type="text/javascript" src="<?php echo $this->templateResource("/javascript/jquery-1.8.2.min.js"); ?>">\x3C/script>';
   document.write(include);
}
</script>

<script type="text/javascript" src="<?php echo $this->templateResource('/javascript/common.js'); ?>"></script>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<?php if($cart_ajax){ //event for adding product to cart by ajax ?>
<script type="text/javascript">
    $('a[href=\\#].productcart').live('click',function(){
        var item = $(this);
        if(item.attr('id')){
            $.ajax({
                    url:'<?php echo $cart_ajax_url; ?>',
                    type:'GET',
                    dataType:'json',
                    data: {product_id:  item.attr('id') },
                    success:function (data) {
                    	var alert_msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $text_cartadded; ?>ssssss</div>';
                        item.closest('.thumbnail .pricetag').before(alert_msg);
                    }
            });
        }
    return false;
});
</script>
<?php }?>

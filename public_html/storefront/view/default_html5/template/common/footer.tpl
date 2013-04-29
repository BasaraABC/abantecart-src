<!-- Footer -->
<footer id="footer">
    <!-- footer blocks placeholder -->
    <section class="footersocial">
    	<div class="container">
      		<div class="row">
      		<div class="span3">
    		<?php echo ${$children_blocks[0]}; ?>
    		</div>
    		<div class="span3">
    		<?php echo ${$children_blocks[1]}; ?>
    		</div>
    		<div class="span3">
    		<?php echo ${$children_blocks[2]}; ?>
    		</div>
    		<div class="span3">
    		<?php echo ${$children_blocks[3]}; ?>
    		</div>
      </div>
    </div>
  </section>
  
  <section class="footerlinks">
    <div class="container">
        <div class="pull-left"> 
		<?php echo ${$children_blocks[4]}; ?>	
         </div>
         <div class="pull-right"> 
		<?php echo ${$children_blocks[5]}; ?>
		</div>
    </div>
  </section>
  <section class="copyrightbottom">
    <div class="container">
		<div class="pull-left">
        	<?php echo ${$children_blocks[6]}; ?>
		</div>
		<div class="pull-right textright"> <?php echo $text_powered_by?> <a href="http://www.abantecart.com" onclick="window.open(this.href);return false;" title="Ideal OpenSource E-commerce Solution">AbanteCart</a></div>
		<div class="pull-right mr20"> 
        	<?php echo ${$children_blocks[7]}; ?>
		</div>
    </div>
  </section>
  <a id="gotop" href="#">Back to top</a>
</footer>

<!--
AbanteCart is open source software and you are free to remove the Powered By AbanteCart if you want, but its generally accepted practise to make a small donatation.
Please donate via PayPal to donate@abantecart.com
//-->

<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo $this->templateResource('/javascript/bootstrap.js'); ?>"></script>
<script src="<?php echo $this->templateResource('/javascript/respond.min.js'); ?>"></script>
<script src="<?php echo $this->templateResource('/javascript/application.js'); ?>"></script>
<script src="<?php echo $this->templateResource('/javascript/bootstrap-tooltip.js'); ?>"></script>
<script src="<?php echo $this->templateResource('/javascript/bootstrap-modal.js'); ?>"></script>
<script defer src="<?php echo $this->templateResource('/javascript/jquery.fancybox.js'); ?>"></script>
<script defer src="<?php echo $this->templateResource('/javascript/jquery.flexslider.js'); ?>"></script>
<script src="<?php echo $this->templateResource('/javascript/cloud-zoom.1.0.2.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $this->templateResource('/javascript/jquery.validate.js'); ?>"></script>
<script type="text/javascript"  src="<?php echo $this->templateResource('/javascript/jquery.carouFredSel-6.1.0-packed.js'); ?>"></script>
<script type="text/javascript"  src="<?php echo $this->templateResource('/javascript/jquery.mousewheel.min.js'); ?>"></script>
<script type="text/javascript"  src="<?php echo $this->templateResource('/javascript/jquery.touchSwipe.min.js'); ?>"></script>
<script type="text/javascript"  src="<?php echo $this->templateResource('/javascript/jquery.ba-throttle-debounce.min.js'); ?>"></script>
<script type="text/javascript"  src="<?php echo $this->templateResource('/javascript/jquery.onebyone.min.js'); ?>"></script>
<script defer src="<?php echo $this->templateResource('/javascript/custom.js'); ?>"></script>
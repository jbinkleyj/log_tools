		</div> <!-- end of the container div -->
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>		
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/global.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/typeahead.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ejs/ejs_fulljslint.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ejs/ejs.js"></script>
		

		<?php
		// way to pass in javascript to append to end of document
		if(isset($scripts)){
			foreach($scripts as $script):
		?>
			<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/<?php echo $script; ?>"></script>
		<?php 
			endforeach; 
		}?>
	</body>
</html>
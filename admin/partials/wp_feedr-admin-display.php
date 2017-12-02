<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.feedr.nl
 * @since      1.0.0
 *
 * @package    Wp_feedr
 * @subpackage Wp_feedr/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form action="options.php" method="post">
        <?php
            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name );
            submit_button();
        ?>
    </form>
</div>


<script type="text/javascript">

(function($){

	$(document).ready(function(){
		$("form").find('[data-advanced="1"]').parents('tr').hide();

		$('[name=show_advanced]').change(function(){
			if (this.checked)
			{
				$("form").find('[data-advanced="1"]').parents('tr').slideDown();
			}
			else
			{
				$("form").find('[data-advanced="1"]').parents('tr').slideUp();
			}
		});

	});

})(jQuery);

</script>
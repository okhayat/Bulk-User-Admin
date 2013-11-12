<?php
/**
 * Search by email domain
 */
?>
<div id="search-box">
	<form action="<?php echo current_page_url() ?>" method="get">
	<strong><?php echo elgg_echo('bulk_user_admin:registered_users') ?></strong>
	<?php echo elgg_view('input/text', array('name' => 'domain')) ?>
	<input type="submit" value="<?php echo elgg_echo('bulk_user_admin:search_by_domain') ?>" />
	</form>
</div>

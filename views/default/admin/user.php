<?php
/**
 * Display a list of users to delete in bulk.
 *
 * Also used to show the search by domain results
 */

// Are we performing a search
$limit = get_input('limit', 10);
$offset = get_input('offset', 0);
$domain = get_input('domain');

$context = elgg_get_context();

if (!$domain) {
	$title = elgg_echo('admin:user');
} else {
	$title = "Users in the domain $domain";
}

elgg_set_context('search');

$options = array(
	'type' => 'user',
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false
);

if ($domain) {
	$users = bulk_user_admin_get_users_by_email_domain($domain, $options);
	$options['count'] = true;
	$users_count = bulk_user_admin_get_users_by_email_domain($domain, $options);
} else {
	$users = elgg_get_entities($options);
	$options['count'] = true;
	$users_count = elgg_get_entities($options);
}

$pagination = elgg_view('navigation/pagination', array(
	'base_url' => current_page_url(),
	'offset' => $offset,
	'count' => $users_count
));

$form_body = <<<HTML
<table width="100%" border="1" cellpadding="3" cellspacing="0">
	<tr>
		<th><input type="checkbox" class="check-all" /></th>
		<th>Icon</th>
		<th>Name</th>
		<th>Username</th>
		<th>Email</th>
		<th>GUID</th>
		<th>Last Login</th>
		<th>Created</th>
		<th>Last Action</th>
		<th>Objects</th>
		<th>Annotations</th>
		<th>Metadata</th>
	</tr>
HTML;
foreach ($users as $user) {
	$form_body .= elgg_view('bulk_user_admin/user', array('entity' => $user));
}
$form_body .= '</table>';
$delete_button = elgg_view('input/submit', array(
	'value' => 'Delete checked',
));

$form_body .= $delete_button;

$site = elgg_get_config('site');

$checked_form = elgg_view('input/form', array(
	'action' =>  $site->url . 'action/bulk_user_admin/delete',
	'body' => $form_body
));


$domain_form = '';

if ($domain) {
	$delete_button = "<br /><br />" . elgg_view('input/submit', array(
		'value' => 'Delete all in domain',
	));

	$hidden = elgg_view('input/hidden', array(
		'name' => 'domain',
		'value' => $domain
	));

	$form_body = $delete_button . $hidden;

	$domain_form = elgg_view('input/form', array(
		'action' =>  $site->url . 'action/bulk_user_admin/delete_by_domain',
		'body' => $form_body
	));

}

$summary = "<div>$users_count user(s) found</div>";

if ($domain) {
	$summary .= '<br />';
	$summary .= elgg_view('output/url', array(
		'href' => elgg_http_remove_url_query_element(current_page_url(), 'domain'),
		'text' => 'All users'
	));
}

elgg_set_context('admin');

echo $title . $summary . $pagination . $checked_form . $domain_form . $pagination;

?>
<script>
$('.check-all').click(function(){
	$('.elgg-form input[class!=check-all]').each(function(){
		$(this).attr('checked') ? $(this).removeAttr('checked') : $(this).attr('checked', 'checked');
	});
});
</script>

<?php
/**
 * Show a user for bulk actions. Includes a checkbox on the left.
 */
if ($vars['entity'] instanceof ElggUser) {
	$icon =	elgg_view_entity_icon($vars['entity'], 'small');
	$banned = $vars['entity']->isBanned();
	$user = $vars['entity'];
	$created = elgg_view_friendly_time($user->time_created);
	$last_login = $user->last_login ? elgg_view_friendly_time($user->last_login) : 'N/A';
	$last_action = $user->last_action ? elgg_view_friendly_time($user->last_action) : 'N/A';
	$objects = elgg_get_entities(array(
		'owner_guid' => $user->guid,
		'count' => true
	));

	$db_prefix = elgg_get_config('dbprefix');

	$q = "SELECT COUNT(id) as count FROM {$db_prefix}annotations WHERE owner_guid = $user->guid";
	$data = get_data($q);
	$annotations = (int) $data[0]->count;

	$q = "SELECT COUNT(id) as count FROM {$db_prefix}metadata WHERE owner_guid = $user->guid";
	$data = get_data($q);
	$metadata = (int) $data[0]->count;

// the CSS for classless <label> is really, really annoying.
$info = <<<___HTML
	<tr>
		<td><input type="checkbox" name="bulk_user_admin_guids[]" value="{$user->guid}"/></td>
		<td>$icon</td>
		<td>{$user->name}</td>
		<td>{$user->username}</td>
		<td>{$user->email}</td>
		<td>{$user->guid}</td>
		<td>$last_login</td>
		<td>$created</td>
		<td>$last_action</td>
		<td>$objects</td>
		<td>$annotations</td>
		<td>$metadata</td>
	</tr>
___HTML;

	if ($banned) {
		$info .= '<div id="profile_banned">';
		$info .= elgg_echo('profile:banned');
		$info .= '<br />';
		$info .= $user->ban_reason;
		$info .= '</div>';
	}
	echo $info;
}

<?php
/**
 * Update functions for version [version]
 *
 * @package LifterLMS/Functions/Updates
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * Record open sessions in wp_lifterlms_events_open_sessions
 *
 * @since [version]
 *
 * @return bool True if it needs to run again, false otherwise.
 */
function llms_update_445_migrate_events_open_sessions() {

	$limit = 200;
	$skip  = get_transient( 'llms_445_skipper_events_open_sessions' );
	if ( ! $skip ) {
		$skip = 0;
	}
	set_transient( 'llms_445_skipper_events_open_sessions', $skip + $limit, DAY_IN_SECONDS );

	global $wpdb;
	$maybe_open_sessions = $wpdb->get_results( // db call ok; no-cache ok.
		$wpdb->prepare(
			"SELECT id, actor_id, object_id
			FROM {$wpdb->prefix}lifterlms_events
			WHERE `event_type`='session'
			AND `event_action`='start'
			ORDER BY id ASC
			LIMIT %d, %d
		",
			$skip,
			$limit
		)
	);

	// Finished.
	if ( empty( $maybe_open_sessions ) ) {
		set_transient( 'llms_update_445_migrate_events_open_sessions', 'complete', DAY_IN_SECONDS );
		return false;
	}

	$insert = '';
	foreach ( $maybe_open_sessions as $maybe_open_session ) {
		// Create an event instance so to pass it to the `LLMS_Sessions::instance()->is_session_open()` util.
		$start = new LLMS_Event( $maybe_open_session->id );
		// Set the only useful properties, without the need to save them from the db.
		$start->set( 'actor_id', $maybe_open_session->actor_id, false );
		$start->set( 'object_id', $maybe_open_session->object_id, false );

		if ( LLMS_Sessions::instance()->is_session_open( $start ) ) {
			$insert .= $wpdb->prepare( '(%s)', $maybe_open_session->id );
		}
	}

	// Add the open sessions to the new table.
	if ( ! empty( $insert ) ) {
		$wpdb->query( // db call ok; no-cache ok.
			"INSERT INTO {$wpdb->prefix}lifterlms_events_open_sessions ( `event_id` ) VALUES " . $insert
		);
	}

	// Needs to run again.
	return true;
}

/**
 * Update db version to [version]
 *
 * @since [version]
 *
 * @return void|true True if it needs to run again, nothing if otherwise.
 */
function llms_update_445_update_db_version() {
	if ( 'complete' !== get_transient( 'llms_update_445_migrate_events_open_sessions' ) ) {
		// Needs to run again.
		return true;
	}

	LLMS_Install::update_db_version( '4.4.5' );
}

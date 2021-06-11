<?php

$custom_alerts = array(
	__( 'bbPress Forums', 'wsal-bbpress' ) => array(
		__( 'Forums', 'wsal-bbpress' ) => array(
			array( 8000, WSAL_INFORMATIONAL, __( 'User created new forum', 'wsal-bbpress' ), __( 'New forum called %ForumName% %LineBreak% %EditorLinkForum%', 'wsal-bbpress' ), 'bbpress-forum', 'created' ),
			array( 8001, WSAL_MEDIUM, __( 'User changed status of a forum', 'wsal-bbpress' ), __( 'Changed the status of the forum %ForumName% %LineBreak% Previous Status: %OldStatus% %LineBreak% New Status: %NewStatus% %LineBreak% %EditorLinkForum%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8002, WSAL_MEDIUM, __( 'User changed visibility of a forum', 'wsal-bbpress' ), __( 'Changed the visibility of the forum %ForumName% %LineBreak% Previous visibility: %OldVisibility% %LineBreak% New visibility: %NewVisibility% %LineBreak% %EditorLinkForum%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8003, WSAL_LOW, __( 'User changed the URL of a forum', 'wsal-bbpress' ), __( 'Changed the URL of the forum %ForumName% %LineBreak% Previous URL: %OldUrl% %LineBreak% New URL: %NewUrl% %LineBreak% %EditorLinkForum%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8004, WSAL_INFORMATIONAL, __( 'User changed order of a forum', 'wsal-bbpress' ), __( 'Changed the sorting order of the forum %ForumName% %LineBreak% Previous sorting order: %OldOrder% %LineBreak% New sorting order: %NewOrder% %LineBreak% %EditorLinkForum%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8005, WSAL_HIGH, __( 'User moved forum to trash', 'wsal-bbpress' ), __( 'Moved the forum %ForumName% to trash', 'wsal-bbpress' ), 'bbpress-forum', 'deleted' ),
			array( 8006, WSAL_HIGH, __( 'User permanently deleted forum', 'wsal-bbpress' ), __( 'Permanently deleted the forum %ForumName%', 'wsal-bbpress' ), 'bbpress-forum', 'deleted' ),
			array( 8007, WSAL_HIGH, __( 'User restored forum from trash', 'wsal-bbpress' ), __( 'Restored the forum %ForumName% from trash', 'wsal-bbpress' ), 'bbpress-forum', 'restored' ),
			array( 8008, WSAL_LOW, __( 'User changed the parent of a forum', 'wsal-bbpress' ), __( 'Changed the parent of the forum %ForumName% %LineBreak% Previous parent: %OldParent% %LineBreak% New parent: %NewParent% %LineBreak% %EditorLinkForum%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8011, WSAL_LOW, __( 'User changed type of a forum', 'wsal-bbpress' ), __( 'Changed the type of the forum %ForumName% %LineBreak% Previous type: %OldType% %LineBreak% New type: %NewType% %LineBreak% %EditorLinkForum%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
		),

		__( 'bbPress Forum Topics', 'wsal-bbpress' ) => array(
			array( 8014, WSAL_INFORMATIONAL, __( 'User created new topic', 'wsal-bbpress' ), __( 'New topic called %TopicName% %LineBreak% %EditorLinkTopic%', 'wsal-bbpress' ), 'bbpress-forum', 'created' ),
			array( 8015, WSAL_INFORMATIONAL, __( 'User changed status of a topic', 'wsal-bbpress' ), __( 'Changed the status of the topic %TopicName% %LineBreak% Previous status: %OldStatus% %LineBreak% New status: %NewStatus% %LineBreak% %EditorLinkTopic%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8016, WSAL_INFORMATIONAL, __( 'User changed type of a topic', 'wsal-bbpress' ), __( 'Changed the type of the topic %TopicName% %LineBreak% Previous type: %OldType% %LineBreak% New type: %NewType% %LineBreak% %EditorLinkTopic%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8017, WSAL_INFORMATIONAL, __( 'User changed URL of a topic', 'wsal-bbpress' ), __( 'Changed the URL of the topic %TopicName% %LineBreak% Previous URL: %OldUrl% %LineBreak% New URL: %NewUrl% %LineBreak% %EditorLinkTopic%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8018, WSAL_INFORMATIONAL, __( 'User changed the forum of a topic', 'wsal-bbpress' ), __( 'Changed the forum of the topic %TopicName% %LineBreak% Previous forum: %OldForum% %LineBreak% New forum: %NewForum% %LineBreak% %EditorLinkTopic%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
			array( 8019, WSAL_MEDIUM, __( 'User moved topic to trash', 'wsal-bbpress' ), __( 'Moved the %TopicName% to trash', 'wsal-bbpress' ), 'bbpress-forum', 'deleted' ),
			array( 8020, WSAL_MEDIUM, __( 'User permanently deleted topic', 'wsal-bbpress' ), __( 'Permanently deleted the topic %TopicName%', 'wsal-bbpress' ), 'bbpress-forum', 'deleted' ),
			array( 8021, WSAL_INFORMATIONAL, __( 'User restored topic from trash', 'wsal-bbpress' ), __( 'Restored the topic %TopicName% from trash', 'wsal-bbpress' ), 'bbpress-forum', 'restored' ),
			array( 8022, WSAL_LOW, __( 'User changed visibility of a topic', 'wsal-bbpress' ), __( 'Changed the visibility of the topic %TopicName% %LineBreak% Previous visibility: %OldVisibility% %LineBreak% New visibility: %NewVisibility% %LineBreak% %EditorLinkTopic%', 'wsal-bbpress' ), 'bbpress-forum', 'modified' ),
		),

		__( 'bbPress Settings', 'wsal-bbpress' ) => array(
			array( 8009, WSAL_HIGH, __( 'User changed forum\'s role', 'wsal-bbpress' ), __( 'Changed the forum user\'s auto role %LineBreak% Previous role: %OldRole% %LineBreak% New role: %NewRole%', 'wsal-bbpress' ), 'bbpress', 'modified' ),
			array( 8010, WSAL_CRITICAL, __( 'User changed option of a forum', 'wsal-bbpress' ), __( 'The option for anonymous posting on the forums', 'wsal-bbpress' ), 'bbpress', 'enabled' ),
			array( 8012, WSAL_MEDIUM, __( 'User changed time to disallow post editing', 'wsal-bbpress' ), __( 'Changed the time to disallow post editing in the forums %LineBreak% Previous time: %OldTime% %LineBreak% New time: %NewTime%', 'wsal-bbpress' ), 'bbpress', 'modified' ),
			array( 8013, WSAL_HIGH, __( 'User changed the forum setting posting throttle time', 'wsal-bbpress' ), __( 'Changed the posting throttle time in the forums %LineBreak% Previous time: %OldTime% %LineBreak% New time: %NewTime%', 'wsal-bbpress' ), 'bbpress', 'modified' ),
		),

		__( 'bbPress User Profiles', 'wsal-bbpress' ) => array(
			array( 8023, WSAL_LOW, __( 'The forum role of a user was changed by another WordPress user', 'wsal-bbpress' ), __( 'Change the forum role of user %TargetUsername% %LineBreak% Previous role: %OldRole% %LineBreak% New role: %NewRole% %LineBreak% First name: %FirstName% %LineBreak% Last name: %LastName% %LineBreak% %EditUserLink%', 'wsal-bbpress' ), 'user', 'modified' ),
		),
	),
);

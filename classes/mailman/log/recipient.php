<?php

namespace Email;

class Mailman_Log_Recipient extends \Orm\Model
{
	protected static $_table_name = 'log_email_recipients';

	public static $_properties = array(
		'id',
		'email_id',
		'list',
		'email',
		'name',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'email' => array(
			'key_from'       => 'email_id',
			'model_to'       => 'Email\Mailman_Log_Email',
			'key_to'         => 'id',
			'cascade_save'   => true,
			'cascade_delete' => false,
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	
}
<?php

namespace Email;

class Mailman_Log_Email extends \Orm\Model
{
	protected static $_table_name = 'log_emails';
	protected static $_properties = array(
		'id',
		'type',
		'from_name',
		'from_addr',
		'subject',
		'body',
		'status',
		'message',
		'created_at',
		'updated_at',
	);

	protected static $_has_many = array(
		'recipients' => array(
			'key_from'       => 'id',
			'model_to'       => 'Email\Mailman_Log_Recipient',
			'key_to'         => 'email_id',
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

	public function date($format = "r")
	{
		return date($format, $this->created_at);
	}


	private function log_recipient($list, $email, $name)
	{
		$recipient = Mailman_Log_Recipient::forge(array(
			'email_id' => $this->id,
			'list'     => $list,
			'email'    => $email,
			'name'     => $name,
		));
		return $recipient->save();
	}


	public static function log_event($message_type, $email, $message_status = 'success', $message_error = 'email_sent')
	{
		$from = $email->get_config('from');

		$log = static::forge(array(
			'type'      => $message_type,
			'from_name' => $email->get_from_name(),
			'from_addr' => $email->get_from_email(),
			'subject'   => $email->get_subject(),
			'body'      => $email->get_body(),
			'status'    => $message_status,
			'message'   => $message_error,
		));

		$log->save();

		foreach (array('to', 'cc', 'bcc', 'reply_to') as $list)
		{
			foreach ($email->{'get_' . $list}() as $key => $value)
			{
				$log->log_recipient($list, $value['email'], $value['name']);
			}
		}

		return $log;
	}

}
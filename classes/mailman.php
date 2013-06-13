<?php

namespace Email;

class Mailman
{
	protected $logging = true;

	public function send_email($message_type, $email)
	{
		try
		{
			if (true)//$email->send())
			{
				$message_status = 'success';
				$message_error  = 'email_sent';
			}
		}
		catch(\EmailValidationFailedException $e)
		{
			$message_status = 'error';
			$message_error  = 'email_validation_failed';
		}
		catch(\EmailSendingFailedException $e)
		{
			$message_status = 'error';
			$message_error  = 'email_sending_failed';
		}

		if ($this->logging == true)
		{
			$log = Mailman_Log_Email::log_event($message_type, $email, $message_status, $message_error);
		}

		return true;

	}
}
<?php
/**
 * NMA
 *
 * This class provides a path to the notifymyandroid.com api.
 *
 * @author Bart Moorman <bart.moorman at id10t dot us>
 * @version 1.00
 * @package NMA
 */
class NMA
{
	private $api = 'https://www.notifymyandroid.com/publicapi/';

	private $apikeys = array();
	private $priority = 0;
	private $application = null;
	private $event = null;
	private $description = null;

	private $debug = false;
	private $error = false;

	private $remaining = 0;
	private $resettimer = 0;

	public function addApiKey($apikey)
	{
		if(strlen($apikey) != 48):
			echo 'apikey must be exactly 48 characters!' . PHP_EOL;
			return false;
		else:
			$this->apikeys[] = $apikey;
			return true;
		endif;
	}
	public function setPriority($priority)
	{
		if($priority > 2 || $priority < -2):
			echo 'priority must be between 2 and -2!' . PHP_EOL;
			return false;
		else:
			$this->priority = $priority;
			return true;
		endif;
	}
	public function setApplication($application)
	{
		if(strlen($application) > 256):
			echo 'application must be 256 characters or less!' . PHP_EOL;
			return false;
		else:
			$this->application = $application;
			return true;
		endif;
	}
	public function setEvent($event)
	{
		if(strlen($event) > 1000):
			echo 'event must be 1,000 characters or less!' . PHP_EOL;
			return false;
		else:
			$this->event = $event;
			return true;
		endif;
	}
	public function setDescription($description)
	{
		if(strlen($description) > 10000):
			echo 'description must be 10,000 characters or less!' . PHP_EOL;
			return false;
		else:
			$this->description = str_replace('\n', PHP_EOL, $description);
			return true;
		endif;
	}

	public function setDebug($debug)
	{
		$this->debug = filter_var($debug, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}

	public function getRemaining()
	{
		if(!empty($this->remaining)):
			return $this->remaining;
		else:
			return false;
		endif;
	}
	public function getResetTimer()
	{
		if(!empty($this->resettimer)):
			return $this->resettimer;
		else:
			return false;
		endif;
	}

	private function prepare()
	{
		if(!empty($this->apikeys)):
			$this->fields['apikey'] = implode(',', $this->apikeys);
		else:
			echo 'apikey is required!' . PHP_EOL;
			$this->error = true;
		endif;

		if(!empty($this->application)):
			$this->fields['application'] = $this->application;
		else:
			echo 'application is required!' . PHP_EOL;
			$this->error = true;
		endif;

		if(!empty($this->event)):
			$this->fields['event'] = $this->event;
		else:
			echo 'event is required!' . PHP_EOL;
			$this->error = true;
		endif;

		if(!empty($this->description)):
			$this->fields['description'] = $this->description;
		else:
			echo 'description is required!' . PHP_EOL;
			$this->error = true;
		endif;

		if(!empty($this->priority)):
			$this->fields['priority'] = $this->priority;
		endif;

		return $this->error ? false : true;
	}
	public function send()
	{
		if($this->prepare()):
			$ch = curl_init();

			$options = array(
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => http_build_query($this->fields),
				CURLOPT_URL => $this->api . 'notify'
			);

			curl_setopt_array($ch, $options);
			$response = curl_exec($ch);
			curl_close($ch);

			$xml = new SimpleXMLElement($response);

			if($xml->success):
				$this->remaining = $xml->success->attributes()->remaining;
				$this->resettimer = $xml->success->attributes()->resettimer;
				return true;
			else:
				echo $xml->error->attributes()->code . ' ' . $xml->error . PHP_EOL;
				return false;
			endif;
		else:
			echo 'unable to send notification!' . PHP_EOL;
			return false;
		endif;
	}
}
?>

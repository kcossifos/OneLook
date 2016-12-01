<?php


class Mail
{

	public function isValidMail($mail) {
		if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
			return FALSE;

		if(!checkdnsrr("gmail.com", "MX"))
			return TRUE;

		list($username, $mdomainname) = explode("@", $mail);
		if(checkdnsrr($mdomainname, "MX"))
			return TRUE;

		return FALSE;
	}

	public function sendMail($to, $subject, $message, $from = 'From: no.reply@dot.com', $isHtml = true) {

	    $from .= "\r\n";
		if($isHtml) {
			$from .= "MIME-Version: 1.0 \r\n";
	        $from .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		}
		$from .= 'X-Mailer: PHP/'.phpversion()."\r\n";

		return mail($to, $subject, $message, $from);

	}

	public function html($string) {
		return htmlentities($string, ENT_QUOTES);
	}


	public function tsince($time, $end_msg = 'ago') {

	    $time = abs(time() - $time);

	    if($time == 0)
	    	return "Just now";

	    $tokens = array (
	        31536000 => 'year',
	        2592000 => 'month',
	        604800 => 'week',
	        86400 => 'day',
	        3600 => 'hour',
	        60 => 'minute',
	        1 => 'second'
	    );

	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' '. $end_msg;
	    }

	}


	public function fError($error) {

		global $set_class;
		echo "<div style='margin:0 auto;text-align:center;width:80%'><div class='alert alert-error'>$error</div></div>";
		include "footer.php";
		die();
	}

	public function error($error='', $return = 0) {
		$html = "<div class='alert alert-error'>$error</div>";
		if($return)
			return $html;
		echo $html;
	}

	public function success($message='', $return = 0) {
		$html = "<div class=\"alert alert-success\">".$message."</div>";
		if($return)
			return $html;

		echo $html;
	}

	public function info($message='', $return = 0) {

		$html = "<div class=\"alert alert-info\">".$message."</div>";
		if($return)
			return $html;

		echo $html;
	}

	public function queryString($type = '', $ignore = array()) {

		$result = '';
		foreach($_GET as $a => $o) {
			if(in_array($a, $ignore))
				continue;

			if($type == 'hidden') {
				$result .= "<input type='hidden' name='".urlencode($a)."' value='".urlencode($o)."'>";
			} else {
				$result[] = urlencode($a)."=".urlencode($o);
			}
		}

		if(is_array($result))
			return "?".implode("&", $result);

		return $result;


	}

	public function prettyPrint($text) {
		return str_replace("_", " ", ucfirst($text));
	}


	public function validUsername($values) {

		return preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $values);
	}


}

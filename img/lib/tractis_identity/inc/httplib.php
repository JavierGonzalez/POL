<?php
/* 
 * File: httplib.php
 * Date: 13/08/2008
 * Author: James Low
 * About: This file contains code for making a http request when cURL isn't avaliable or file_get_contents
 *        isn't enabled to allow getting contents over from a remote location over HTTP. This can be
 *        checkd by checking the ini_get("allow_url_fopen") which is set in the php.ini file.
 */

//Class that implements the request
class HTTPRequest {
    var $_fp;        // HTTP socket
    var $_url;        // full URL
    var $_host;        // HTTP host
    var $_protocol;    // protocol (HTTP/HTTPS)
    var $_uri;        // request URI
    var $_port;        // port
    var $_params;		//Additional get params not on the url
    
    // scan url
    function _scan_url() {
        $req = $this->_url;
        
        $pos = strpos($req, '://');
        $this->_protocol = strtolower(substr($req, 0, $pos));
        
        $req = substr($req, $pos+3);
        $pos = strpos($req, '/');
        if($pos === false) {
            $pos = strlen($req);
        }
        $host = substr($req, 0, $pos);
        
        if(strpos($host, ':') !== false) {
            list($this->_host, $this->_port) = explode(':', $host);
        } else {
            $this->_host = $host;
            $this->_port = ($this->_protocol == 'https') ? 443 : 80;
        }
        
        $this->_uri = substr($req, $pos);
        if($this->_uri == '') {
            $this->_uri = '/';
        }
    }
    
    // constructor
    function HTTPRequest($url) {
        $this->_url = $url;
        $this->_scan_url();
    }
    
    //Basic header common to both GET and POST
    function BasicHeader($type) {
  		$crlf = "\r\n";
  		//If has get params, add them
    	return $type . ' ' . $this->_uri . ($this->_params == '' ? '' : '?'.$this->_params) . ' HTTP/1.0' . $crlf
            . 'Host: ' . $this->_host . $crlf;
    }
    
    //Encode request param
    function ParamEncode($key, $value) {
    	return urlencode($key) . '=' . urlencode($value);
    }
    
    //Encode request params
    function RequestEncode($paramarray) {
    	$content = '';
		//Encode all post parameters
		foreach($paramarray as $key => $value) {
			if ($content == '') {
				$content = $this->ParamEncode($key,$value);
			} else {
				$content .= '&' .$this->ParamEncode($key,$value);			
			}
		}
		return $content;
    }
    
    //Get request
    function Get($paramarray = null, $details = false) {
		$crlf = "\r\n";
		if (isset($paramarray)) {
			$this->_params = $this->RequestEncode($paramarray);
		} else {
			$this->_params = '';
		}
        $req = $this->BasicHeader('GET') . $crlf;
        return $this->Request($req,false,$details);
    }
    
    //Post request
    function Post($paramarray, $details = false) {
		$crlf = "\r\n";
    	$this->_params = '';
		$content = $this->RequestEncode($paramarray);
        $req = $this->BasicHeader('POST')
        	. 'Content-Type: application/x-www-form-urlencoded' . $crlf
        	. 'Content-Length: ' . strlen($content) . $crlf
        	. $crlf
        	. $content;
		return $this->Request($req,true,$details);
    }
    
    //General request
    function Request($req, $post = false, $details = false) {
	    $crlf = "\r\n";
	    
        // fetch
        $this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
        fwrite($this->_fp, $req);
        while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp)) {
            $response .= fread($this->_fp, 1024);
        }
        fclose($this->_fp);
        
        // split header and body
        $pos = strpos($response, $crlf . $crlf);
        if($pos === false)
            return($response);
        $header = substr($response, 0, $pos);
        $body = substr($response, $pos + 2 * strlen($crlf));

        // parse headers
        $headers = array();
        $lines = explode($crlf, $header);
        $firsttime = true;
        foreach($lines as $line) {
        	if ($firsttime) {
        		$codes = explode(" ", $line);
        		$code['version'] = $codes[0];
        		$code['code'] = intval($codes[1]);
        		$code['message'] = $codes[2];
        		$firsttime = false;
        	}
            if(($pos = strpos($line, ':')) !== false) {
                $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
            }
        }
        
        // redirection?
        if(isset($headers['location'])) {
            $http = new HTTPRequest($headers['location']);
			return $http->Request($req, $post, $details);
        } else {
        	if ($details) {
        		$result['http'] = $code;
        		$result['header'] = $headers;
        		$result['body'] = $body;
            	return $result;
            } else {
            	return $body;
            }
        }
    }
    
    //Download URL to string, included for backwards compatibilty with versions that did have seperate GET/POST
    function DownloadToString() {
		return $this->Get(null,false);
    }
}

//Simple function to do it quickly
function get_url($url) {
	$http = new HTTPRequest($url);
	return $http->Get();
}
?>
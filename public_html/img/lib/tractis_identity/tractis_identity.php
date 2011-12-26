<?php
require_once('inc/httplib.php');

class tractis_identity 
{
    var $form_action = "https://www.tractis.com/verifications";
    var $check_url = "https://www.tractis.com/data_verification";
    var $check_params = array(
        'tractis:attribute:name',
		'tractis:attribute:dni',
		'token',
		'verification_code',
    );
    var $api_key;
    var $notification_callback;
    var $public_verification;
    var $image_button;
	var $return_method;
		
	// Constructor
	function tractis_identity($api_key, $notification_callback, $public_verification = "false", $image_button = "", $return_method = "GET")
	{
		if (!isset($api_key) || !isset($notification_callback))
		{
			return false;
		} 
		$this->api_key = $api_key;
		$this->notification_callback = $notification_callback;
		$this->public_verification = $public_verification;
		$this->image_button = $image_button;
		$this->return_method = $return_method;
	}
		
	function show_form()
	{
		$form = '<!-- Tractis Identity Verifications Connect Button -->'.
				'<form action="'.$this->form_action.'" method="post">'.
				'  <input id="api_key" name="api_key" type="hidden" value="'.$this->api_key.'" />'.
				'  <input id="notification_callback" name="notification_callback" type="hidden" value="'.$this->notification_callback.'" />'.
				'  <input id="public_verification" name="public_verification" type="hidden" value="'.$this->public_verification.'" />'.
				'  <input type="image" src="'.$this->image_button.'" name="tractis_icon">'.
				'</form>'.
				'<!-- /Tractis Identity Verifications Connect Button -->';
		return $form;
	}
    
    function check_auth() 
    {
        $params = array();
        $from_auth = true;

        foreach ($this->check_params as $getParam) {
        	$param_to_check = $this->return_method == "POST" ? $_POST[$getParam] : $_GET[$getParam];
            if (!$param_to_check) {
                $from_auth = false;
            }
            else {
                $params[$getParam] = $param_to_check;
            }
        } 
       
        if ($from_auth == true && $this->check_auth_response($params))
        {
            return $params;
        }
        else
        {
        	return false;
        }        	    		
    }
    
    function check_auth_response($params = array())
    {           
        $httpclient = new HTTPRequest($this->check_url);
        
        // Add Api key to response
        $params['api_key'] = $this->api_key;
        $res = $httpclient->Post($params, true);
		
        if ($res['http']['code'] == 200 && $res['body'] == $params['verification_code']) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function get_image_url()
    {
    	(in_array($this->image_button, $this->tractis_images)) ? $image = "./images/".$this->image_button : $image = $this->image_button; 
		
		return $image;
    }
}	  
?>
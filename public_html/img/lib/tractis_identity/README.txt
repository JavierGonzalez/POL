	Overview
------------------------------------------------------------------------------------------
This is a php class + sample webapp to illustrate how Tractis Identity Verifications work on PHP. 
 
Tractis Identity Verifications php class encapsulates the validation + auth form creation for you
to perform authentication processes based on electronic certificates using Tractis platform.
 
	Usage
------------------------------------------------------------------------------------------
 
You'll find an example implementation in /example directory.
 
The steps performed by your application are:
1. Calculate the notification callback
$notification_callback = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

2. tractis_identity object creation, initializing the Tractis api_key, notification callback, and the button used to the form

3. Check if a callback from Tractis if performed and the Authentication Response result

Then you have a user array with the user data.

To create this API_KEY you must:
	   1. Sign in at Tractis and go to http://www.tractis.com/identity_verifications.
   	   2. Generate an API Key introducing the url of the site that will call Identity Verification services
   	   2.b (Optional) A more fine grained configuration about which attributes request and verify could be performed here  

 
	License
------------------------------------------------------------------------------------------
 
	The MIT License
 
Copyright (c) 2009 Tractis
 
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
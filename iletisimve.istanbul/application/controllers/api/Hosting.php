<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Hosting extends REST_Controller {
	var $data = 	array('info'=>false,'error'=>false,'isadmin'=>false,'domains'=>array(),'unverified_domains'=>array(),'emails'=>array(),'users'=>array(),'fwd_dests'=>array());
	var $userid = '';
	var $user ='';
	var $base ='';  
		 
function __construct()
    	{
	        // Construct the parent class
	        parent::__construct();
		// Your own constructor code
	        $this->load->library('Cpanel_Api');
	        $this->load->library('InternetBs_Api');
	        $this->load->library('Aauth');
		$this->load->helper('language');
		$this->lang->load('ive',$this->session->userdata('language'));
	        // Configure limits on our controller methods
	        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
	        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
	        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
	        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
	        $this->load->helper('url');
	        
	        $func_name = $this->uri->segment(3, 0);
	        $this->base= $this->uri->segment(2, 1);
	        
	        if($func_name != 'index' && 
	           $func_name != 'enter' &&  
	           $func_name != 'teklif_al' && 
	           $func_name != 'forgot_password' && 
	           $func_name != 'reset_password' && 
	           $func_name != 'verification' &&
	           $func_name != 'tolang' &&
	           $func_name != 'lang'
	           )
	        { 
	        	if(!empty($this->input->get_request_header('token')))
		        	if($this->input->get_request_header('token')!='null')
		        		$this->aauth->login_fast($this->aauth->list_users($this->input->get_request_header('token'),1)[0]->id);
		        if(!$this->aauth->is_loggedin()){
		        	
			        $this->response( [
			         	'status' => FALSE,
			                'message' => $this->lang->line('session_ended') ], 
			                REST_Controller::HTTP_BAD_REQUEST); 
		        } else {
		        	if($this->aauth->is_admin())
	        			$this->userid=$this->session->userdata('id');
	        		
		        }
	        }
    	}

	    public function lang_get(){
	    
		$this->lang->load('ive',$this->get('lang'));
		$this->lang->load('aauth',$this->get('lang'));
		$this->lang->load('rest_controller',$this->get('lang'));
	     	$this->response($this->lang, REST_Controller::HTTP_OK);
	    }
	    public function tolang_get(){
		        
			$this->lang->load('ive',$this->get('lang'));
			$this->lang->load('aauth',$this->get('lang'));
			$this->lang->load('rest_controller',$this->get('lang'));
		        $this->response($this->lang, REST_Controller::HTTP_OK);
			
	    }
	    public function enter_post(){
	    	
		$post= json_decode(file_get_contents("php://input"));
		if(empty($post->email)){
			$this->response( [
	         	'status' => FALSE,
	                'message' => $this->lang->line('no_email')], 
	                REST_Controller::HTTP_BAD_REQUEST); 
		}
		$post_email = strtolower($post->email);
		if(!$this->aauth->user_exist_by_email($post_email)){
			$this->aauth->create_user($post_email,$post->password);	
		}
		
		$this->aauth->login($post->email, $post->password);
		if($this->aauth->is_loggedin()){
			$apiRequestUrl ="http://iletisimve.istanbul/api/key";
			$client     = new GuzzleHttp\Client();
		        $res = $client->request('PUT', $apiRequestUrl, array());
			
			if($this->aauth->is_admin()){
			/*		$this->aauth->create_perm('register');
					$this->aauth->allow_user(6,'register');
					$user_id =$this->aauth->create_user('internetbs@registrars.ive.ist','iboerbAA84');
					$this->aauth->create_group('registrars');
					$this->aauth->add_member($user_id,1);
					$this->aauth->add_member($user_id,'registrars');
					$this->aauth->allow_user(6,'register');*/
			}
			$user_groups=$this->aauth->get_user_groups($this->aauth->get_user_id());
			//print_r($user_groups);
			foreach($user_groups as $user_group){
			
				if($user_group->definition=="token"){
				       $this->db
				            ->where(config_item('rest_key_column'), $user_group->name)
				            ->delete(config_item('rest_keys_table'));
			        	$this->aauth->delete_group($user_group->group_id);
			        }
			        
			}
			$this->aauth->create_group(json_decode($res->getBody())->key,"token");
			$this->aauth->add_member($this->aauth->get_user_id(),json_decode($res->getBody())->key);
			
	        	$this->response(json_decode($res->getBody())->key, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			
		}else{
			$this->response( [
	         	'status' => FALSE,
	                'message' => $this->lang->line('login_failed')], 
	                REST_Controller::HTTP_BAD_REQUEST); 
        
         
		}
	}
	public function logout_get(){
			$user_groups=$this->aauth->get_user_groups($this->aauth->get_user_id());
			//print_r($user_groups);
			foreach($user_groups as $user_group){
				if($user_group->definition==""){
				
				       	$this->db
				            ->where(config_item('rest_key_column'), $user_group->name)
				            ->delete(config_item('rest_keys_table'));
			        	$this->aauth->delete_group($user_group->group_id);
			        }
			        
			}
		$langTemp = $this->session->userdata('language');
		$this->aauth->logout();
		$this->session->sess_regenerate();
		$this->session->set_userdata('language',$langTemp );
	        $this->response($this->lang->line('goodbye'), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		
	}
	public function teklifal_post(){
		if($this->aauth->user_exist_by_username($this->post('email')))
			$userid=$this->aauth->get_user_id($this->post('email'));
		else
			$userid=0;
		$this->aauth->send_pm($userid,2,$this->post('email'),$this->post('name').'<br>\n<br>\n'.$this->post('message'));
		
		$this->response('', REST_Controller::HTTP_OK); 
	}
	public function forgot_password_get(){
	
		if(!empty($this->input->get('email'))){
			$this->aauth->remind_password($this->input->get('email'));
			
			$this->session->set_flashdata('info',$this->input->get('email').$this->lang->line('reset_message_sent'));
			
	        	$this->response($this->input->get('email').$this->lang->line('reset_message_sent'), REST_Controller::HTTP_OK); 
		}else{
			$this->response( [
	         	'status' => FALSE,
	                'message' => $this->lang->line('enter_valid_email_address')], 
	                REST_Controller::HTTP_BAD_REQUEST); 
		}
	}
	public function reset_password_get($ver_cod){
		$this->aauth->reset_password($ver_cod);
		
		$this->response($this->lang->line('reset_password_verified'), REST_Controller::HTTP_OK); 
		
	}
	public function verification_get($userid,$ver_cod){
		$this->aauth->verify_user($userid,$ver_cod);
		
		$this->response($this->lang->line('user_verified'), REST_Controller::HTTP_OK); 
		
	}
	public function users_get(){
	        $users=[];
	        if($this->aauth->is_admin()){
	        	$groups= $this->aauth->get_user_groups();
	        	foreach($groups as $group){
	        	    if($group->id==0){
	        	        
	        	    }
	        		if( $group->id==1 || $group->id==2 || $group->id==3) // default groups (account, hosting, domain)
	        			continue;
	        		else{
	        		 	$users=array_unique(array_merge($users,$this->aauth->list_users($group->group_id)), SORT_REGULAR); // an admin could be more than one group admin

	        			}
	        	}
		        if(!$this->get('group')===NULL) // group url parametre is not null
		        	$users = $this->aauth->list_users($this->get('group')); 
		        	
		        // define which user is admin and others
		        $j=0;$i=0;
    			foreach($users as $user){
    				if($user->id == $this->aauth->get_user_id() ){ // if logged in user 
    				    if($this->aauth->is_member(0)){
    				        $user->ismaster = true;   
    				    }
    					$user->isadmin=true; // isadmin parametre set true to define admin
    				}else{
    					$user->deletable=true;
    				}
    			$i++;
    			}
		    }else
			$users=[$this->aauth->get_user()];
		
	        $id = $this->get('id');
	
	        // If the id parameter doesn't exist return all the users
	
	        if ($id === NULL)
	        {
	            // Check if the users data store contains users (in case the database result returns NULL)
	            if ($users)
	            {
	                // Set the response and exit
	                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	            }
	            else
	            {
	                // Set the response and exit
	                $this->response([
	                    'status' => FALSE,
	                    'message' => $this->lang->line('no_user')
	                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code 
	            }
	        }

	        // Find and return a single record for a particular user.
	        $id = (int) $id;
	        // Validate the id.
	        if ($id <= 0)
	        {
	            // Invalid id, set the response and exit.
	            $this->response([
	                    'status' => FALSE,
	                    'message' => $this->lang->line('no_user')
	                ], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
	        }
	
	        // Get the user from the array, using the id as key for retreival.
	        // Usually a model is to be used for this.
	
	        $user = NULL;
	        if (!empty($users))
	        {
	            foreach ($users as $value)
	            {
	                if (isset($value->id) && $value->id == $id)
	                {
	                    $user = $value;
	                }
	            }
	        }
	        if (!empty($user))
	        {
	            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	        }
	        else
	        {
	            $this->set_response([
	                'status' => FALSE,
	                'message' => 'User could not be found'
	            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
	        }
    	}
        public function add_user_post(){
    		$post= json_decode(file_get_contents("php://input"));
		$post_email = strtolower($post->email);
		
		if($this->aauth->is_admin()){
			$resp=$this->aauth->create_user($post_email ,$post->password);
			
		}
		if($resp){
			$groups= $this->aauth->get_user_groups();
	        	foreach($groups as $group){
	        		if( $group->id==2 || $group->id==3 || $group->definition=="token") // default groups and token 
	        			continue;
	        		else if($group->group_id==1){ //super admin of iletisimve.istanbul
	        			$this->aauth->add_member($resp,5); // add to group of (iletisimve.istanbul)
	        		}else{
					$this->aauth->add_member($resp,$group->group_id); // add to group of admin 
	        		}
	        	}
			$this->set_response($post_email.$this->lang->line('user_added'), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}else{
			if($this->aauth->is_banned($this->aauth->get_user_id($post_email))){
				$this->aauth->unban_user($this->aauth->get_user_id($post_email));
				$this->set_response($post_email.$this->lang->line('user_added'), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else
				$this->set_response([
		                'status' => FALSE,
		                'message' => 'User already exist'
		            ], REST_Controller::HTTP_BAD_REQUEST); // NOT_FOUND (404) being the HTTP response code
	        }
	            
	}
	public function remove_user_post(){
    		$post= json_decode(file_get_contents("php://input"));
		$post_user = strtolower($post->email);
		
		if($this->aauth->is_admin()){
			$this->aauth->ban_user($this->aauth->get_user_id($post_user));
		}
		$this->set_response($post_user.$this->lang->line('user_removed'), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	}
    	public function add_unverified_domain_post(){
    		$post= json_decode(file_get_contents("php://input"));
		$_POST['domain']=$post->domain;
		$_POST['user']=$post->user;
		//$domain=$this->first_email_check();

		$domain= strtolower($post->domain);
		
		if(array_search($domain,json_decode($this->aauth->get_user_var("unverified_domains",$this->userid)))===FALSE){
			array_push($this->data['unverified_domains'],$domain);
			$this->aauth->set_user_var("unverified_domains",json_encode($this->data['unverified_domains']),$this->userid);
		}else
			$this->set_response([
	                'status' => FALSE,
	                'message' => 'Domain already exist'
	            ], REST_Controller::HTTP_BAD_REQUEST); // NOT_FOUND (404) being the HTTP response code
	            
		$this->set_response($domain.$this->lang->line('domain_sentto_verification'), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		
	}
	public function verify_domain(){
		$post= json_decode(file_get_contents("php://input"));
		$_POST['domain']=$post->domain;
		$_POST['user']=$post->user;
		$_POST['hosting']=$post->hosting;
		$_POST['registrar']=$post->registrar;
		$domain=$this->first_email_check();
		$this->data['unverified_domains']=json_decode($this->aauth->get_user_var("unverified_domains",$this->userid));
		$is_unverified =array_search($this->input->post("domain"),$this->data['unverified_domains']);
		if($is_unverified!==FALSE)
			array_splice($this->data['unverified_domains'],$is_unverified,1); 
			//remove from unverified and new unverified array to user_vars
		$this->aauth->set_user_var("unverified_domains",json_encode($this->data['unverified_domains']),$this->userid);
		$this->data['domains']=json_decode($this->aauth->get_user_var("domains",$this->userid));
		
		$verified=false;
		foreach($this->data['domains'] as $domain)
			if($domain==$this->input->post("domain")){
				$verified=true;
				break;
				}
				
		if(!$verified){
			array_push($this->data['domains'],array(
				"name"=>$this->input->post("domain"),
				"hosting"=>$this->input->post("hosting"),
				"registrar"=>$this->input->post("registrar")
				));
			$this->aauth->set_user_var("domains",json_encode($this->data['domains']),$this->userid);
		}
		$this->set_response($this->input->post("domain").$this->lang->line('domain_verified'), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		
	
	}
	public function remove_domain_post(){
		$post= json_decode(file_get_contents("php://input"));
		$_POST['domain']=$post->domain;
		$_POST['user']=$post->user;
		//$domain=$this->first_email_check();
		$this->data['unverified_domains']=json_decode($this->aauth->get_user_var("unverified_domains",$this->userid));
		$is_unverified =array_search($this->input->post("domain"),$this->data['unverified_domains']);
		
		if($is_unverified!==FALSE)
			array_splice($this->data['unverified_domains'],$is_unverified,1);
		$this->data['domains']=json_decode($this->aauth->get_user_var('domains',$this->userid));
		foreach($this->data['domains'] as $user_domain){
			if($user_domain->name==$this->input->post("domain"))
				array_splice($this->data['domains'],array_search($user_domain,$this->data['domains']),1);
		}
		$this->aauth->set_user_var("unverified_domains",json_encode($this->data['unverified_domains']),$this->userid);
		$this->aauth->set_user_var("domains",json_encode($this->data['domains']),$this->userid);
		
		$this->set_response($this->input->post("domain").$this->lang->line('domain_removed'), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		
		
	}
	public function domains_get(){
		if($this->aauth->is_admin())
			$this->user =$this->get('user');
		else
			$this->user=$this->aauth->get_user()->email;
		if($this->user == null){
			// Invalid id, set the response and exit.
            		$this->response( [
            			'status' => FALSE,
		                'message' => $this->lang->line('user_not_found')
		                ], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            	
		}

		$this->userid= $this->aauth->get_user_id($this->user);
		if($this->userid <=0){
			$this->response([
		                'status' => FALSE,
		                'message' => $this->lang->line('user_not_found')
		            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
		
		$account =json_decode($this->aauth->get_user_var('account',$this->userid));
		if($this->aauth->is_admin() && $account!=null){
			$registrars = explode('@',$this->get('user'));
			if($account->api=='internetbs_api'){
				foreach($this->internetbs_api->execute('Domain/List',array())->domain as $domain)
				array_push($this->data['domains'],array("name"=>$domain,"registrar"=>$this->userid,"hosting"=>""));
			}
			if($account->api =='cpanel_api'){
				$this->cpanel_api->password_auth($account->username,$account->password);
				$this->cpanel_api->set_host($account->host);
				$this->cpanel_api->set_port($account->port);
				$result = json_decode($this->cpanel_api->api2_query(
					$account->username, 
					'DomainLookup', 
					'getbasedomains'
					));
				foreach($result->cpanelresult->data as $domain){
						array_push($this->data['domains'],array("name"=>$domain->domain,"hosting"=>$this->userid,"registrar"=>""));
				}	
			}
			
		}
		if(json_decode($this->aauth->get_user_var("domains",$this->userid))!=null)
			foreach(json_decode($this->aauth->get_user_var("domains",$this->userid)) as $domain){
						array_push($this->data['domains'],$domain);
				}
		
		$this->set_response($this->data['domains'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			
			
		
		//echo(json_encode($this->data['domains']));
	}
	
	public function unverified_domains_get(){
		if($this->aauth->is_admin())
			$this->user =$this->get('user');
		else
			$this->user=$this->aauth->get_user()->email;
			
		if($this->user == null){
			// Invalid id, set the response and exit.
            		$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            	
		}
		if($this->user !== false){
			$this->userid= $this->aauth->get_user_id($this->user);
			
			$this->data['unverified_domains']=json_decode($this->aauth->get_user_var("unverified_domains",$this->userid));
			$this->set_response($this->data['unverified_domains'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			
		}else{
			// Invalid id, set the response and exit.
            		$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            	
            	}
		//echo(json_encode($this->data['unverified_domains']));
	}
	
	public function emails_get(){
		$domain=$this->first_email_check();
            	$params=array(
					 'domain'          => '',
			        	 'regex'           => $this->input->get('domain'),
				);
		$this->data['emails']=$this->emails_from_hosting_api($domain,"listpops",$params);
		
		$this->set_response($this->data['emails'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		
	}
	
	public function fwd_dests_get(){
		$domain=$this->first_email_check();
		$params=array(
					 'domain'          => $this->input->get('domain'),
			        	 'regex'           => ''
				);
		$this->data['fwd_dests']=$this->emails_from_hosting_api($domain,"listforwards",$params);
		
		
		$this->set_response($this->data['fwd_dests'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	}
	
	
	public function add_email_post(){
		$post= json_decode(file_get_contents("php://input"));
		$_POST['domain']=$post->domain;
		$_POST['user']=$post->user;
		$domain=$this->first_email_check();
		$params=array(
			'email'=>$post->email,
			'password'=>$post->password,
			'domain'=>$post->domain,
			'quota'=>0
		) ;
		$this->emails_from_hosting_api($domain,"addpop",$params);
		
		$this->set_response($post->email.'@'.$this->input->post('domain').$this->lang->line('email_added'), REST_Controller::HTTP_OK); 
	}
	public function remove_email_post(){
		$post= json_decode(file_get_contents("php://input"));
		$temp = explode('@',$post->email);
		$_POST['domain']=$temp[1];
		$_POST['user']=$post->user;
		$domain=$this->first_email_check();
		$params=array(
			'email'=>$temp[0],
			'domain'=>$temp[1]
		);
		$this->emails_from_hosting_api($domain,"delpop",$params);
		
		$this->set_response($post->email.$this->lang->line('email_removed'), REST_Controller::HTTP_OK);
		
	}
	
	public function add_fwd_post(){
		$post= json_decode(file_get_contents("php://input"));
		$temp = explode('@',$post->email);
		$_POST['domain']=$temp[1];
		$_POST['user']=$post->user;
		$domain=$this->first_email_check();
		$fwds = $post->fwds;
		$params=array(
			'email'=>$post->email,
			'domain'=>$post->domain,
			'fwdopt'          => 'fwd',
			'fwdemail'        => $fwds
		);
			
		$this->emails_from_hosting_api($domain,"addforward",$params);
		
		$this->set_response($post->email.$this->lang->line('forward_added'), REST_Controller::HTTP_OK);
	}
	
	public function remove_fwd_post(){
		$post= json_decode(file_get_contents("php://input"));
		$temp = explode('@',$post->dest);
		$_POST['domain']=$temp[1];
		$_POST['user']=$post->user;
		$domain=$this->first_email_check();
		$email =$post->dest;
		$fwd = $post->to;
		$params=array(
			'email'=>$email,
			'emaildest'=>$fwd
		);
		
		
		$this->emails_from_hosting_api($domain,"delforward",$params);
		
		$this->set_response($post->dest.'->'.$post->to.$this->lang->line('forward_removed'), REST_Controller::HTTP_OK);
		
	}
	public function check_domain_get($domain){
		if($domain==null)
			$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
	
		$domain= strtolower($domain);
		
		$this->data['domains']=$this->internetbs_api->execute('Domain/Check',array('Domain'=>$domain))->status;
		$this->set_response($this->data['domains'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	}
	private function emails_from_hosting_api($domain,$function,$params){
		$ret=array();
		$hosting=json_decode($this->aauth->get_user_var('account',$domain->hosting));
		if($hosting==null)
			$this->response(array(
					'status'=>$domain,
					'message'=>$this->lang->line('no_hosting')
				),
				REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		$this->load->library($hosting->api);
		
		if($hosting->api=='cpanel_api'){
			$this->cpanel_api->set_host($hosting->host);
			$this->cpanel_api->set_port($hosting->port);
			$this->cpanel_api->password_auth($hosting->username,$hosting->password);
			
			$result = json_decode($this->cpanel_api->api2_query(
				$this->config->item('user', 'cpanel_api'), 
				"Email", 
				$function ,
				$params
			));
			foreach($result->cpanelresult->data as $email){
				array_push($ret,$email);
			}
			if(property_exists($result,'error'))
				$this->response(array(
					'status'=>FALSE,
					'message'=>$result->error
				),
				REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			
			return $ret;
			
		}
	}	
	private function first_email_check(){
		
		if($this->input->post_get('domain')==null)
			$this->response(array(
					'status'=>FALSE,
					'message'=>$this->lang->line('no_domain')
				), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		
		$intrusion=true;
		if($this->aauth->is_admin()){
			if($this->input->post_get('user')==null)
				$this->response(array(
						'status'=>FALSE,
						'message'=>$this->lang->line('no_user')
					), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			$this->userid= $this->aauth->get_user_id($this->input->post_get('user'));
		}else
			$this->userid=$this->aauth->get_user_id();
		$account =json_decode($this->aauth->get_user_var('account',$this->userid));
		if($this->aauth->is_member('registrars',$this->userid) && $account!=null)
			return (object)['hosting'=>$this->userid];
			
		foreach(json_decode($this->aauth->get_user_var("domains",$this->userid)) as $domain){
			if($domain->name ==$this->input->post_get('domain') ){
				$intrusion=false;
				return $domain;
			} 
		}
		if($intrusion){
			$this->response(array(
				'status'=>FALSE,
				'message'=>$this->lang->line('wrong_domain')
			),
			REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            	}
            	
	}

}

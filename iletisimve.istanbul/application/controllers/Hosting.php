<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hosting extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	var $data = array('info'=>false,'error'=>false,'isadmin'=>false,'domains'=>array(),'unverified_domains'=>array(),'emails'=>array(),'users'=>array());
	var $userid = '';
	var $user ='';
	var $base ='';
	public function __construct() {
	        parent::__construct();
	        // Your own constructor code
	        $this->load->library('Cpanel_Api');
	        $this->load->library('InternetBs_Api');
	        $this->load->library('Aauth');
	        $this->load->library('Session');
	        $this->load->helper('language');
		$this->lang->load('ive',$this->session->userdata('language'));
	        $this->load->helper('url');
	        
	        $func_name = $this->uri->segment(2, 0);
	        $this->base= $this->uri->segment(1, 0);
	        
	        if($func_name != 'index' && 
	           $func_name != 'enter' && 
	           $func_name != 'logout' && 
	           $func_name != 'forgot_password' && 
	           $func_name != 'reset_password' && 
	           $func_name != 'verification' &&
	           $func_name != 'tolang'
	           )
	        {
	        	if($this->aauth->is_loggedin()){
	        	
				$this->isadmin();
	        	}else{
	        		redirect($this->base);
	        	}
	        	
			if(!$this->aauth->is_admin()){
				if($func_name == 'add_user' || 
			           $func_name == 'remove_user' ||
			           $func_name == 'verify_domain' 
			           )
			        {
			         redirect($this->base);
		        	}
			}
	        }
	}
	public function index()
	{
		$this->data['error']=$this->session->flashdata('error');
		$this->data['info']=$this->session->flashdata('info');
		$this->load->view('anasayfa',$this->data);
		
	}
	public function main()
	{
		
		$this->data['error']=$this->session->flashdata('error');
		$this->data['info']=$this->session->flashdata('info');
		$this->load->view('domains',$this->data);
		
	}
	public function tolang($lang){
	        
	        $this->session->set_userdata('language',$lang);
	        
			$this->lang->load('ive',$lang);
			$this->lang->load('aauth',$lang);
		redirect($this->base.'/main');
		
	}
	public function enter(){
		
		if($this->input->post('email')==null){
			redirect($this->base);
		}
		$post_email = strtolower($this->input->post('email'));
		if(!$this->aauth->user_exist_by_email($post_email)){
			$this->aauth->create_user($post_email,$this->input->post('password'));	
		}
		
		$this->aauth->login($post_email, $this->input->post('password'));
		if($this->aauth->is_loggedin()){
			
			if($this->aauth->is_admin()){
			/*		$this->aauth->create_perm('register');
					$this->aauth->allow_user(6,'register');
					$user_id =$this->aauth->create_user('internetbs@registrars.ive.ist','iboerbAA84');
					$this->aauth->create_group('registrars');
					$this->aauth->add_member($user_id,1);
					$this->aauth->add_member($user_id,'registrars');
					$this->aauth->allow_user(6,'register');*/
			}
			
			$this->session->set_flashdata('info',$this->lang->line('welcome'));
			redirect($this->base.'/main');
			
		}else{
			$this->session->set_flashdata('error',$this->aauth->get_errors_array());
			redirect($this->base);
		}
	}
	public function logout(){
		$langTemp = $this->session->userdata('language');
		$this->aauth->logout();
		$this->session->sess_regenerate();
		$this->session->set_userdata('language',$langTemp );
		$this->session->set_flashdata('info',$this->lang->line('goodbye'));
		redirect($this->base);
		
	}
	public function add_user(){
		$post_email = strtolower($this->input->post('email'));
		
		
		$resp=$this->aauth->create_user($post_email ,$this->input->post('password'));
		
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
	        }
		
		$this->session->set_flashdata('info',$post_email.$this->lang->line('user_added'));
		redirect($this->base.'/main');
	}
	public function remove_user(){
		$post_user = strtolower($this->input->get('user'));
		
		$this->aauth->ban_user($this->aauth->get_user_id($post_user));
		
		$this->session->set_flashdata('error',$this->aauth->get_errors_array());
		$this->session->set_flashdata('info',$post_user.$this->lang->line('user_removed'));
		redirect($this->base.'/main');
		
	}
	public function change_pass(){
		$post_user = strtolower($this->input->post('user'));
		
		
		$this->aauth->update_user($this->aauth->get_user_id($post_user),$post_user,$this->input->post('password'));
		
		$this->session->set_flashdata('error',$this->aauth->get_errors_array());
		$this->session->set_flashdata('info',$post_user.$this->lang->line('pass_changed'));
		redirect($this->base.'/main');
		
	}
	public function add_domain(){
		/* without verification adding domain disabled
		$domain= strtolower($this->input->post('domain'));
		$this->isadmin();
		if(array_search($domain,$this->data['domains'])===FALSE){
			array_push($this->data['domains'],$domain);
			$this->aauth->set_user_var("domains",json_encode($this->data['domains']),$this->userid);
		}
		$this->session->set_flashdata('info',$domain.$this->lang->line('domain_added'));
		*/
		redirect($this->base.'/main');
	}
	public function add_unverified_domain(){
		$domain= strtolower($this->input->post('domain'));
		
		if(array_search($domain,$this->data['unverified_domains'])===FALSE){ // if it were not added before
			array_push($this->data['unverified_domains'],$domain);
			$this->aauth->set_user_var("unverified_domains",json_encode($this->data['unverified_domains']),$this->userid);
		}
		
		$this->session->set_flashdata('error',$this->aauth->get_errors_array());
		$this->session->set_flashdata('info',$domain.$this->lang->line('domain_sentto_verification'));
		redirect($this->base.'/main');
		
	}
	public function verify_domain(){
		if(!$this->aauth->is_member(0)){
			redirect($this->base.'/main');
			return;
		}
		$searchr=array_search($this->input->post("domain"),$this->data['unverified_domains']);
		if($searchr!==FALSE)
			array_splice($this->data['unverified_domains'],$searchr,1); 
			//remove from unverified and new unverified array to user_vars
		$this->aauth->set_user_var("unverified_domains",json_encode($this->data['unverified_domains']),$this->userid);
		
		$verified=false;
		foreach($this->data['domains'] as $domain)
			if($domain==$this->input->post("domain"))
				$verified=true;
		if(!$verified){
			array_push($this->data['domains'],array(
				"name"=>$this->input->post("domain"),
				"hosting"=>$this->input->post("hosting"),
				"registrar"=>$this->input->post("registrar")
				));
			$this->aauth->set_user_var("domains",json_encode($this->data['domains']),$this->userid);
		}
		$this->session->set_flashdata('error',$this->aauth->get_errors_array());
		$this->session->set_flashdata('info',$this->input->get("domain").$this->lang->line('domain_verified'));
		redirect($this->base.'/main');
	
	}
	public function remove_domain(){
		$searchr=array_search($this->input->get("domain"),$this->data['unverified_domains']);
		if($searchr!==FALSE)
			array_splice($this->data['unverified_domains'],$searchr,1);
		foreach($this->data['domains'] as $user_domain){
			if($user_domain->name==$this->input->get("domain"))
				array_splice($this->data['domains'],array_search($user_domain,$this->data['domains']),1);
		}
		$this->aauth->set_user_var("unverified_domains",json_encode($this->data['unverified_domains']),$this->userid);
		$this->aauth->set_user_var("domains",json_encode($this->data['domains']),$this->userid);
		
		$this->session->set_flashdata('error',$this->aauth->get_errors_array());
		$this->session->set_flashdata('info',$this->input->get("domain").$this->lang->line('domain_removed'));
		redirect($this->base.'/main');
		
	}
	public function add_email(){
		$params=array(
			'email'=>$this->input->post('email'),
			'password'=>$this->input->post('password'),
			'domain'=>$this->input->post('domain'),
			'quota'=>0
		) ;
		
		$this->email_to_hosting($this->input->post('domain'),'addpop',$params);
		

		$this->session->set_flashdata('info',$this->input->post('email').'@'.$this->input->post("domain").$this->lang->line('email_added'));
		redirect($this->base.'/main');
		
	}
	public function remove_email(){
		$temp = explode('@',$this->input->get('email'));
		$params=array(
			'email'=>$temp[0],
			'domain'=>$temp[1]
		);
		
		$this->email_to_hosting($temp[1],'delpop',$params);
		
		$this->session->set_flashdata('info',$this->input->get("email").$this->lang->line('email_removed'));
		
		redirect($this->base.'/main');
		
		
	}
	public function add_fwd(){
		$user = $this->input->post('user');
		$fwds = $this->input->post('fwds');
		$params=array(
			'email'=>$this->input->post('email'),
			'domain'=>$this->input->post('domain'),
			'fwdopt'          => 'fwd',
			'fwdemail'        => $fwds
		);
			
		
		$this->email_to_hosting($this->input->post('domain'),'addforward',$params);
		
		$this->session->set_flashdata('info',$this->input->post('email').'@'.$this->input->post("domain").$this->lang->line('forward_added'));
		
		redirect($this->base.'/main');
		
	}
	public function remove_fwd(){
		$temp = explode('@',$this->input->get('dest'));
		$email =$this->input->get('dest');
		$fwd = $this->input->get('to');
		$params=array(
			'email'=>$email,
			'emaildest'=>$fwd
		);
		
		$this->email_to_hosting($temp[1],'delforward',$params);
		
		$this->session->set_flashdata('info',$this->input->get('dest').'->'.$this->input->get("to").$this->lang->line('forward_removed'));
		
		redirect($this->base.'/main');
		
	}
	public function forgot_password(){
	
		if(!empty($this->input->get('user'))){
			$this->aauth->remind_password($this->input->get('user'));
			
			$this->session->set_flashdata('info',$this->input->get('user').$this->lang->line('reset_message_sent'));
		}else{
			
			$this->session->set_flashdata('error',array($this->lang->line('enter_valid_email_address')));
		}
		redirect($this->base);
	}
	public function reset_password($ver_cod){
		$this->aauth->reset_password($ver_cod);
		
		$this->session->set_flashdata('error',$this->aauth->get_errors_array());
		$this->session->set_flashdata('info',$this->lang->line('reset_password_verified'));
		redirect($this->base);
		
	}
	public function verification($userid,$ver_cod){
		$this->aauth->verify_user($userid,$ver_cod);
		
		$this->session->set_flashdata('error',$this->aauth->get_errors_array());
		$this->session->set_flashdata('info',$this->lang->line('user_verified'));
		redirect($this->base);
		
	}
	private function isadmin(){
		$this->user = $this->input->get_post('user');
		if($this->user !== false){
			$this->userid= $this->aauth->get_user_id($this->user);
		}
		
		if(json_decode($this->aauth->get_user_var("domains",$this->userid))==null)
			$this->data['domains']=array();
		else
			$this->data['domains']=json_decode($this->aauth->get_user_var("domains",$this->userid));
			
		if(json_decode($this->aauth->get_user_var("unverified_domains",$this->userid))==null)
			$this->data['unverified_domains']=array();
		else
			$this->data['unverified_domains']=json_decode($this->aauth->get_user_var("unverified_domains",$this->userid));
				
		if($this->aauth-> is_admin() && $this->aauth->is_member(5)){
			$this->data['users'] = $this->aauth->list_users();
			$this->data['isadmin']=true;
			
		}else{
			$this->data['isadmin']=false;
			$this->data['users'] = array($this->aauth->get_user());
		}
	}
	private function email_to_hosting($domain,$function,$params){
		$ret=array();
		$account= new stdClass();
		foreach(json_decode($this->aauth->get_user_var('domains',$this->userid)) as $user_domain){
			if($user_domain->name==$domain)
				$account=$user_domain;
		}
		$hosting=json_decode($this->aauth->get_user_var('account',$account->hosting));
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
			if(property_exists($result,'error')){
				$this->session->set_flashdata('error',$result->error);
				redirect($this->base.'/main');
			}else {
				$this->session->set_flashdata('error',false);
			}
			return $ret;
			
		}
	}
	
	
	
}

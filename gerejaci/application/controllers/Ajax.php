<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ajax extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model("Tablesmodel");
	}

	public function index(){
		echo"halo";

	}


	public function inserttable(){




		$tipe	 	= 'good';
		$messege 	= "Berhasil input database";

		$post = $this->input->post();

		// debug($post);
		//generate input table
		$table = $post['table'];
		unset($post['table']);

		//unset captcha{
			if(isset($post['captcha'])){
				unset($post['captcha']);
		}
		//cekRetype
		if(isset($post['retype'])){
			unset($post['retype']);
		}
		//format Nama
		if(isset($post['nama'])){
			$post['nama'] = formatNama($post['nama']);
		}


		//generate md5


		//generate format tanggal
		if(isset($post['tanggal'])){
			if($post['tanggal'] == "current"){
				$post['tanggal'] = date('Y-m-d');
			}
		}

		if( isset($post['redirect']) ){
			$redirect 	= $post['redirect'];
			unset($post['redirect']);
		}else{
			$redirect 	= null;
		}

		//uploadFile
		if(isset($_FILES['userfile'])){
			$path		= $post['path'];
			unset ($post['path']);
			if (!file_exists($path))
			{
			    mkdir($path, 777, true);
			}
			$config['upload_path']          = $path;
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 5012;
			$config['max_width']            = 2000;
			$config['max_height']           = 2000;
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('userfile'))
			{
			    debug($this->upload->display_errors());
			}else{
				$post['namaFile']			= $this->upload->data('file_name');
			}
		}
		//debug($post);
		//generate code aktivasi
		if(isset($post['veryCustom'])){
			if($post['veryCustom']=='emailNotifikasi'){

				$messege		='Pesan terkirim. Tunggu respon dari kami.';
				$subject		= "New Message From ".substr($post['nama'], 0, (strlen($post['nama'])>10?10:sizeof($post['nama'])));
				$pesan			= "Dear hasanisby@hotmail.com\nYou've got new message\n".date("D - d/m/Y")."\n\nSender Information\nEmail / handphone: ". $post['kontak']."\nName: ".$post['nama']."\n\n********MESSAGE********\n". $post['pesan']."\n";
				sentMail("hasanisby@hotmail.com",array("mailbox@hastasampurna.com"=>"Hasta Mailbox"),$subject,$pesan);
			}
			if($post['veryCustom']=='aktivasiEmail'){
				$messege = 'Pendaftaran berhasil. Silahkan aktivasi akun anda.';
				if(isset($post['aktivasi'])){
					$codeAktivasi 		= rand();
					$post['aktivasi'] 	= $codeAktivasi;
				}

				//sent mail
				$to 		= $post['email'];
				$subject	= "Verifikasi Pendaftaran";
				$konten		= "
					Terima kasih sudah mendaftar di Prodela
					Nikmati fitur yang disediakan oleh prodela
					Klik ".base_url()."/fungsi/aktivasi?code=$codeAktivasi untuk menggunakan fitur dan login

				";
				sentMail($to,$subject,$konten);

			}

			if($post['veryCustom'] == 'registration'){
				$pesan		='Welcome ' . $post['name'] . "\nPlease check this website on weekly";
				$subject		= "Welcome to YouthGBZ";
				$Message  = "Registration Success";
				sentMail(array($post["email"]),array("mailbox@hastasampurna.com"=>"Alert"),$subject,$pesan);
				unset($post['registrationCode']);
			}

			unset($post['veryCustom']);
		}

		//aktivasi


		//debug($post);
		$this->Tablesmodel->insert($table,$post);



		if($redirect){
			$data		= array(
				'tipe'	=> $tipe,
				'messege' => $messege
			);
			$this->session->set_flashdata($data);

			redirect($redirect);
		}
	}

	public function getwhere(){

		$post = $this->input->post();
		//debug($post);
		$table = $post['table'];
		unset($post['table']);

		$query = $this->Tablesmodel->getWhere($table,$post);

		echo json_encode($query->result());
	}


	public function getunique(){

		$post = $this->input->post();

		$table		= $post['table'];
		unset($post['table']);


		$query = $this->Tablesmodel->getWhere($table,$post);
		if($query->num_rows() == 0){
			echo "true";
		}else{
			echo "false";
		}

	}

	public function updatemultipleonecolumn(){
		$messege	= "Update berhasil";
		$post 		= $this->input->post();
		//debug($post);

		//table
		$table 		= $post['table'];
		unset($post['table']);

		$column		= $post['column'];
		unset($post['column']);

		//redirect
		if( isset($post['redirect']) ){
			$redirect 	= $post['redirect'];
			unset($post['redirect']);
		}else{
			$redirect 	= null;
		}

		//debug($post['persen'][0]);

		$this->Tablesmodel->updateMultipleOneColumn($table,$column,$post);


		if($redirect){
			$data		= array(
				'tipe'	=> 'good',
				'messege' => $messege
			);
			$this->session->set_flashdata($data);

			redirect($redirect);
		}

	}

	public function delete(){
		$messege	= "Delete data berhasil";

		$post 		= $this->input->post();
		//debug($post);
		$table 		= $post['table'];
		unset($post['table']);


		if( isset($post['redirect']) ){
			$redirect 	= $post['redirect'];
			unset($post['redirect']);
		}else{
			$redirect 	= null;
		}


		$this->Tablesmodel->delete($table,$post);




		if($redirect){
			$data		= array(
				'tipe'	=> 'good',
				'messege' => $messege
			);
			$this->session->set_flashdata($data);

			redirect($redirect);
		}
	}

	public function countrange(){
		$post		= $this->input->post();
		//debug($post);
		$query = $this->Tablesmodel->countrange($post['table'],$post['by'],$post['range'])->result();
		echo json_encode($query);
	}
	public function updaterow(){
		/*
		 * wajib
		 * 		table
		 * 		id
		 *
		 * support
		 * 		nama
		 * 		redirect
		 * 		veryCustom resetPassword
		 * 		password
		 * 		passwordRetype
		 */
		$messege	= "Update berhasil";
		$tipe		= "good";
		$post 		= $this->input->post();
		//debug($post);

		if( isset($post['redirect']) ){
			$redirect 	= $post['redirect'];
			unset($post['redirect']);
		}else{
			$redirect 	= null;
		}
		if(isset($post['name'])){
			$post['name'] = formatNama($post['nama']);
		}

		if(isset($post['passwordRetype'])){
			unset($post['passwordRetype']);
		}


		if(isset($post['veryCustom'])){
			if($post['veryCustom'] == "resetPassword"){
				$to			=	$post['to'];
				unset($post['to']);

				$dataUser	= $this->Tablesmodel->getWhere("user",array('email'=>$to))->row();
				if($dataUser){
					$post['id']	= $dataUser->id;
					$code = mt_rand();
					$post['resetPassword']	= $code;
					$messege = 'Silahkan buka email anda untuk reset';
					$konten="
Halo $dataUser->nama.
Silahkan tekan ".base_url()."lupa-password/reset?code=$code untuk reset password
					";
					sentMail($to,"Reset Password",$konten);
				}else{
					$tipe='bad';
					$messege='Email belum terdaftar';
					$data		= array(
						'tipe'	=> $tipe,
						'messege' => $messege
					);
					$this->session->set_flashdata($data);
					redirect($redirect);
				}
			}
			unset($post['veryCustom']);
		}

		$table 		= $post['table'];
		unset($post['table']);

		$id			= $post['id'];
		unset($post['id']);
		//debug($post);





		$this->Tablesmodel->updateRow($table,$id,$post);


		if($redirect){
			$data		= array(
				'tipe'	=> $tipe,
				'messege' => $messege
			);
			$this->session->set_flashdata($data);

			redirect($redirect);
		}

	}
}




?>

<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class aktifitas_tupoksi extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		
		/* Load Library */
		$this->load->library('form_validation');
		$this->load->library('session');
		
		/* Title Page :: Common */
		$this->page_title->push(lang('menu_aktifasi_tupoksi'));
		$this->data['pagetitle'] = $this->page_title->show();
		
		/* Breadcrumbs :: Common */
		$this->breadcrumbs->unshift(1, lang('menu_users'), 'admin/users');
		$this->load->helper(array('form', 'url'));
		$this->load->model ('setup/aktifitas_tupoksi_model' );
		$this->load->model ('setup/uraian_tupoksi_model');
		$this->load->model ('setup/tupoksi_model');
		$this->load->model ('setup/skpd_model');
		
	}
	
	public function validationData(){
		
		$this->form_validation->set_rules('id_aktifitas', 'lang:tupoksi_id_aktifitas','max_length[50]');
		$this->form_validation->set_rules('nama_aktifitas', 'lang:tupoksi_nama_aktifitas','max_length[50]');
		$this->form_validation->set_rules('kategori', 'lang:tupoksi_kategori','max_length[50]');
		$this->form_validation->set_rules('satuan_output', 'lang:tupoksi_satuan_output','max_length[50]');
		$this->form_validation->set_rules('lama_waktu', 'lang:tupoksi_lama_waktu','max_length[50]');
		$this->form_validation->set_rules('bobot', 'lang:tupoksi_bobot','max_length[50]');
		$this->form_validation->set_rules('aktif', 'lang:tupoksi_aktif','max_length[1]');
		
		
		return $this->form_validation->run();
	}
	
	/* Setup Property column */
	public function inputSetting($data){
		$this->data['id_tupoksi'] = array(
				'name'  => 'id_tupoksi',
				'id'    => 'id_tupoksi',
				'type'  => 'hidden',
				'class' => 'form-control',
				'readonly'=>'readonly',
				'placeholder'=>'id tupoksi',
				'value' => $data['id_tupoksi'],
		);
		$this->data['tupoksi'] = array(
				'name'  => 'tupoksi',
				'id'    => 'tupoksi',
				'type'  => 'text',
				'class' => 'form-control',
				'readonly'=>'readonly',
				'placeholder'=>'tupoksi',
				'value' => $data['tupoksi'],
		);
		$this->data['id_uraian'] = array(
				'name'  => 'id_uraian',
				'id'    => 'id_uraian',
				'type'  => 'number',
				'class' => 'form-control',
				'placeholder'=>'uraian tupoksi',
				'value' => $data['id_uraian'],
		);
		$this->data['uraian_tupoksi'] = array(
				'name'  => 'aktifasi_tupoksi',
				'id'    => 'aktifasi_tupoksi',
				'type'  => 'text',
				'class' => 'form-control',
				'readonly'=> 'readonly',
				'placeholder'=>'uraian tupoksi',
				'value' => $data['uraian_tupoksi'],
		);
		$this->data['id_aktifitas'] = array(
				'name'  => 'id_aktifitas',
				'id'    => 'id_aktifitas',
				'class' => 'form-control',
				'type'  => 'text',
				'placeholder'=>'kode aktifitas',
				'value' => $data['id_aktifitas'],
		);
		$this->data['nama_aktifitas'] = array(
				'name'  => 'nama_aktifitas',
				'id'    => 'nama_aktifitas',
				'type'  => 'number',
				'class' => 'form-control',
				'type'  => 'text',
				'placeholder'=>'nama aktifitas',
				'value' => $data['nama_aktifitas'],
		);
		$this->data['kategori'] = array(
				'name'  => 'kategori',
				'id'    => 'kategori',
				'class' => 'form-control',
				'placeholder'=>'kategori',
				'type'  => 'text',
				'value' => $data['kategori'],
		);
		$this->data['satuan_output'] = array(
				'name'  => 'satuan_output',
				'id'    => 'satuan_output',
				'class' => 'form-control',
				'type'  => 'number',
				'placeholder'=>'satuan output',
				'value' => $data['satuan_output'],
		);
		$this->data['lama_waktu'] = array(
				'name'  => 'lama_waktu',
				'id'    => 'lama_waktu',
				'class' => 'form-control',
				'type'  => 'number',
				'placeholder'=>'lama waktu',
				'value' => $data['lama_waktu'],
		);
		$this->data['tingkat_kesulitan'] = array(
				'name'  => 'tingkat_kesulitan',
				'id'    => 'tingkat_kesulitan',
				'class' => 'form-control',
				'type'  => 'number',
				'placeholder'=>'tingkat kesulitan',
				'value' => $data['tingkat_kesulitan'],
		);
		$this->data['bobot'] = array(
				'name'  => 'bobot',
				'id'    => 'bobot',
				'class' => 'form-control',
				'type'  => 'number',
				'placeholder'=>'bobot',
				'value' => $data['bobot'],
		);
		$this->data['aktif'] = array(
				'name'  => 'aktif',
				'id'    => 'aktif',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'aktif',
				'value' => $data['aktif'],
		);
		return $this->data;
	}
	
	public function index($id=null,$id2=null) {
		
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth/login', 'refresh');
		}
		else
		{
			/* Breadcrumbs */
			$this->data['breadcrumb'] = $this->breadcrumbs->show();
		
			/* Get all users */

			$config = array ();
			$config ["base_url"] = base_url () . "setup/aktifasi_tupoksi/index/".$id."/".$id2;
			$config ["total_rows"] = $this->aktifitas_tupoksi_model->record_count($id,$id2);
			$config ["per_page"] = 25;
			$config ["uri_segment"] = 4;
			$choice = $config ["total_rows"] / $config ["per_page"];
			$config ["num_links"] = 5;
			
			// config css for pagination
			$config ['full_tag_open'] = '<ul class="pagination">';
			$config ['full_tag_close'] = '</ul>';
			$config ['first_link'] = 'First';
			$config ['last_link'] = 'Last';
			$config ['first_tag_open'] = '<li>';
			$config ['first_tag_close'] = '</li>';
			$config ['prev_link'] = 'Previous';
			$config ['prev_tag_open'] = '<li class="prev">';
			$config ['prev_tag_close'] = '</li>';
			$config ['next_link'] = 'Next';
			$config ['next_tag_open'] = '<li>';
			$config ['next_tag_close'] = '</li>';
			$config ['last_tag_open'] = '<li>';
			$config ['last_tag_close'] = '</li>';
			$config ['cur_tag_open'] = '<li class="active"><a href="#">';
			$config ['cur_tag_close'] = '</a></li>';
			$config ['num_tag_open'] = '<li>';
			$config ['num_tag_close'] = '</li>';
			
			if ($this->uri->segment ( 4 ) == "") {
				$data ['number'] = 0;
			} else {
				$data ['number'] = $this->uri->segment ( 4 );
			}
			
			$this->pagination->initialize ( $config );
			$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
			
			$this->data ['aktifasi_tupoksi'] = $this->aktifitas_tupoksi_model->fetchAll($id,$id2,$config ["per_page"], $page);
			$this->data ['links'] = $this->pagination->create_links();
			
			$this->data['id_tupoksi'] = array(
					'name'  => 'id_aktifasi_tupoksi',
					'id'    => 'id_aktifasi_tupoksi',
					'type'  => 'text',
					'class' => 'form-control',
					'readonly'=>'readonly',
					'placeholder'=>'id aktifasi_tupoksi',
					'value' => $id,
			);
			$this->data['tupoksi'] = array(
					'name'  => 'tupoksi',
					'id'    => 'tupoksi',
					'type'  => 'text',
					'class' => 'form-control',
					'readonly'=>'readonly',
					'placeholder'=>'Tupoksi',
					'value' => $this->tupoksi_model->getName($id),
			);
			$this->data['id_uraian'] = array(
					'name'  => 'id_uraian',
					'id'    => 'id_uraian',
					'type'  => 'text',
					'class' => 'form-control',
					'readonly'=>'readonly',
					'placeholder'=>'id uraian tupoksi',
					'value' => $id2,
			);
			$this->data['uraian_tupoksi'] = array(
					'name'  => 'tupoksi',
					'id'    => 'tupoksi',
					'type'  => 'text',
					'class' => 'form-control',
					'readonly'=>'readonly',
					'placeholder'=>'Tupoksi',
					'value' => $this->uraian_tupoksi_model->getName($id,$id2),
			);
			
			$this->template->admin_render('setup/aktifitas_tupoksi/index', $this->data);
		}
	}
	
	public function add($id=null,$id2=null){
		if($this->input->post('submit')){
			if($this->validationData()==TRUE){
				$data = array(
						'id_tupoksi'=>$id,
						'id_uraian'=>$id2,
						'id_aktifitas'=>$this->input->post('id_aktifitas'),
						'nama_aktifitas'=>$this->input->post('nama_aktifitas'),
						'kategori'=>$this->input->post('kategori'),
						'satuan_output'=>$this->input->post('satuan_output'),
						'lama_waktu'=>$this->input->post('lama_waktu'),
						'tingkat_kesulitan'=>$this->input->post('tingkat_kesulitan'),
						'bobot'=>$this->input->post('bobot'),
						'aktif'=>$this->input->post('aktif')
				);
				if($this->user_operation_model->userSecurity($this->session->userdata('ss_user'),'aktifitas_tupoksi','add') == true ){
					$message = $this->aktifitas_tupoksi_model->create($data);
					$this->session->set_flashdata('message', $message);
					redirect('setup/aktifitas_tupoksi/index/'.$id.'/'.$id2,'refresh');
				}else{
					$this->session->set_flashdata('message',"The user can not access to perform actions.");
					redirect('setup/aktifitas_tupoksi/index/'.$id.'/'.$id2,'refresh');
					}
			}else{
				$this->session->set_flashdata('message', validation_errors());
				redirect('setup/aktifitas_tupoksi/add/'.$id.'/'.$id2, 'refresh');
			}
			
		} else {
			$data = array(
					'id_tupoksi'=>$id,
					'tupoksi'=>$this->tupoksi_model->getName($id),
					'id_uraian'=>$id2,
					'uraian_tupoksi'=>$this->uraian_tupoksi_model->getName($id,$id2),
					'id_aktifitas'=>null,
					'nama_aktifitas'=>null,
					'kategori'=>null,
					'satuan_output'=>null,
					'lama_waktu'=>null,
					'tingkat_kesulitan'=>null,
					'bobot'=>null,
					'aktif'=>null
			);
			$this->template->admin_render('setup/aktifitas_tupoksi/form',$this->inputSetting($data));
		}
	}
	
	public function modify($id=null,$id2=null,$id3=null) {
		if($this->input->post('submit')){
			if($this->validationData()==TRUE){
				$data = array(
					'id_tupoksi'=>$id,
					'id_uraian'=>$id2,
					'id_aktifitas'=>$this->input->post('id_aktifitas'),
					'nama_aktifitas'=>$this->input->post('nama_aktifitas'),
					'kategori'=>$this->input->post('kategori'),
					'satuan_output'=>$this->input->post('satuan_output'),
					'lama_waktu'=>$this->input->post('lama_waktu'),
					'tingkat_kesulitan'=>$this->input->post('tingkat_kesulitan'),
					'bobot'=>$this->input->post('bobot'),
					'aktif'=>$this->input->post('aktif')
				);
			}
			if($this->user_operation_model->userSecurity($this->session->userdata('ss_user'),'aktifitas_tupoksi','modify') == true ){
				$message = $this->aktifitas_tupoksi_model->update($data);
				$this->session->set_flashdata('message', $message);
				redirect('setup/aktifitas_tupoksi/modify/'.$id.'/'.$id2.'/'.$id3,'refresh');
			}else{
				$this->session->set_flashdata('message',"The user can not access to perform actions.");
				redirect('setup/aktifitas_tupoksi/index/'.$id.'/'.$id2,'refresh');
			}
		} else {
			$query = $this->aktifitas_tupoksi_model->fetchById($id,$id2,$id3);
			foreach ($query as $row){
				$this->template->admin_render('setup/aktifitas_tupoksi/form',$this->inputSetting($row));
			}
		}
	}
	
	public function remove($id=null,$id2=null,$id3=null) {
		if($this->user_operation_model->userSecurity($this->session->userdata('ss_user'),'aktifitas_tupoksi','delete') == true ){
			$message = $this->aktifitas_tupoksi_model->delete($id,$id2,$id3);
			$this->session->set_flashdata('message', $message);
			redirect ('setup/aktifitas_tupoksi/index/'.$id.'/'.$id2,'refresh');
		}else{
			$this->session->set_flashdata('message',"The user can not access to perform actions.");
			redirect ('setup/aktifitas_tupoksi/index/'.$id.'/'.$id2,'refresh');
		}
	}
	
	public function find(){
		
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth/login', 'refresh');
		}
		else {
			if($this->input->post('submit')){
				$column = $this->input->post('column');
				$query = $this->input->post('data');
				
				$option = array(
					'user_column'=>$column,
					'user_data'=>$query
				);
				$this->session->set_userdata($option);
			}else{
			   $column = $this->session->has_userdata('user_column');
			   $query = $this->session->has_userdata('user_data');
			}
			
			/* Breadcrumbs */
			$this->data['breadcrumb'] = $this->breadcrumbs->show();
		
			/* Get all users */
		
			$config = array ();
			$config ["base_url"] = base_url () . "setup/aktifasi_tupoksi/find";
			$config ["total_rows"] = $this->uraian_aktifitas_tupoksi_model->search_count($column,$query);
			$config ["per_page"] = 25;
			$config ["uri_segment"] = 4;
			$choice = $config ["total_rows"] / $config ["per_page"];
			$config ["num_links"] = 5;
				
			// config css for pagination
			$config ['full_tag_open'] = '<ul class="pagination">';
			$config ['full_tag_close'] = '</ul>';
			$config ['first_link'] = 'First';
			$config ['last_link'] = 'Last';
			$config ['first_tag_open'] = '<li>';
			$config ['first_tag_close'] = '</li>';
			$config ['prev_link'] = 'Previous';
			$config ['prev_tag_open'] = '<li class="prev">';
			$config ['prev_tag_close'] = '</li>';
			$config ['next_link'] = 'Next';
			$config ['next_tag_open'] = '<li>';
			$config ['next_tag_close'] = '</li>';
			$config ['last_tag_open'] = '<li>';
			$config ['last_tag_close'] = '</li>';
			$config ['cur_tag_open'] = '<li class="active"><a href="#">';
			$config ['cur_tag_close'] = '</a></li>';
			$config ['num_tag_open'] = '<li>';
			$config ['num_tag_close'] = '</li>';
				
			if ($this->uri->segment ( 4 ) == "") {
				$data ['number'] = 0;
			} else {
				$data ['number'] = $this->uri->segment ( 4 );
			}
				
			$this->pagination->initialize ( $config );
			$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
				
			$this->data ['aktifasi_tupoksi'] = $this->aktifitas_tupoksi_model->search($column,$query,$config ["per_page"], $page);
			$this->data ['links'] = $this->pagination->create_links ();
			$this->template->admin_render('setup/aktifasi_tupoksi/index', $this->data);
		}
	}
	
}
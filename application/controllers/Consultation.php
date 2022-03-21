<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultation extends CI_Controller {

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
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
    function __construct()
	{
		parent::__construct();
		$this->load->model('Consultation_Model');
	}

	public function index()
	{
		$this->load->view('consultation');
	
	}
	public function saveData(){
	$response       =  array();
	$clinicName 	= "";
    $pname 			= "";
    $pcontact 		= "";
    $fname 			= "";
    $lname 		    = "";
    $p_dob 			= "";
    $contact 		= "";
    $complaint 		= "";
    $consultation 	= "";
	$clinic_logo    = "";

	if(isset($_POST['clinicName']) && trim($_POST['clinicName']) != "") {
		$clinicName 	=  trim($_POST['clinicName']);
	}
	if(isset($_POST['pname']) && trim($_POST['pname']) != "") {
		$pname			=  trim($_POST['pname']);
	}
	if(isset($_POST['p_contact']) && trim($_POST['p_contact']) != "") {
		$pcontact		=  trim($_POST['p_contact']);
	}
	if(isset($_POST['fname']) && trim($_POST['fname']) != "") {
		$fname			=  trim($_POST['fname']);
	}
	if(isset($_POST['lname']) && trim($_POST['lname']) != "") {
		$lname		    =  trim($_POST['lname']);
	}
	if(isset($_POST['p_dob']) && trim($_POST['p_dob']) != "") {
		$p_dob			=  trim($_POST['p_dob']);
	}
	if(isset($_POST['contact']) && trim($_POST['contact']) != "") {
		$contact		= trim($_POST['contact']);
	}
	if(isset($_POST['complaint']) && trim($_POST['complaint']) != "") {
		$complaint	    =  trim($_POST['complaint']);
	}
	if(isset($_POST['consultation']) && trim($_POST['consultation']) != "") {
		$consultation	=  trim($_POST['consultation']);
	}
	$clinicID		    = isset($_POST['clinicID'])?$_POST['clinicID']:'';
	$param 				= array();
    $param['clinicName'] = isset($clinicName)?$clinicName:''; 
	$clinic_logo		 = isset($_POST['clinic_logo_hidden'])?$_POST['clinic_logo_hidden']:'';

	$clinicExists = $this->Consultation_Model->clinicNameExits($param);
	if(empty($clinicExists)){
		$addClinic = array(
			"id"=>$clinicID,
			"clinic_name"=>$clinicName	
		);
		$clinicid	=	$this->Consultation_Model->saveClinic($addClinic);
	}
	else{
		$clinicid =$clinicExists['id'];
	}
	$addPatient = array(
		"id"=>$clinicID,
		"clinic_id"=>$clinicid,
		"physician_name"=>$pname,	
		"physician_contact"=>$pcontact,
		"patient_Fname"=>$fname,	
		"patient_Lname"=>$lname,	
		"patient_Dob"=>$p_dob,	
		"patient_contact"=>$contact,	
		"chief_complaint"=>$complaint,	
		"consultaion_note"=>$consultation	
		);
		$Patientid	=	$this->Consultation_Model->savePatient($addPatient);
		$uploadPath	  =	$this->custom->getRootValue("rootpath")."/upload/comapnylogo/";
	if(sizeof($_FILES) >0){
		if($_FILES['clinic_logo']['name']){
		
				if(!file_exists($this->custom->getRootValue("rootpath")."/upload")) {
					mkdir($this->custom->getRootValue("rootpath")."/upload", 0777);
				}
	
				if(!file_exists($this->custom->getRootValue("rootpath")."/upload/comapnylogo")) {
					mkdir($this->custom->getRootValue("rootpath")."/upload/comapnylogo", 0777);
				}
	
				$uploadPath	  =	$this->custom->getRootValue("rootpath")."/upload/comapnylogo/";
				if(isset($_FILES['clinic_logo']['name']) && trim($_FILES['clinic_logo']['name']) != "" && trim($_FILES['clinic_logo']['size']) > 0 && trim($_FILES['clinic_logo']['error']) == 0) {
				
					$config['upload_path']		=	$uploadPath;
					$config['allowed_types']	=	'jpeg|jpg|png';
					$config['max_size']			=	0;
					$config['max_width']		=	0;
					$config['max_height']		=	0;
					$config['file_name']		=	$clinicid;
					$config['overwrite']		=	TRUE;
					
					$this->load->library('upload', $config);
	
					if ( ! $this->upload->do_upload('clinic_logo')) {
						
						$error = array('error' => $this->upload->display_errors());
										
					} else {
						$uploaded_data = $this->upload->data();
						$data = array('upload_data' => $uploaded_data);

						$config 				 = array();
						$config['uploaded_data'] = $uploaded_data;
						$config['upload_path']   = $uploadPath;
						$config['width'] 		 = 100;
						$config['height'] 		 = 90;
						$config['orginal_name']  = false;
						$config['crop']          = false;
						$new_file_small          = $this->custom->resize_image($config);
						$clinicLogoName	 =	trim($data['upload_data']['orig_name']);
						$clinicLogoPath	 =	$clinicid;
						
					}
				}
	
				$updateClinicImageData	=	array(
												"id"=>$clinicid,
												"clinic_logo" => $clinicLogoPath
											);

				$Upclinic_logo=$uploadPath.$clinicLogoName;
				if($this->Consultation_Model->saveClinic($updateClinicImageData)) {

					$response['status']	= "success";
				} else {
					$response['status']	= "error";
				}
	
				} else {
					$response['status']	= "error";
				}
			
		}
		$docName = 'CR_'.$lname.'_'.$fname.'_'.$p_dob.'.pdf';
		

		$param= array(
			"Clinic Name"=>$clinicName,
			"Clinic Logo"=>isset($clinic_logo)?$uploadPath.$clinic_logo:$Upclinic_logo,
			"Physician Name"=>$pname,
			"Physician Contact"=>$pcontact,
			"Patient First Name"=>$fname,
			"Patient Last Name"=>$lname,
			"Patient DOB"=>$p_dob,
			"Patient Contact"=>$contact,
			"Chief Complaint"=>strip_tags($complaint),
			"Consultation Note"=>strip_tags($consultation),
			"docName"=>$docName
			
		);
		$this->custom->DownloadPDF($param);

	}

	public function getPrevious()
	{
		$data = $this->Consultation_Model->getPrevious();
		echo json_encode($data);
	}

public function downloadpdf($id)
{
	$data = $this->Consultation_Model->getPrevious($id);
	
	$docName = 'CR_'.$data['patient_Lname'].'_'.$data['patient_Fname'].'_'.$data['patient_Dob'].'.pdf';

	$param= array(
		"Clinic Name"=>$data['clinic_name'],
		"Clinic Logo"=>base_url()."upload/comapnylogo/".$data['clinic_logo'],
		"Physician Name"=>$data['physician_name'],
		"Physician Contact"=>$data['physician_contact'],
		"Patient First Name"=>$data['patient_Fname'],
		"Patient Last Name"=>$data['patient_Lname'],
		"Patient DOB"=>$data['patient_Dob'],
		"Patient Contact"=>$data['patient_contact'],
		"Chief Complaint"=>strip_tags($data['chief_complaint']),
		"Consultation Note"=>strip_tags($data['consultaion_note']),
		"docName"=>$docName
		
	);
	$this->custom->DownloadPDF($param);

}

public function exportData()
{
	$this->custom->loadPHPExcel();

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$userData = $this->Consultation_Model->getPrevious();

	header('Content-Type: text/csv; charset=utf-8');  
	header('Content-Disposition: attachment; filename=data.csv');  
	$output = fopen("php://output", "w");  
	fputcsv($output, array('ID', 'Patient Name', 'Clinic Name', 'Physician Name', 'Physician Contact','Patient DOB','Patient Contact','Chief Complaint','Consultation Note'));  

		foreach($userData as $key => $val){ 
		$user['ID']=$val['id'];
		$user['Patient Name']=$val['patient_Fname'].$val['patient_Lname'];
		$user['Clinic Name'] =$val['clinic_name'];
		$user['Physician Name']=$val['physician_name'];
		$user['Physician Contact']=$val['physician_contact'];
		$user['Patient DOB']=$val['patient_Dob'];
		$user['Patient Contact']=$val['patient_contact'];
		$user['Chief Complaint']=strip_tags($val['chief_complaint']);
		$user['Consultation Note']=strip_tags($val['consultaion_note']);
		
		
		fputcsv($output, $user);  
	}  
	fclose($output);  



}
public function search()
{
	$term = $_GET[ "term" ];
	$clinic = $this->Consultation_Model->searchClinic($term);
	echo json_encode($clinic);
	// echo "<pre>";print_r($clinic);
}

}

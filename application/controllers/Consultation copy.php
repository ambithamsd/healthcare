<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . 'third_party/fpdf/fpdf.php';

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
        // $this->load->library(array('fpdfcustom'));

	}

	public function index()
	{
		// echo "<PRE>";print_r($_SERVER);"</PRE>";die;
		$this->load->view('consultation');
		//cho "hit";
		// require('fpdf.php');
		// downloadPdfdocument();

		
	}
	public function saveData(){

		// $clinic_logo 	= "";
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
		// echo "<pre>";print_r($_FILES);die("fbcb");

	if(isset($_POST['clinicName']) && trim($_POST['clinicName']) != "") {
		$clinicName =	trim($_POST['clinicName']);
	}
	if(isset($_POST['pname']) && trim($_POST['pname']) != "") {
		$pname	=	trim($_POST['pname']);
	}
	if(isset($_POST['p_contact']) && trim($_POST['p_contact']) != "") {
		$pcontact	=	trim($_POST['p_contact']);
	}
	if(isset($_POST['fname']) && trim($_POST['fname']) != "") {
		$fname	=	trim($_POST['fname']);
	}
	if(isset($_POST['lname']) && trim($_POST['lname']) != "") {
		$lname	=	trim($_POST['lname']);
	}
	if(isset($_POST['p_dob']) && trim($_POST['p_dob']) != "") {
		$p_dob	=	trim($_POST['p_dob']);
	}
	if(isset($_POST['contact']) && trim($_POST['contact']) != "") {
		$contact	=	trim($_POST['contact']);
	}
	if(isset($_POST['complaint']) && trim($_POST['complaint']) != "") {
		$complaint	=	trim($_POST['complaint']);
	}
	if(isset($_POST['consultation']) && trim($_POST['consultation']) != "") {
		$consultation	=	trim($_POST['consultation']);
	}
	$clinicID		= isset($_POST['clinicID'])?$_POST['clinicID']:'';

	$param = array();
	// $param['clinic_id']  = isset($companyID)?$companyID:''; 
    $param['clinicName'] = isset($clinicName)?$clinicName:''; 
	$clinicExists = $this->Consultation_Model->clinicNameExits($param);
	if(empty($clinicExists)){

		$addClinic = array(
			"id"=>$clinicID,
			"clinic_name"=>$clinicName	
		);
		//  echo "<PRE>";print_r($addCompany);"</PRE>";die;
		$clinicid	=	$this->Consultation_Model->saveClinic($addClinic);
	}
	else{
		$clinicid =$clinicExists['id'];
	}
		// $addPhysician = array(
		// 	"id"=>$clinicID,
		// 	"physician_name"=>$pname,	
		// 	"physician_contact"=>$pcontact	
		// 	);
		// 	//  echo "<PRE>";print_r($addCompany);"</PRE>";die;
		// 	$Physicianid	=	$this->Consultation_Model->savePhysician($addPhysician);

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
				//  echo "<PRE>";print_r($addCompany);"</PRE>";die;
				$Patientid	=	$this->Consultation_Model->savePatient($addPatient);
		

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
					// $config = array();
					// $config['uploaded_data'] = $uploaded_data;
	
					if ( ! $this->upload->do_upload('clinic_logo')) {
						
						$error = array('error' => $this->upload->display_errors());
										
					} else {
						$uploaded_data = $this->upload->data();
						$data = array('upload_data' => $uploaded_data);

						$config = array();
						$config['uploaded_data'] = $uploaded_data;
						$config['upload_path']   = $uploadPath;


						$config['width'] = 100;
						$config['height'] = 90;
						$config['orginal_name'] = false;
						$config['crop'] = false;
						// $imgData = $this->resize_image($image, 100, 90);
						// $new_file_small = $this->custom->crop_image($config);
						$new_file_small = $this->custom->resize_image($config);




						$clinicLogoName	=	trim($data['upload_data']['orig_name']);
						$clinicLogoPath	=	$clinicLogoName;
						// uploadToS3($lectureFilePath, $s3UploadPath.$lectureFileName, null,TRUE);
						
					}
				}
	
				$updateClinicImageData	=	array(
												"id"=>$clinicid,
												"clinic_logo" => $clinicLogoPath
											);

				if($this->Consultation_Model->saveClinic($updateClinicImageData)) {
					$response['status']	= "success";
					// $response['image']	= $comapnyLogoPath;
				} else {
					$response['status']	= "error";
				}
	
				} else {
					$response['status']	= "error";
				}
			
		}
$docName = 'CR_'.$lname.'_'.$fname.'_'.$p_dob.'pdf';

$param= array(
	"Clinic Name"=>$clinicName,
	"Clinic Logo"=>$uploadPath.$clinicLogoName,
	"Physician Name"=>$pname,
	"Physician Contact"=>$pcontact,
	"Patient First Name"=>$fname,
	"Patient Last Name"=>$lname,
	"Patient DOB"=>$p_dob,
	"Patient Contact"=>$contact,
	"Chief Complaint"=>$complaint,
	"Consultation Note"=>$consultation,
	"docName"=>$docName
	
);
	$this->custom->DownloadPDF($param);

	}

public function getPrevious()
{
	$data = $this->Consultation_Model->getPrevious();
	// echo $this->db->last_query();
	echo json_encode($data);
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


	
	// $headstyleArray	=	array(
	// 						'font'  => array(
	// 											'bold'  => true,
	// 											'color' => array('rgb' => 'FFFFFF'),
	// 											'size'  => 10,
	// 											'name'  => 'Verdana'
	// 										),
	// 						'fill' => array(
	// 											'type' => PHPExcel_Style_Fill::FILL_SOLID,
	// 											'color' => array('rgb' => '4594e3')
	// 										)
	// 						);

	// $styleArray	=	array(
	// 					'font' => array(
	// 										'bold'  => false,
	// 										'color' => array('rgb' => '000000'),
	// 										'size'  => 9,
	// 										'name'  => 'Verdana'
	// 									),
	// 					'borders' => array(
	// 										'allborders' => array(
	// 																'style' => PHPExcel_Style_Border::BORDER_THIN,
	// 																'color' => array('rgb' => '000000')
	// 															)
	// 									)
	// 				);
				


	// $objPHPExcel->getActiveSheet()->setTitle('Patient Details');
	// $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sl.No');
	// $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Patient Name');
	// $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Clinic Name');
	// $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Physician Name');
	// $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Physician Contact');
	// $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Patient DOB');
	// $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Patient Contact');
	// $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Chief Complaint');
	// $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Consultation Note');

	// $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($headstyleArray);
	// $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	// $objPHPExcel->getActiveSheet()->getStyle('D1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	// // $objPHPExcel->getActiveSheet()->getStyle('M1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	// $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

	// $rowNumber = 2;
	// 		$i	=	1;


	// 		for($s=0; $s<count($userData); $s++) {

	// 			$ClinicName	       	=	stripslashes(trim($userData[$s]['clinic_name']));
	// 			$PhysicianName	    =	stripslashes(trim($userData[$s]['physician_name']));
	// 			$PhysicianContact	=	stripslashes(trim($userData[$s]['physician_contact']));
	// 			$PatientName   		=   stripslashes(trim($userData[$s]['patient_Fname']))." ".stripslashes(trim($userData[$s]['patient_Lname']));
	// 			$PatientDOB   		=   stripslashes(trim($userData[$s]['patient_Dob']));
	// 			$PatientContact   	=   stripslashes(trim($userData[$s]['patient_contact']));
	// 			$ChiefComplaint   	=   stripslashes(trim($userData[$s]['chief_complaint']));
	// 			$ConsultationNote   =   stripslashes(trim($userData[$s]['consultaion_note']));

	// 			$objPHPExcel->getActiveSheet()->setCellValue('A'. $rowNumber , $i);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('B'. $rowNumber, $PatientName);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('C'. $rowNumber, $ClinicName);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('D'. $rowNumber, $PhysicianName);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('E'. $rowNumber, $PhysicianContact);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('F'. $rowNumber, $PatientDOB);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('G'. $rowNumber, $PatientContact);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('H'. $rowNumber,$ChiefComplaint);
	// 			$objPHPExcel->getActiveSheet()->setCellValue('I'. $rowNumber, $ConsultationNote);

	// 			// $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowNumber, $mobileNo, PHPExcel_Cell_DataType::TYPE_STRING);
	// 			$rowNumber++;
	// 			$i++;
	// 		}
	// 			$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	// 			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);



	// header('Content-Type: application/csv');
	// header('Content-Disposition: attachment;filename="record_list.csv"');
	// header('Cache-Control: max-age=0');
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	// $objWriter->save('php://output');


}

}

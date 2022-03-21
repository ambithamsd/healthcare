<?php
/**
* 
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Consultation_Model extends CI_Model
{


	public function clinicNameExits($param)
	{
		$company_id  = isset($param['clinic_id'])?$param['clinic_id']:''; 
        $companyName = isset($param['clinicName'])?$param['clinicName']:'';
        $this->db->select("id,clinic_name");
        $this->db->where('clinic_name',$companyName);
        $result   = $this->db->get('clinic')->row_array();
        return $result;
	}

	public function saveClinic($data)
	{
		if(!empty($data['id'])){
            
            $this->db->where("id", $data['id']);
            $this->db->update("clinic", $data);
              return $data['id'];
          } if(empty($data['id'])){
             $this->db->insert("clinic", $data);  
                     
             return $this->db->insert_id();         
         }
	}

	public function savePatient($data)
	{
		if(!empty($data['id'])){
            
            $this->db->where("id", $data['id']);
            $this->db->update("patient", $data);
              return $data['id'];
          } if(empty($data['id'])){
             $this->db->insert("patient", $data);  
                     
             return $this->db->insert_id();         
         }
	}
	public function getPrevious($id=false)
	{
		if($id != false){
            $this->db->where("patient.id", $id);  
        }
		$this->db->select('patient.id, `physician_name`, `physician_contact`, `patient_Fname`, `patient_Lname`, `patient_Dob`, `patient_contact`, `chief_complaint`, `consultaion_note`, clinic.clinic_name, clinic.clinic_logo');
		$this->db->join('clinic','clinic.id=patient.clinic_id','inner');
		if($id){
			$result= $this->db->get('patient')->row_array();
		}else{

			$result= $this->db->get('patient')->result_array();
		}
		return $result;
	}

	public function searchClinic($term)
	{
		$this->db->select('clinic.id,clinic.clinic_name as value,clinic.clinic_name as label,clinic.clinic_logo');
		$this->db->like('clinic.clinic_name',$term);
		$result= $this->db->get('clinic')->result_array();
		return $result;
	}
}

?>

<html>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url();?>assets/css/redactor.css" >
   <link rel="stylesheet" href="<?=base_url();?>assets/css/custom.css"/>
	<style>
		#ui-datepicker-div{
			z-index: 999 !important;
		}
		.dataTableLayout {
    table-layout:fixed;
    width:100%;
}
.modal-header {
	display: initial !important;

}
	</style>
   <!-- Material form subscription -->
   
   <body>
      <section>
            <div class="account-details-page-top"></div>

            <div class="account-details-header-blk">
               <div class="container"><div class="account-details-header">Cousultation Details</div></div>
            </div>

            <div class="account-details-blk">
               <div class="container">
                  <form style="color: #757575;" method="POST" id="accountForm" action="<?=base_url()?>consultation/saveData" enctype="multipart/form-data" class="col-md-9 m-auto">
                     <div class="form-group align-baseline row">
                        <div class="col-sm-11" id="error"></div>
                     </div>
                     <div class="form-group align-baseline row">
                        <div class="col-sm-5">  <label for="clinicName">Clinic Name</label> </div>
                        <div class="col-md-7"> 
                           <span class="errorMsg" id="clinic_name_error"> </span>
                           <input type="text" id="clinicName" name="clinicName"  class="form-control"/>
                        </div>
                     </div>
					 <div class=" form-group align-baseline row">
					 <div class="col-sm-5"><label class="custom-form-label initial">Clinic Logo</label></div>
						<div class="col-md-7">
						<span class="errorMsg" id="clinic_logo_error"> </span>
							<label class="portfolio-upload-holder">
							<input class="multisteps-form__input" id="clinic_logo" name="clinic_logo" onchange="loadImageFile();" type="file"/>
							<input type="hidden" id="clinic_logo_hidden" name="clinic_logo_hidden" />
							<img class="uploaded-logo" id="uploaded-logo" src="/assets/images/logo_new.png" style="display: none">
							</label>
						</div>
					</div>

                     <div class="form-group align-baseline row">
                        <div class="col-sm-5">  <label for="name">Physician Name</label></div>
                        <div class="col-md-7"> 
                           <span class="errorMsg" id="pname_error"></span>
                           <input type="text" id="pname" name="pname"  class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group align-baseline row">
                        <div class="col-sm-5">  <label for="contact">Physician Contact</label></div>
                        <div class="col-md-7"> 
                           <span class="errorMsg" id="pcontact_error"></span>
                           <input type="text" id="pcontact" name="p_contact"  class="form-control"/>
                        </div>
                     </div>
					 <div class="form-group align-baseline row">
                        <div class="col-sm-5">  <label for="fname">Patient First Name</label></div>
                        <div class="col-md-7"> 
                           <span class="errorMsg" id="fname_error"></span>
                           <input type="text" id="fname" name="fname"  class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group align-baseline row">
                        <div class="col-sm-5">  <label for="lname">Patient Last Name</label></div>
                        <div class="col-md-7"> 
                           <span class="errorMsg" id="lname_error"></span>
                           <input type="text" id="lname" name="lname"  class="form-control"/>
                        </div>
                     </div>


                     <div class="form-group align-baseline row">
                        <div class="col-sm-5"> <label for="dob">Patient DOB</label></div>
                        <div class="col-md-7">
                           <span class="errorMsg" id="dob_error"></span>
                           <input type="text" id="p_dob" name="p_dob"  class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group align-baseline row">
                        <div class="col-sm-5">  <label for="contact">Patient Contact</label></div>
                        <div class="col-md-7">
                           <span class="errorMsg" id="contact_error"> </span>
                           <input type="text" id="contact"  name="contact" class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group align-baseline row">
                        <div class="col-sm-5"> <label for="complaint">Chief Complaint</label></div>
                        <div class="col-md-7">
                           <span class="errorMsg" id="complaint_error"> </span>
						   <textarea id="complaint" name="complaint" class="form-control"></textarea>
                        </div>
                     </div>
                     <div class="form-group align-baseline row">
                        <div class="col-sm-5">  <label for="consultation">Consultation Note</label></div>
                        <div class="col-md-7">
                           <span class="errorMsg" id="consultation_error"></span>
						   <textarea id="consultation" name="consultation" class="form-control"></textarea>
                        </div>
                     </div>
                     
                     <div class="account-chart-btn-blk text-center col-6 offset-2">
                        <button class="btn btn-rounded account-chart-btn" id="submitAccount"  type="submit">Generate Report</button>
                     </div>
                  </form>
                  

                  <div class="text-center col-5 offset-3" id="downloadBtn">
                     <button class="btn btn-rounded download-chart-btn" id="print-btn"   class="btn btn-primary btn-lg">View Previous Counsultation </button>
                  </div>

                
               </div>
            </div>


			<!-- <a data-toggle="modal" href="#myModal" class="btn btn-primary btn-lg">Launch demo modal</a> -->
			
			<!-- List Modal -->
			<div id="consultationModel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
						<div class="modal-dialog modal-lg">
								<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h3 class="modal-title ">ConsultationList</h3>
							
							</div>
							<div class="modal-body">
								<button class="btn btn-primary export_csv"  id="export_csv" >Export CSV</button>
								<table id="consultationTable"  class="table table-striped table-bordered" style="width:100%">
									</table>
								</div>
							
							</div>
					</div>
		</div>
		<!-- Material form subscription -->
		</section>


</body>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
	<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script> 
   <script src="<?=base_url();?>assets/js/redactor.js" ></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
   <script src="<?=base_url();?>assets/js/main.js"></script>


	<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
   <script></script>
   <script>
		var __siteUrl= '<?php echo site_url() ?>';
    $R('#complaint');
    $R('#consultation');
	 $('#p_dob').datepicker({ dateFormat: 'dd-mm-yy' });
	 $('#myModal').on('shown.bs.modal', function () {
    $(this).find('.modal-dialog').css({width:'auto',
                               height:'auto', 
                              'max-height':'100%'});
});



$(document).ready(function() {
    $('#example').DataTable();
} );

    </script>
</html>

var __resultArray = [];
var fileReader = new FileReader();
var filterType = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

fileReader.onload = function (event) {
    var image = new Image();
    image.onload = function () {
        //   document.getElementById("original-Img").src=image.src;
        var canvas = document.createElement("canvas");
        var context = canvas.getContext("2d");
        canvas.width = 91;
        canvas.height = 83;
        context.drawImage(image,
            0,
            0,
            image.width,
            image.height,
            0,
            0,
            canvas.width,
            canvas.height
        );

        document.getElementById("uploaded-logo").src = canvas.toDataURL();
		// $("#clinic_logo_hidden").val(canvas.toDataURL());
        $("#uploaded-logo").css("display", "block");
    }
    image.src = event.target.result;
};

function loadImageFile() {
    var uploadImage = document.getElementById("clinic_logo");

    //check and retuns the length of uploded file.
    if (uploadImage.files.length === 0) {
        return;
    }

    //Is Used for validate a valid file.
    var uploadFile = document.getElementById("clinic_logo").files[0];
    if (!filterType.test(uploadFile.type)) {
        alert("Please select a valid image.");
        return;
    }

    fileReader.readAsDataURL(uploadFile);

}
$(function() {
$( "#clinicName" ).autocomplete({
	source: __siteUrl+"Consultation/search",
	minLength: 2,
	select: function( event, ui ) {
		console.log(ui.item.clinic_logo);
		if(ui.item.clinic_logo != ""){
			$("#clinic_logo_hidden").val(ui.item.clinic_logo);
			document.getElementById("uploaded-logo").src = __siteUrl+"upload/comapnylogo/"+ui.item.id+"_100x90.png";
			$("#uploaded-logo").css("display", "block");
		}

	}
 });
});







$(document).on('click', '#submitAccount', function (e) {
    e.preventDefault();
    if (validation() == true) {
		$("#accountForm").submit();
		$('#accountForm').trigger("reset");
		$("#clinic_logo").val("");
		$("#uploaded-logo").attr("src","")
		$("#clinic_logo_hidden").val("");
		$('.redactor-in p').html("");

    }


})

function validation() {
    $(".errorMsg").html('');
    var errorCount = 0;
    var errorMessage = '';
    var validationSucess = true;
	var phoneno = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
    var ClinicName = $('#clinicName').val();
    var clinic_logo = $("#clinic_logo").prop('files')[0];
    var clinic_logo_val = $("#clinic_logo_hidden").val();
    var pname = $('#pname').val();
    var pcontact = $('#pcontact').val();
    var fname = $('#fname').val();
    var lname = $('#lname').val();
    var p_dob = $('#p_dob').val();
    var contact = $('#contact').val();
    var complaint = $('#complaint').val();
    var consultation = $('#consultation').val();
    if (ClinicName == '') {
        errorCount++;
        $("#clinic_name_error").html('Please enter Clinic Name');
    }
	if (clinic_logo == undefined && clinic_logo_val == undefined) {
		errorCount++;
		$("#clinic_logo_error").html('Image  is required');
	}


    if (pname == '') {
        errorCount++;
        $("#pname_error").html('Please enter Physician Name');
    } if (pcontact == '') {
        errorCount++;
        $("#pcontact_error").html('Please enter  Physician  conatct');
    }
	if (phoneno.test(pcontact) == false) {
        errorCount++;
        $("#pcontact_error").html('Please enter valid conatct');
    }
    if (fname == '') {
        errorCount++;
        $("#fname_error").html('Please enter Patient first  name');
    }
    if (lname == '') {
        errorCount++;
        $("#lname_error").html('Please enter Patient last  name');
    } if (p_dob == '') {
        errorCount++;
        $("#dob_error").html('Please enter Patient dob');
    } if (contact == '') {
        errorCount++;
        $("#contact_error").html('Please enter Patient contact');
    }
	if (phoneno.test(contact) == false) {
        errorCount++;
        $("#contact_error").html('Please enter valid conatct');
    }
	if (complaint == '') {
        errorCount++;
        $("#complaint_error").html('Please enter chief complaint');
    } if (consultation == '') {
        errorCount++;
        $("#consultation_error").html('Please enter counslutation note ');
    }

    if (errorCount > 0) {
        //  $('#error').prepend(errorMessage);
        validationSucess = false;
    }
    return validationSucess;
}





function calculatePDF_height_width(selector, index) {
    page_section = $(selector).eq(index);
    HTML_Width = page_section.width();
    HTML_Height = page_section.height();
    top_left_margin = 15;
    PDF_Width = HTML_Width + (top_left_margin * 2);
    PDF_Height = (PDF_Width * 1.2) + (top_left_margin * 2);
    canvas_image_width = HTML_Width;
    canvas_image_height = HTML_Height;
}

$("#print-btn").on('click',function(){
	Certificate.getConsultation();
});

$(document).on("click", ".downloadCertificate", function(){

    var userID 			=	$(this).data('userid');

    Certificate.downloadPdf(userID);

});
$(document).on("click", ".export_csv", function(){
	window.open( __siteUrl+"Consultation/exportData", "_blank");

});

var Certificate = {

getConsultation: ()=>{
	
		$.ajax({
		url: __siteUrl + "Consultation/getPrevious",
		type: "POST",
		success: function(response) {
			try{
				if(response != "" && response != "undefined" && typeof response != "undefined") {
					var result	=	JSON.parse(response);
					if(result.length < 0){
						$("#export_csv").prop( "disabled", true );
					}
					
					// if(result.status == 'success'){
						var userList = result;

						this.certificateCount	=	1;
						var userId = '';
						this.certificateResult += "<thead><tr><th>SNo</th><th>Clinic Name</th><th>Physician Name</th><th>Physician Contact</th><th>Patient Name</th><th>Patient DOB</th><th>Patient Contact</th><th>Action</th></tr></thead><tbody>";

						for(var i=0; i<userList.length; i++) {
							userId		           	=	userList[i]['id'];
							var ClinicName	       	=	userList[i]['clinic_name'];
							var PhysicianName	    =	userList[i]['physician_name'];
							var PhysicianContact	=	userList[i]['physician_contact'];
							var PatientName   		=  userList[i]['patient_Fname']+" " +userList[i]['patient_Lname'];
							var PatientDOB   		=   userList[i]['patient_Dob'];
							var PatientContact   	=   userList[i]['patient_contact'];

							this.certificateResult	+=	"<tr><td>"+this.certificateCount+"</td><td>"+ClinicName+"</td><td>"
							+PhysicianName+"</td><td>"+ PhysicianContact+"</td><td>"+PatientName+"</td><td>"+ PatientDOB+"</td><td>"+PatientContact+"</td><td data-id='' > <a href='#'  id='certificate_"+userId+"' data-userId='"+userId+"' data-certificate='' data-certificatepath='' data-process='downloadCertificate' class='downloadCertificate'>Download </a> </td></tr>";
							this.certificateCount++;
						}
						this.certificateResult	+=	"</tbody>";
						$("#consultationModel").modal("show");
						$("#consultationTable").html(this.certificateResult);
						$('#consultationTable').DataTable( {
							destroy: true,
							// "pagingType": "full_numbers"
							"columnDefs": [ {
										"searchable": false,
										"orderable": false,
										"targets": "no-sort"
									} ],
									"bPaginate": true,
									"bLengthChange": false,
									oLanguage: {
									sLengthMenu: "_MENU_",
									},
									"bFilter": true,
									"bSort": true,
									"bInfo": true,
									"bAutoWidth": true
						} );

					// } else if(result.status == "norecords") {
					// 	common.showCustomAlert('No users available', "error");
					// }
				}else{
					common.showCustomAlert('Something Went Worng. Please try again', "error");
				}
			} catch(e) {
				console.log(e.message);
			}
		},
		error: function() {
			$(".overlay").hide();
			console.log("Error fetching groups");
		}
});
},
downloadPdf: (userID)=>{
	window.open( 
		__siteUrl+"Consultation/downloadpdf/"+userID, "_blank");
}


}


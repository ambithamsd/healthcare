<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Fpdfcustom.php';
class Custom
{
    var $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
       // echo 'here auth---';
        
    }

   
    public function responseHandler($data = array(), $header = "") {

		if($header == 1) {
			header("Content-Type: application/json", true);
		}
		echo json_encode($data, true);
		exit;

	}
	public function getRootValue($type)
    {
        if($type == "rootpath") {
			if($_SERVER['HTTP_HOST'] == "localhost") {
				$returnValue	=	$_SERVER['DOCUMENT_ROOT']."/healthreport";
			} else {

				$returnValue	=	$_SERVER['DOCUMENT_ROOT'];

			}
		} else if($type == "rooturl") {
			if($_SERVER['HTTP_HOST'] == "localhost") {
				$returnValue	=	"http://".$_SERVER['HTTP_HOST']."/healthreport";
			} else {
				$returnValue	=	"https://".$_SERVER['HTTP_HOST'];
			}
		}

		return $returnValue; 
        
	}
	public function paginationconfig($perpage = "",$data = "") {
		$this->CI->load->library("pagination");
        $last_page = 1;
        if($data != ""){
            $total_companies = $data['total_companies'];
            $per_page        = 10;
            $last_page       = ceil($total_companies/$per_page);
        }

		$config	=	array();
		$config["base_url"]		=	"#";
		$config["uri_segement"]	=	2;
		$config["use_page_numbers"]	=	TRUE; 
		$config['first_link'] = FALSE;
		$config['last_link'] = FALSE;
		// $config["first_tag_open"]	=	"<li class=' pagination-nav '>";
		// $config["first_tag_close"]	=	"</li>";
		// $config["last_tag_open"]	=	"<li class=' pagination-nav '>";
		// $config["last_tag_close"]	=	"</li>";
		$config["next_link"]		=	"<span class='arrow-label'>Next</span><span class='rht-arrow-icon'><svg enable-background='new 0 0 96 96' height='96px' id='arrow_right' version='1.1' viewBox='0 0 96 96' width='96px' xml:space='preserve' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><path d='M12,52h62.344L52.888,73.456c-1.562,1.562-1.562,4.095-0.001,5.656c1.562,1.562,4.096,1.562,5.658,0l28.283-28.284l0,0  c0.186-0.186,0.352-0.391,0.498-0.609c0.067-0.101,0.114-0.21,0.172-0.315c0.066-0.124,0.142-0.242,0.195-0.373  c0.057-0.135,0.089-0.275,0.129-0.415c0.033-0.111,0.076-0.217,0.099-0.331C87.973,48.525,88,48.263,88,48l0,0  c0-0.003-0.001-0.006-0.001-0.009c-0.001-0.259-0.027-0.519-0.078-0.774c-0.024-0.12-0.069-0.231-0.104-0.349  c-0.039-0.133-0.069-0.268-0.123-0.397c-0.058-0.139-0.136-0.265-0.208-0.396c-0.054-0.098-0.097-0.198-0.159-0.292  c-0.146-0.221-0.314-0.427-0.501-0.614L58.544,16.888c-1.562-1.562-4.095-1.562-5.657-0.001c-1.562,1.562-1.562,4.095,0,5.658  L74.343,44L12,44c-2.209,0-4,1.791-4,4C8,50.209,9.791,52,12,52z'/></svg></span><li class='pagination-nav '><a href='#' data-ci-pagination-page='".$last_page."' rel='start' id='last_page_div'>Last</a></li>";
		$config["next_tag_open"]	=	"<li class='pagination-nav '>";
		$config["next_tag_close"]	=	"</li>";
		$config["prev_link"]		=	"<span class='lft-arrow-icon'><svg enable-background='new 0 0 96 96' height='96px' id='arrow_left' version='1.1' viewBox='0 0 96 96' width='96px' xml:space='preserve' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><path d='M84,44H21.656l21.456-21.456c1.562-1.562,1.562-4.095,0.001-5.657c-1.562-1.562-4.096-1.562-5.658,0L9.172,45.171l0,0  c-0.186,0.186-0.352,0.391-0.498,0.61c-0.067,0.101-0.114,0.21-0.171,0.315c-0.067,0.124-0.142,0.242-0.196,0.373  c-0.056,0.135-0.088,0.276-0.129,0.416c-0.032,0.111-0.075,0.217-0.098,0.331C8.028,47.475,8,47.737,8,48l0,0  c0,0.003,0.001,0.005,0.001,0.008c0,0.259,0.027,0.519,0.078,0.774c0.024,0.121,0.069,0.232,0.104,0.349  c0.039,0.133,0.07,0.268,0.123,0.397c0.058,0.139,0.136,0.265,0.208,0.396c0.054,0.098,0.096,0.198,0.159,0.292  c0.147,0.221,0.314,0.427,0.501,0.614l28.282,28.281c1.562,1.562,4.095,1.562,5.657,0.001c1.562-1.562,1.562-4.096,0-5.658  L21.657,52H84c2.209,0,4-1.791,4-4S86.209,44,84,44z'/></svg></span><span class='arrow-label'>Prev</span>";
		$config["prev_tag_open"]	=	"<li class='pagination-nav '><a href='#' data-ci-pagination-page='1' rel='start'>First</a></li><li class='pagination-nav '>";
		$config["prev_tag_close"]	=	"</li>";
		$config["cur_tag_open"]		=	"<li class='pagination-nav  active'><a class='' href='javascript:;'>";
		$config["cur_tag_close"]	=	"</a></li>";
		$config["num_tag_open"]		=	"<li class='pagination-nav '>";
		$config["num_tag_close"]	=	"</li>";
		$config["num_links"]		=	2;
		$config["per_page"]			=	$perpage;

		return $config;

	}
	public function crop_image($config)
    {


		// echo "<PRE>"; print_r($config);die;
        $uploaded_data = isset($config['uploaded_data']) ? $config['uploaded_data'] : array();
        $width = isset($config['width']) ? $config['width'] : 360;
        $height = isset($config['height']) ? $config['height'] : 160;
        $orginal_name = isset($config['orginal_name']) ? $config['orginal_name'] : false;

        $source_path = $uploaded_data['full_path'];

        $DESIRED_IMAGE_WIDTH = $width;
        $DESIRED_IMAGE_HEIGHT = $height;
        /*
         * Add file validation code here
         */

        list($source_width, $source_height, $source_type) = getimagesize($source_path);

        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $DESIRED_IMAGE_WIDTH / $DESIRED_IMAGE_HEIGHT;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $DESIRED_IMAGE_HEIGHT;
            $temp_width = ( int )($DESIRED_IMAGE_HEIGHT * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $DESIRED_IMAGE_WIDTH;
            $temp_height = ( int )($DESIRED_IMAGE_WIDTH / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $temp_gdim,
            $source_gdim,
            0,
            0,
            0,
            0,
            $temp_width,
            $temp_height,
            $source_width,
            $source_height
        );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $DESIRED_IMAGE_WIDTH) / 2;
        $y0 = ($temp_height - $DESIRED_IMAGE_HEIGHT) / 2;
        $desired_gdim = imagecreatetruecolor($DESIRED_IMAGE_WIDTH, $DESIRED_IMAGE_HEIGHT);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0,
            0,
            $x0,
            $y0,
            $DESIRED_IMAGE_WIDTH,
            $DESIRED_IMAGE_HEIGHT
        );

        /*
         * Render the image
         * Alternatively, you can save the image in file-system or database
         */

        //header('Content-type: image/jpeg');
        $directory = $config['upload_path'];
        $this->make_directory($directory);
        if ($orginal_name) {
            $final_file = $uploaded_data['raw_name'] . '.jpg';
        } else {
            $final_file = $uploaded_data['raw_name'] . '_' . $width . 'x' . $height . '.jpg';
        }
        imagejpeg($desired_gdim, $directory . $final_file);

        /*
         * Add clean-up code here
         */
        //return $uploaded_data['raw_name'].'.jpg';
        return $final_file;
    }

    private function make_directory($path = false)
    {
        if (!$path) {
            return false;
        }
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }
    function resize_image($config) {

        $uploaded_data = isset($config['uploaded_data']) ? $config['uploaded_data'] : array();
        $w= isset($config['width']) ? $config['width'] : 100;
        $h = isset($config['height']) ? $config['height'] : 90;
        $orginal_name = isset($config['orginal_name']) ? $config['orginal_name'] : false;
        $crop = isset($config['crop']) ? $config['crop'] : false;

        $source_path = $uploaded_data['full_path'];



        list($width, $height) = getimagesize($source_path);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
                // $newheight = $h;
                // $newwidth = $w;
        }

        
        //Get file extension
        $exploding = explode(".",$source_path);
        $ext = end($exploding);
        
        switch($ext){
            case "png":
                $src = imagecreatefrompng($source_path);
            break;
            case "jpeg":
            case "jpg":
                $src = imagecreatefromjpeg($source_path);
            break;
            case "gif":
                $src = imagecreatefromgif($source_path);
            break;
            default:
                $src = imagecreatefromjpeg($source_path);
            break;
        }
        
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        $directory = $config['upload_path'];
        $this->make_directory($directory);
        if ($orginal_name) {
            $final_file = $uploaded_data['raw_name'] . '.png';
        } else {
            $final_file = $uploaded_data['raw_name'] . '_' . $w . 'x' . $h . '.png';
        }

        imagepng($dst, $directory . $final_file);

        return $dst;
    }
public function DownloadPDF($param)
{



	$pdf = new Fpdfcustom();
	$pdf->imagePath = $param['Clinic Logo'];
	$pdf->AliasNbPages();
	$pdf->AddPage("P","A4");
	
	$docName = $param['docName'];
	$pdf->SetFont('arial','',12);
	unset($param['Clinic Logo']);
	unset($param['docName']);

	foreach($param as $key => $val)
    $pdf->Cell(0,10,$key ."  :-  " . $val,0,1);
	for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

	$pdf->Output("D",$docName);
}
	
	public function loadPHPExcel() {

		require_once($this->getRootValue("rootpath")."/application/third_party/PHPExcel.php");
		require_once($this->getRootValue("rootpath")."/application/third_party/PHPExcel/IOFactory.php");

	}


}

?>

<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'../vendor/autoload.php';
// require_once 'dompdf/autoload.inc.php';
/**
 * CodeIgniter DomPDF Library
 *
 * Generate PDF's from HTML in CodeIgniter
 *
 * @packge        CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author        Ardianta Pargo
 * @license        MIT License
 * @link        https://github.com/ardianta/codeigniter-dompdf
 */
use Dompdf\Dompdf;
use Dompdf\Options;
class Pdf extends Dompdf{
    protected $ci;
    private $filename;

    public function __construct()
    {
       parent::__construct();
        $this->ci =& get_instance();
    }

    public function setFileName($filename)
   {
      $this->filename = $filename;
   }

   public function loadView($viewFile, $data = array())
   {
      $options = new Options();
      $options->setChroot(FCPATH);
      $options->setDefaultFont('courier');

      $this->setOptions($options);

      $html = $this->ci->load->view($viewFile, $data, true);
      $this->loadHtml($html);
      $this->render();
      $this->stream($this->filename, ['Attachment' => 0]);
   }


}

/* End of file PDFlib.php */
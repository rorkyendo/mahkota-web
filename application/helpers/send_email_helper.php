<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('sendMail'))
{
  function sendMail($subject, $mailContent, $mailTo, $data)
  {
      $CI =& get_instance();
      $CI->load->library('email');
      $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'smtp.gmail.com',
      'smtp_port' => 465,
      'smtp_user' => 'test@gmail.com',
      'smtp_pass' => '',
      'smtp_crypto' => 'ssl',
      'mailtype' => 'html', //plaintext 'text' mails or 'html'
      'charset' => 'iso-8859-1'
    );
      $mailContent = $CI->load->view($mailContent,$data,TRUE);
      $CI->email->set_newline("\r\n");
      $CI->email->initialize($config);
      $CI->email->from('test@gmail.com', 'Test');
      $CI->email->to($mailTo);
      $CI->email->subject($subject);
      $CI->email->message($mailContent);
      if($CI->email->send()==TRUE){
      }else{
          echo $CI->email->print_debugger();die;
      }

  }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Updatecolor extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('mycore');
        $this->load->model('mupdatecolor');
//        $this->load->library('phpmailer');
//        $this->load->library('openex/oleread');

    }

    public function index()
    {
        $this->load->view('include/header');
        $this->load->view('include/nav');
        $this->load->view('updatecolor/index');
        $this->load->view('include/footer');
    }

    public function uploadFile()
    {
        $model = new Mupdatecolor();
        $config['upload_path'] = 'assets/uploads';
        $config['allowed_types'] = 'xlsx';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('FilePath')) {
            $error = array('error' => $this->upload->display_errors());
            print_r($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file = 'assets/uploads/' . $data['upload_data']['file_name'];
            if (file_exists($file)) {
                //load the excel library
                $this->load->library('excel');
                //read file from path
                $objPHPExcel = PHPExcel_IOFactory::load($file);

                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

                //extract to a PHP readable array format
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = $data_value;
                    } else {
                        $arr_data[$row][$column] = $data_value;
                    }
                }

                //send the data in an array format
                $data['header'] = $header;
                $data['values'] = $arr_data;
                $r = range('A', 'L');
                foreach ($data['values'] as $key => $val) {
                    if (isset($val[$r[0]])) {
                        $productBarcode = $val[$r[0]];
                        $product_detail = $model->getProductDetail($productBarcode);
                        if (isset($product_detail[0]->product_code) && $product_detail[0]->product_code != '') {
                            $product_code = $product_detail[0]->product_code;
                            if (isset($val[$r[1]]) && $val[$r[1]] != '') {
                                $colorcode = '#'.ltrim($val[$r[1]]) ;
                            } else {
                                $colorcode = $val[$r[2]];
                            }
                            $PostData = array(
                                'list_name' => $colorcode
                            );
                            $model->Edit($PostData, $product_code);
                        }
                    }
                }
                echo 1;
            } else {
                echo $file;
            }

        }
    }
}
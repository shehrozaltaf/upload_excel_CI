<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('mycore');
        $this->load->model('memail');
//        $this->load->library('phpmailer');
//        $this->load->library('openex/oleread');

    }

    public function index()
    {
        $this->load->view('include/header');
        $this->load->view('include/nav');
        $this->load->view('email/index');
        $this->load->view('include/footer');
    }

    public function uploadFile()
    {
        $model = new Memail();
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
                        $productBarcode = $val[$r[6]];
                        $product_detail = $model->getProductDetail($productBarcode);
                        if (isset($product_detail[0]->product_code) && $product_detail[0]->product_code != '') {
                            $product_code = $product_detail[0]->product_code;
                            $PostData = array(
                                'product_name' => strip_tags(str_replace('_x000D_', ' ', $val[$r[7]])),
                                'product_desc' => strip_tags(str_replace('_x000D_', ' ', $val[$r[8]])),
                                'product_nut_info' => strip_tags(str_replace('_x000D_', ' ', $val[$r[9]])),
                                'product_price' => $val[$r[3]],
                                'product_weight_type' => (isset($val[$r[4]]) && $val[$r[4]] != '' ? 'Y' : 'N'),
                                'product_weight' => (isset($val[$r[4]]) && $val[$r[4]] != '' ? $val[$r[4]] : '1'),
                                'product_alter' => $val[$r[5]]
                            );
                            $model->Edit($PostData, $product_code);
                        } else {
                            $maxproduct_code = $model->getMaxProCode();
                            $product_code = $maxproduct_code[0]->Max + 1;
                            $d = array(
                                'product_code' => $product_code,
                                'acno' => 'KHI-03437',
                                'product_date' => date('Y-m-d'),
                                'product_time' => date('hi'),
                                'product_ref' => $val[$r[6]],
                                'product_name' => $val[$r[7]],
                                'product_desc' => strip_tags(str_replace('_x000D_', ' ', $val[$r[8]])),
                                'product_nut_info' => strip_tags(str_replace('_x000D_', ' ', $val[$r[9]])),
                                'product_price' => $val[$r[3]],
                                'product_cost_price' => '0',
                                'product_weight_type' => (isset($val[$r[4]]) && $val[$r[4]] != '' ? 'Y' : 'N'),
                                'product_weight' => (isset($val[$r[4]]) && $val[$r[4]] != '' ? $val[$r[4]] : '1'),
                                'product_alter' => $val[$r[5]],
                                'product_stat' => 'I',
                                'product_related' => '1',
                                'product_featured' => 'N',
                                'product_sale' => 'N',
                                'product_sale_price' => '0',
                                'url_slug' => '',
                                'page_title' => '',
                                'page_meta' => '',
                                'meta_desc' => ''
                            );
                            $sku = array(
                                'sku_code' => $product_code . '00',
                                'product_code' => $product_code,
                                'sku_def' => 'none',
                                'sku_desc' => 'none',
                                'sku_weight' => '',
                                'sku_price' => '',
                                'sku_qty' => '100000',
                                'acno' => 'KHI-03437',
                            );
                            $model->Insert($d);
                            $model->Insertsku($sku);
                        }
                        if ($val[$r[0]] == 18 || $val[$r[0]] == '18') {
                            $categ1 = 3;
                        } else {
                            $categ1 = 1;
                        }
                        $product_category = array(
                            'product_code' => $product_code,
                            'level_one' => $categ1,
                            'level_two' => $val[$r[0]],
                            'level_three' => $val[$r[1]],
                            'level_four' => $val[$r[2]],
                            'level_five' => '0',
                            'level_six' => '0',
                            'acno' => 'KHI-03437',
                        );

                        if (isset($val[$r[11]]) && $val[$r[11]] != '') {
                            $model->Insertproduct_category($product_category);
                            $product_color = array(
                                'vari_code' => 1,
                                'vari_name' => 'Color',
                                'product_code' => $product_code,
                                'acno' => 'KHI-03437'
                            );
                            $model->Insertproduct_color($product_color);

                            $product_color_value = array(
                                'vari_code' => 1,
                                'list_code' => 1,
                                'list_name' => $val[$r[11]],
                                'product_code' => $product_code,
                                'acno' => 'KHI-03437'
                            );
                            $model->Insertproduct_color_value($product_color_value);
                        }

                        if (isset($val[$r[10]]) && $val[$r[10]] != '') {
                            $model->Insertproduct_category($product_category);
                            $product_color = array(
                                'vari_code' => 2,
                                'vari_name' => 'Size',
                                'product_code' => $product_code,
                                'acno' => 'KHI-03437'
                            );
                            $model->Insertproduct_color($product_color);

                            $product_color_value = array(
                                'vari_code' => 2,
                                'list_code' => 1,
                                'list_name' => $val[$r[10]],
                                'product_code' => $product_code,
                                'acno' => 'KHI-03437'
                            );
                            $model->Insertproduct_color_value($product_color_value);
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
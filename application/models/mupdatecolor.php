<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mupdatecolor extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    function  getProductDetail($product_red)
    {
        $SQL = "SELECT * FROM `product` where product_ref='$product_red'";
        $query = $this->db->query($SQL);
        return $query->result();
    }


    function Insert($Data)
    {
        $insert = $this->db->insert('product', $Data);
        if ($insert) {
            $id = $Data['product_code'];
            return $id;
        } else {
            return FALSE;
        }
    }

    function Edit($Data, $Where)
    {

        $this->db->where('product_code', $Where);
        $this->db->where('list_code', 1);
        $this->db->where('vari_code', 1);
        $update = $this->db->update('product_vari_list', $Data);
        if ($update) {
            return 1;
        } else {
            return 0;
        }
    }

}
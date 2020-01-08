<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MEmail extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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
        $update = $this->db->update('product', $Data);
        if ($update) {
            return 1;
        } else {
            return 0;
        }
    }

    function Insertsku($Data)
    {
        $insert = $this->db->insert('sku_detail', $Data);
        if ($insert) {
            $id = $Data['sku_code'];
            return $id;
        } else {
            return FALSE;
        }
    }

    function Insertproduct_category($Data)
    {
        $insert = $this->db->insert('product_category', $Data);
        if ($insert) {
            return true;
        } else {
            return FALSE;
        }
    }

    function  Insertproduct_color($Data)
    {
        $insert = $this->db->insert('product_vari', $Data);
        if ($insert) {
            return true;
        } else {
            return FALSE;
        }
    }

    function  Insertproduct_color_value($Data)
    {
        $insert = $this->db->insert('product_vari_list', $Data);
        if ($insert) {
            return true;
        } else {
            return FALSE;
        }
    }


    function getMaxProCode()
    {
        $Qry = "SELECT MAX(product_code) as 'Max' FROM `product`";
        $query = $this->db->query($Qry);
        return $query->result();
    }

    function  getProductDetail($product_red)
    {
        $SQL = "SELECT * FROM `product` where product_ref='$product_red'";
        $query = $this->db->query($SQL);
        return $query->result();
    }

    function getDataByEmail($Email)
    {
        $Qry = "select * from email where Email='" . $Email . "' and  IsActive=1";
        $query = $this->db->query($Qry);
        return $query->result();
    }


}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuruModel;

class GuruController extends Controller
{

    public function __construct()
    {
        $this->GuruModel = new GuruModel();
    }

    public function index()
    {   
        $data = [
            'guru' => $this->GuruModel->allData(),
        ];
        return view('v_guru',$data);
    }

    public function detail($id_guru)
    {
        if(!$this->GuruModel->detailData($id_guru)){
            abort(404);
        }
        $data = [
            'guru' => $this->GuruModel->detailData($id_guru),
        ];
        return view('v_detailguru',$data);
    }

    public function add()
    {
        return view('v_addguru');
    }

    public function insert()
    {
        
    }

   
}

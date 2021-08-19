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
        Request()->validate([
            'nip' => 'required|unique:tbl_guru,nip|min:4|max:5',
            'nama_guru' => 'required',
            'mapel' => 'required',
            'foto_guru' => 'required|mimes:jpg,jpeg,png|max:2020kb',
        ],[
            'nip.required' => 'Wajib Diisi!!',
            'nip.unique' => 'Nip Tidak Boleh Sama!!',
            'nip.min' => 'Minimal 4 Karakter!!',
            'nip.max' => 'Maximal 5 karakter!',
            'nama_guru.required' => 'Wajib Diisi!!',
            'mapel.required' => 'Wajib Diisi!!',
            'foto_guru.required' => 'Wajib Diisi!!',
          
        ]);

        ///jika ter validasi
        //upload gambar / foto
        $file = Request()->foto_guru;
        $fileName = Request()->nip . '.' . $file->extension();
        $file->move(public_path('foto_guru'), $fileName);

        $data = [
            'nip' => Request()->nip,
            'nama_guru' => request()->nama_guru,
            'mapel' => request()->mapel,
            'foto_guru' => $fileName,
        ];

        $this->GuruModel->addData($data);
        return redirect()->route('guru')
        ->with('pesan', 'Data Berhasil Ditambahkan !!!');
    }

    public function edit($id_guru)
    {
        if(!$this->GuruModel->detailData($id_guru)){
            abort(404);
        }
        $data = [
            'guru' => $this->GuruModel->detailData($id_guru),
        ];
        return view('v_editguru', $data);
    }

    public function update($id_guru)
    {
        Request()->validate([
            // 'nip' => 'required|unique:tbl_guru,nip|min:4|max:5',
            'nama_guru' => 'required',
            'mapel' => 'required',
            
        ],[
            'nip.required' => 'Wajib Diisi!!',
            'nip.min' => 'Minimal 4 Karakter!!',
            'nip.max' => 'Maximal 5 karakter!',
            'nama_guru.required' => 'Wajib Diisi!!',
            'mapel.required' => 'Wajib Diisi!!',
          
        ]);

        if(Request()->foto_guru <> "")
        {   //jika ingin ganti foto
            $file = Request()->foto_guru;
            $fileName = Request()->nip . '.' . $file->extension();
            $file->move(public_path('foto_guru'), $fileName);
    
            $data = [
                'nip' => Request()->nip,
                'nama_guru' => request()->nama_guru,
                'mapel' => request()->mapel,
                'foto_guru' => $fileName,
            ];
    
            $this->GuruModel->editData($id_guru, $data);
        }else{
            //jika tidak ingin ganti foto
            $data = [
                'nip' => Request()->nip,
                'nama_guru' => request()->nama_guru,
                'mapel' => request()->mapel,   
            ];
    
            $this->GuruModel->editData($id_guru, $data);
        }
        ///jika ter validasi
        //upload gambar / foto
      
        return redirect()->route('guru')
        ->with('pesan', 'Data Berhasil Di Update !!!');
    }

    public function delete($id_guru)
    {
        $guru = $this->GuruModel->detailData($id_guru);
        if ($guru->foto_guru <> ""){
            unlink(public_path('foto_guru') . '/' . $guru->foto_guru);
        }

        $this->GuruModel->deleteData($id_guru);
        return redirect()->route('guru')
        ->with('pesan', 'Data Berhasil Di Hapus !!!');
    }
   
}

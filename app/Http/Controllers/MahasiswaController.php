<?php

namespace App\Http\Controllers;

use App\helpers\MahasiswaApi;
use App\Models\Mahasiswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Mahasiswa::latest()->get();


        if($data){
            return MahasiswaApi::CreateApi(202,'Succes Get Data', $data);
        } else {
            return MahasiswaApi::CreateApi('404','Failed Get Data');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nama' => 'required',
                'alamat' => 'required',
                'jurusan' => 'required',
                'image' => 'required|max:1500',
                'slug' => 'required|unique:mahasiswas',
                'nim' => 'required|unique:mahasiswas'
            ]);
    
            $validateData['image'] = $request->file('image')->store('mahasiswa');
            
            $mahasiswa = Mahasiswa::create($validateData);
            
            $data = Mahasiswa::where('slug', $mahasiswa->slug)->get();
    
            if($data){
                return MahasiswaApi::CreateApi(202,'Succes Insert Data', $data);
            } else {
                return MahasiswaApi::CreateApi(402,'Failed Insert Data');
            }
        } catch (Exception $err) {
            return MahasiswaApi::CreateApi(402,'Failed Insert Data');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(Mahasiswa $mahasiswa)
    {
        $data = Mahasiswa::where('slug', $mahasiswa->slug)->get();

        if($data){
            return MahasiswaApi::CreateApi(202,'Succes Get Data by id', $data);
        } else {
            return MahasiswaApi::CreateApi('404','Failed Get Data');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
       try {
        $rule = [
            'nama' => 'required',
            'alamat' => 'required',
            'jurusan' => 'required',
            'image' => 'max:1500'
    ];

    if($request->slug != $mahasiswa->slug){
        $rule['slug'] = 'required|unique:mahasiswas';
    }

    if($request->nim != $mahasiswa->nim){
        $rule['nim'] = 'required|unique:mahasiswas';
    }

    $validateData = $request->validate($rule);

    if($request->file('image')){
        if($request->oldImage){
            Storage::delete($request->oldImage);
        }
        $validateData['image'] = $request->file('image')->store('mahasiswa');
       }

    $mhs = Mahasiswa::where('slug', $mahasiswa->slug)->update($validateData);

    $data = Mahasiswa::where('slug', $mhs->slug)->get();

    if($data){
        return MahasiswaApi::CreateApi(202,'Get Data Succes', $data);
    } else {
        return MahasiswaApi::CreateApi(404, 'Failed Get Data');
    }
    } catch (Exception $err) {
        return $err;
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        if($mahasiswa->image){
            Storage::delete($mahasiswa->image);
        }

        $mhs = Mahasiswa::where('slug', $mahasiswa->slug);

        $data = $mhs->delete();

        if($data){
            return MahasiswaApi::CreateApi(202,'Delete Succesfully', $data);
        } else {

            return MahasiswaApi::CreateApi(404,'Failed Delete Mahasiswa');
        }
    }
}

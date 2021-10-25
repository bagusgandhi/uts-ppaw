<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*')->orderBy('created_at','DESC');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$row->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
            $btn .= '<a href="javascript:void(0)" id="'.$row->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';
            
            return $btn;
            })->rawColumns(['action'])->make(true);
        }         
        return view('user');
    }


    public function create()
    {
        //
    }

    public function moveData($file, $key, $fileName)
    {
            $ext = $file->getClientOriginalExtension();
            $data = $key . "_". $fileName .".". $ext;
            $file->move("dokumen/", $data);
            return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required',
            'jabatan' => 'required',
            'foto' => 'required|max:1000|mimes:pdf,jpeg,png',
            'password' => 'required'
        ]);

        $data_key = \Str::random(7);

        $foto = $request->file('foto');
        $fileFoto = $this->moveData($foto, $data_key, "foto");
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'foto' =>  $fileFoto,
            'password' => $request->password,
        ]);

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return response()->json($user);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->jabatan = $request->jabatan;

        if($request->hasFile('foto')){
            $data_key = \Str::random(7);
            $foto = $request->file('foto');
            $fileFoto = $this->moveData($foto, $data_key, "foto");
            $user->foto = $fileFoto;
        }

        $user->save();
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}

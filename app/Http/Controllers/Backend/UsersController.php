<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        return view('backend.users.list', [
            'users' => User::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customers()
    {
       
        return view('backend.users.list', [
            'users' => User::where('is_admin', 0)->get()
        ]);
    }

    public function get()
    {
        return datatables()->of(User::query())
        ->editColumn('is_admin', function($row) {
            if($row->is_admin == 0)
            {
                return "<span class='badge badge-secondary'> Customer </span>";   
            }
            elseif($row->is_admin == 1)
            {
                return "<span class='badge badge-primary'> Admin </span>";   
            }
            elseif($row->is_admin == 3)
            {
                return "<span class='badge badge-info'> Seller </span>";   
            }
            
        })
        ->addIndexColumn()
        ->addColumn('action', function($row){

               
               $btn = '<a href="'.route('backend.users.edit', $row->id).'" class="edit btn btn-primary btn-sm">Edit</a>';
               $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm">Delete</a>';

                return $btn;
        })
        ->rawColumns(['action', 'is_admin'])
        ->make(true);
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
        //
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
    public function edit($id)
    {
        dd('d');
        return view('backend.users.edit', [
            'user' => User::findOrFail($id)
        ]);
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
        $user = User::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'is_admin' => ['required'],
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address1 = $request->address1;
        $user->address2 = $request->address2;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->pin_code = $request->pin_code;
        $user->email_verified_at = ($request->email_verified_at == 1) ? date('Y-m-d h:i:s') : null;
        $user->is_admin = $request->is_admin;
        if($request->password)
        {
            $user->password = Hash::make($request->password);
        }
        $user->update();

        return redirect()->route('backend.users.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

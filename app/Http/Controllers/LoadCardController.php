<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\User;
use App\LoadCard;
use App\LoadCardRecharge;
use Keygen;
use Auth;
use Illuminate\Validation\Rule;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LoadCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('unit')) {
            $lims_customer_list = Customer::where('is_active', true)->get();
            $lims_user_list = User::where('is_active', true)->get();
            $lims_gift_card_all = LoadCard::where('is_active', true)->orderBy('id', 'desc')->get();

            return view('backend.load_card.index', compact('lims_customer_list', 'lims_user_list', 'lims_gift_card_all'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function generateCode()
    {
        $id = Keygen::numeric(16)->generate();
        return $id;
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'card_no' => [
                'max:255',
                    Rule::unique('load_cards')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ]
        ]);

        $data = $request->all();

        if($request->input('user'))
            $data['customer_id'] = null;
        else
            $data['user_id'] = null;

        $data['is_active'] = true;
        $data['created_by'] = Auth::id();
        $data['expense'] = 0;
        LoadCard::create($data);
        $message = 'LoadCard created successfully';
        if($data['user_id']){
            $lims_user_data = User::find($data['user_id']);
            $data['email'] = $lims_user_data->email;
            $data['name'] = $lims_user_data->name;
            try{
                Mail::send( 'mail.load_card_create', $data, function( $message ) use ($data)
                {
                    $message->to( $data['email'] )->subject( 'LoadCard' );
                });
            }
            catch(\Exception $e){
                $message = 'LoadCard created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        else{
            $lims_customer_data = Customer::find($data['customer_id']);
            if($lims_customer_data->email){
                $data['email'] = $lims_customer_data->email;
                $data['name'] = $lims_customer_data->name;
                try{
                    Mail::send( 'mail.load_card_create', $data, function( $message ) use ($data)
                    {
                        $message->to( $data['email'] )->subject( 'LoadCard' );
                    });
                }
                catch(\Exception $e){
                    $message = 'LoadCard created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                }
            }
        }
        return redirect('load_cards')->with('message', $message);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $lims_gift_card_data = LoadCard::find($id);
        return $lims_gift_card_data;
    }


    public function update(Request $request, $id)
    {
        $request['card_no'] = $request['card_no_edit'];
        $this->validate($request, [
            'card_no' => [
                'max:255',
                Rule::unique('load_cards')->ignore($request['load_card_id'])->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ]
        ]);

        $data = $request->all();
        $lims_gift_card_data = LoadCard::find($data['load_card_id']);
        $lims_gift_card_data->card_no = $data['card_no_edit'];
        $lims_gift_card_data->amount = $data['amount_edit'];
        if($request->input('user_edit')){
            $lims_gift_card_data->user_id = $data['user_id_edit'];
            $lims_gift_card_data->customer_id = null;
        }
        else{
            $lims_gift_card_data->user_id = null;
            $lims_gift_card_data->customer_id = $data['customer_id_edit'];
        }
        $lims_gift_card_data->expired_date = $data['expired_date_edit'];
        $lims_gift_card_data->save();
        return redirect('load_cards')->with('message', 'LoadCard updated successfully');
    }

    public function destroy($id)
    {
        $lims_gift_card_data = LoadCard::find($id);
        $lims_gift_card_data->is_active = false;
        $lims_gift_card_data->save();
        return redirect('load_cards')->with('not_permitted', 'Data deleted successfully');
    }
    public function deleteBySelection(Request $request)
    {
        $gift_card_id = $request['gift_cardIdArray'];
        foreach ($gift_card_id as $id) {
            $lims_gift_card_data = LoadCard::find($id);
            $lims_gift_card_data->is_active = false;
            $lims_gift_card_data->save();
        }
        return 'Load Card deleted successfully!';
    }
}

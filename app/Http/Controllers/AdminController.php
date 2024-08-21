<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Precense;
use App\Models\Esp32Mode;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use App\Models\TimePrecense;
use Illuminate\Http\Request;
use App\Events\PrecenseEvent;
use App\Events\RegisterCardEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $precense = Precense::todayPrecense()->latest()->get();
        $employe = Employee::all()->count();
        return view('admin.dashboard.index', compact('precense','employe'));
    }

    public function employee_acct()
    {
        $employees = Employee::orderBy('id', 'desc')->get();

        $select_employ = [];

        foreach ($employees as $item) {
            if(empty($item?->card_id)){
                $select_employ[$item->id] = $item->name;
            }
        }   
        
        // dd(Auth::user()->createToken('admin')->plainTextToken);

        return view('admin.employee.index', compact('employees', 'select_employ'));
    }

    public function employee_store(Request $request)
    {
        $this->validate($request,[
            'name' => ['required','max:255'],
            'position' => ['required','max:255'],
            'email' => ['required','email','max:50','unique:users,email'],
            'mobile' => ['required','max:20'],
            'nik' => ['required'],
            'date_birth' => ['required'],
            'address' => ['nullable'],
            'kelurahan' => ['nullable'],
            'kecamatan' => ['nullable'],
            'kota' => ['nullable'],
            'provinsi' => ['nullable'],
            'image' => ['nullable']
        ]);

        DB::beginTransaction();
        try{
            $firstname = explode(' ',$request->name)[0];
            $username = Str::slug($firstname);
            $password = $request->date_birth;

            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'role' => 'employee',
                'password' => Hash::make($password)
            ]);

            if($user) {
                $employe = Employee::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'position' => $request->position,
                    'email' => $request->email,
                    'nik' => $request->nik,
                    'mobile' => $request->mobile,
                    'birthday' => $request->date_birth,
                    'image' => $request->image,
                    'enc_password' => Crypt::encryptString($password)
                ]);

                $user->assignRole('employee');
    
                if($employe && $request->address){
                    UserAddress::create([
                        'employee_id' => $employe->id,
                        'street_address' => $request->address,
                        'kelurahan' => $request->kelurahan,
                        'kecamatan' => $request->kecamatan,
                        'kota' => $request->kota,
                        'provinsi' => $request->provinsi
                    ]);
                }

                DB::commit();
                return redirect()->back()->with('success', 'Create Data Employee Successfuly.');
            }

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }

    }

    public function update_employe(Request $request)
    {
        $this->validate($request,[
            'id' => ['required'],
            'name' => ['required','max:255'],
            'position' => ['required','max:255'],
            'email' => ['required','email','max:50'],
            'mobile' => ['required','max:20'],
            'nik' => ['required'],
            'date_birth' => ['required'],
            'address' => ['nullable'],
            'kelurahan' => ['nullable'],
            'kecamatan' => ['nullable'],
            'kota' => ['nullable'],
            'provinsi' => ['nullable'],
            'image' => ['nullable']
        ]);

        $employee = Employee::find($request->id);
        DB::beginTransaction();
        try{
            $employee->update([
                'name' => $request->name,
                'position' => $request->position,
                'email' => $request->email,
                'nik' => $request->nik,
                'mobile' => $request->mobile,
                'birthday' => $request->date_birth,
                'image' => $request->image,
            ]);

            if($request->address){
                $address = UserAddress::find($employee->address->id);
                if($address){
                    $address->update([
                        'street_address' => $request->address,
                        'kelurahan' => $request->kelurahan,
                        'kecamatan' => $request->kecamatan,
                        'kota' => $request->kota,
                        'provinsi' => $request->provinsi
                    ]);
                }else{
                    UserAddress::create([
                        'employee_id' => $employee->id,
                        'street_address' => $request->address,
                        'kelurahan' => $request->kelurahan,
                        'kecamatan' => $request->kecamatan,
                        'kota' => $request->kota,
                        'provinsi' => $request->provinsi
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Update Successfully');

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }

    }

    public function delete_employe($id)
    {
        $employe = Employee::find($id);

        if($employe){
            User::find($employe->user_id)->delete();
            $employe->delete();

            return redirect()->back()->with('success', 'Deleted Successfully');
        }

        return redirect()->back()->with('errors', 'Employee Not Found');
    }

    public function updateCardId(Request $request)
    {
        $this->validate($request,[
            'employee_id' => 'required|max:8',
            'card_id' => 'required|max:25'
        ]);

        $employee = Employee::find($request->employee_id);

        if(!$employee){
            return response()->json([
                'type' => 'error',
                'msg' => 'Employee is Not Found!' 
            ]);
        }

        try{
            $employee->card_id = $request->card_id;
            $employee->save();

            Esp32Mode::setPrecense()->setOffRegis();

            return response()->json([
                'type' => 'success',
                'msg' => 'Register Card Successfuly.',
                'card_id' => $employee->card_id
            ]);

        }catch(\Exception $e){
            return response()->json([
                'type' => 'error',
                'msg' => $e->getMessage() 
            ]);
        };
    }

    public function set_action_mode(string $id)
    {   
        if($id == 2){
            Esp32Mode::setRegis()->setOffPrecense();
        }else{
            Esp32Mode::setPrecense()->setOffRegis();
        }

        return response()->json([
            'type' => 'success'
        ]);
    }

    public function requestEsp(Request $request)
    {
        $card_id = $request->input('card_id');

        if(Esp32Mode::getStatusRegis()){
            event(new RegisterCardEvent($card_id));
        }

        if(Esp32Mode::getStatusPrecense()){
            
            $user = Employee::where('card_id', $card_id)->first();

            if($user){
                $precense = Precense::where('employe_id', $user->id)
                            ->where('type' , 1)
                            ->where(function ($query) {
                                $query->where('status', 1)
                                    ->orWhere('status', 2);
                            })
                            ->whereDate('created_at', Carbon::now())->exists();

                if($precense){
                    return response()->json([
                        'status' => true,
                        'message' => 'Today you have taken attendance',
                        'sound' => ''
                    ]);
                }else{
                    $time = TimePrecense::where('type', 'settings')->first();

                    if(!$time){
                        return response()->json([
                            'status' => true,
                            'message' => 'Need Settings Time Precense First'
                        ]);
                    }
                    $status = 0;
                    $type = 1;
                    if(Carbon::now()->format('H:i') <= $time?->min_in_office || Carbon::now()->format('H:i') >= $time?->min_in_office && Carbon::now()->format('H:i') <= $time?->max_in_office){
                        $status = 1;
                    }

                    if(Carbon::now()->format('H:i') >= $time?->max_in_office && Carbon::now()->format('H:i') <= $time?->min_out_office){
                        $status = 2;
                    }

                    if(Carbon::now()->format('H:i') >= $time?->min_out_office){
                        $type = 2;
                        $status = 0;
                    }

                    $new_precense = Precense::create([
                        'employe_id' => $user->id,
                        'type' => $type,
                        'status' => $status,
                        'time' => Carbon::now()->format('H:i'),
                    ]);

                    $data = [
                        'image' => get_data_image($new_precense->employe->image)['img_url'] ?? '',
                        'name' => $new_precense->employe->name,
                        'time' => $new_precense->time,
                        'today_total' => Precense::todayPrecense()->count()
                    ];

                    event(new PrecenseEvent($data));

                    return response()->json([
                        'status' => true,
                        'message' => 'Precense Successfully.',
                        'status_time' => $status,
                        'sound' => 1
                    ], 200);
                }
            }else{
                return response()->json([
                    'status' => true,
                    'message' => 'Invalid Card',
                    'sound' => ''
                ], 404);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Registrasi Successfully',
            'sound' => ''
        ], 200);
    }

    public function presence()
    {
        $precenses = Precense::latest()->get();

        return view('admin.presence.index', compact('precenses'));
    }

    public function settings()
    {

        $time = TimePrecense::where('type', 'settings')->latest()->first();
        
        $settings = [
            [
                'title' => 'Token API',
                'icon' => 'fa-solid fa-key',
                'key' => '',
                'route' => '#',
                'methode' => '',
                'field' => [
                    [
                        'type' => 'text',
                        'title' => 'Token',
                        'option' => 'readonly',
                        'value' => Auth::user()->createToken('token')->plainTextToken
                    ]
                ],
                'button' => false
            ],
            [
                'title' => 'Alarm',
                'icon' => 'fa-solid fa-bell',
                'key' => '',
                'route' => '#',
                'methode' => '',
                'button' => false
            ],
            [
                'title' => 'Time Precense',
                'icon' => 'fa-solid fa-clock',
                'key' => 'time_precense_settings',
                'route' => route('admin.update-time-precense'),
                'methode' => '',
                'field' => [
                    [
                        'type' => 'time',
                        'title' => 'Min Time In Office',
                        'name' => 'min_in_office',
                        'value' => $time?->min_in_office,
                    ],
                    [
                        'type' => 'time',
                        'title' => 'Max Time In Office',
                        'name' => 'max_in_office',
                        'value' => $time?->max_in_office,
                    ],
                    [
                        'type' => 'time',
                        'title' => 'Min Time Out Office',
                        'name' => 'min_out_office',
                        'value' => $time?->min_out_office,
                    ],
                ],
                'button' => true
            ]
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function setTimePrecense(Request $request)
    {
        $this->validate($request, [
            'min_in_office' => 'required',
            'max_in_office' => 'required',
            'min_out_office' => 'required'
        ]);
        
        try{
            TimePrecense::updateOrCreate(
                ['type' => 'settings'],
                [
                    'type' => 'settings',
                    'min_in_office' => $request->min_in_office,
                    'max_in_office' => $request->max_in_office,
                    'min_out_office' => $request->min_out_office
                ]
            );

            return response()->json([
                'type' => 'success',
                'msg' => 'Update Successfully'
            ]);

        } catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function update_static_uption(Request $request)
    {
        $data = $request->formData;

        foreach ($data ?? [] as $key => $value) {
            update_static_option($key, $value);
        }

        return response()->json([
            'type' => 'success',
            'msg' => 'Update Successfully'
        ]);
    }
}

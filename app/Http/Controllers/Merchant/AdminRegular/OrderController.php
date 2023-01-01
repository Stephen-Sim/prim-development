<?php

namespace App\Http\Controllers\Merchant\AdminRegular;

use App\Models\TypeOrganization;
use App\Models\PgngOrder;
use App\Models\ProductOrder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    private function getOrganizationId()
    {
        $role_id = DB::table('organization_roles')->where('nama', 'Regular Merchant Admin')->first()->id;
        $type_org_id = TypeOrganization::where('nama', 'Peniaga Barang Umum')->first()->id;

        $org_id = DB::table('organizations as o')
        ->join('organization_user as ou', 'ou.organization_id', 'o.id')
        ->where([
            ['user_id', Auth::id()],
            ['role_id', $role_id],
            ['status', 1],
            ['type_org', $type_org_id],
            ['deleted_at', NULL],
        ])
        ->select('o.id')
        ->first()->id;
        
        return $org_id;
    }

    public function index()
    {
        return view('merchant.regular.admin.order.index');
    }

    public function getAllOrders(Request $request)
    {
        $org_id = $this->getOrganizationId();
        $total_price[] = 0;
        $pickup_date[] = 0;
        $filteredID = array();
        $order_day = $request->order_day;

        $order = DB::table('pgng_orders as pu')
                ->join('users as u', 'pu.user_id', '=', 'u.id')
                ->whereIn('status', ["Paid"])
                ->where('organization_id', $org_id)
                ->select('pu.id', 'pu.updated_at', 'pu.pickup_date', 'pu.total_price', 'pu.note', 'pu.status',
                'u.name', 'u.telno')
                ->orderBy('status', 'desc')
                ->orderBy('pickup_date', 'asc')
                ->orderBy('pu.updated_at', 'desc')
                ->get();
        
        if(request()->ajax()) 
        {
            if($order_day == "") 
            {
                $order = $order;
            }
            else
            {
                foreach($order as $row) {
                    $day_pickup = Carbon::parse($row->pickup_date)->format('l');
                    $day = app('App\Http\Controllers\CooperativeController')->getDayIntegerByDayName($day_pickup);
                    if($day == $order_day) {
                        $filteredID[] = $row->id;
                    }
                }
                
                $order = DB::table('pgng_orders as pu')
                ->join('users as u', 'pu.user_id', 'u.id')
                ->whereIn('pu.id', $filteredID)
                ->select('pu.id', 'pu.updated_at', 'pu.pickup_date', 'pu.total_price', 'pu.note', 'pu.status',
                'u.name', 'u.telno')
                ->orderBy('status', 'desc')
                ->orderBy('pickup_date', 'asc')
                ->orderBy('pu.updated_at', 'desc');
            }
            
            $table = Datatables::of($order);

            $table->addColumn('status', function ($row) {
                if ($row->status == "Paid") {
                    $btn = '<span class="badge rounded-pill bg-success text-white">Berjaya dibayar</span>';
                    return $btn;
                } else {
                    $btn = '<span class="badge rounded-pill bg-danger text-white">Tidak Diambil</span>';
                    return $btn;
                }
            });

            $table->addColumn('action', function ($row) {
                $btn = '<div class="d-flex justify-content-center align-items-center">';
                $btn = $btn.'<button type="button" class="btn-done-pickup btn btn-primary mr-2" data-order-id="'.$row->id.'"><i class="fas fa-clipboard-check"></i></button>';
                $btn = $btn.'<button type="button" class="btn-cancel-order btn btn-danger" data-order-id="'.$row->id.'">';
                $btn = $btn.'<i class="fas fa-trash-alt"></i></button></div>';

                return $btn;
            });

            $table->editColumn('note', function ($row) {
                if($row->note != null) {
                    return $row->note;
                } else {
                    return "<i>Tiada Nota</i>";
                }
                return number_format($row->total_price, 2, '.', '');
            });

            $table->editColumn('total_price', function ($row) {
                $total_price = number_format($row->total_price, 2, '.', '');
                $total = $total_price." | ";
                $total = $total."<a href='".route('admin-reg.order-detail', $row->id)."'>Lihat Pesanan</a>";
                return $total;
            });

            $table->editColumn('pickup_date', function ($row) {
                return Carbon::parse($row->pickup_date)->format('d/m/y H:i A');
            });

            $table->rawColumns(['note', 'total_price', 'status', 'action']);

            return $table->make(true);
        }
    }

    public function orderPickedUp(Request $request)
    {
        $update_order = PgngOrder::find($request->o_id)->update(['status' => 'Picked-Up']);

        if ($update_order) {
            Session::flash('success', 'Pesanan Berjaya Diambil');
            return View::make('layouts/flash-messages');
        } else {
            Session::flash('error', 'Pesanan Gagal Disahkan');
            return View::make('layouts/flash-messages');
        }
    }

    public function showHistory()
    {
        return view('merchant.regular.admin.order.history');
    }

    public function getAllHistories(Request $request)
    {
        $org_id = $this->getOrganizationId();
        $total_price[] = 0;
        $pickup_date[] = 0;
        $filteredID = array();
        $order_day = $request->order_day;

        $order = DB::table('pgng_orders as pu')
                ->join('users as u', 'pu.user_id', 'u.id')
                ->whereIn('status', ["Cancel by user", "Cancel by merchant", "Picked-Up"])
                ->where('organization_id', $org_id)
                ->select('pu.id', 'pu.pickup_date', 'pu.total_price', 'pu.status',
                'u.name', 'u.telno')
                ->orderBy('pickup_date', 'asc')
                ->orderBy('pu.updated_at', 'desc')
                ->get();
        
        if(request()->ajax()) 
        {
            if($order_day == "") 
            {
                $order = $order;
            }
            else
            {
                foreach($order as $row) {
                    $day_pickup = Carbon::parse($row->pickup_date)->format('l');
                    $day = app('App\Http\Controllers\CooperativeController')->getDayIntegerByDayName($day_pickup);
                    if($day == $order_day) {
                        $filteredID[] = $row->id;
                    }
                }

                $order = DB::table('pgng_orders as pu')
                ->join('users as u', 'pu.user_id', 'u.id')
                ->whereIn('pu.id', $filteredID)
                ->select('pu.id', 'pu.pickup_date', 'pu.total_price', 'pu.status',
                'u.name', 'u.telno')
                ->orderBy('pickup_date', 'asc')
                ->orderBy('pu.updated_at', 'desc');
            }

            $table = Datatables::of($order);

            $table->addColumn('status', function ($row) {
                if ($row->status == "Picked-Up") {
                    $btn = '<span class="badge rounded-pill bg-success text-white">Berjaya Diambil</span>';
                    return $btn;
                } else if($row->status == "Cancel by user") {
                    $btn = '<span class="badge rounded-pill bg-danger text-white">Dibatalkan Oleh Pelanggan</span>';
                    return $btn;
                } else if($row->status == "Cancel by merchant") {
                    $btn = '<span class="badge rounded-pill bg-danger text-white">Dibatalkan Oleh Peniaga</span>';
                    return $btn;
                }
            });

            $table->editColumn('total_price', function ($row) {
                $total_price = number_format($row->total_price, 2, '.', '');
                $total = $total_price." | ";
                $total = $total."<a href='".route('admin-reg.order-detail', $row->id)."'>Lihat Pesanan</a>";
                return $total;
            });

            $table->editColumn('pickup_date', function ($row) {
                return Carbon::parse($row->pickup_date)->format('d/m/y H:i A');
            });

            $table->rawColumns(['total_price', 'status']);

            return $table->make(true);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->o_id;

        $update_order = PgngOrder::find($id)->update(['status' => "Cancel by merchant"]);
        $delete_order = PgngOrder::find($id)->delete();
        
        $cart = ProductOrder::where('pgng_order_id', $id)->delete();
        
        if($update_order && $delete_order && $cart) {
            Session::flash('success', 'Pesanan Berjaya Dibuang');
            return View::make('layouts/flash-messages');
        } else {
            Session::flash('error', 'Pesanan Gagal Dibuang');
            return View::make('layouts/flash-messages');
        }
    }

    public function showList($id)
    {
        // Get Information about the order
        $list = DB::table('pgng_orders as pu')
                ->join('users as u', 'u.id', '=', 'pu.user_id')
                ->where('pu.id', $id)
                ->where('pu.status', '!=' , 'In cart')
                ->select('pu.updated_at', 'pu.pickup_date', 'pu.total_price', 'pu.note', 'pu.status',
                        'u.name', 'u.telno', 'u.email')
                ->first();

        $order_date = Carbon::parse($list->updated_at)->format('d/m/y H:i A');
        $pickup_date = Carbon::parse($list->pickup_date)->format('d/m/y H:i A');
        $total_order_price = number_format($list->total_price, 2, '.', '');

        // get all product based on order
        $item = DB::table('product_order as po')
                ->join('product_item as pi', 'po.product_item_id', '=', 'pi.id')
                ->where('po.pgng_order_id', $id)
                ->select('po.id', 'pi.name', 'pi.price', 'po.quantity', 'po.selling_quantity')
                ->get();

        $total_price[] = array();
        $price[] = array();
        
        foreach($item as $row)
        {
            $price[$row->id] = number_format($row->price, 2, '.', '');
            $total_price[$row->id] = number_format(doubleval($row->price * ($row->quantity * $row->selling_quantity)), 2, '.', ''); // calculate total for each item in cart
        }

        return view('merchant.regular.admin.list', compact('list', 'order_date', 'pickup_date', 'total_order_price', 'item', 'price', 'total_price'));
    }
}

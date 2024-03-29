<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PgngOrder extends Model
{
    use SoftDeletes;

    protected $table = "pgng_orders";
    
    protected $fillable = ['order_type', 'pickup_date', 'delivery_date', 'total_price', 'address', 'state', 'postcode', 'city', 'note', 'expired_at', 'status', 'user_id', 'organization_id', 'transaction_id','confirm_picked_up_time','confirm_by'];
    
    public $timestamps = true;

    public function organization(){
        return $this->belongsTo(Organization::class);
    }
    public function transaction(){
        return $this->hasOne(Transaction::class ,'id' , 'transaction_id');
    }
}

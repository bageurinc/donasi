<?php

namespace Bageur\Donasi\Model;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $table = 'bgr_yayasan_transaksi';
    protected $appends = ['status_format'];

    public function getStatusFormatAttribute()
    {
        if($this->status == 'pending'){
        	return 'Menunggu Pembayaran';
        }else if($this->status == 'batal'){
        	return 'Batal';
        }else if($this->status == 'berhasil'){
        	return 'Pembayaran Berhasil';
        }
    }

}

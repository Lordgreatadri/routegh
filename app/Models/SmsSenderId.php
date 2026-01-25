<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class SmsSenderId extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'sms_sender_ids';

    protected $fillable = [
        'user_id',
        'sender_id',
        'description',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
        'admin_remarks',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }


    public function isPending()
    {
        return $this->approval_status === 'pending';
    }


    public function approve($approverId, $remarks = null)
    {
        $this->approval_status = 'approved';
        $this->approved_by = $approverId;
        $this->approved_at = now();
        $this->admin_remarks = $remarks;
        $this->save();
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }


    public function reject($approverId, $remarks = null)
    {
        $this->approval_status = 'rejected';
        $this->approved_by = $approverId;
        $this->approved_at = now();
        $this->admin_remarks = $remarks;
        $this->save();
    }

    public function activate()
    {
        $this->status = 'active';
        $this->save();
    }

    public function deactivate()
    {
        $this->status = 'inactive';
        $this->save();
    }

    public function smsMessages(): HasMany
    {
        return $this->hasMany(SmsMessage::class, 'sms_sender_id');
    }
}

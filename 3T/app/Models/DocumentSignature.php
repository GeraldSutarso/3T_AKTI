<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSignature extends Model
{
    use HasFactory;

    protected $table = 'document_signatures';

    protected $fillable = ['document_type', 'document_id', 'role', 'signer'];

}

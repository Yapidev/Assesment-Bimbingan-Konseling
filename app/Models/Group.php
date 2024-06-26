<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Relasi untuk mendapatkan anggota group terkait
     *
     * @return BelongsToMany
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups')->withTimestamps();
    }

    /**
     * Fungsi untuk mendapatkan anggota yang sedang login
     *
     * @return object
     */
    public function authMember(): object
    {
        return $this->user()->where('user_id', auth()->id());
    }

    /**
     * Fungsi untuk mengecek apakah user yang sedang login merupakan anggota grup
     *
     * @return void
     */
    public function isMember()
    {
        return $this->authMember()->exists();
    }

    public function instrumens()
    {
        return $this->belongsToMany(Instrumen::class, 'groups_to_instruments');
    }
}

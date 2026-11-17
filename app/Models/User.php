<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role_id', 'is_active', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{ 
    
    public function student(): HasOne
   {  
      return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
   {
      return $this->hasOne(Teacher::class);
    }
    public function role(): BelongsTo
   {
       return $this->belongsTo(Role::class);
    }

    public function emailVerificationOtp(): HasOne
   {
    return $this->hasOne(EmailVerificationOtp::class);
   }

       // The user's own registration request
    public function registrationRequest(): HasOne
    {
    return $this->hasOne(RegistrationRequest::class);
    }

      // Applications reviewed by this admin
    public function reviewedRegistrationRequests(): HasMany
    {
    return $this->hasMany(RegistrationRequest::class, 'reviewed_by');
    }
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role_id' => 'integer',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
         ];
        
    }
}

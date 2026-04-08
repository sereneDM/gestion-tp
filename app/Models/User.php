<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // ADD THIS LINE
        'must_reset_password', 
        'profile_picture', // NEW
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'must_reset_password' => 'boolean',
        ];
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    // Relationship: A teacher can have many classes
public function teachingClasses()
{
    return $this->hasMany(ClassModel::class, 'teacher_id');
}

// Relationship: A student can belong to many classes
public function classes()
{
    return $this->belongsToMany(ClassModel::class, 'class_student', 'student_id', 'class_id')
                ->withTimestamps();
}
public function getProfilePictureUrlAttribute()
{
    if ($this->profile_picture) {
        return asset('storage/' . $this->profile_picture);
    }

    // Get first character safely
    $firstChar = mb_substr($this->name, 0, 1, 'UTF-8');

    // Convert accented letters to ASCII
    $translit = $this->removeAccents($firstChar);
    $initial = strtoupper($translit);

    // Fallback if something weird happens
    if (!$initial || !ctype_alpha($initial)) {
        $initial = 'U';
    }

    // Pick a consistent color based on the initial
    $colors = [
        '#667eea', '#764ba2', '#f093fb', '#f5576c',
        '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
        '#fa709a', '#fee140', '#a18cd1', '#fbc2eb',
    ];
    $colorIndex = ord($initial) % count($colors);
    $bg = $colors[$colorIndex];

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">'
         . '<rect width="100" height="100" fill="' . $bg . '"/>'
         . '<text x="50" y="50" font-family="Arial,sans-serif" font-size="42" font-weight="bold" '
         . 'fill="white" text-anchor="middle" dominant-baseline="central">' . $initial . '</text>'
         . '</svg>';

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

// Helper function to remove accents
private function removeAccents($string)
{
    $normalized = \Normalizer::normalize($string, \Normalizer::FORM_D);
    return preg_replace('/\p{M}/u', '', $normalized);
}
public function enrolledClasses()
{
    return $this->belongsToMany(ClassModel::class, 'class_student', 'student_id', 'class_id')
                ->withTimestamps();
}
}
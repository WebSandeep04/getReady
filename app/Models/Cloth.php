<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cloth extends Model
{
    use HasFactory;

    protected $table = 'clothes';

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'gender',
        'brand',
        'fabric',
        'color',
        'bottom_type',
        'size',
        'fit_type',
        'condition',
        'defects',
        'is_cleaned',
        'rent_price',
        'security_deposit',
        'is_available',
        'chest_bust',
        'waist',
        'length',
        'shoulder',
        'sleeve_length',
    ];

    protected $casts = [
        'is_cleaned' => 'boolean',
        'is_available' => 'boolean',
        'rent_price' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cloth.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the images for the cloth.
     */
    public function images()
    {
        return $this->hasMany(ClothImage::class);
    }

    /**
     * Get the cart items for this cloth.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the availability blocks for this cloth.
     */
    public function availabilityBlocks()
    {
        return $this->hasMany(AvailabilityBlock::class);
    }
} 
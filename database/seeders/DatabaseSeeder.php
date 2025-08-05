<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\FabricType;
use App\Models\Color;
use App\Models\Size;
use App\Models\BottomType;
use App\Models\BodyTypeFit;
use App\Models\GarmentCondition;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'gender' => 'Male',
        ]);

        // Create sample categories
        Category::create(['name' => 'Wedding']);
        Category::create(['name' => 'Party']);
        Category::create(['name' => 'Casual']);
        Category::create(['name' => 'Formal']);

        // Create sample fabric types
        FabricType::create(['name' => 'Silk']);
        FabricType::create(['name' => 'Cotton']);
        FabricType::create(['name' => 'Polyester']);
        FabricType::create(['name' => 'Linen']);

        // Create sample colors
        Color::create(['name' => 'Red']);
        Color::create(['name' => 'Blue']);
        Color::create(['name' => 'Green']);
        Color::create(['name' => 'Black']);
        Color::create(['name' => 'White']);

        // Create sample sizes
        Size::create(['name' => 'XS']);
        Size::create(['name' => 'S']);
        Size::create(['name' => 'M']);
        Size::create(['name' => 'L']);
        Size::create(['name' => 'XL']);
        Size::create(['name' => 'XXL']);

        // Create sample bottom types
        BottomType::create(['name' => 'Straight']);
        BottomType::create(['name' => 'Skinny']);
        BottomType::create(['name' => 'Wide Leg']);
        BottomType::create(['name' => 'Palazzo']);

        // Create sample body type fits
        BodyTypeFit::create(['name' => 'Regular']);
        BodyTypeFit::create(['name' => 'Slim']);
        BodyTypeFit::create(['name' => 'Loose']);
        BodyTypeFit::create(['name' => 'Oversized']);

        // Create sample garment conditions
        GarmentCondition::create(['name' => 'Brand New']);
        GarmentCondition::create(['name' => 'Like New']);
        GarmentCondition::create(['name' => 'Good Condition']);
        GarmentCondition::create(['name' => 'Worn but Usable']);
    }
}

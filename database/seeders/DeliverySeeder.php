<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Delivery ;

class DeliverySeeder extends Seeder
{
    public function run()
    {
        $governorates = [
            'الجيزة', 'القليوبية', 'الشرقية', 'الدقهلية',
            'البحيرة', 'المنوفية', 'الغربية', 'كفر الشيخ', 'دمياط',
            'بورسعيد', 'الإسماعيلية', 'السويس', 'الفيوم', 'بني سويف',
            'المنيا', 'أسيوط', 'سوهاج', 'قنا', 'الأقصر',
            'أسوان', 'البحر الأحمر', 'الوادي الجديد', 'مطروح',
            'شمال سيناء', 'جنوب سيناء'
        ];

        foreach ($governorates as $gov) {
            Delivery::create([
                'government' => $gov,
                'tax' => rand(50, 100),
            ]);
        }
    }
}

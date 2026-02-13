<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{

    public function run()
    {
        $settings =[
            [
                'key' => 'main_section_text_1' ,
                'value'=> 'تجربة تسوق',
            ],
            [
                'key' => 'main_section_text_2' ,
                'value'=> 'بلا حدود.',
            ],
            [
                'key' => 'main_section_text_3' ,
                'value'=> 'اكتشف أرقى المنتجات العالمية بأفضل الأسعار مع خدمة توصيل سريعة وضمان استرجاع حقيقي.',
            ],
            [
                'key' => 'main_section_cover' ,
                'value'=> 'بلا حدود.',
            ],
            [
                'key' => 'about_us_section_text_1' ,
                'value'=> 'نحن لا نبيع المنتجات فقط، بل نقدم تجربة تسوق فريدة.',
            ],
            [
                'key' => 'about_us_section_text_2' ,
                'value'=> 'بدأ متجرنا بفكرة بسيطة: توفير منتجات عالية الجودة بأسعار تنافسية. اليوم، نحن نفخر بتقديم آلاف المنتجات لعملائنا في جميع أنحاء الوطن العربي مع ضمان جودة حقيقي وخدمة ما بعد البيع لا تضاهى. ',
            ],

            [
                'key' => 'about_us_section_text_3' ,
                'value'=> 'ضمان أصالة المنتج',
            ],
            [
                'key' => 'about_us_section_text_4' ,
                'value'=> 'توصيل خلال 48 ساعة',
            ],
            [
                'key' => 'about_us_section_image' ,
                'value'=> '',
            ],
            [
                'key' => 'branch_section_government_branch_1' ,
                'value'=> 'الجيزة',
            ],
            [
                'key' => 'branch_section_title_address_1' ,
                'value'=> 'مزار مول - Mazar Mall',
            ],
            [
                'key' => 'branch_section_details_address_1' ,
                'value'=> 'الشيخ زايد، الحي الـ 16، اسكوير مول محور جمال عبدالناصر - الدور الأرضي.',
            ],
            [
                'key' => 'branch_section_openning_time' ,
                'value'=> 'يومياً: 10:00 صباحاً - 11:00 مساءً',
            ],
            [
                'key' => 'branch_section_link_map' ,
                'value'=> 'https://www.google.com/maps/place/%D9%85%D9%88%D9%84+%D9%85%D8%B2%D8%A7%D8%B1%E2%80%AD/@30.0533624,30.9636987,17z/data=!3m1!4b1!4m6!3m5!1s0x145859a32da97fef:0x6a1ea2a9efd2e473!8m2!3d30.0533578!4d30.9611238!16s%2Fg%2F11c1p649kq?coh=277534&entry=tts&g_ep=EgoyMDI2MDEyMS4wIPu8ASoKLDEwMDc5MjA3MUgBUAM%3D&skid=3fef6000-4bf5-458f-8e30-bb24a3287d5f',
            ],
        ] ;
        Setting::insert($settings);
    }
}

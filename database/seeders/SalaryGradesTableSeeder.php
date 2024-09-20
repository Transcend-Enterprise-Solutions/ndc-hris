<?php

namespace Database\Seeders;

use App\Models\SalaryGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryGradesTableSeeder extends Seeder
{
    public function run()
    {
        $salaryGradeData = [
            [
                'salary_grade' => 1,'step1' => 13530.00, 'step2' => 13633.00, 'step3' => 13748.00, 'step4' => 13862.00,
                'step5' => 13979.00, 'step6' => 14095.00, 'step7' => 14213.00, 'step8' => 14331.00
            ],
            [
                'salary_grade' => 2,'step1' => 14372.00, 'step2' => 14482.00, 'step3' => 14593.00, 'step4' => 14706.00,
                'step5' => 14818.00, 'step6' => 14931.00, 'step7' => 15047.00, 'step8' => 15161.00
            ],
            [
                'salary_grade' => 3,'step1' => 15265.00, 'step2' => 15384.00, 'step3' => 15501.00, 'step4' => 15621.00,
                'step5' => 15741.00, 'step6' => 15861.00, 'step7' => 15984.00, 'step8' => 16105.00
            ],
            [
                'salary_grade' => 4,'step1' => 16209.00, 'step2' => 16334.00, 'step3' => 16460.00, 'step4' => 16586.00,
                'step5' => 16714.00, 'step6' => 16841.00, 'step7' => 16971.00, 'step8' => 17101.00
            ],
            [
                'salary_grade' => 5,'step1' => 17205.00, 'step2' => 17338.00, 'step3' => 17471.00, 'step4' => 17605.00,
                'step5' => 17739.00, 'step6' => 17877.00, 'step7' => 18014.00, 'step8' => 18151.00
            ],
            [
                'salary_grade' => 6,'step1' => 18255.00, 'step2' => 18396.00, 'step3' => 18537.00, 'step4' => 18680.00,
                'step5' => 18824.00, 'step6' => 18968.00, 'step7' => 19114.00, 'step8' => 19261.00
            ],
            [
                'salary_grade' => 7,'step1' => 19365.00, 'step2' => 19514.00, 'step3' => 19663.00, 'step4' => 19815.00,
                'step5' => 19966.00, 'step6' => 20120.00, 'step7' => 20274.00, 'step8' => 20430.00
            ],
            [
                'salary_grade' => 8,'step1' => 20534.00, 'step2' => 20720.00, 'step3' => 20908.00, 'step4' => 21096.00,
                'step5' => 21287.00, 'step6' => 21479.00, 'step7' => 21674.00, 'step8' => 21870.00
            ],
            [
                'salary_grade' => 9,'step1' => 22219.00, 'step2' => 22404.00, 'step3' => 22591.00, 'step4' => 22780.00,
                'step5' => 22971.00, 'step6' => 23162.00, 'step7' => 23356.00, 'step8' => 23551.00
            ],
            [
                'salary_grade' => 10,'step1' => 24381.00, 'step2' => 24585.00, 'step3' => 24790.00, 'step4' => 24998.00,
                'step5' => 25207.00, 'step6' => 25417.00, 'step7' => 25630.00, 'step8' => 25844.00
            ],
            [
                'salary_grade' => 11,'step1' => 28512.00, 'step2' => 28796.00, 'step3' => 29085.00, 'step4' => 29377.00,
                'step5' => 29673.00, 'step6' => 29974.00, 'step7' => 30278.00, 'step8' => 30587.00
            ],
            [
                'salary_grade' => 12,'step1' => 30705.00, 'step2' => 30989.00, 'step3' => 31277.00, 'step4' => 31568.00,
                'step5' => 31863.00, 'step6' => 32162.00, 'step7' => 32464.00, 'step8' => 32770.00
            ],
            [
                'salary_grade' => 13,'step1' => 32870.00, 'step2' => 33183.00, 'step3' => 33499.00, 'step4' => 33819.00,
                'step5' => 34144.00, 'step6' => 34472.00, 'step7' => 34804.00, 'step8' => 35141.00
            ],
            [
                'salary_grade' => 14,'step1' => 35434.00, 'step2' => 35794.00, 'step3' => 36158.00, 'step4' => 36528.00,
                'step5' => 36900.00, 'step6' => 37278.00, 'step7' => 37662.00, 'step8' => 38049.00
            ],
            [
                'salary_grade' => 15,'step1' => 38413.00, 'step2' => 38810.00, 'step3' => 39212.00, 'step4' => 39619.00,
                'step5' => 40030.00, 'step6' => 40446.00, 'step7' => 40868.00, 'step8' => 41296.00
            ],
            [
                'salary_grade' => 16,'step1' => 41616.00, 'step2' => 42052.00, 'step3' => 42494.00, 'step4' => 42941.00,
                'step5' => 43394.00, 'step6' => 43852.00, 'step7' => 44317.00, 'step8' => 44786.00
            ],
            [
                'salary_grade' => 17,'step1' => 45138.00, 'step2' => 45619.00, 'step3' => 46105.00, 'step4' => 46597.00,
                'step5' => 47095.00, 'step6' => 47599.00, 'step7' => 48109.00, 'step8' => 48626.00
            ],
            [
                'salary_grade' => 18,'step1' => 49015.00, 'step2' => 49542.00, 'step3' => 50077.00, 'step4' => 50617.00,
                'step5' => 51166.00, 'step6' => 51721.00, 'step7' => 52282.00, 'step8' => 52851.00
            ],
            [
                'salary_grade' => 19,'step1' => 53873.00, 'step2' => 54649.00, 'step3' => 55437.00, 'step4' => 56237.00,
                'step5' => 57051.00, 'step6' => 57878.00, 'step7' => 58719.00, 'step8' => 59573.00
            ],
            [
                'salary_grade' => 20,'step1' => 60157.00, 'step2' => 61032.00, 'step3' => 61922.00, 'step4' => 62827.00,
                'step5' => 63747.00, 'step6' => 64669.00, 'step7' => 65599.00, 'step8' => 66532.00
            ],
            [
                'salary_grade' => 21,'step1' => 67005.00, 'step2' => 67992.00, 'step3' => 68996.00, 'step4' => 70016.00,
                'step5' => 71054.00, 'step6' => 72107.00, 'step7' => 73143.00, 'step8' => 74231.00
            ],
            [
                'salary_grade' => 22,'step1' => 74836.00, 'step2' => 75952.00, 'step3' => 77086.00, 'step4' => 78238.00,
                'step5' => 79409.00, 'step6' => 80562.00, 'step7' => 81771.00, 'step8' => 82999.00
            ],
            [
                'salary_grade' => 23,'step1' => 83659.00, 'step2' => 84918.00, 'step3' => 86199.00, 'step4' => 87507.00,
                'step5' => 88936.00, 'step6' => 90387.00, 'step7' => 91862.00, 'step8' => 93299.00
            ],
            [
                'salary_grade' => 24,'step1' => 94132.00, 'step2' => 95668.00, 'step3' => 97230.00, 'step4' => 98817.00,
                'step5' => 100430.00, 'step6' => 102069.00, 'step7' => 103685.00, 'step8' => 105378.00
            ],
            [
                'salary_grade' => 25,'step1' => 107208.00, 'step2' => 108958.00, 'step3' => 110736.00, 'step4' => 112543.00,
                'step5' => 114381.00, 'step6' => 116247.00, 'step7' => 118145.00, 'step8' => 120073.00
            ],
            [
                'salary_grade' => 26,'step1' => 121146.00, 'step2' => 123122.00, 'step3' => 125132.00, 'step4' => 127174.00,
                'step5' => 129250.00, 'step6' => 131359.00, 'step7' => 133503.00, 'step8' => 135682.00
            ],
            [
                'salary_grade' => 27,'step1' => 136893.00, 'step2' => 139128.00, 'step3' => 141399.00, 'step4' => 143638.00,
                'step5' => 145983.00, 'step6' => 148080.00, 'step7' => 150498.00, 'step8' => 152954.00
            ],
            [
                'salary_grade' => 28,'step1' => 154320.00, 'step2' => 156838.00, 'step3' => 159398.00, 'step4' => 161845.00,
                'step5' => 164485.00, 'step6' => 167171.00, 'step7' => 169654.00, 'step8' => 172423.00
            ],
            [
                'salary_grade' => 29,'step1' => 173962.00, 'step2' => 176802.00, 'step3' => 179688.00, 'step4' => 182621.00,
                'step5' => 185601.00, 'step6' => 188267.00, 'step7' => 191340.00, 'step8' => 194463.00
            ],
            [
                'salary_grade' => 30,'step1' => 196199.00, 'step2' => 199401.00, 'step3' => 202558.00, 'step4' => 205765.00,
                'step5' => 209022.00, 'step6' => 212434.00, 'step7' => 215796.00, 'step8' => 219319.00
            ],
            [
                'salary_grade' => 31,'step1' => 285813.00, 'step2' => 291395.00, 'step3' => 297086.00, 'step4' => 302741.00,
                'step5' => 308504.00, 'step6' => 314468.00, 'step7' => 320516.00, 'step8' => 326681.00
            ],
            [
                'salary_grade' => 32,'step1' => 339921.00, 'step2' => 346777.00, 'step3' => 353769.00, 'step4' => 360727.00,
                'step5' => 368002.00, 'step6' => 375424.00, 'step7' => 382996.00, 'step8' => 390719.00
            ],
            [
                'salary_grade' => 33,'step1' => 428994.00, 'step2' => 441863.00, 'step3' => null, 'step4' => null,
                'step5' => null, 'step6' => null, 'step7' => null, 'step8' => null
            ]
        ];
    
        foreach ($salaryGradeData as $data) {
            SalaryGrade::create([
                'salary_grade' => $data['salary_grade'],
                'step1' => $data['step1'],
                'step2' => $data['step2'],
                'step3' => $data['step3'] ?? 0,
                'step4' => $data['step4'] ?? 0,
                'step5' => $data['step5'] ?? 0,
                'step6' => $data['step6'] ?? 0,
                'step7' => $data['step7'] ?? 0,
                'step8' => $data['step8'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
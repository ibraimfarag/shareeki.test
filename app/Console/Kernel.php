<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
            $schedule->command('ads:expire')->dailyAt('00:00');
                $schedule->command('ads:expire')->dailyAt('12:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}



// تأكد من إضافة الكرون على السيرفر لتشغيل مجدول لارافيل:

// افتح محرر الكرون:

// crontab -e

// أضف السطر:

// php /path/to/your/project/artisan schedule:run >> /dev/null 2>&1

// ملاحظات:

// dailyAt('00:00') يشغّل المهمة مرة واحدة عند بداية كل يوم.

// dailyAt('12:00') يشغّلها مرة عند منتصف اليوم.

// تأكد أن timezone في config/app.php مضبوط كما تريد، لأن أوقات الجدولة تعتمد عليه.

// يمكن تغيير 12:00 إلى 13:00 أو أي وقت بصيغة HH:MM لو تحب.


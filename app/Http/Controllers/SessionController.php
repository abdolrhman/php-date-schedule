<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateDatesScheduler;
use Illuminate\Http\Request;

/**
 * Author : Abdulrahman
 * Getting List of dates Depend on some inputs
 * Class SessionController
 * @package App\Http\Controllers
 */
class SessionController extends Controller
{
    public function Scheduler(GenerateDatesScheduler $request)
    {
        /**
         * Getting Inputs
         */
        $arrOfDates = [];
        $chapters = 30;
        $NoOfDaysToFinishChapter = $request->input('NoOfDaysToFinishChapter');
        //exTest: $NoOfDaysToFinishChapter = 1;
        $startDate = $request->input('startDate');
        //ex: $startDate = '2019-12-1';
        $NoOfDaysConsumedToFinishAChapter = 0;
        $NoOfChaptersFinished = 0;
        $daysOfWeekToFinishBook = $request->input('daysOfWeekToFinishBook');

        /**
         * Second Validation
         */
        if ($NoOfDaysToFinishChapter === 0 || empty($daysOfWeekToFinishBook)) {
            return response()->json(['msg' => 'Advance validation depends on business logic']);
        }
        //also there are some validation can be done on $daysOfWeekToFinishBook, like
        //1. redundant number in the array
        //2. array length
        //for simplicity i wont deal with this

        //ex: $daysOfWeekToFinishBook = [1,2,3];
        $WeekDaysNumbersMapper = [
            'Sat' => 1,
            'Sun' => 2,
            'Mon' => 3,
            'Tue' => 4,
            'Wed' => 5,
            'Thu' => 6,
            'Fri' => 7
        ];

        //this instead of do..while
        $startDate = date('Y-m-d', strtotime($startDate . ' - 1 days'));

        /**
         * Processor
         */
        $date = $startDate;
        while ($NoOfChaptersFinished < $chapters) {
            //count one to the date given
            $date = date('Y-m-d', strtotime($date . ' + 1 days'));
            //get day Number of the week that represent this date day ex: saturday => 1
            $day = $WeekDaysNumbersMapper[date('D', strtotime($date))];
            //check day is in the given user array
            if (in_array($day, $daysOfWeekToFinishBook)) {
                array_push($arrOfDates, $date);
                if ($NoOfDaysConsumedToFinishAChapter < $NoOfDaysToFinishChapter) {
                    $NoOfDaysConsumedToFinishAChapter++;
                } else {
                    $NoOfDaysConsumedToFinishAChapter = 1;
                    $NoOfChaptersFinished++;
                }
            }

        }
        array_pop($arrOfDates);
        return response()
            ->json(['data' => $arrOfDates, 'statusCode' => 200, 'NumberOfDates' => count($arrOfDates)]);
    }
}

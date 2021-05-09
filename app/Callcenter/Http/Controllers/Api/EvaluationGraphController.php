<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as ImageObject;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\User\User;

class EvaluationGraphController extends Controller
{
    /**
     * Height of the grey arrow
     * @var int
     */
    const ARROW_HEIGHT = 6;

    /**
     * Width of the grey arrow
     * @var int
     */
    const ARROW_WIDTH = 8;

    /**
     * Width of a column containing a bar
     * @var int
     */
    const BAR_COL_WIDTH = 75;

    /**
     * Margin to bottom befor bar starts
     * @var int
     */
    const BAR_MARGIN_BOTTOM = 70;

    /**
     * margin to left before bar starts
     * @var int
     */
    const BAR_MARGIN_LEFT = 75;

    /**
     * Maximum height of a bar
     * @var int
     */
    const BAR_MAX_HEIGHT = 312;

    /**
     * Maximum width of a bar
     * @var int
     */
    const BAR_WIDTH = 32;

    /**
     * Colorcode black
     * @var string
     */
    const COLOR_BLACK = '#2f404e';

    /**
     * Colorcode grey
     * @var string
     */
    const COLOR_GREY = '#cfcfcf';

    /**
     * Colorcode Red
     * @var string
     */
    const COLOR_RED = '#ff424c';

    /**
     * Colorcode Light Red
     * @var string
     */
    const COLOR_RED_LIGHT = '#db0831';

    /**
     * default height of graph
     * @var int
     */
    const GRAPH_HEIGHT = 420;

    /**
     * Thickness of grey line
     * @var int
     */
    const LINE_THICKNESS = 2;

    /**
     * @var string
     */
    private $font_bold;

    /**
     * @var string
     */
    private $font_regular;

    public function __construct()
    {
        $this->font_regular = resource_path('fonts/Roboto/Roboto-Regular.ttf');
        $this->font_bold = resource_path('fonts/Roboto/Roboto-Bold.ttf');
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    public function endpoint(Request $request)
    {
        /**
         * TODO Data validation
         */
        $user = User::find($request->agent_id);
        switch ($request->encoding) {
            case 'base64':
            case 'url-encoded':
            case 'data-url':
                $cfg['encoding'] = 'data-url';
                break;
            default:
                $cfg['encoding'] = 'png';
        }
        if (isset($request->week)) {
            $cfg['week'] = $request->week;
            $cfg['year'] = $request->year ?? now('Europe/Berlin')->year;
            return $this->weekGraph($user, $cfg);
        }
        if (isset($request->year)) {
            return $this->yearGraph($user, $request->year);
        }
    }

    /**
     * Draws an rectangle with a gradient into an ImageObject
     * Can only draw from left-down to right-up
     *
     * @param ImageObject $img   the ImageObject
     * @param int         $x     the starting point x-value
     * @param int         $y     the starting point y-value
     * @param int         $x1    the end point x-value
     * @param int         $y1    the end point y-value
     * @param string      $start the color at the beginning
     * @param string      $end   the color at the end
     */
    public function rectangleGradient(ImageObject $img, int $x, int $y, int $x1, int $y1, string $start, string $end)
    {
        if ($x > $x1 || $y > $y1) {
            return false;
        }
        $start = preg_replace('/#/', '', $start);
        $end = preg_replace('/#/', '', $end);
        $s = [
            \hexdec(substr($start, 0, 2)),
            \hexdec(substr($start, 2, 2)),
            \hexdec(substr($start, 4, 2)),
        ];
        $e = [
            \hexdec(substr($end, 0, 2)),
            \hexdec(substr($end, 2, 2)),
            \hexdec(substr($end, 4, 2)),
        ];
        $steps = $y1 - $y;
        for ($i = 0; $i < $steps; $i++) {
            $r = $s[0] - ((($s[0] - $e[0]) / $steps) * $i);
            $g = $s[1] - ((($s[1] - $e[1]) / $steps) * $i);
            $b = $s[2] - ((($s[2] - $e[2]) / $steps) * $i);
            $color = [$r, $g, $b];
            $img->rectangle($x, $y + $i, $x1, $y + $i + 1, function ($el) use ($color) {
                $el->background($color);
            });
        }
    }

    /**
     * @param  User    $user
     * @param  array   $cfg
     * @return mixed
     */
    public function weekGraph(User $user, array $cfg)
    {
        $dt_start = Carbon::create($cfg['year']);
        $dt_start->week = $cfg['week'];
        $dt_start->startOfWeek();
        $dt_end = Carbon::create($dt_start->year, $dt_start->month, $dt_start->day)->endOfWeek();
        $cfg['data'] = [];
        $comments = $user->comments()
            ->whereReason(CommentReason::APPOINTMENT)
            ->whereBetween('created_at', [$dt_start, $dt_end])
            ->get();
        $iv = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($dt_start, $iv, $dt_end);
        foreach ($period as $date) {
            $d_start = $date->startOfDay()->toDateTimeString();
            $d_end = $date->endOfDay()->toDateTimeString();
            $cfg['data'][$date->locale('de')->minDayName] = $comments->whereBetween('created_at', [$d_start, $d_end])->count();
        }
        return $this->drawGraph($cfg);
    }

    /**
     * Draws a graph that displays the appointments an agent reached during a year
     *
     * @param  User    $user
     * @param  array   $cfg
     * @return mixed
     */
    public function yearGraph(User $user, array $cfg)
    {
        $cfg['data'] = [];
        $start = Carbon::create($cfg['year']);
        $end = Carbon::create($cfg['year'])->endOfYear();
        $comments = $user
            ->comments()
            ->whereReason(CommentReason::APPOINTMENT)
            ->whereBetween('date', [$start, $end])
            ->get();
        $iv = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($start, $iv, $end);
        foreach ($period as $month) {
            $start = $month->startOfMonth()->toDateTimeString();
            $end = $month->endOfMonth()->toDateTimeString();
            $data[$month->locale('de')->shortMonthName] = $comments
                ->whereBetween('date', [$start, $end])
                ->count();
        }
        return $this->drawGraph($cfg);
    }

    /**
     * Draws the grey arrow into an ImageObject
     *
     * @param ImageObject $img          the Image Object
     * @param int         $w            the width of the arrow
     * @param int         $line_lower_y the lower end of the line of the arrow
     */
    private function arrow(ImageObject $img, $w, $line_lower_y)
    {
        $bg_grey = function ($el) {
            $el->background(self::COLOR_GREY);
        };
        $line_upper_y = $line_lower_y - self::LINE_THICKNESS + 1;
        $points = [
            $w, $line_upper_y,
            $w, $line_lower_y,
            $w - self::ARROW_WIDTH, $line_lower_y + (self::ARROW_HEIGHT - self::LINE_THICKNESS) / 2,
            $w - self::ARROW_WIDTH, $line_upper_y - (self::ARROW_HEIGHT - self::LINE_THICKNESS) / 2,
        ];
        $img
            ->rectangle(1, $line_upper_y, $w - self::ARROW_WIDTH, $line_lower_y, $bg_grey)
            ->rectangle(0, $line_upper_y - 2, 1, $line_lower_y + 2, $bg_grey)
            ->polygon($points, $bg_grey);
    }

    /**
     * Draws Bars into an ImageObject
     *
     * @param ImageObject $img    the Image Object
     * @param int         $x      the starting x-value of the first bar
     * @param int         $y_base the starting y-value of the bars
     * @param array       $data   an data with description as key and a number as value
     */
    private function bars(ImageObject $img, $x, $y_base, $data)
    {
        $max = max($data);
        $t_format = function ($el) {
            $el->file($this->font_bold);
            $el->size(12);
            $el->color(self::COLOR_BLACK);
            $el->align('center');
        };
        foreach ($data as $title => $value) {
            $t_num = number_format($value, 0, ',', '.');
            $t_center = $x + 17;
            if ($value > 0) {
                $bar_height = self::BAR_MAX_HEIGHT * $value / $max;
                $img->text($t_num, $t_center, (int) ($y_base - $bar_height - 17), $t_format);
                $this->rectangleGradient($img, $x, (int) ($y_base - $bar_height), $x + self::BAR_WIDTH, $y_base, self::COLOR_RED_LIGHT, self::COLOR_RED);
            } else {
                $img->text($t_num, $t_center, $y_base - 1, $t_format);
            }
            $img->text((string) $title, $t_center, (int) ($y_base + 26), function ($el) {
                $el->file($this->font_regular);
                $el->size(12);
                $el->color(self::COLOR_BLACK);
                $el->align('center');
            });
            $img->text($t_num, $t_center, $y_base + 56, $t_format);
            $x += self::BAR_COL_WIDTH;
        }
    }

    /**
     * @param  array   $cfg
     * @return mixed
     */
    private function drawGraph($cfg)
    {
        $w = count($cfg['data']) * self::BAR_COL_WIDTH;
        $img = Image::canvas($w, self::GRAPH_HEIGHT);
        $this->arrow($img, $w, self::GRAPH_HEIGHT - self::BAR_MARGIN_BOTTOM);
        $this->bars($img, self::BAR_MARGIN_LEFT + 1, self::GRAPH_HEIGHT - self::BAR_MARGIN_BOTTOM - self::LINE_THICKNESS, $cfg['data']);
        return $img->response($cfg['encoding']);
    }
}

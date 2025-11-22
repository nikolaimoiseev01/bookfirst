<?php

namespace App\Service;

use Illuminate\Http\Request;

class PartPageBlockStatus
{

    public function get_status($class_wrap, $color): array
    {
        if ($color === 'green') {
            $status_color = '#47AF98';
            $status_color_shadow = 'box-shadow: 0 0 7px 1px #47af9880;';
            $status_icon = '<img class="status_icon" src="/img/check_green.svg" type="image/svg+xml">';
        } elseif ($color === 'yellow') {
            $status_color = '#FFA500';
            $status_color_shadow = 'box-shadow: 0 0 7px 1px #FFA50080;';
            $status_icon = '<img class="status_icon" src="/img/hourglass_yellow.svg" type="image/svg+xml">';
        } elseif ($color === 'grey') {
            $status_color = '#cbcbcb';
            $status_color_shadow = 'box-shadow: 0 0 7px 1px rgba(0, 0, 0, 0.07);';
            $status_icon = '<img class="status_icon" src="/img/hourglass_grey.svg" type="image/svg+xml">';
        } elseif ($color === 'blue') {
            $status_color = '#578bcd';
            $status_color_shadow = 'box-shadow: 0 0 7px 1px #578bcda1;';
            $status_icon = '<img class="status_icon" src="/img/process_blue.svg" type="image/svg+xml">';
        } else {
            $status_color = '#cbcbcb';
            $status_color_shadow = 'box-shadow: 0 0 7px 1px rgba(0, 0, 0, 0.07);';
            $status_icon = '<img class="status_icon" src="/img/hourglass_grey.svg" type="image/svg+xml">';
        }

        $page_style = /** @lang text */
            "<style>
                $class_wrap {
                    border: 2px $status_color solid;
                }

                $class_wrap .line {
                    background: $status_color;
                }

                $class_wrap .status_icon svg {
                    fill: $status_color;
                }

                $class_wrap .block_wrap {
                    $status_color_shadow
                }

                $class_wrap .hero_wrap {
                    border-bottom: 1px $status_color solid;
                }



                $class_wrap .hero_wrap h2 {
                    color: $status_color;
                }
            </style>";

        return [
            'status_color' => $status_color,
            'status_color_shadow' => $status_color_shadow,
            'status_icon' => $status_icon,
            'page_style' => $page_style
        ];
    }
}

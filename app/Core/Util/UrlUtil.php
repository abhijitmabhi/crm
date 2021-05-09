<?php


namespace LocalheroPortal\Core\Util;


class UrlUtil
{

    public static function getUrlDomain($url) {
        $shortened_url = $url;
        $shortened_url = str_replace('https://', '', $shortened_url);
        $shortened_url = str_replace('http://', '', $shortened_url);
        $shortened_url = str_replace('www.', '', $shortened_url);
        $url_parts = explode('/', $shortened_url);
        return $url_parts[0];
    }
}
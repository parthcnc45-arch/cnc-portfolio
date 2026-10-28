<?php
/**
 * Country Flags Helper using FlagCDN
 * Maps country names to ISO 3166-1 alpha-2 codes and renders accessible,
 * responsive SVG flag badges for the portfolio listing table.
 */

if (!function_exists('get_country_iso_code')) {
    /**
     * Get the lowercase ISO 3166-1 alpha-2 code for a country name.
     * Covers all countries from js/countries.js plus common variations and aliases.
     *
     * @param string $name Country name or alias
     * @return string|null Two-letter ISO code or null if unrecognized
     */
    function get_country_iso_code($name) {
        static $country_map = null;
        if ($country_map === null) {
            $country_map = [
                'afghanistan' => 'af',
                'albania' => 'al',
                'algeria' => 'dz',
                'andorra' => 'ad',
                'angola' => 'ao',
                'antigua and barbuda' => 'ag',
                'argentina' => 'ar',
                'armenia' => 'am',
                'australia' => 'au',
                'austria' => 'at',
                'azerbaijan' => 'az',
                'bahamas' => 'bs',
                'bahrain' => 'bh',
                'bangladesh' => 'bd',
                'barbados' => 'bb',
                'belarus' => 'by',
                'belgium' => 'be',
                'belize' => 'bz',
                'benin' => 'bj',
                'bhutan' => 'bt',
                'bolivia' => 'bo',
                'bosnia and herzegovina' => 'ba',
                'botswana' => 'bw',
                'brazil' => 'br',
                'brunei' => 'bn',
                'bulgaria' => 'bg',
                'burkina faso' => 'bf',
                'burundi' => 'bi',
                "côte d'ivoire" => 'ci',
                "cote d'ivoire" => 'ci',
                "cã´te d'ivoire" => 'ci',
                'cabo verde' => 'cv',
                'cape verde' => 'cv',
                'cambodia' => 'kh',
                'cameroon' => 'cm',
                'canada' => 'ca',
                'central african republic' => 'cf',
                'chad' => 'td',
                'chile' => 'cl',
                'china' => 'cn',
                'colombia' => 'co',
                'comoros' => 'km',
                'congo' => 'cg',
                'congo (congo-brazzaville)' => 'cg',
                'republic of the congo' => 'cg',
                'costa rica' => 'cr',
                'croatia' => 'hr',
                'cuba' => 'cu',
                'cyprus' => 'cy',
                'czechia' => 'cz',
                'czech republic' => 'cz',
                'czechia (czech republic)' => 'cz',
                'democratic republic of the congo' => 'cd',
                'dr congo' => 'cd',
                'drc' => 'cd',
                'denmark' => 'dk',
                'djibouti' => 'dj',
                'dominica' => 'dm',
                'dominican republic' => 'do',
                'ecuador' => 'ec',
                'egypt' => 'eg',
                'el salvador' => 'sv',
                'equatorial guinea' => 'gq',
                'eritrea' => 'er',
                'estonia' => 'ee',
                'eswatini' => 'sz',
                "eswatini (fmr. 'swaziland')" => 'sz',
                'swaziland' => 'sz',
                'ethiopia' => 'et',
                'fiji' => 'fj',
                'finland' => 'fi',
                'france' => 'fr',
                'gabon' => 'ga',
                'gambia' => 'gm',
                'georgia' => 'ge',
                'germany' => 'de',
                'ghana' => 'gh',
                'greece' => 'gr',
                'grenada' => 'gd',
                'guatemala' => 'gt',
                'guinea' => 'gn',
                'guinea-bissau' => 'gw',
                'guyana' => 'gy',
                'haiti' => 'ht',
                'holy see' => 'va',
                'vatican' => 'va',
                'vatican city' => 'va',
                'honduras' => 'hn',
                'hungary' => 'hu',
                'iceland' => 'is',
                'india' => 'in',
                'indonesia' => 'id',
                'iran' => 'ir',
                'iraq' => 'iq',
                'ireland' => 'ie',
                'israel' => 'il',
                'italy' => 'it',
                'jamaica' => 'jm',
                'japan' => 'jp',
                'jordan' => 'jo',
                'kazakhstan' => 'kz',
                'kenya' => 'ke',
                'kiribati' => 'ki',
                'kuwait' => 'kw',
                'kyrgyzstan' => 'kg',
                'laos' => 'la',
                'latvia' => 'lv',
                'lebanon' => 'lb',
                'lesotho' => 'ls',
                'liberia' => 'lr',
                'libya' => 'ly',
                'liechtenstein' => 'li',
                'lithuania' => 'lt',
                'luxembourg' => 'lu',
                'madagascar' => 'mg',
                'malawi' => 'mw',
                'malaysia' => 'my',
                'maldives' => 'mv',
                'mali' => 'ml',
                'malta' => 'mt',
                'marshall islands' => 'mh',
                'mauritania' => 'mr',
                'mauritius' => 'mu',
                'mexico' => 'mx',
                'micronesia' => 'fm',
                'moldova' => 'md',
                'monaco' => 'mc',
                'mongolia' => 'mn',
                'montenegro' => 'me',
                'morocco' => 'ma',
                'mozambique' => 'mz',
                'myanmar' => 'mm',
                'myanmar (formerly burma)' => 'mm',
                'burma' => 'mm',
                'namibia' => 'na',
                'nauru' => 'nr',
                'nepal' => 'np',
                'netherlands' => 'nl',
                'new zealand' => 'nz',
                'nicaragua' => 'ni',
                'niger' => 'ne',
                'nigeria' => 'ng',
                'north korea' => 'kp',
                'north macedonia' => 'mk',
                'macedonia' => 'mk',
                'norway' => 'no',
                'oman' => 'om',
                'pakistan' => 'pk',
                'palau' => 'pw',
                'palestine' => 'ps',
                'palestine state' => 'ps',
                'panama' => 'pa',
                'papua new guinea' => 'pg',
                'paraguay' => 'py',
                'peru' => 'pe',
                'philippines' => 'ph',
                'poland' => 'pl',
                'portugal' => 'pt',
                'qatar' => 'qa',
                'romania' => 'ro',
                'russia' => 'ru',
                'russian federation' => 'ru',
                'rwanda' => 'rw',
                'saint kitts and nevis' => 'kn',
                'saint lucia' => 'lc',
                'saint vincent and the grenadines' => 'vc',
                'samoa' => 'ws',
                'san marino' => 'sm',
                'sao tome and principe' => 'st',
                'saudi arabia' => 'sa',
                'senegal' => 'sn',
                'serbia' => 'rs',
                'seychelles' => 'sc',
                'sierra leone' => 'sl',
                'singapore' => 'sg',
                'slovakia' => 'sk',
                'slovenia' => 'si',
                'solomon islands' => 'sb',
                'somalia' => 'so',
                'south africa' => 'za',
                'south korea' => 'kr',
                'korea' => 'kr',
                'south sudan' => 'ss',
                'spain' => 'es',
                'sri lanka' => 'lk',
                'sudan' => 'sd',
                'suriname' => 'sr',
                'sweden' => 'se',
                'switzerland' => 'ch',
                'syria' => 'sy',
                'tajikistan' => 'tj',
                'tanzania' => 'tz',
                'thailand' => 'th',
                'timor-leste' => 'tl',
                'east timor' => 'tl',
                'togo' => 'tg',
                'tonga' => 'to',
                'trinidad and tobago' => 'tt',
                'tunisia' => 'tn',
                'turkey' => 'tr',
                'türkiye' => 'tr',
                'turkmenistan' => 'tm',
                'tuvalu' => 'tv',
                'uganda' => 'ug',
                'ukraine' => 'ua',
                'united arab emirates' => 'ae',
                'uae' => 'ae',
                'uea' => 'ae',
                'dubai' => 'ae',
                'united kingdom' => 'gb',
                'uk' => 'gb',
                'great britain' => 'gb',
                'united states of america' => 'us',
                'united states' => 'us',
                'usa' => 'us',
                'us' => 'us',
                'uruguay' => 'uy',
                'uzbekistan' => 'uz',
                'vanuatu' => 'vu',
                'venezuela' => 've',
                'vietnam' => 'vn',
                'viet nam' => 'vn',
                'yemen' => 'ye',
                'zambia' => 'zm',
                'zimbabwe' => 'zw'
            ];
        }

        if (empty($name)) {
            return null;
        }

        $clean = mb_strtolower(trim($name), 'UTF-8');
        $clean = trim($clean, " \t\n\r\0\x0B.,");
        // Accept both country names (e.g. "India") and ISO-2 codes (e.g. "IN")
        // returned by IP geolocation APIs.
        if (preg_match('/^[a-z]{2}$/', $clean)) {
            return $clean;
        }
        return $country_map[$clean] ?? null;
    }
}

if (!function_exists('render_country_flags')) {
    /**
     * Render country flags and names for the portfolio table.
     * Supports multiple comma-separated countries.
     *
     * @param string|null $country_raw Raw country string from database
     * @return string Safe HTML string
     */
    function render_country_flags($country_raw) {
        if (empty($country_raw) || trim($country_raw) === '' || trim($country_raw) === '-') {
            return '<span class="text-muted">-</span>';
        }

        $parts = explode(',', $country_raw);
        $items = [];
        $full_titles = [];

        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '') continue;

            // IP geolocation may store ISO-2 codes (e.g. IN) instead of names.
            $country_names_by_code = [
                'in' => 'India', 'us' => 'United States', 'gb' => 'United Kingdom',
                'ae' => 'United Arab Emirates', 'ca' => 'Canada', 'au' => 'Australia',
                'nz' => 'New Zealand', 'sg' => 'Singapore', 'pk' => 'Pakistan',
                'bd' => 'Bangladesh', 'lk' => 'Sri Lanka', 'np' => 'Nepal',
                'de' => 'Germany', 'fr' => 'France', 'it' => 'Italy', 'es' => 'Spain',
                'jp' => 'Japan', 'cn' => 'China', 'br' => 'Brazil', 'ru' => 'Russia',
                'za' => 'South Africa', 'sa' => 'Saudi Arabia', 'id' => 'Indonesia',
                'my' => 'Malaysia', 'th' => 'Thailand', 'ph' => 'Philippines',
                'ng' => 'Nigeria', 'ke' => 'Kenya', 'mx' => 'Mexico',
                'kr' => 'South Korea', 'tr' => 'Turkey', 'nl' => 'Netherlands',
                'ch' => 'Switzerland', 'se' => 'Sweden', 'no' => 'Norway',
                'ie' => 'Ireland', 'pk' => 'Pakistan'
            ];
            $raw_code = strtolower($p);
            $display_name = $country_names_by_code[$raw_code] ?? $p;
            $full_titles[] = $display_name;
            $code = get_country_iso_code($p);
            $safe_name = htmlspecialchars($display_name, ENT_QUOTES, 'UTF-8');

            $html = '<span class="country-item">';
            if ($code) {
                $flag_url = 'https://flagcdn.io/flags/4x3/' . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . '.svg';
                $html .= '<img class="country-flag-img" src="' . $flag_url . '" width="24" height="18" alt="' . $safe_name . ' flag" loading="lazy" onerror="this.style.display=\'none\';">';
            }
            $html .= '<span class="country-name-text">' . $safe_name . '</span>';
            $html .= '</span>';

            $items[] = $html;
        }

        if (empty($items)) {
            return '<span class="text-muted">-</span>';
        }

        $tooltip = htmlspecialchars(implode(', ', $full_titles), ENT_QUOTES, 'UTF-8');
        return '<div class="country-cell-wrapper" title="' . $tooltip . '">' . implode('<span class="country-separator-sr" style="display:none;">, </span>', $items) . '</div>';
    }
}

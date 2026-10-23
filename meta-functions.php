<?php
function extractProjectName($domain) {
    $url = str_replace('https://', '', $domain);
    $url = str_replace('www.', '', $url);
    // Split the domain by periods
    $domainParts = explode('.', $url);
    
    // Get the third part from the end of the domain
    $projectName = $domainParts[0];
    
    return $projectName;
}
function get_metas_cnc($url){  
    $ch = curl_init();
    $change_url = $url.'?ver=56.45';
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $change_url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    
    $data = curl_exec($ch);
    curl_close($ch);
    
    // Load HTML to DOM Object
    $dom = new DOMDocument();
    @$dom->loadHTML($data);
    
    // Parse DOM to get Title
    $nodes = $dom->getElementsByTagName('title');
    $title = $nodes->item(0)->nodeValue;
    
    // Parse DOM to get Meta Description
    $metas = $dom->getElementsByTagName('meta');
    $body = "";
    $image_src = SITE_URL."/images/no-image.png";
    for ($i = 0; $i < $metas->length; $i ++) {
        $meta = $metas->item($i);
        if ($meta->getAttribute('name') == 'description') {
            $body = $meta->getAttribute('content');
        }
        if ($meta->getAttribute('property') == 'og:image') {
            $image_src = $meta->getAttribute('content');
        }
    }
    
    $output = array(
        'title' => $title,
        'image_src' => $image_src,
        'body' => $body
    );

    return json_encode($output);

}

?>



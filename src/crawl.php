<?php
// Include the Simple HTML DOM Parser library
include('../lib/simple_html_dom.php');

function getBaseUrlFromShortenedUrl($url)
{
    $headers = get_headers($url, 1);

    if (isset($headers['Location'])) {
        $finalUrl = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];

        return parse_url($finalUrl, PHP_URL_HOST);
    }

    return parse_url($url, PHP_URL_HOST);
}

// Read URLs from the JSON file
$urlsJson = file_get_contents('../urls.json');
$urlsArray = json_decode($urlsJson, true);

// Initialize variables for counting items with title, price, and discount
$crawlData = [];

// Loop through all URLs in $urlsArray
foreach ($urlsArray as $urlInfo) {
    $url = $urlInfo['url'];

    $base_url = getBaseUrlFromShortenedUrl($url);

    // Get HTML content from the URL using file_get_contents
    $html = file_get_contents($url);

    // Create a Simple HTML DOM object
    $dom = new simple_html_dom();
    $dom->load($html);

    // Find and print the content of specific HTML elements (e.g., <title>, <span>, <div>)
    $title = $dom->find('._5uSO3a', 0);
    $price = $dom->find('.TVzooJ.typo-m18', 0);
    $img = $dom->find('meta[property=og:image"]', 0);
    $discount = $dom->find('.badge__promotion', 0);

    // Check if title and price are present and discount is either a string or empty
    if ($title && $price && !empty($title->innertext) && !empty($price->innertext) && ($discount || $discount === "")) {
        // Check if discount is present
        $discount_filter = '';
        if ($discount) {
            preg_match('/(\d+)%/', $discount->innertext, $matches);

            // Check if a match is found
            if (!empty($matches)) {
                $discount_filter = $matches[1];
            }
        }

        // Store crawl data in the array
        $crawlData[] = [
            'url' => $url,
            'base_url' => $base_url,
            'title' => $title->innertext,
            'price' => $price->innertext,
            'discount' => $discount_filter,
            'img' => $img->content,
        ];
    }

    // Clear the Simple HTML DOM object to free up resources
    $dom->clear();
    unset($dom);
}

// Export the crawled data to a JSON file
$outputJson = json_encode($crawlData, JSON_PRETTY_PRINT);
file_put_contents('../data/data.json', $outputJson);

// Output success message
echo "Data exported to output.json successfully.\n";
?>

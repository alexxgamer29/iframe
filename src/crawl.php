<?php
// Include the Simple HTML DOM Parser library
include('../lib/simple_html_dom.php');

function getBaseUrlFromShortenedUrl($url)
{
    $headers = get_headers($url, 1);

    if (isset($headers['Location'])) {
        $final_url = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];

        return parse_url($final_url, PHP_URL_HOST);
    }

    return parse_url($url, PHP_URL_HOST);
}

// Read URLs from the text file
$url_arr = file('../urls.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Initialize variables for counting items with title, price, and discount
$crawl_data = [];
$failed_url = [];

// Loop through all URLs in $url_arr
foreach ($url_arr as $url) {
    echo $url . "\n";
    $base_url = getBaseUrlFromShortenedUrl($url);
    echo $base_url . "\n";

    if (strpos($base_url, 'shopee') !== false) {
        try {
            //  Get HTML content from the URL using file_get_contents
            $html = file_get_contents($url);

            // Create a Simple HTML DOM object
            $dom = new simple_html_dom();
            $dom->load($html);

            // Check landing page
            $landing_page_check = $dom->find('title[data-rh=true]', 0);
            echo $landing_page_check . "\n";
            if ($landing_page_check) {
                echo "\$Shopee Single.\n";

                // Find and print the content of specific HTML elements (e.g., <title>, <span>, <div>)
                $title = $dom->find('meta[property=og:title]', 0);
                $price = $dom->find('.typo-m18', 0);
                $img = $dom->find('meta[property=og:image]', 0);
                $discount = $dom->find('.badge__promotion', 0);

                // Check if title and price are present and discount is either a string or empty
                if ($title && $price && !empty($title->content) && !empty($price->innertext)) {
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
                    $crawl_data[] = [
                        'url' => $url,
                        'base_url' => $base_url,
                        'title' => str_replace(" | Shopee Việt Nam", "", $title->content),
                        'price' => $price->innertext,
                        'discount' => $discount_filter ? $discount_filter : null,
                        'img' => $img->content,
                    ];
                }

                // Clear the Simple HTML DOM object to free up resources
                $dom->clear();
                unset($dom);
            } else {
                echo "\$Shopee Landing page.\n";
                $html = shell_exec("node chromium.js {$url}");
                $dom = new simple_html_dom();
                $dom->load($html);
                $scripts = $dom->find('script');

                foreach ($scripts as $script) {
                    if ($script->type === 'application/ld+json') {
                        $json = json_decode($script->innertext, true);
                        if (isset($json['@type']) && $json['@type'] === 'Product') {
                            $price = null;
                            if (isset($json['offers']['price'])) {
                                $price = '₫ ' . number_format($json['offers']['price'], 0, ',', '.');
                            } elseif (isset($json['offers']['lowPrice'])) {
                                $price = '₫ ' . number_format($json['offers']['lowPrice'], 0, ',', '.');
                            }

                            // Store crawl data in the array
                            $crawl_data[] = [
                                'url' => $url,
                                'base_url' => $base_url,
                                'title' => $json['name'],
                                'price' => $price,
                                'discount' => null,
                                'img' => $json['image'],
                            ];
                        }
                    }
                }
                // Clear the Simple HTML DOM object to free up resources
                $dom->clear();
                unset($dom);
            }
        } catch (Exception $e) {
            $failed_url[$url] = $e->getMessage();
            echo 'Error: ',  $e->getMessage(), "\n";
            continue;
        }
    }

    if (strpos($base_url, 'lazada') !== false) {
        // Get HTML content from the URL using file_get_contents
        try {
            $html = file_get_contents($url);

            // Create a Simple HTML DOM object
            $dom = new simple_html_dom();
            $dom->load($html);

            $title = "";
            $price = "";
            $img = "";

            $script = $dom->find('script[type=text/javascript]', 0);
            if (preg_match('/var pdpTrackingData = "(.*)";/', $script, $matches)) {
                $json_str = str_replace('\"', '"', $matches[1]);
                $pdt_data = json_decode($json_str);
                if ($pdt_data === null && json_last_error() !== JSON_ERROR_NONE) {
                    echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                } else {
                    $title = $pdt_data->pdt_name;
                    $price = $pdt_data->pdt_price;
                    $img = $pdt_data->pdt_photo;
                    echo $img . "\n";
                }
            }

            // if (empty($img) || is_null($img)) {
            //     $img = $dom->find('meta[property=og:image]', 0);
            //     if ($img) {
            //         $img = $img->content;
            //     }
            // }

            if (substr($img, 0, 2) == '//') {
                $img = 'https:' . $img;
            }

            if ($price) {
                $price = str_replace(' ₫', '', $price);
                $price = '₫ ' . $price;
            }

            if ($title && $price && $img) {

                // Store crawl data in the array
                $crawl_data[] = [
                    'url' => $url,
                    'base_url' => $base_url,
                    'title' => $title,
                    'price' => $price,
                    'discount' => null,
                    'img' => $img,
                ];
            }

            // Clear the Simple HTML DOM object to free up resources
            $dom->clear();
            unset($dom);

            sleep(2);
        } catch (Exception $e) {
            $failed_url[$url] = $e->getMessage();
            echo 'Error: ',  $e->getMessage(), "\n";
            continue;
        }
    }
}

// Export the crawled data to a JSON file
$data = json_encode($crawl_data, JSON_PRETTY_PRINT);
file_put_contents('../data/data.json', $data);

$error_links = json_encode($failed_url, JSON_PRETTY_PRINT);
file_put_contents('../data/error.json', $error_links);

// Output success message
echo "Data exported to output.json successfully.\n";

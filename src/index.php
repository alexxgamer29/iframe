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
$itemsFound = 0;
$maxItemsToFind = 6;
$crawlData = [];

// Loop until the desired number of items are found
while ($itemsFound < $maxItemsToFind && !empty($urlsArray)) {
    // Randomly select a URL
    $index = array_rand($urlsArray, 1);
    $url = $urlsArray[$index]['url'];

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
        // Increment the counter
        $itemsFound++;
        // Remove the selected URL from $urlsArray
        unset($urlsArray[$index]);
    }

    // Clear the Simple HTML DOM object to free up resources
    $dom->clear();
    unset($dom);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iframe</title>
    <style>
        body,
        html {
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            color: rgba(0, 0, 0, .8);
            line-height: 1.2;
            height: 100%;
            margin: 0;
        }

        body {
            background: rgb(255, 200, 100);
            font-size: .875rem;
            text-size-adjust: none;
            -webkit-text-size-adjust: none;
        }

        .tab-panels {
            position: relative;
            margin-right: auto;
            margin-left: auto;
        }

        section.panels {
            width: 100%;
            min-height: 3.75rem;
        }

        div.panels-product {
            display: flex;
            min-height: calc(100vh - 11.25rem);
            flex-wrap: wrap;
            align-content: flex-start;
            padding-top: 0.3125rem;
        }

        div.product-item__container {
            width: calc(100% /6);
            padding: 0.3125rem;
            box-sizing: border-box;
        }

        div#product-item__wrapper {
            display: contents;
        }

        div#product-item__wrapper * {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-scroll-snap-strictness: proximity;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgba(59, 130, 246, 0.5);
            --tw-ring-offset-shadow: 0 0 transparent;
            --tw-ring-shadow: 0 0 transparent;
            --tw-shadow: 0 0 transparent;
            --tw-shadow-colored: 0 0 transparent;
            box-sizing: border-box;
            border: 0 solid #e5e7eb;
        }

        .product-item {
            transition-timing-function: cubic-bezier(.4, 0, .6, 1);
            transition-duration: .2s;
            position: relative;
        }

        .product-info__container {
            border-color: transparent;
            border-style: solid;
            border-width: 1px;
            position: relative;
        }

        a.product-link {
            color: inherit;
            text-decoration: inherit;
        }

        .product-info__wrapper {
            --tw-bg-opacity: 1;
            background-color: rgba(255, 255, 255, var(--tw-bg-opacity));
            border-color: rgba(0, 0, 0, .0902);
            border-width: 0.5px;
        }

        .product-info {
            display: flex;
            flex-direction: column;
        }

        /* #1 */
        .product-img__container {
            padding-top: 100%;
            flex-shrink: 0;
            width: 100%;
            position: relative;
        }

        .product-img {
            display: contents;
        }

        .product-img>img {
            display: block;
            object-fit: contain;
            width: 100%;
            height: 100%;
            top: 0;
            bottom: 0;
            position: absolute;
            pointer-events: none;
            max-width: 100%;
        }

        .product-frame {
            width: 100%;
            height: 100%;
            z-index: 10;
            top: 0;
            left: 0;
            position: absolute;
        }

        .product-frame>img {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
        }

        .product-discount {
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
            --tw-bg-opacity: 1;
            background-color: rgba(255, 233, 122, var(--tw-bg-opacity));
            border-bottom-left-radius: 0.125rem;
            z-index: 30;
            top: 0;
            right: 0;
            position: absolute;
        }

        .product-discount .discount {
            --tw-text-opacity: 1;
            color: rgba(236, 56, 20, var(--tw-text-opacity));
            line-height: .875rem;
            font-weight: 500;
            font-size: .75rem;
            display: block;
        }

        .mall-logo__container {
            flex-direction: column;
            display: flex;
            margin-top: 0.5rem;
            margin-left: -3px;
            z-index: 30;
            top: 0;
            left: 0;
            position: absolute;
        }

        .mall-logo__wrapper {
            margin-bottom: 0;
        }

        .mall-logo {
            width: 24px;
            height: 18px;
            background-repeat: no-repeat;
            background-size: contain;
        }

        /* #2 */
        .product-title__container {
            max-width: 100%;
            padding: 0.5rem;
            flex-direction: column;
            display: flex;
        }

        .product-title {
            margin-bottom: 0.25rem;
            min-height: 40px;
        }

        .product-title>div {
            line-height: 1.25rem;
            font-size: .875rem;
            overflow-wrap: break-word;
            max-height: 2.5rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .product-price__container {
            min-height: 20px;
            margin-bottom: 0;
        }

        .product-price__wrapper {
            width: 100%;
        }

        .product-price {
            max-width: 200px;
            box-sizing: border-box;
        }

        .product-price>div {
            --tw-text-opacity: 1;
            color: rgba(238, 77, 45, var(--tw-text-opacity));
            line-height: 1;
            font-weight: 400;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        span.price {
            line-height: 1.25rem;
            font-size: 1rem;
            vertical-align: baseline;
        }
    </style>
</head>

<body>
    <div class="tab-panels">
        <section class="panels">
            <div class="panels-product">
                <?php if (!empty($crawlData)) : ?>
                    <?php foreach ($crawlData as $item) : ?>
                        <div class="product-item__container">
                            <div id="product-item__wrapper">
                                <div class="product-item">
                                    <div class="product-info__container">
                                        <a href="" class="product-link">
                                            <div class="product-info__wrapper">
                                                <div class="product-info">
                                                    <div class="product-img__container">
                                                        <div class="product-img">
                                                            <img src="<?php echo htmlspecialchars($item['img']); ?>" alt="">
                                                        </div>
                                                        <!-- Shopee frame -->
                                                        <!-- <div class="product-frame">
                                                            <img alt="" src="https://down-vn.img.susercontent.com/file/vn-50009109-12cec261f4c3657f7efb42286595e174">
                                                        </div> -->
                                                        <div class="product-discount">
                                                            <span class="discount">-<?php echo htmlspecialchars($item['discount']); ?>%</span>
                                                        </div>
                                                        <!-- Shopee Mall logo -->
                                                        <!-- <div class="mall-logo__container">
                                                            <span class="mall-logo__wrapper">
                                                                <img class="mall-logo" src="https://down-vn.img.susercontent.com/file/56a4f5097228286786f83f8036c313bc" alt="">
                                                            </span>
                                                        </div> -->
                                                    </div>
                                                    <div class="product-title__container">
                                                        <div class="product-title">
                                                            <div><?php echo htmlspecialchars($item['title']); ?></div>
                                                        </div>
                                                        <div class="product-price__container">
                                                            <div class="product-price__wrapper">
                                                                <div class="product-price">
                                                                    <div>
                                                                        <span class="price"><?php echo htmlspecialchars($item['price']); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</body>

</html>
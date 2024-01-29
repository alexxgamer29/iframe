<!DOCTYPE html>
<html lang="en" style="overflow: hidden;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iframe</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body,
        html {
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            color: rgba(0, 0, 0, .8);
            line-height: 1.2;
            margin: 0;
        }

        body {
            font-size: .5rem;
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
            display: block;
            /* min-height: 100vh; */
        }

        div.panels-product {
            display: flex;
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
            /*min-height: 40px;*/
        }

        .product-title>div {
            line-height: 1.25rem;
            font-size: .8rem;
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
            font-size: 0.8rem;
            vertical-align: baseline;
        }

        .location__container {
            display: flex;
            /*margin-top: 0.5rem;*/
            flex-direction: row;
            align-items: center;
            color: rgba(0, 0, 0, .87);
        }

        .location__wrapper {
            color: rgba(0, 0, 0, .65);
            line-height: 1.125rem;
            min-height: 1em;
            text-align: left;
            font-weight: 200;
            flex: 0 1 auto;
            font-size: .75rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sold-product__container {
            display: flex;
            align-items: center;
            height: 1.25rem;
            /*margin-top: 0.75rem;*/
        }

        .sold-product {
            color: rgba(0, 0, 0, .87);
            font-size: .75rem;
            line-height: .875rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        @media only screen and (min-width: 1200px) {
            div.product-item__container {
                width: calc(100% / 6);
                padding: 0.3125rem;
                box-sizing: border-box;
            }
        }

        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            div.product-item__container {
                width: calc(100% / 6);
                padding: 0.3125rem;
                box-sizing: border-box;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 991px) {
            div.product-item__container {
                width: calc(100% / 5);
                padding: 0.3125rem;
                box-sizing: border-box;
            }

            div.product-item__container:nth-child(-n+5) {
                display: block;
            }

            div.product-item__container:not(:nth-child(-n+5)) {
                display: none;
            }

            .product-title>div {
                line-height: 1.25rem;
                font-size: .75rem;
                overflow-wrap: break-word;
                max-height: 2.5rem;
                overflow: hidden;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }

            span.price {
                line-height: 1.25rem;
                font-size: 0.8rem;
                vertical-align: baseline;
            }
        }

        @media only screen and (min-width: 576px) and (max-width: 767px) {
            div.product-item__container {
                width: calc(100% / 4);
                padding: 0.3125rem;
                box-sizing: border-box;
            }

            div.product-item__container:nth-child(-n+4) {
                display: block;
            }

            div.product-item__container:not(:nth-child(-n+4)) {
                display: none;
            }

            .product-title>div {
                line-height: 1.25rem;
                font-size: .75rem;
                overflow-wrap: break-word;
                max-height: 2.5rem;
                overflow: hidden;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }

            span.price {
                line-height: 1.25rem;
                font-size: 0.8rem;
                vertical-align: baseline;
            }
        }

        @media only screen and (max-width: 576px) {
            div.product-item__container {
                width: calc(100% / 2);
                padding: 0.3125rem;
                box-sizing: border-box;
            }

            div.product-item__container:nth-child(-n+2) {
                display: block;
            }

            div.product-item__container:not(:nth-child(-n+2)) {
                display: none;
            }


            .product-title>div {
                line-height: 1.25rem;
                font-size: .75rem;
                overflow-wrap: break-word;
                max-height: 2.5rem;
                overflow: hidden;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }

            span.price {
                line-height: 1.25rem;
                font-size: 0.8rem;
                vertical-align: baseline;
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            var CACHE_EXPIRATION_TIME = 24 * 60 * 60 * 1000; // 24 hours in milliseconds

            // Check if cached data exists and if it's expired
            var cachedData = localStorage.getItem('iframe-data');
            var cachedTimestamp = localStorage.getItem('cached-timestamp');


            // Check if data is already in localStorage
            var cachedData = localStorage.getItem('iframe-data');

            if (cachedData && cachedTimestamp) {
                var currentTime = new Date().getTime();
                if (currentTime - parseInt(cachedTimestamp) < CACHE_EXPIRATION_TIME) {
                    // If data is not expired, use the cached data
                    displayData(JSON.parse(cachedData));
                    return; // Exit early
                }
            } else {
                // Fetch data via AJAX
                $.ajax({
                    url: 'load.php',
                    type: 'GET',
                    cache: true,
                    dataType: 'json',
                    success: function(data) {
                        // Cache the data to localStorage
                        console.log(typeof data)
                        localStorage.setItem('iframe-data', JSON.stringify(data));
                        localStorage.setItem('cached-timestamp', new Date().getTime().toString());
                        // Display the fetched data
                        displayData(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            function displayData(data) {
                // Shuffle the data array

                shuffleArray(data);

                // Select the first 6 items
                var selectedData = data.slice(0, 6);

                // Display data in the HTML
                selectedData.forEach(function(item, index) {
                    var productItem = `
                        <div class="product-item__container">
                            <div id="product-item__wrapper">
                                <div class="product-item">
                                    <div class="product-info__container">
                                        <a href="${item.url}" class="product-link" target="_blank">
                                            <div class="product-info__wrapper">
                                                <div class="product-info">
                                                    <div class="product-img__container">
                                                        <div class="product-img">
                                                            <img src="${item.img}" alt="">
                                                        </div>
                                                        ${item.discount ? 
                                                            `<div class="product-discount">
                                                                <span class="discount">
                                                                    -${item.discount}%
                                                                </span>
                                                            </div>` : ''}
                                                    </div>
                                                    <div class="product-title__container">
                                                        <div class="product-title">
                                                            <div>${item.title}</div>
                                                        </div>
                                                        <div class="product-price__container">
                                                            <div class="product-price__wrapper">
                                                                <div class="product-price">
                                                                    <div>
                                                                        <span class="price">
                                                                            ${item.landing ? 'Sản phẩm ' : ''}${item.price}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        ${item.sold ? 
                                                            `<div class="sold-product__container">
                                                                <div class="sold-product">
                                                                    Người theo dõi ${item.sold}
                                                                </div>
                                                            </div>` : ''}
                                                        ${item.location ? 
                                                            `<div class="location__container">
                                                                <div class="location__wrapper">
                                                                    ${item.location}
                                                                </div>
                                                            </div>` : ''}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    // Append product item to the product container
                    $('.panels-product').append(productItem);
                });
            }

            // Function to shuffle array
            function shuffleArray(array) {
                for (var i = array.length - 1; i > 0; i--) {
                    var j = Math.floor(Math.random() * (i + 1));
                    var temp = array[i];
                    array[i] = array[j];
                    array[j] = temp;
                }
            }
        });
    </script>
</head>
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
                                        <a href="<?php echo htmlspecialchars_decode($item['url']); ?>" class="product-link" target="_blank">
                                            <div class="product-info__wrapper">
                                                <div class="product-info">
                                                    <div class="product-img__container">
                                                        <div class="product-img">
                                                            <img src="<?php echo htmlspecialchars_decode($item['img']); ?>" alt="">
                                                        </div>
                                                        <!-- Shopee frame -->
                                                        <!-- <div class="product-frame">
                                                            <img alt="" src="https://down-vn.img.susercontent.com/file/vn-50009109-12cec261f4c3657f7efb42286595e174">
                                                        </div> -->
                                                        <?php if (isset($item['discount']) && !is_null($item['discount'])) : ?>
                                                            <div class="product-discount">
                                                                <span class="discount">
                                                                    <?php echo "-" . htmlspecialchars_decode($item['discount']) . "%" ?>
                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <!-- Shopee Mall logo -->
                                                        <!-- <div class="mall-logo__container">
                                                            <span class="mall-logo__wrapper">
                                                                <img class="mall-logo" src="https://down-vn.img.susercontent.com/file/56a4f5097228286786f83f8036c313bc" alt="">
                                                            </span>
                                                        </div> -->
                                                    </div>
                                                    <div class="product-title__container">
                                                        <div class="product-title">
                                                            <div><?php echo htmlspecialchars_decode($item['title']); ?></div>
                                                        </div>
                                                        <div class="product-price__container">
                                                            <div class="product-price__wrapper">
                                                                <div class="product-price">
                                                                    <div>
                                                                        <span class="price">
                                                                            <?php
                                                                            if (isset($item['landing']) && !is_null($item['landing'])) {
                                                                                echo "Sản phẩm " . htmlspecialchars_decode($item['price']);
                                                                            } else {
                                                                                echo htmlspecialchars_decode($item['price']);
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if (isset($item['sold']) && !is_null($item['sold'])) : ?>
                                                            <div class="sold-product__container">
                                                                <div class="sold-product">
                                                                    Người theo dõi <?php echo htmlspecialchars_decode($item['sold']); ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>


                                                        <?php if (isset($item['location']) && !is_null($item['location'])) : ?>
                                                            <div class="location__container">
                                                                <div class="location__wrapper">
                                                                    <?php echo htmlspecialchars_decode($item['location']); ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
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
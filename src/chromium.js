const path = require('path');
const dotenv = require('dotenv');

dotenv.config({ path: '../.env'  });

console.log(process.env.EMAIL);

const puppeteerExtra = require('puppeteer-extra');
const stealthPlugin = require('puppeteer-extra-plugin-stealth');
const fs = require('fs');

puppeteerExtra.use(stealthPlugin());

const TIMEOUT_SHORT = 3000;

async function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function crawlAndLogin(url, email, password) {
    const browser = await puppeteerExtra.launch({
        headless: false,
        // args: [
        //     '--no-sandbox',
        //     '--disable-setuid-sandbox',
        //     '--disable-dev-shm-usage',
        //     '--disable-accelerated-2d-canvas',
        //     '--no-first-run',
        //     '--no-zygote',
        //     '--single-process',
        //     '--disable-gpu',
        //     '--js-flags="--max-old-space-size=2048"'
        // ]
    });
    try {
        const page = await browser.newPage();
        page.setViewport({
            width: 1800,
            height: 900,
        });

        //login
        await page.setExtraHTTPHeaders({
            'user-agent': 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'
        });
        await page.goto(url, { waitUntil: "networkidle0" });

        // Perform login
        await loginToWebsite(page, email, password);

        // Wait for a fixed amount of time (e.g., 20 seconds) after login
        await page.waitForTimeout(20000);

        console.log("Login successful SHOPEE");
        await sleep(3000);

        const html = await page.content();

        // Write HTML content to a file
        fs.writeFileSync('test.html', html);
        console.log(html);
        await browser.close();
    } catch (err) {
        console.log("Browser has disconnected! SHOPEE", err)
        await browser.close();
        // main(baseUrl, username, password)
    }
}

async function loginToWebsite(page, email, password) {
    const loginKeySelector = 'input[name="loginKey"]';
    const passwordSelector = 'input[name="password"]';
    const loginButtonSelector = 'button.wyhvVD._1EApiB.hq6WM5.L-VL8Q.cepDQ1._7w24N1';

    await page.waitForSelector(loginKeySelector, { timeout: TIMEOUT_SHORT });
    await page.type(loginKeySelector, email, { delay: 100 });

    await page.waitForSelector(passwordSelector, { timeout: TIMEOUT_SHORT });
    await page.type(passwordSelector, password, { delay: 100 });

    await page.waitForTimeout(TIMEOUT_SHORT);
    await page.waitForSelector(loginButtonSelector, { timeout: TIMEOUT_SHORT });
    await page.click(loginButtonSelector);

    await page.waitForNavigation({ waitUntil: 'domcontentloaded' });

    //click send code via email
    try {
        await page.waitForSelector("div[class='a1FTzG'] > button");
        await page.click("div[class='a1FTzG'] > button");
        console.log("Wait to verify email SHOPEE");
        await page.waitForNavigation({ waitUntil: "networkidle0", timeout: 50000 });
    } catch (err) {
        console.log(`Click button send code via email failed: ${err}`);
    }
}

const args = process.argv.slice(2);
console.log('Arguments:', args);

const url = args[0];
const email = process.env.EMAIL;
const password = process.env.PASSWORD;

crawlAndLogin(url, email, password).catch(error => {
    console.error('Error:', error);
});

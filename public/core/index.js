//
// const pageUrl = "https://jobs.ge/?page=1&q=&cid=6&lid=1&jid=1&has_salary=1";
// if chrome is not installed: node node_modules/puppeteer/install.js

const argv = require('minimist')(process.argv.slice(2));
const pageUrl = argv.url;

/**
 *
 * @type {boolean, string}
 * value (string) : new // new headless
 * value (boolean) : false // browser
 * value (boolean) : true // old headless
 */
const runBackground = 'new';


const fs = require("fs");
var fsp = require('fs/promises');
const path = require('path');
const puppeteer = require('puppeteer-extra')


// Add stealth plugin and use defaults (all tricks to hide puppeteer usage)
const StealthPlugin = require('puppeteer-extra-plugin-stealth')
puppeteer.use(StealthPlugin())

//block resources plugin
// const blockResources = require('puppeteer-extra-plugin-block-resources')
// puppeteer.use(blockResources({
//     blockedTypes: new Set([ 'stylesheet']),
// }))


// adblock plugin save bendwith
const AdblockerPlugin = require('puppeteer-extra-plugin-adblocker')
puppeteer.use(AdblockerPlugin({blockTrackers: true}))




async function getPageHtml(page,content){ //go to url and get table html
    let completeObject = [];
    for(let index =0;index<content.length;++index){
        let tab = content[index];
        var uri = "https://jobs.ge" + tab.url;
        await page.goto(uri, {waitUntil: 'networkidle2'});
        await page.waitForSelector(".dtable");
        let bodyContentEl = await page.$('.dtable')
        let bodyContentObj = await page.evaluate(el => el.innerHTML, bodyContentEl)
        let newObj = { ...tab, pageHtml: bodyContentObj }
        completeObject.push(newObj);

    }
    return completeObject;
}



puppeteer.launch({
    headless: runBackground,
    // product: 'firefox',
    ignoreDefaultArgs: ['--disable-extensions'],
    // executablePath: "/var/www/amp.mysite.ge/public/amazon/.cache/puppeteer/chrome/linux-117.0.5938.92/chrome-linux64/chrome",
    executablePath: "G:\\DRIVE BK\\PRIVATE\\OpenServer\\domains\\jobsApi\\public\\core\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome",
    // args: ["--user-agent=" + userAgent + "", '--no-sandbox', '--disable-setuid-sandbox'],
    args: ['--disable-gpu',
        '--disable-dev-shm-usage',
        '--disable-setuid-sandbox',
        '--no-first-run',
        '--no-sandbox',
        '--no-zygote',
        '--single-process'],


}).then(async browser => {
    const page = await browser.newPage()
    await page.setViewport({width: 1200, height: 1200})
    // console.log(`Testing the stealth plugin..`)
    await page.setUserAgent(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36');

    await page.goto(pageUrl, {
        waitUntil: 'load'
    })


    // check if amazon code is existing and click reload  code to bypass
    const checkCodeCaptcha = await page.$$('.a-row .a-column.a-span6.a-span-last.a-text-right')
    // console.log(checkCodeCaptcha.length)
    if (checkCodeCaptcha.length > 0) {
        await checkCodeCaptcha[0].click();
    }



    const content = await page.evaluate(() => {
        const tbody = document.querySelector("#job_list_table tbody");
        const trs = Array.from(
            tbody.querySelectorAll("tr"),
        );
        const content = [];
        for (const tr of trs) {
            const tds = Array.from(tr.querySelectorAll("td"));
            const data = tds.map((td) => td);
            if (tds.length >= 5) {
                const urls = Array.from(data[1].querySelectorAll("a[href]"));
                const array = urls.map((url) => url.getAttribute('href'));

                // push the data
                content.push({
                    url : "https://jobs.ge"+array[0],
                    job_title: data[1].innerText,
                    company_title: data[3].innerText,
                    start_date: data[4].innerText,
                    end_date: data[5].innerText,

                });
            }
        }

        return content;
    });




    //if want to get page html content from url
    // var withPageHtml =  await getPageHtml(page, content);


    // await fsp.writeFile("..\\storage\\app\\public\\products.json", JSON.stringify(data));

    // for local testing " node index.js "
    // await fsp.writeFile("../storage/json.json", JSON.stringify({}));
    // await fsp.writeFile("../storage/json.json", JSON.stringify(content));

    await fsp.writeFile("storage/app/public/json.json", JSON.stringify({}));
    await fsp.writeFile("storage/app/public/json.json", JSON.stringify(content));


    await browser.close()
    return true;
})

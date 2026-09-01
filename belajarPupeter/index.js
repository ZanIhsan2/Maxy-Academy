const path = require("path");
const puppeteer = require("puppeteer");

(async () => {
  let browser;

  try {
    browser = await puppeteer.launch({
      headless: false,
      defaultViewport: { width: 1280, height: 800 },
    });

    const page = await browser.newPage();
    await page.goto("https://github.com/ZanIhsan2", {
      waitUntil: "domcontentloaded",
    });
    await page.waitForSelector("h1");

    const screenshotPath = path.join(__dirname, "screenshot.png");
    await page.screenshot({ path: screenshotPath, fullPage: true });
    console.log(`Screenshot berhasil disimpan: ${screenshotPath}`);
  } catch (error) {
    console.error(error.message);
    process.exitCode = 1;
  } finally {
    if (browser) {
      await browser.close();
    }
  }
})();

const path = require("path");
const puppeteer = require("puppeteer");
require("dotenv").config({ path: path.join(__dirname, ".env") });

const GAME_URL = "https://farmrpg.com/";

(async () => {
  let browser;

  try {
    const cookieName = process.env.FARM_SESSION_COOKIE_NAME;
    const cookieValue = process.env.FARM_SESSION_COOKIE_VALUE;

    if (!cookieName || !cookieValue) {
      throw new Error(
        "FARM_SESSION_COOKIE_NAME dan FARM_SESSION_COOKIE_VALUE wajib diisi di .env.",
      );
    }

    browser = await puppeteer.launch({
      headless: false,
      defaultViewport: { width: 1280, height: 800 },
    });

    const page = await browser.newPage();
    await page.setCookie({
      name: cookieName,
      value: cookieValue,
      domain: "farmrpg.com",
      path: "/",
      secure: true,
      httpOnly: true,
    });

    await page.goto(GAME_URL, { waitUntil: "domcontentloaded" });
    await page.waitForSelector("body");

    if (/login|signin/i.test(page.url())) {
      throw new Error(
        "Cookie tidak memvalidasi sesi. Ambil cookie terbaru dari akun FarmRPG sendiri.",
      );
    }

    console.log(`Sesi berhasil diverifikasi: ${page.url()}`);

    const configuredSelector = process.env.FARM_ACTION_SELECTOR?.trim();
    const configuredUrl = process.env.FARM_ACTION_URL?.trim();
    const actionSelector = configuredSelector?.startsWith("http")
      ? ""
      : configuredSelector;
    const actionUrl =
      configuredUrl ||
      (configuredSelector?.startsWith("http") ? configuredSelector : "");

    if (actionSelector) {
      await page.waitForSelector(actionSelector);
      await page.click(actionSelector);
      console.log(`Aksi berhasil: klik ${actionSelector}`);
    } else if (actionUrl) {
      const targetUrl = new URL(actionUrl);
      if (targetUrl.hostname !== "farmrpg.com") {
        throw new Error("FARM_ACTION_URL harus berada di farmrpg.com.");
      }
      await page.goto(targetUrl.href, { waitUntil: "domcontentloaded" });
      await page.waitForSelector("body");
      console.log(`Aksi berhasil: navigasi ke ${page.url()}`);
    } else {
      throw new Error(
        "Isi FARM_ACTION_SELECTOR atau FARM_ACTION_URL di .env untuk menjalankan aksi.",
      );
    }
  } catch (error) {
    console.error(error.message);
    process.exitCode = 1;
  }
})();
